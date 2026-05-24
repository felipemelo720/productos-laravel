<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clandent PIM — Acceso</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,600;1,300;1,600&display=swap" rel="stylesheet">
    <style>
        .brand-serif { font-family: 'Cormorant Garamond', Georgia, serif; }

        .panel-left {
            background: linear-gradient(145deg, #0a2e30 0%, #16484a 55%, #1e6264 100%);
            position: relative;
            overflow: hidden;
        }

        .deco-ring {
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(49, 166, 168, 0.18);
            pointer-events: none;
        }

        .input-field {
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }
        .input-field:focus {
            border-color: #31A6A8;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(49, 166, 168, 0.12);
            outline: none;
        }
        .input-field::placeholder { color: #b0b8c1; }

        .btn-login {
            background: #31A6A8;
            transition: background 0.2s ease, box-shadow 0.2s ease, transform 0.1s ease;
        }
        .btn-login:hover {
            background: #2a9193;
            box-shadow: 0 6px 24px rgba(49, 166, 168, 0.38);
        }
        .btn-login:active { transform: translateY(1px); }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .slide-up { animation: slideUp 0.5s cubic-bezier(.22,.68,0,1.2) both; }

        @keyframes panelIn {
            from { opacity: 0; transform: translateX(-24px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        .panel-in { animation: panelIn 0.7s cubic-bezier(.22,.68,0,1.2) both; }
    </style>
</head>
<body class="min-h-screen flex bg-white">

    {{-- Left panel --}}
    <div class="panel-left panel-in hidden lg:flex lg:w-5/12 xl:w-[45%] flex-col justify-between p-12 xl:p-16">

        {{-- Decorative rings --}}
        <div class="deco-ring" style="width:520px;height:520px;top:-160px;right:-200px;"></div>
        <div class="deco-ring" style="width:300px;height:300px;top:-60px;right:-50px;border-color:rgba(49,166,168,0.28);"></div>
        <div class="deco-ring" style="width:700px;height:700px;bottom:-320px;left:-280px;border-color:rgba(49,166,168,0.10);"></div>
        <div class="deco-ring" style="width:180px;height:180px;bottom:100px;right:40px;border-color:rgba(255,255,255,0.07);"></div>

        {{-- Dot grid --}}
        <svg class="absolute inset-0 w-full h-full" style="opacity:0.08;" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <defs>
                <pattern id="dotgrid" x="0" y="0" width="28" height="28" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="1.4" fill="#31A6A8"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#dotgrid)"/>
        </svg>

        {{-- Top brand --}}
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <div style="width:32px;height:2px;background:#31A6A8;border-radius:2px;"></div>
                <span class="text-xs font-semibold tracking-[0.22em] uppercase" style="color:#31A6A8;">Sistema PIM</span>
            </div>
            <h1 class="brand-serif text-white leading-[0.92] tracking-tight"
                style="font-size: clamp(3.8rem, 5.5vw, 6rem); font-weight:300;">
                Clan<em style="font-style:italic; font-weight:300;">dent</em>
            </h1>
        </div>

        {{-- Middle copy --}}
        <div class="relative z-10">
            <p class="brand-serif text-white/50 leading-relaxed"
               style="font-size:1.3rem; font-weight:300; max-width:320px; font-style:italic;">
                Gestión centralizada de información de productos dentales.
            </p>
            <div class="mt-8 flex items-center gap-4">
                <div style="width:40px;height:1px;background:rgba(49,166,168,0.5);"></div>
                <span class="text-white/25 text-xs tracking-widest uppercase">Productos · Marcas · Exportación</span>
            </div>
        </div>

        {{-- Bottom --}}
        <div class="relative z-10 flex items-center justify-between">
            <span class="text-white/20 text-xs tracking-[0.18em] uppercase">© 2026 Clandent</span>
            <div class="flex gap-1.5">
                <div style="width:7px;height:7px;border-radius:50%;background:rgba(49,166,168,0.65);"></div>
                <div style="width:7px;height:7px;border-radius:50%;background:rgba(49,166,168,0.3);"></div>
                <div style="width:7px;height:7px;border-radius:50%;background:rgba(49,166,168,0.12);"></div>
            </div>
        </div>
    </div>

    {{-- Right form panel --}}
    <div class="flex-1 flex flex-col items-center justify-center px-6 py-14 sm:px-12 lg:px-16 xl:px-24">
        <div class="w-full max-w-[360px]">

            {{-- Mobile brand --}}
            <div class="lg:hidden mb-10 text-center slide-up" style="animation-delay:0s;">
                <h1 class="brand-serif font-light leading-none" style="font-size:3.5rem;color:#31A6A8;">
                    Clan<em>dent</em>
                </h1>
                <p class="text-xs tracking-[0.2em] text-gray-400 mt-2 uppercase">Sistema PIM</p>
            </div>

            {{-- Heading --}}
            <div class="mb-8 slide-up" style="animation-delay:0.04s;">
                <h2 class="text-[1.6rem] font-bold tracking-tight text-gray-900 leading-tight">
                    Bienvenido
                </h2>
                <p class="text-sm text-gray-400 mt-1.5">Ingresa tus credenciales para continuar</p>
            </div>

            {{-- Error --}}
            @if($errors->any())
            <div class="mb-6 slide-up flex items-start gap-3 px-4 py-3.5 bg-red-50 border border-red-200 rounded-2xl"
                 style="animation-delay:0.08s;">
                <svg class="w-4 h-4 text-red-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-red-700 leading-snug">{{ $errors->first() }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- Username --}}
                <div class="slide-up" style="animation-delay:0.1s;">
                    <label class="block text-[11px] font-semibold text-gray-400 uppercase tracking-[0.14em] mb-1.5">
                        Usuario
                    </label>
                    <input type="text"
                           name="username"
                           value="{{ old('username') }}"
                           required
                           autofocus
                           autocomplete="username"
                           placeholder="nombre de usuario"
                           class="input-field w-full px-4 py-3 text-sm bg-gray-50 border border-gray-200 rounded-xl text-gray-900">
                </div>

                {{-- Password --}}
                <div class="slide-up" style="animation-delay:0.16s;">
                    <label class="block text-[11px] font-semibold text-gray-400 uppercase tracking-[0.14em] mb-1.5">
                        Contraseña
                    </label>
                    <div class="relative">
                        <input type="password"
                               id="pwd"
                               name="password"
                               required
                               autocomplete="current-password"
                               placeholder="••••••••"
                               class="input-field w-full px-4 py-3 pr-11 text-sm bg-gray-50 border border-gray-200 rounded-xl text-gray-900">
                        <button type="button"
                                onclick="togglePwd()"
                                tabindex="-1"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors p-1 rounded"
                                aria-label="Mostrar contraseña">
                            <svg id="ico-show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="ico-hide" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="slide-up pt-2" style="animation-delay:0.22s;">
                    <button type="submit"
                            class="btn-login w-full py-3 rounded-xl text-white text-sm font-semibold tracking-wide">
                        Ingresar
                    </button>
                </div>
            </form>

            {{-- Footer --}}
            <p class="slide-up mt-10 text-center text-xs text-gray-300 tracking-wide" style="animation-delay:0.28s;">
                Clandent PIM &mdash; Uso interno
            </p>
        </div>
    </div>

    <script>
        function togglePwd() {
            const f = document.getElementById('pwd');
            const s = document.getElementById('ico-show');
            const h = document.getElementById('ico-hide');
            if (f.type === 'password') {
                f.type = 'text'; s.classList.add('hidden'); h.classList.remove('hidden');
            } else {
                f.type = 'password'; s.classList.remove('hidden'); h.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
