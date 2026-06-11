<!-- Premium Navigation Bar -->
<nav class="w-full bg-warm-cream border-b border-stone sticky top-0 z-50">
    <div class="max-w-[1200px] mx-auto px-6 py-5 flex justify-between items-center">
        <!-- Bagian Kiri (Logo) -->
        <a href="/" class="text-2xl font-medium tracking-tight">Kelana</a>

        <!-- Bagian Tengah (Menu) -->
        <div class="hidden md:flex space-x-8">
            <a href="{{ url('/') }}" class="text-graphite hover:text-near-black transition-colors">Beranda</a>
            <a href="{{ url('/#destinasi') }}" class="text-graphite hover:text-near-black transition-colors">Destinasi</a>
            <a href="#" class="text-graphite hover:text-near-black transition-colors">Cara Kerja</a>
            <a href="#" class="text-graphite hover:text-near-black transition-colors">Testimoni</a>
        </div>

        <!-- Bagian Kanan (Auth) -->
        <div class="flex items-center space-x-3">
            @if (Auth::guard('customer')->check())
                <a href="{{ route('dashboard') }}" class="bg-electric-lime border border-near-black px-6 py-2.5 rounded-[26px] font-medium hover:opacity-90 transition">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="border border-near-black px-6 py-2.5 rounded-[26px] hover:bg-stone transition font-medium">Logout</button>
                </form>
            @elseif (Auth::guard('admin')->check())
                <a href="{{ route('admin.dashboard') }}" class="bg-electric-lime border border-near-black px-6 py-2.5 rounded-[26px] font-medium hover:opacity-90 transition">Admin Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="border border-near-black px-6 py-2.5 rounded-[26px] hover:bg-stone transition font-medium">Logout</button>
                </form>
            @elseif (Auth::guard('trip_leader')->check())
                <a href="{{ route('trip_leader.dashboard') }}" class="bg-electric-lime border border-near-black px-6 py-2.5 rounded-[26px] font-medium hover:opacity-90 transition">Leader Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="border border-near-black px-6 py-2.5 rounded-[26px] hover:bg-stone transition font-medium">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="border border-near-black px-6 py-2.5 rounded-[26px] hover:bg-stone transition font-medium">Login</a>
                <a href="{{ route('register') }}" class="bg-electric-lime border border-near-black px-6 py-2.5 rounded-[26px] font-medium hover:opacity-90 transition">Register</a>
            @endif
        </div>
    </div>
</nav>


