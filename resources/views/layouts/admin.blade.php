<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelana ERP - Enterprise Administration</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }
    </style>
</head>
<body class="bg-[#f3f4f0] text-near-black flex min-h-screen antialiased">
    
    <!-- SIDEBAR KIRI (Deep Dark Forest Green like Mockup) -->
    <aside class="w-72 bg-[#0b1611] text-[#8e8e93] fixed h-full p-6 flex flex-col justify-between z-50 border-r border-[#dfdfd6]/10">
        <div class="space-y-8">
            <!-- Brand Logo with Siohioma Asterisk SVG -->
            <div class="flex items-center space-x-3 px-3 py-2">
                <svg class="w-6 h-6 text-[#1e5e3a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="2" x2="12" y2="22"></line>
                    <line x1="12" y1="12" x2="2" y2="12"></line>
                    <line x1="12" y1="12" x2="22" y2="12"></line>
                    <line x1="12" y1="12" x2="4.93" y2="4.93"></line>
                    <line x1="12" y1="12" x2="19.07" y2="19.07"></line>
                    <line x1="12" y1="12" x2="4.93" y2="19.07"></line>
                    <line x1="12" y1="12" x2="19.07" y2="4.93"></line>
                </svg>
                <span class="text-xl font-bold tracking-tight text-white font-sans">Kelana</span>
            </div>

            <!-- Nav Links with Active Indicator Line on the left side -->
            <nav class="space-y-1">
                <span class="text-[10px] uppercase font-bold text-stone/40 px-3 block mb-3 tracking-widest font-sans">Menu ERP</span>

                <a href="{{ route('admin.dashboard') }}" class="relative flex items-center px-4 py-3 transition-all duration-200 text-sm {{ request()->routeIs('admin.dashboard') ? 'text-white font-semibold' : 'hover:text-white font-medium' }}">
                    @if(request()->routeIs('admin.dashboard'))
                        <span class="absolute left-0 top-3.5 bottom-3.5 w-1 bg-[#1e5e3a] rounded-r-full"></span>
                    @endif
                    <svg class="w-5 h-5 mr-3.5 {{ request()->routeIs('admin.dashboard') ? 'text-[#1e5e3a]' : 'text-stone/40' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/>
                    </svg>
                    Overview
                </a>
                
                <a href="{{ route('admin.paket.index') }}" class="relative flex items-center px-4 py-3 transition-all duration-200 text-sm {{ request()->routeIs('admin.paket.*') ? 'text-white font-semibold' : 'hover:text-white font-medium' }}">
                    @if(request()->routeIs('admin.paket.*'))
                        <span class="absolute left-0 top-3.5 bottom-3.5 w-1 bg-[#1e5e3a] rounded-r-full"></span>
                    @endif
                    <svg class="w-5 h-5 mr-3.5 {{ request()->routeIs('admin.paket.*') ? 'text-[#1e5e3a]' : 'text-stone/40' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Master Paket Wisata
                </a>

                <a href="{{ route('admin.jadwal.index') }}" class="relative flex items-center px-4 py-3 transition-all duration-200 text-sm {{ request()->routeIs('admin.jadwal.*') ? 'text-white font-semibold' : 'hover:text-white font-medium' }}">
                    @if(request()->routeIs('admin.jadwal.*'))
                        <span class="absolute left-0 top-3.5 bottom-3.5 w-1 bg-[#1e5e3a] rounded-r-full"></span>
                    @endif
                    <svg class="w-5 h-5 mr-3.5 {{ request()->routeIs('admin.jadwal.*') ? 'text-[#1e5e3a]' : 'text-stone/40' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Jadwal & Penugasan
                </a>

                <a href="{{ route('admin.transaksi.index') }}" class="relative flex items-center px-4 py-3 transition-all duration-200 text-sm {{ request()->routeIs('admin.transaksi.*') ? 'text-white font-semibold' : 'hover:text-white font-medium' }}">
                    @if(request()->routeIs('admin.transaksi.*'))
                        <span class="absolute left-0 top-3.5 bottom-3.5 w-1 bg-[#1e5e3a] rounded-r-full"></span>
                    @endif
                    <svg class="w-5 h-5 mr-3.5 {{ request()->routeIs('admin.transaksi.*') ? 'text-[#1e5e3a]' : 'text-stone/40' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Transaksi Pemesanan
                </a>

                <a href="{{ route('admin.messages.index') }}" class="relative flex items-center px-4 py-3 transition-all duration-200 text-sm {{ request()->routeIs('admin.messages.*') ? 'text-white font-semibold' : 'hover:text-white font-medium' }}">
                    @if(request()->routeIs('admin.messages.*'))
                        <span class="absolute left-0 top-3.5 bottom-3.5 w-1 bg-[#1e5e3a] rounded-r-full"></span>
                    @endif
                    <svg class="w-5 h-5 mr-3.5 {{ request()->routeIs('admin.messages.*') ? 'text-[#1e5e3a]' : 'text-stone/40' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    Pesan Customer Service
                </a>

                <a href="{{ route('admin.customers.index') }}" class="relative flex items-center px-4 py-3 transition-all duration-200 text-sm {{ request()->routeIs('admin.customers.*') ? 'text-white font-semibold' : 'hover:text-white font-medium' }}">
                    @if(request()->routeIs('admin.customers.*'))
                        <span class="absolute left-0 top-3.5 bottom-3.5 w-1 bg-[#1e5e3a] rounded-r-full"></span>
                    @endif
                    <svg class="w-5 h-5 mr-3.5 {{ request()->routeIs('admin.customers.*') ? 'text-[#1e5e3a]' : 'text-stone/40' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Data Customer
                </a>

                <a href="{{ route('admin.laporan.index') }}" class="relative flex items-center px-4 py-3 transition-all duration-200 text-sm {{ request()->routeIs('admin.laporan.index') ? 'text-white font-semibold' : 'hover:text-white font-medium' }}">
                    @if(request()->routeIs('admin.laporan.index'))
                        <span class="absolute left-0 top-3.5 bottom-3.5 w-1 bg-[#1e5e3a] rounded-r-full"></span>
                    @endif
                    <svg class="w-5 h-5 mr-3.5 {{ request()->routeIs('admin.laporan.index') ? 'text-[#1e5e3a]' : 'text-stone/40' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Laporan Finansial
                </a>
            </nav>
        </div>

        <!-- User profile & Logout Footer -->
        <div class="space-y-5 pt-6 border-t border-[#dfdfd6]/10">
            <div class="flex items-center space-x-3 px-2">
                <img class="w-9 h-9 rounded-full object-cover bg-electric-lime border border-[#dfdfd6]/20" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80" alt="Avatar">
                <div class="overflow-hidden flex-grow">
                    <p class="font-bold text-sm text-white truncate leading-tight">{{ Auth::guard('admin')->user()->name ?? 'Fandaww Punx' }}</p>
                    <p class="text-xs text-[#8e8e93]/70 truncate mt-0.5">{{ Auth::guard('admin')->user()->email ?? 'fandaww6@gmail.com' }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-center py-3 rounded-xl bg-red-600/10 text-red-400 font-bold hover:bg-red-600 hover:text-white transition-all duration-200 text-xs border border-red-600/20">🚪 Logout System</button>
            </form>
        </div>
    </aside>

    <!-- KONTEN KANAN -->
    <div class="flex-grow ml-72 flex flex-col min-h-screen max-w-[calc(100%-18rem)] overflow-x-hidden">
        <!-- Top bar (Clean White with search & header actions from mockup) -->
        @php
            $actionButton = null;

            if (request()->routeIs('admin.paket.*')) {
                $actionButton = [
                    'label' => 'Add new product',
                    'url' => route('admin.paket.create')
                ];
            } elseif (request()->routeIs('admin.jadwal.*')) {
                $actionButton = [
                    'label' => 'Add new trip',
                    'url' => route('admin.jadwal.create')
                ];
            }
        @endphp
        <header class="w-full bg-white px-8 py-4 flex justify-between items-center border-b border-[#dfdfd6] sticky top-0 z-40">
            <!-- Search bar inside Capsule (Left) -->
            <form method="GET" action="{{ request()->url() }}" class="hidden md:flex items-center bg-[#f4f3ed]/60 rounded-full px-4 py-2 w-96 border border-[#dfdfd6]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search anything in Kelana ERP..." class="bg-transparent border-0 focus:ring-0 text-xs text-near-black placeholder-graphite/40 w-full outline-none p-0">
                <button type="submit" class="focus:outline-none" aria-label="Search">
                    <svg class="w-4 h-4 text-graphite ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
            </form>
 
            <!-- Right Actions (Right) -->
            <div class="flex items-center space-x-6">
                <!-- Add New Product / Trip Button (Context Aware) -->
                @if($actionButton)
                    <a href="{{ $actionButton['url'] }}" class="bg-near-black text-white hover:bg-[#1e5e3a] text-xs font-bold px-4 py-2.5 rounded-full transition duration-150 flex items-center space-x-1.5">
                        <span>{{ $actionButton['label'] }}</span>
                        <span class="w-4 h-4 rounded-full bg-white/20 flex items-center justify-center text-[10px] font-black">+</span>
                    </a>
                @endif

                <!-- Workspace Dropdown (Sales Admin) -->
                <div class="relative" x-data="{ openWorkspace: false }">
                    <button @click="openWorkspace = !openWorkspace" class="flex items-center text-sm font-bold text-near-black focus:outline-none hover:opacity-80 transition duration-150">
                        <span>Sales Admin</span>
                        <svg class="w-4 h-4 ml-1 text-graphite transition-transform duration-200" :class="openWorkspace ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="openWorkspace" 
                         @click.away="openWorkspace = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2.5 w-48 bg-white border border-[#dfdfd6] rounded-xl py-2 shadow-lg z-50 text-xs font-semibold"
                         style="display: none;">
                        <div class="px-4 py-1.5 text-[10px] text-graphite uppercase tracking-wider border-b border-[#dfdfd6] mb-1">
                            Active Workspace
                        </div>
                        <span class="block px-4 py-2 text-[#1e5e3a] bg-stone/20 font-bold">
                            Sales Admin (ERP)
                        </span>
                        <a href="{{ url('/') }}" class="block px-4 py-2 text-near-black hover:bg-stone/10 transition">
                            View Public Site
                        </a>
                        <div class="border-t border-[#dfdfd6] my-1"></div>
                        <form method="POST" action="{{ route('logout') }}" id="workspace-logout-form">
                            @csrf
                            <button type="submit" class="w-full text-left block px-4 py-2 text-red-600 hover:bg-red-50 transition">
                                Logout System
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-8 flex-grow">
            <!-- GLOBAL ALERT COMPONENT (ALPINE.JS) -->
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)" class="bg-[#1e5e3a] text-white p-4.5 rounded-xl mb-6 flex justify-between items-center text-xs font-semibold">
                    <p>✅ {{ session('success') }}</p>
                    <button @click="show = false" class="text-white font-bold px-2 py-1 bg-black/10 rounded-full">Tutup</button>
                </div>
            @endif
            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition class="bg-red-600 text-white p-4.5 rounded-xl mb-6 flex justify-between items-center text-xs font-semibold">
                    <p>❌ {{ session('error') }}</p>
                    <button @click="show = false" class="text-white font-bold px-2 py-1 bg-black/10 rounded-full">Tutup</button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
