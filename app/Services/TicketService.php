<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\TicketStatus;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TicketService
{
    /**
     * Genera el siguiente número de secuencia para tickets.
     */
    public function generateTicketNumber($empresaId)
    {
        return DB::transaction(function () use ($empresaId) {
            $secuencia = DB::table('sequences')
                ->where('empresa_id', $empresaId)
                ->where('name', 'tickets')
                ->lockForUpdate()
                ->first();

            if (! $secuencia) {
                DB::table('sequences')->insert([
                    'empresa_id' => $empresaId,
                    'name' => 'tickets',
                    'next' => 2,
                    'increment' => 1,
                    'padding' => 0,
                    'created' => now(),
                    'updated' => now(),
                ]);

                return '1';
            } else {
                DB::table('sequences')
                    ->where('id', $secuencia->id)
                    ->update([
                        'next' => $secuencia->next + $secuencia->increment,
                        'updated' => now(),
                    ]);

                return (string) $secuencia->next;
            }
        });
    }

    /**
     * Crea el hilo inicial del ticket.
     */
    public function createInitialThread(Ticket $ticket, $body, $staffId)
    {
        if (empty($body)) {
            return null;
        }

        $hilo = $ticket->thread()->create([
            'created' => now(),
        ]);

        $hilo->entries()->create([
            'empresa_id' => $ticket->empresa_id,
            'staff_id' => $staffId,
            'body' => $body,
            'is_internal' => 0,
            'created' => now(),
        ]);

        return $hilo;
    }

    /**
     * Añade una respuesta al hilo del ticket, opcionalmente con adjuntos.
     */
    public function addReply(Ticket $ticket, $body, $staffId, $attachments = [])
    {
        $hilo = $ticket->thread;

        if (! $hilo) {
            $hilo = $ticket->thread()->create([
                'created' => now(),
            ]);
        }

        $entrada = $hilo->entries()->create([
            'empresa_id' => $ticket->empresa_id,
            'staff_id' => $staffId,
            'body' => $body,
            'is_internal' => 0,
            'created' => now(),
        ]);

        if (! empty($attachments)) {
            foreach ($attachments as $archivo) {
                if ($archivo instanceof UploadedFile) {
                    $ruta = $archivo->store('tickets/attachments');

                    $entrada->attachments()->create([
                        'empresa_id' => $ticket->empresa_id,
                        'filename' => $archivo->hashName(),
                        'original_filename' => $archivo->getClientOriginalName(),
                        'mimetype' => $archivo->getMimeType(),
                        'size' => $archivo->getSize(),
                        'path' => $ruta,
                        'created' => now(),
                    ]);
                }
            }
        }

        return $entrada;
    }

    /**
     * Actualiza el estado del ticket y realiza acciones secundarias.
     */
    public function updateStatus(Ticket $ticket, $statusId, $staffId = null)
    {
        $ticket->status_id = $statusId;

        if ($statusId == 3) { // Cerrado
            $ticket->closed = now();
        }

        $nuevoEstado = TicketStatus::find($statusId);

        if ($nuevoEstado && in_array($nuevoEstado->name, ['En camino', 'En proceso'])) {
            // Auto asignar si está sin asignar
            if (! $ticket->staff_id || $ticket->staff_id == 0) {
                $ticket->staff_id = $staffId ?? 1;
            }

            // Simular ubicación en staff_locations para que aparezca en el mapa inmediatamente
            DB::table('staff_locations')->updateOrInsert(
                ['staff_id' => $ticket->staff_id],
                [
                    'lat' => 8.9824 + (rand(-10, 10) * 0.002),
                    'lng' => -79.5199 + (rand(-10, 10) * 0.002),
                    'updated_at' => now(),
                ]
            );
        }

        $ticket->save();

        return $ticket;
    }

    /**
     * Procesa y guarda la firma en formato Base64.
     */
    public function saveSignatureFromBase64(Ticket $ticket, $base64Image)
    {
        $imageParts = explode(';base64,', $base64Image);
        $imageTypeAux = explode('image/', $imageParts[0]);
        $imageType = $imageTypeAux[1] ?? 'png';
        $imageBase64 = base64_decode($imageParts[1]);

        $filename = 'tickets/signatures/ticket_'.$ticket->id.'_'.time().'.'.$imageType;

        Storage::disk('public')->put($filename, $imageBase64);

        $ticket->client_signature = $filename;
        $ticket->save();

        return $ticket;
    }
}
