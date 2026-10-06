<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReport;
use App\Models\TicketReportItem;
use Illuminate\Http\Request;

class TicketReportController extends Controller
{
    /**
     * Display the tickets that require a report (billing).
     */
    public function facturacion(Request $request)
    {
        $search = $request->input('q');
        $month = $request->input('month', date('Y-m'));

        $query = Ticket::with(['department', 'staff', 'user', 'report'])
            ->where('status_id', 3); // Assuming 3 is Closed

        if ($month !== 'all') {
            $query->whereYear('closed', substr($month, 0, 4))
                ->whereMonth('closed', substr($month, 5, 2));
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('firstname', 'like', "%{$search}%")
                            ->orWhere('lastname', 'like', "%{$search}%");
                    });
            });
        }

        $tickets = $query->orderBy('closed', 'desc')->paginate(10);

        // Get months with closed tickets for filter
        $months = Ticket::where('status_id', 3)
            ->selectRaw("DATE_FORMAT(closed, '%Y-%m') as month")
            ->distinct()
            ->orderBy('month', 'desc')
            ->pluck('month')
            ->toArray();

        if (empty($months)) {
            $months = [date('Y-m')];
        }

        return view('agent.tickets.billing', compact('tickets', 'months', 'month', 'search'));
    }

    /**
     * Display the report sheet for a specific ticket.
     */
    public function hojaReporte($id)
    {
        $ticket = Ticket::with(['department', 'staff', 'user', 'report.items'])->findOrFail($id);

        if ($ticket->status_id != 3) {
            return redirect()->route('agent.tickets.show', $id)
                ->with('error', 'El ticket no está cerrado, no se puede realizar el reporte.');
        }

        return view('agent.tickets.report_sheet', compact('ticket'));
    }

    /**
     * Store or update the ticket report.
     */
    public function guardarReporte(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $request->validate([
            'observations' => 'nullable|string',
            'report_type' => 'required|in:pending,visita_tecnica,cotizacion',
            'item_description' => 'required|array|min:1',
            'item_price' => 'required|array|min:1',
        ]);

        $itemDescriptions = $request->input('item_description');
        $itemPrices = $request->input('item_price');

        $total = 0;
        $items = [];
        foreach ($itemDescriptions as $i => $desc) {
            if (! empty($desc) && isset($itemPrices[$i])) {
                $price = floatval(str_replace(',', '.', $itemPrices[$i]));
                $items[] = [
                    'description' => $desc,
                    'price' => $price,
                ];
                $total += $price;
            }
        }

        if (empty($items)) {
            return redirect()->back()->with('error', 'Debe agregar al menos un ítem con descripción y precio.');
        }

        $workDescLines = [];
        foreach ($items as $it) {
            $workDescLines[] = $it['description'].' - $'.number_format($it['price'], 2);
        }
        $workDescConcat = implode("\n", $workDescLines);

        $report = TicketReport::updateOrCreate(
            ['ticket_id' => $ticket->id],
            [
                'work_description' => $workDescConcat,
                'observations' => $request->input('observations'),
                'final_price' => $total,
                'billing_status' => $request->input('report_type'),
                'empresa_id' => $ticket->empresa_id ?? 1,
                'created_by' => auth('staff')->id() ?? 1,
            ]
        );

        $report->items()->delete();

        foreach ($items as $it) {
            TicketReportItem::create([
                'report_id' => $report->id,
                'description' => $it['description'],
                'price' => $it['price'],
                'empresa_id' => $ticket->empresa_id ?? 1,
            ]);
        }

        return redirect()->route('agent.tickets.report_sheet', $ticket->id)
            ->with('success', 'Reporte guardado correctamente.');
    }
}
