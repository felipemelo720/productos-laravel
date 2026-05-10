<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Clandent PIM')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { --color-brand: #31A6A8; }
        .btn-brand { @apply bg-teal-500 hover:bg-teal-600 text-white; }
    </style>
    @yield('head')
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-xl font-bold" style="color: #31A6A8;">Clandent</a>
                    <div class="ml-10 flex space-x-4">
                        @auth
                            <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-teal-600">Products</a>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('users.index') }}" class="text-gray-700 hover:text-teal-600">Users</a>
                            @endif
                        @endauth
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <span class="text-gray-700">{{ auth()->user()->full_name }}</span>
                        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-teal-600">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-teal-600">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($message = session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ $message }}</div>
        @endif

        @if($message = session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">{{ $message }}</div>
        @endif

        @yield('content')
    </div>
</body>
</html>
