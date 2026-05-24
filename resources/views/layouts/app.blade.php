<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Clandent PIM')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('head')
</head>
<body class="bg-gray-50">

    {{-- Header --}}
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">

                {{-- Brand + Nav --}}
                <div class="flex items-center gap-8">
                    <a href="/" class="flex items-center gap-2">
                        <span class="text-base font-bold" style="color:#31A6A8;">Clandent</span>
                        <span class="text-xs font-medium bg-teal-50 text-teal-600 px-1.5 py-0.5 rounded">PIM</span>
                    </a>

                    @auth
                    <nav class="flex items-center gap-1">
                        <a href="{{ route('products.index') }}"
                           class="flex items-center gap-2 px-3 py-1.5 rounded-md text-sm font-medium transition-colors
                                  {{ request()->routeIs('products.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            Products
                        </a>

                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('users.index') }}"
                           class="flex items-center gap-2 px-3 py-1.5 rounded-md text-sm font-medium transition-colors
                                  {{ request()->routeIs('users.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Users
                        </a>
                        @endif
                    </nav>
                    @endauth
                </div>

                {{-- User --}}
                @auth
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-semibold shrink-0"
                             style="background:#f0fafa; color:#31A6A8;">
                            {{ strtoupper(substr(auth()->user()->full_name, 0, 1)) }}
                        </div>
                        <span class="text-sm text-gray-700">{{ auth()->user()->full_name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-400 hover:text-gray-600 transition-colors">
                            Sign out
                        </button>
                    </form>
                </div>
                @endauth
            </div>
        </div>
    </header>

    {{-- Main --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($message = session('success'))
            <div class="mb-6 flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $message }}
            </div>
        @endif

        @if($message = session('warning'))
            <div class="mb-6 flex items-center gap-3 p-4 bg-yellow-50 border border-yellow-200 text-yellow-700 rounded-lg text-sm">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                {{ $message }}
            </div>
        @endif

        @if($message = session('error'))
            <div class="mb-6 flex items-center gap-3 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $message }}
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>
