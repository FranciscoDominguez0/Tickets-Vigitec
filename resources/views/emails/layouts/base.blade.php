<!DOCTYPE html>
<html lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', config('app.name', 'Sistema de Tickets'))</title>
    <style>
        /* Estilos generales compatibles con clientes de correo */
        body {
            margin: 0;
            padding: 0;
            background-color: #0b0f19;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #334155;
            -webkit-font-smoothing: antialiased;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }
        table {
            border-spacing: 0;
            border-collapse: collapse;
        }
        td {
            padding: 0;
        }
        img {
            border: 0;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #0b0f19;
            padding-bottom: 40px;
        }
        .main-container {
            max-width: 620px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.4);
        }
        .header-bar {
            background: linear-gradient(135deg, #1e1b4b 0%, #0f172a 50%, #020617 100%);
            padding: 30px 35px 25px 35px;
            text-align: left;
            border-bottom: 3px solid #ef4444;
        }
        .brand-title {
            color: #ffffff;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin: 0;
            text-decoration: none;
        }
        .header-subtitle {
            color: #94a3b8;
            font-size: 13px;
            margin: 6px 0 0 0;
        }
        .content-area {
            padding: 35px 35px 25px 35px;
            background-color: #ffffff;
        }
        .info-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 18px 20px;
            margin: 20px 0;
        }
        .meta-row {
            padding: 5px 0;
            font-size: 13.5px;
        }
        .meta-label {
            font-weight: 600;
            color: #64748b;
            width: 130px;
            display: inline-block;
        }
        .meta-value {
            color: #0f172a;
            font-weight: 500;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-red { background-color: #fee2e2; color: #dc2626; }
        .badge-green { background-color: #dcfce7; color: #16a34a; }
        .badge-blue { background-color: #dbeafe; color: #2563eb; }
        .badge-amber { background-color: #fef3c7; color: #d97706; }
        .badge-purple { background-color: #f3e8ff; color: #9333ea; }

        .btn-cta {
            display: inline-block;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #ffffff !important;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
            margin: 20px 0 10px 0;
        }
        .footer-bar {
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 22px 35px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            line-height: 1.5;
        }
        .footer-bar a {
            color: #ef4444;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top: 30px;">
            <tr>
                <td align="center">
                    <table class="main-container" role="presentation" width="100%" cellpadding="0" cellspacing="0">
                        <!-- Header -->
                        <tr>
                            <td class="header-bar">
                                <div class="brand-title">
                                    @yield('encabezado_marca', config('app.name', 'Sistema de Tickets'))
                                </div>
                                <div class="header-subtitle">
                                    @yield('subtitulo_marca', 'Centro de Soporte y Gestión de Requerimientos')
                                </div>
                            </td>
                        </tr>

                        <!-- Body Content -->
                        <tr>
                            <td class="content-area">
                                @yield('contenido')
                            </td>
                        </tr>

                        <!-- Footer -->
                        <tr>
                            <td class="footer-bar">
                                <div>Este es un correo automático generado por el <strong>{{ config('app.name', 'Sistema de Tickets') }}</strong>.</div>
                                <div style="margin-top: 4px;">Por favor, no respondas directamente a este mensaje a menos que el canal esté habilitado para recepción.</div>
                                <div style="margin-top: 10px; font-size: 11px; color: #cbd5e1;">
                                    &copy; {{ date('Y') }} Todos los derechos reservados.
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>

