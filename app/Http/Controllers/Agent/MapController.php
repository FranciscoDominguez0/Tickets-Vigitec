<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{
    public function index()
    {
        return view('agent.map.index');
    }

    public function ubicaciones(Request $request)
    {
        // Solo agentes con tickets "En camino" o "En proceso"
        // Consultar dinámicamente los IDs correspondientes en la DB.
        $statusIds = DB::table('ticket_status')
            ->whereIn('name', ['En camino', 'En proceso'])
            ->pluck('id')
            ->toArray();

        if (empty($statusIds)) {
            $statusIds = [4, 5]; // Fallback si aún no existen
        }
        $statusIdsStr = implode(',', $statusIds);

        $ubicaciones = DB::table('staff as s')
            ->join(DB::raw("(SELECT staff_id, MAX(id) as max_ticket_id FROM tickets WHERE status_id IN ($statusIdsStr) AND closed IS NULL GROUP BY staff_id) as t_active"), 't_active.staff_id', '=', 's.id')
            ->join('tickets as t', 't.id', '=', 't_active.max_ticket_id')
            ->join('ticket_status as ts', 't.status_id', '=', 'ts.id')
            ->leftJoin('staff_locations as sl', 's.id', '=', 'sl.staff_id')
            ->select([
                's.id as staff_id',
                DB::raw("CONCAT(s.firstname, ' ', s.lastname) as name"),
                DB::raw("COALESCE(sl.lat, 8.9824 + (s.id * 0.005)) as lat"), // Fallback: ciudad de Panamá con variación según ID
                DB::raw("COALESCE(sl.lng, -79.5199 + (s.id * 0.005)) as lng"),
                'sl.updated_at as updated',
                't.id as ticket_id',
                't.ticket_number',
                'ts.name as status',
            ])
            ->get();

        return response()->json([
            'ok' => true,
            'locations' => $ubicaciones,
        ]);
    }

    public function updateLocation(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $staffId = auth('staff')->id();

        if ($staffId) {
            DB::table('staff_locations')->updateOrInsert(
                ['staff_id' => $staffId],
                [
                    'lat' => $request->lat,
                    'lng' => $request->lng,
                    'updated_at' => now(),
                ]
            );

            return response()->json(['ok' => true]);
        }

        return response()->json(['ok' => false], 403);
    }
}
