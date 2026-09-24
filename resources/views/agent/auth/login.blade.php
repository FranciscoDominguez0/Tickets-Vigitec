<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#b91c1c">
    <title>Login Agente - {{ config('app.name', 'Tickets') }}</title>
    <link rel="stylesheet" href="{{ asset('css/agent-login.css') }}">
    <link rel="preload" as="image" href="{{ asset('img/vigitec-logo.webp') }}">
    <link rel="preload" as="image" href="{{ asset('img/agent-background.webp') }}">
    <style>
        :root {
            --accent-rgb: 239, 68, 68;
            --accent-color: rgb(239, 68, 68);
            --accent-hover: rgb(220, 38, 38);
            --accent-glow: rgba(239, 68, 68, 0.35);
            --accent-glow-soft: rgba(239, 68, 68, 0.12);
        }
        
        /* ── Modern Premium Base Styling ── */
        body.agent-login {
            background-color: #000000 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-height: 100vh;
        }
        .agent-login-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 0 16px;
        }
        .agent-login-subtext {
            text-align: center;
            margin: -6px 0 24px;
            color: rgba(255, 255, 255, 0.95);
            font-weight: 750;
            letter-spacing: 0.02em;
            font-size: 13px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.4);
        }
        .agent-login-brand img {
            height: 58px;
            width: auto;
            max-width: 100%;
            object-fit: contain;
            display: block;
            filter: drop-shadow(0 10px 30px rgba(0,0,0,0.3));
        }

        /* ── Dynamic Intelligent Accent Theme Mapping ── */
        .agent-login-panel {
            background: rgba(9, 9, 11, 0.45) !important;
            backdrop-filter: blur(20px) saturate(140%) !important;
            -webkit-backdrop-filter: blur(20px) saturate(140%) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6) !important;
            position: relative;
            padding: 50px 40px !important;
        }
        .agent-login-panel:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 35px 70px rgba(0, 0, 0, 0.7) !important;
            border-color: rgba(255, 255, 255, 0.12) !important;
        }
        .agent-login-panel::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-hover), var(--accent-color));
            z-index: 10;
        }
        .agent-btn-login {
            background: linear-gradient(135deg, var(--accent-hover) 0%, var(--accent-color) 100%) !important;
            box-shadow: 0 6px 20px var(--accent-glow-soft), 0 2px 8px rgba(0,0,0,0.2) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            -webkit-tap-highlight-color: transparent !important;
            width: 100%;
            padding: 12px 20px;
            color: white;
            border-radius: 999px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 1rem;
        }
        .agent-btn-login:hover:not(.loading) {
            transform: translateY(-2px) scale(1.01) !important;
            box-shadow: 0 10px 30px var(--accent-glow), 0 4px 12px var(--accent-glow-soft) !important;
            background: linear-gradient(135deg, var(--accent-color) 0%, var(--accent-hover) 100%) !important;
        }
        .agent-form-group {
            position: relative;
            margin-bottom: 1rem;
            display: flex;
            flex-direction: column;
        }
        .agent-form-group label {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 13px;
        }
        .agent-form-group input {
            background: rgba(255, 255, 255, 0.03) !important;
            border: none !important;
            border-bottom: 2px solid rgba(255, 255, 255, 0.12) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            border-radius: 8px 8px 0 0 !important;
            padding: 14px 16px 14px 16px !important;
            color: #ffffff !important;
            font-weight: 500 !important;
            width: 100%;
            box-sizing: border-box;
        }
        .agent-form-group input:focus {
            background: rgba(255, 255, 255, 0.06) !important;
            border-bottom-color: var(--accent-color) !important;
            box-shadow: 0 4px 15px var(--accent-glow-soft) !important;
            transform: none !important;
            outline: none;
        }
        .agent-form-group input:focus + .agent-input-icon {
            color: var(--accent-color) !important;
            opacity: 1 !important;
            transform: translateY(-50%) scale(1.05) !important;
        }
        .agent-input-icon {
            position: absolute;
            right: 14px;
            top: 65%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            color: rgba(255,255,255,0.4);
            pointer-events: none;
        }
        #togglePasswordAgent {
            pointer-events: auto !important;
            transition: all 0.2s !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            top: 75%;
        }
        #togglePasswordAgent:hover svg {
            color: var(--accent-color) !important;
            opacity: 1 !important;
            transform: scale(1.1) !important;
        }
        .back-btn {
            background: rgba(9, 9, 11, 0.45) !important;
            backdrop-filter: blur(15px) !important;
            -webkit-backdrop-filter: blur(15px) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            transition: all 0.2s !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2) !important;
        }
        .back-btn:hover {
            background: var(--accent-color) !important;
            border-color: var(--accent-color) !important;
            box-shadow: 0 4px 15px var(--accent-glow) !important;
            transform: translateY(-1px) !important;
        }
    </style>
