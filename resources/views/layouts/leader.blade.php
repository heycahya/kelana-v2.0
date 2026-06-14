<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Field Ops - Kelana Trip Leader</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }
    </style>
</head>
<body class="bg-warm-cream text-near-black flex min-h-screen antialiased">
    
    <!-- LEADER SIDEBAR KIRI -->
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

            <!-- Nav Links -->
            <nav class="space-y-1">
                <span class="text-[10px] uppercase font-bold text-stone/40 px-3 block mb-3 tracking-widest font-sans">Menu</span>

                <a href="{{ route('leader.dashboard') }}" class="relative flex items-center px-4 py-3 transition-all duration-200 {{ request()->routeIs('leader.dashboard') || request()->routeIs('leader.manifest.*') ? 'text-white font-semibold' : 'hover:text-white font-medium' }}">
                    @if(request()->routeIs('leader.dashboard') || request()->routeIs('leader.manifest.*'))
                        <span class="absolute left-0 top-3.5 bottom-3.5 w-1 bg-[#1e5e3a] rounded-r-full"></span>
                    @endif
                    <svg class="w-5 h-5 mr-3.5 {{ request()->routeIs('leader.dashboard') || request()->routeIs('leader.manifest.*') ? 'text-[#1e5e3a]' : 'text-stone/40' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Jadwal Memandu
                </a>

                <a href="{{ route('leader.chat.index') }}" class="relative flex items-center px-4 py-3 transition-all duration-200 {{ request()->routeIs('leader.chat.*') ? 'text-white font-semibold' : 'hover:text-white font-medium' }}">
                    @if(request()->routeIs('leader.chat.*'))
                        <span class="absolute left-0 top-3.5 bottom-3.5 w-1 bg-[#1e5e3a] rounded-r-full"></span>
                    @endif
                    <svg class="w-5 h-5 mr-3.5 {{ request()->routeIs('leader.chat.*') ? 'text-[#1e5e3a]' : 'text-stone/40' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    Chat Ops Support
                </a>

                <a href="{{ route('leader.statistics') }}" class="relative flex items-center px-4 py-3 transition-all duration-200 {{ request()->routeIs('leader.statistics') ? 'text-white font-semibold' : 'hover:text-white font-medium' }}">
                    @if(request()->routeIs('leader.statistics'))
                        <span class="absolute left-0 top-3.5 bottom-3.5 w-1 bg-[#1e5e3a] rounded-r-full"></span>
                    @endif
                    <svg class="w-5 h-5 mr-3.5 {{ request()->routeIs('leader.statistics') ? 'text-[#1e5e3a]' : 'text-stone/40' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"/>
                    </svg>
                    Statistics
                </a>


                <span class="text-[10px] uppercase font-bold text-stone/40 px-3 block pt-4 pb-2 tracking-widest font-sans">General</span>

                <a href="{{ route('profile.edit') }}" class="relative flex items-center px-4 py-3 transition-all duration-200 hover:text-white font-medium">
                    <svg class="w-5 h-5 mr-3.5 text-stone/40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    </svg>
                    Settings
                </a>
            </nav>
        </div>

        <!-- User profile & Logout Footer -->
        <div class="space-y-5 pt-6 border-t border-[#dfdfd6]/10">
            <div class="flex items-center space-x-3 px-2">
                <div class="w-9 h-9 rounded-full bg-electric-lime flex items-center justify-center font-bold text-white uppercase text-xs">
                    {{ substr(Auth::guard('trip_leader')->user()->name ?? 'L', 0, 1) }}
                </div>
                <div class="overflow-hidden flex-grow">
                    <p class="font-bold text-xs text-white truncate leading-tight">{{ Auth::guard('trip_leader')->user()->name ?? 'Trip Leader' }}</p>
                    <p class="text-[9px] text-[#8e8e93]/70 truncate mt-0.5">{{ Auth::guard('trip_leader')->user()->email ?? 'leader@kelana.com' }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-center py-3 rounded-xl bg-red-600/10 text-red-400 font-bold hover:bg-red-600 hover:text-white transition-all duration-200 text-xs border border-red-600/20">🚪 Keluar Panel</button>
            </form>
        </div>
    </aside>

    <!-- CONTENT AREA -->
    <div class="flex-grow ml-72 flex flex-col min-h-screen">
        <!-- Top bar (Clean White with search & header actions from mockup) -->
        <header class="w-full bg-white px-8 py-4 flex justify-between items-center border-b border-[#dfdfd6] sticky top-0 z-40">
            <!-- Workspace Dropdown (Leader Workspace) -->
            <div class="relative" x-data="{ openWorkspace: false }">
                <button @click="openWorkspace = !openWorkspace" class="flex items-center text-sm font-bold text-near-black focus:outline-none hover:opacity-80 transition duration-150">
                    <span>Leader Workspace</span>
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
                     class="absolute left-0 mt-2.5 w-48 bg-white border border-[#dfdfd6] rounded-xl py-2 shadow-lg z-50 text-xs font-semibold"
                     style="display: none;">
                    <div class="px-4 py-1.5 text-[10px] text-graphite uppercase tracking-wider border-b border-[#dfdfd6] mb-1">
                        Active Workspace
                    </div>
                    <span class="block px-4 py-2 text-[#1e5e3a] bg-stone/20 font-bold">
                        Trip Leader (Ops)
                    </span>
                    <a href="{{ url('/') }}" class="block px-4 py-2 text-near-black hover:bg-stone/10 transition">
                        View Public Site
                    </a>
                    <div class="border-t border-[#dfdfd6] my-1"></div>
                    <form method="POST" action="{{ route('logout') }}" id="leader-workspace-logout-form">
                        @csrf
                        <button type="submit" class="w-full text-left block px-4 py-2 text-red-600 hover:bg-red-50 transition">
                            Logout System
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Mockup Search bar inside Capsule -->
            <div class="hidden md:flex items-center bg-[#f4f3ed]/60 rounded-full px-4 py-2 w-80 border border-[#dfdfd6]">
                <input type="text" placeholder="Search anything in Siohioma..." class="bg-transparent border-0 focus:ring-0 text-xs text-near-black placeholder-graphite/40 w-full outline-none p-0">
                <svg class="w-4 h-4 text-graphite ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <div class="flex items-center space-x-3">
                <span class="text-xs font-bold text-near-black bg-[#f4f3ed] px-3.5 py-1.5 rounded-full border border-[#dfdfd6]">
                    🟢 Field Status: Active
                </span>
            </div>
        </header>

        <main class="p-10 flex-grow relative">
            <!-- FLASH MESSAGES -->
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)" class="bg-[#1e5e3a] text-white p-5 rounded-[26px] mb-8 flex justify-between items-center">
                    <p class="font-medium text-sm">✅ {{ session('success') }}</p>
                    <button @click="show = false" class="text-white font-bold text-xs px-3 py-1.5 bg-black/10 rounded-full hover:bg-black/20">Tutup</button>
                </div>
            @endif
            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition class="bg-red-600 text-white p-5 rounded-[26px] mb-8 flex justify-between items-center">
                    <p class="font-medium text-sm">❌ {{ session('error') }}</p>
                    <button @click="show = false" class="text-white font-bold text-xs px-3 py-1.5 bg-black/10 rounded-full hover:bg-black/20">Tutup</button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
