<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Kelana') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-near-black antialiased h-screen overflow-hidden bg-warm-cream">
        <div class="flex h-full w-full">
            
            <!-- Left Column: Content (50% Split) -->
            <div class="w-full lg:w-1/2 flex flex-col justify-between p-8 sm:p-12 md:p-16 lg:p-20 relative overflow-y-auto bg-warm-cream">
                
                <!-- Top Header: Back Button on the Left -->
                <div class="flex items-center justify-start">
                    <!-- Consistent Back Button with Text -->
                    <a href="/" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full border border-transparent bg-stone/50 text-xs font-semibold uppercase tracking-wider text-near-black hover:bg-near-black hover:text-white transition-all duration-300 ease-in-out">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                        <span>Back</span>
                    </a>
                </div>
                
                <!-- Form Area (No Nested Card Container, direct layout) -->
                <div class="my-auto w-full max-w-[360px] mx-auto py-8">
                    <!-- Enlarged Typography Branding above Welcome header -->
                    <div class="mb-8">
                        <a href="/" class="text-4xl font-bold tracking-tight text-near-black hover:opacity-85 transition-opacity">
                            Kelana
                        </a>
                    </div>
                    
                    {{ $slot }}
                </div>

            </div>

            <!-- Right Column: Full-Bleed Split Screen Image (50% Split) -->
            <div class="hidden lg:block lg:w-1/2 h-full relative overflow-hidden bg-near-black">
                <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=1200&q=80" 
                     class="w-full h-full object-cover transition-transform duration-[8000ms] hover:scale-105 ease-out" 
                     alt="Kelana Nature Journey">
                
                <!-- Premium Subtle Overlay for Image Depth -->
                <div class="absolute inset-0 bg-gradient-to-t from-near-black/30 via-transparent to-transparent"></div>
            </div>
            
        </div>
    </body>
</html>