</head>
<body class="agent-login" style="background-image:url('{{ asset('img/agent-background.webp') }}'); background-size: cover; background-position: center;">
    <div class="back-to-user-login" style="position:fixed;top:10px;left:10px;z-index:9999;">
        <a href="{{ route('login') }}" class="back-btn" style="display:inline-flex;align-items:center;gap:8px;padding:10px 14px;background:rgba(255,255,255,0.15);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,0.2);border-radius:10px;color:#fff;text-decoration:none;font-weight:600;font-size:14px;line-height:1;">
            <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:block;flex:0 0 auto;">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            <span>Login de Cliente</span>
        </a>
    </div>

    <!-- CONTENEDOR PRINCIPAL -->
    <div class="agent-login-container">
        <!-- PANEL DE LOGIN (GLASSMORPHISM) -->
        <div class="agent-login-panel">
            <div class="agent-login-brand">
                <img src="{{ asset('img/vigitec-logo.webp') }}" alt="{{ config('app.name', 'Tickets') }}" loading="eager" fetchpriority="high" decoding="async">
            </div>
            <div class="agent-login-subtext">Acceso de Agentes</div>
            
            <form method="post" action="{{ route('agent.login') }}" class="agent-login-form">
                @csrf
                
                @if ($errors->any())
                    <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #f87171; padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 13px;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- Usuario -->
                <div class="agent-form-group">
                    <label for="username">Usuario</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="Usuario"
                        value="{{ old('username') }}"
                        required
                    >
                    <svg class="agent-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>

                <!-- Contraseña -->
                <div class="agent-form-group">
                    <label for="password">Contraseña</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Contraseña"
                        required
                        style="padding-right: 40px;"
                    >
                    <button type="button" id="togglePasswordAgent" class="agent-input-icon" tabindex="-1" style="background: none; border: none; cursor: pointer; padding: 0; pointer-events: auto; right: 14px; top: 65%;">
                        <svg id="eyeIconAgent" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>

                <!-- Botón Login -->
                <button type="submit" class="agent-btn-login">Inicia sesión</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toggleBtn = document.getElementById('togglePasswordAgent');
            var pwdInput = document.getElementById('password');
            if(toggleBtn && pwdInput) {
                toggleBtn.addEventListener('click', function() {
                    var isPassword = pwdInput.getAttribute('type') === 'password';
                    pwdInput.setAttribute('type', isPassword ? 'text' : 'password');
                    if (isPassword) {
                        this.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>';
                    } else {
                        this.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
                    }
                });
            }
        });

        // ── Extractor Inteligente de Colores de Fondo ──
        (function() {
            var bgUrl = "{{ asset('img/agent-background.webp') }}";
            
            function extractColors(url) {
                var img = new Image();
                img.crossOrigin = "Anonymous";
                img.onload = function() {
                    var canvas = document.createElement('canvas');
                    canvas.width = 16;
                    canvas.height = 16;
                    var ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, 16, 16);
                    var data;
                    try {
                        data = ctx.getImageData(0, 0, 16, 16).data;
                    } catch(e) {
                        applyColor({r: 239, g: 68, b: 68});
                        return;
                    }
                    
                    var bestColor = null;
                    var maxVibrancy = -1;
                    
                    for (var i = 0; i < data.length; i += 4) {
                        var r = data[i];
                        var g = data[i+1];
                        var b = data[i+2];
                        var a = data[i+3];
                        if (a < 220) continue; 
                        
                        var max = Math.max(r, g, b);
                        var min = Math.min(r, g, b);
                        var chroma = max - min;
                        
                        if (max < 60 || max > 235) continue;
                        if (chroma < 30) continue;
                        
                        var vibrancy = chroma;
                        if (vibrancy > maxVibrancy) {
                            maxVibrancy = vibrancy;
                            bestColor = {r: r, g: g, b: b};
                        }
                    }
                    
                    if (!bestColor) {
                        var sumR = 0, sumG = 0, sumB = 0, count = 0;
                        for (var i = 0; i < data.length; i += 4) {
                            if (data[i+3] > 200) {
                                sumR += data[i];
                                sumG += data[i+1];
                                sumB += data[i+2];
                                count++;
                            }
                        }
                        if (count > 0) {
                            bestColor = {
                                r: Math.round(sumR / count),
                                g: Math.round(sumG / count),
                                b: Math.round(sumB / count)
                            };
                        } else {
                            bestColor = {r: 239, g: 68, b: 68};
                        }
                    }
                    
                    applyColor(bestColor);
                };
                img.onerror = function() {
                    applyColor({r: 239, g: 68, b: 68});
                };
                img.src = url;
            }
            
            function applyColor(color) {
                var r = color.r;
                var g = color.g;
                var b = color.b;
                
                var brightness = (r * 299 + g * 587 + b * 114) / 1000;
                if (brightness < 80) {
                    r = Math.min(255, r + 40);
                    g = Math.min(255, g + 40);
                    b = Math.min(255, b + 40);
                }
                
                var root = document.documentElement;
                root.style.setProperty('--accent-rgb', r + ', ' + g + ', ' + b);
                root.style.setProperty('--accent-color', 'rgb(' + r + ', ' + g + ', ' + b + ')');
                
                var hoverFactor = brightness > 150 ? 0.82 : 1.18;
                var hr = Math.min(255, Math.max(0, Math.round(r * hoverFactor)));
                var hg = Math.min(255, Math.max(0, Math.round(g * hoverFactor)));
                var hb = Math.min(255, Math.max(0, Math.round(b * hoverFactor)));
                
                root.style.setProperty('--accent-hover', 'rgb(' + hr + ', ' + hg + ', ' + hb + ')');
                root.style.setProperty('--accent-glow', 'rgba(' + r + ', ' + g + ', ' + b + ', 0.35)');
                root.style.setProperty('--accent-glow-soft', 'rgba(' + r + ', ' + g + ', ' + b + ', 0.12)');
                
                document.body.style.opacity = '1';
            }
            
            document.body.style.opacity = '0.01';
            document.body.style.transition = 'opacity 0.35s ease';
            
            extractColors(bgUrl);
        })();
    </script>
</body>
</html>
