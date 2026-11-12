<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acta de Cierre - Ticket #{{ $ticket->ticket_number }}</title>
    <style>
        @page {
            margin: 28px 32px 35px 32px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.45;
            background-color: #ffffff;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #dc2626;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }
        .company-logo {
            max-height: 50px;
            max-width: 180px;
        }
        .company-title {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
            text-transform: uppercase;
        }
        .company-details {
            font-size: 9px;
            color: #64748b;
            margin-top: 3px;
        }
        .doc-title {
            text-align: right;
            vertical-align: top;
        }
        .doc-title h1 {
            font-size: 14px;
            font-weight: 800;
            color: #dc2626;
            margin: 0 0 3px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .doc-number {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
        }
        .badge-status {
            display: inline-block;
            padding: 3px 8px;
            font-size: 9px;
            font-weight: bold;
            color: #ffffff;
            background-color: #16a34a;
            border-radius: 3px;
            text-transform: uppercase;
            margin-top: 4px;
        }
        .grid-table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: separate;
            border-spacing: 10px 0;
        }
        .card-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 10px 12px;
            vertical-align: top;
        }
        .card-box h3 {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: #475569;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 4px;
            margin: 0 0 8px 0;
            letter-spacing: 0.5px;
        }
        .data-row {
            margin-bottom: 4px;
        }
        .data-label {
            font-weight: bold;
            color: #475569;
            font-size: 9.5px;
            display: inline-block;
            width: 90px;
        }
        .data-value {
            color: #0f172a;
            font-size: 9.5px;
        }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
            background-color: #f1f5f9;
            border-left: 3px solid #dc2626;
            padding: 5px 8px;
            margin: 14px 0 8px 0;
            letter-spacing: 0.3px;
        }
        .table-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .table-items th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 9.5px;
            font-weight: 600;
            text-align: left;
            padding: 6px 10px;
            text-transform: uppercase;
        }
        .table-items td {
            border-bottom: 1px solid #e2e8f0;
            padding: 6px 10px;
            font-size: 9.5px;
            color: #334155;
        }
        .table-items .total-row td {
            font-weight: bold;
            font-size: 10px;
            background-color: #f8fafc;
            border-top: 1px solid #cbd5e1;
            color: #0f172a;
        }
        .conformity-box {
            background-color: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 4px;
            padding: 8px 12px;
            font-size: 9px;
            color: #475569;
            text-align: justify;
            margin-bottom: 16px;
            line-height: 1.4;
        }
        .signatures-table {
            width: 100%;
            margin-top: 15px;
            border-collapse: separate;
            border-spacing: 20px 0;
        }
        .signature-cell {
            width: 50%;
            vertical-align: top;
            text-align: center;
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            border-radius: 4px;
            padding: 10px;
        }
        .signature-img-container {
            height: 75px;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .signature-img {
            max-height: 70px;
            max-width: 220px;
        }
        .signature-line {
            border-top: 1px solid #94a3b8;
            margin: 0 auto 6px auto;
            width: 80%;
        }
        .signature-name {
            font-size: 10px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 2px;
        }
        .signature-role {
            font-size: 8.5px;
            color: #64748b;
            text-transform: uppercase;
        }
        .footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 20px;
            border-top: 1px solid #e2e8f0;
            padding-top: 4px;
            font-size: 8px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Encabezado Principal -->
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 55%; vertical-align: top;">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo" class="company-logo">
                @else
                    <h2 class="company-title">{{ $nombreEmpresa }}</h2>
                @endif
                <div class="company-details">
                    @if($direccionEmpresa) <div>{{ $direccionEmpresa }}</div> @endif
                    @if($telefonoEmpresa) <span>Tel: {{ $telefonoEmpresa }}</span> @endif
                    @if($sitioWebEmpresa) <span> | {{ $sitioWebEmpresa }}</span> @endif
                </div>
            </td>
            <td class="doc-title" style="width: 45%;">
                <h1>Acta de Servicio y Cierre</h1>
                <div class="doc-number">Ticket: #{{ $ticket->ticket_number }}</div>
                <div class="badge-status">Conformidad Firmada</div>
            </td>
        </tr>
    </table>

    <!-- Información General en Dos Columnas -->
    <table class="grid-table" cellpadding="0" cellspacing="0">
        <tr>
            <!-- Datos del Cliente -->
            <td class="card-box" style="width: 50%;">
                <h3>Datos del Cliente / Solicitante</h3>
                <div class="data-row">
                    <span class="data-label">Nombre:</span>
                    <span class="data-value">{{ $ticket->user->name ?? 'Cliente General' }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Correo:</span>
                    <span class="data-value">{{ $ticket->user->email ?? 'No registrado' }}</span>
                </div>
                @if($ticket->user->phone ?? false)
                    <div class="data-row">
                        <span class="data-label">Teléfono:</span>
                        <span class="data-value">{{ $ticket->user->phone }}</span>
                    </div>
                @endif
                @if($ticket->user->address ?? false)
                    <div class="data-row">
                        <span class="data-label">Dirección:</span>
                        <span class="data-value">{{ $ticket->user->address }}</span>
                    </div>
                @endif
            </td>

            <!-- Datos del Ticket y Servicio -->
            <td class="card-box" style="width: 50%;">
                <h3>Información del Servicio</h3>
                <div class="data-row">
                    <span class="data-label">Departamento:</span>
                    <span class="data-value">{{ $ticket->department->name ?? 'Soporte Técnico' }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Prioridad:</span>
                    <span class="data-value">{{ $ticket->priority->name ?? 'Normal' }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Técnico Asignado:</span>
                    <span class="data-value">
                        @if($ticket->staff)
                            {{ $ticket->staff->firstname }} {{ $ticket->staff->lastname }}
                        @else
                            Personal de Guardia
                        @endif
                    </span>
                </div>
                <div class="data-row">
                    <span class="data-label">Apertura:</span>
                    <span class="data-value">{{ $ticket->created ? \Carbon\Carbon::parse($ticket->created)->format('d/m/Y h:i A') : 'N/A' }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Cierre:</span>
                    <span class="data-value">{{ $ticket->closed ? \Carbon\Carbon::parse($ticket->closed)->format('d/m/Y h:i A') : now()->format('d/m/Y h:i A') }}</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Requerimiento o Asunto -->
    <div class="section-title">Requerimiento o Problema Reportado</div>
    <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 8px 12px; margin-bottom: 12px;">
        <div style="font-weight: bold; color: #0f172a; margin-bottom: 4px; font-size: 10.5px;">
            {{ $ticket->subject }}
        </div>
        @php
            $primerMensaje = optional(optional($ticket->thread)->entries)->first();
        @endphp
        @if($primerMensaje && !empty($primerMensaje->body))
            <div style="color: #475569; font-size: 9.5px; line-height: 1.4;">
                {!! nl2br(e(strip_tags($primerMensaje->body))) !!}
            </div>
        @endif
    </div>

    <!-- Detalle de Trabajos Realizados si tiene reporte -->
    @if($ticket->report && $ticket->report->items && $ticket->report->items->count() > 0)
        <div class="section-title">Trabajos Técnicos Ejecutados</div>
        <table class="table-items" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th style="width: 75%;">Descripción de Actividades y Materiales</th>
                    <th style="width: 25%; text-align: right;">Importe (USD)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ticket->report->items as $item)
                    <tr>
                        <td>{{ $item->description }}</td>
                        <td style="text-align: right;">${{ number_format($item->price, 2) }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td style="text-align: right;">Importe Total:</td>
                    <td style="text-align: right; color: #dc2626;">${{ number_format($ticket->report->final_price, 2) }}</td>
                </tr>
            </tbody>
        </table>
        @if(!empty($ticket->report->observations))
            <div style="margin-bottom: 12px; font-size: 9px; color: #64748b;">
                <strong>Observaciones técnicas adicionales:</strong> {{ $ticket->report->observations }}
            </div>
        @endif
    @endif

    <!-- Cláusula de Conformidad -->
    <div class="conformity-box">
        <strong>DECLARACIÓN DE CONFORMIDAD:</strong> El cliente abajo firmante certifica que los trabajos, servicios de mantenimiento o asistencias técnicas detalladas en este documento fueron ejecutados a entera satisfacción, constatando la operatividad de los sistemas atendidos y otorgando su visto bueno y cierre formal al requerimiento.
    </div>

    <!-- Sección de Firmas -->
    <table class="signatures-table" cellpadding="0" cellspacing="0">
        <tr>
            <!-- Firma del Técnico -->
            <td class="signature-cell">
                <div class="signature-img-container">
                    <div style="font-style: italic; color: #64748b; font-size: 11px; padding-top: 25px;">
                        Verificado por Soporte
                    </div>
                </div>
                <div class="signature-line"></div>
                <div class="signature-name">
                    @if($ticket->staff)
                        {{ $ticket->staff->firstname }} {{ $ticket->staff->lastname }}
                    @else
                        Técnico Encargado
                    @endif
                </div>
                <div class="signature-role">Firma del Técnico Responsable</div>
            </td>

            <!-- Firma del Cliente -->
            <td class="signature-cell">
                <div class="signature-img-container">
                    @if($firmaBase64)
                        <img src="{{ $firmaBase64 }}" alt="Firma Digital" class="signature-img">
                    @else
                        <div style="color: #94a3b8; font-size: 9px; padding-top: 25px;">
                            (Firma Digital Registrada en Sistema)
                        </div>
                    @endif
                </div>
                <div class="signature-line"></div>
                <div class="signature-name">{{ $ticket->user->name ?? 'Cliente' }}</div>
                <div class="signature-role">
                    Firma de Conformidad del Cliente<br>
                    <span style="font-size: 7.5px; color: #94a3b8;">
                        Registrada: {{ $ticket->closed ? \Carbon\Carbon::parse($ticket->closed)->format('d/m/Y h:i:s A') : now()->format('d/m/Y h:i:s A') }}
                    </span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Pie de Página -->
    <div class="footer">
        Documento generado automáticamente por {{ $nombreEmpresa }} | Fecha de emisión: {{ $fechaGeneracion }} | Ticket #{{ $ticket->ticket_number }}
    </div>

</body>
</html>
