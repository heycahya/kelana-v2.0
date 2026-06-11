<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Kelana') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-near-black antialiased h-screen overflow-hidden bg-white">
        <div class="flex h-full w-full">
            <!-- Kolom Kiri: Form -->
            <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 sm:px-16 lg:px-24 xl:px-32 relative overflow-y-auto bg-white py-12">
                <div class="absolute top-8 left-8 sm:left-16 lg:left-24 xl:left-32">
                    <a href="/" class="flex items-center text-xl font-bold tracking-tight text-near-black hover:opacity-80 transition gap-2">
                        <div class="w-8 h-8 bg-electric-lime rounded-[8px] flex items-center justify-center text-near-black text-sm">K</div>
                        Kelana
                    </a>
                </div>
                
                <div class="w-full max-w-[400px] mx-auto mt-12 lg:mt-0">
                    {{ $slot }}
                </div>
            </div>

            <!-- Kolom Kanan: Gambar Cover (Fullscreen Split) -->
            <div class="hidden lg:block lg:w-1/2 relative bg-warm-cream p-6 xl:p-8">
                <img src="https://images.unsplash.com/photo-1506012787146-f92b2d7d6d96?auto=format&fit=crop&w=1200&q=80" class="w-full h-full object-cover rounded-[26px]" alt="Kelana Destination">
            </div>
        </div>
    </body>
</html>
