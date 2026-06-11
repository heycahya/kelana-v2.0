<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Kelana') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-warm-cream text-near-black font-sans antialiased min-h-screen flex flex-col">
        <!-- Top Navbar -->
        <nav class="sticky top-0 z-50 bg-warm-cream border-b border-stone py-4 px-6 flex justify-between items-center">
            <!-- Kiri (Logo) -->
            <a href="/" class="text-2xl font-bold text-near-black tracking-tight hover:opacity-80 transition">Kelana</a>
            
            <!-- Tengah (Menu) -->
            <div class="hidden md:flex space-x-6">
                <a href="/" class="text-near-black font-medium hover:text-graphite transition">Home</a>
            </div>

            <!-- Kanan (Auth) -->
            <div class="flex items-center">
                @if (Auth::guard('customer')->check())
                    <a href="{{ route('dashboard') }}" class="px-6 py-2 bg-electric-lime text-near-black rounded-3xl hover:opacity-80 transition font-medium">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="ml-3">
                        @csrf
                        <button type="submit" class="px-6 py-2 border border-near-black text-near-black rounded-3xl hover:bg-stone transition font-medium">Logout</button>
                    </form>
                @elseif (Auth::guard('admin')->check())
                    <a href="{{ route('admin.dashboard') }}" class="px-6 py-2 bg-electric-lime text-near-black rounded-3xl hover:opacity-80 transition font-medium">Admin Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="ml-3">
                        @csrf
                        <button type="submit" class="px-6 py-2 border border-near-black text-near-black rounded-3xl hover:bg-stone transition font-medium">Logout</button>
                    </form>
                @elseif (Auth::guard('trip_leader')->check())
                    <a href="{{ route('trip_leader.dashboard') }}" class="px-6 py-2 bg-electric-lime text-near-black rounded-3xl hover:opacity-80 transition font-medium">Leader Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="ml-3">
                        @csrf
                        <button type="submit" class="px-6 py-2 border border-near-black text-near-black rounded-3xl hover:bg-stone transition font-medium">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-6 py-2 border border-near-black text-near-black rounded-3xl hover:bg-stone transition font-medium">Login</a>
                    <a href="{{ route('register') }}" class="px-6 py-2 bg-electric-lime text-near-black rounded-3xl ml-3 hover:opacity-80 transition font-medium">Register</a>
                @endif
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-grow flex flex-col justify-center items-center">
            @if(request()->routeIs('login') || request()->routeIs('register') || request()->routeIs('password.request') || request()->routeIs('password.reset'))
                <div class="w-full sm:max-w-md px-6 py-8 bg-white border border-stone rounded-3xl my-10">
                    {{ $slot }}
                </div>
            @else
                <div class="w-full">
                    {{ $slot }}
                </div>
            @endif
        </main>
    </body>
</html>
