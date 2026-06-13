<!-- Premium Navigation Bar -->
<nav class="w-full bg-warm-cream border-b border-stone sticky top-0 z-50">
    <div class="max-w-[1400px] mx-auto px-6 py-5 flex justify-between items-center">
        <a href="/" class="text-2xl font-bold tracking-tight text-near-black flex items-center">
            Kelana
        </a>

        <!-- Center Menu (English Translated) -->
        <div class="hidden md:flex space-x-8 text-sm font-semibold tracking-wide">
            <a href="{{ url('/') }}" class="text-graphite hover:text-near-black transition-colors">Home</a>
            <a href="{{ url('/#destinasi') }}" class="text-graphite hover:text-near-black transition-colors">Destinations</a>
            <a href="#" class="text-graphite hover:text-near-black transition-colors">How It Works</a>
            <a href="#" class="text-graphite hover:text-near-black transition-colors">Testimonials</a>
        </div>

        <!-- Right Side (Auth - English Translated) -->
        <div class="flex items-center space-x-3">
            @if (Auth::guard('customer')->check())
                <a href="{{ route('dashboard') }}" class="bg-electric-lime border border-transparent px-6 py-2.5 rounded-[26px] font-semibold text-white hover:bg-near-black hover:text-white hover:scale-105 active:scale-95 transition-all duration-300 ease-in-out">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-stone/50 border border-transparent px-6 py-2.5 rounded-[26px] hover:bg-near-black hover:text-white hover:scale-105 active:scale-95 transition-all duration-300 ease-in-out font-semibold text-near-black">Logout</button>
                </form>
            @elseif (Auth::guard('admin')->check())
                <a href="{{ route('admin.dashboard') }}" class="bg-electric-lime border border-transparent px-6 py-2.5 rounded-[26px] font-semibold text-white hover:bg-near-black hover:text-white hover:scale-105 active:scale-95 transition-all duration-300 ease-in-out">Admin Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-stone/50 border border-transparent px-6 py-2.5 rounded-[26px] hover:bg-near-black hover:text-white hover:scale-105 active:scale-95 transition-all duration-300 ease-in-out font-semibold text-near-black">Logout</button>
                </form>
            @elseif (Auth::guard('trip_leader')->check())
                <a href="{{ route('trip_leader.dashboard') }}" class="bg-electric-lime border border-transparent px-6 py-2.5 rounded-[26px] font-semibold text-white hover:bg-near-black hover:text-white hover:scale-105 active:scale-95 transition-all duration-300 ease-in-out">Leader Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-stone/50 border border-transparent px-6 py-2.5 rounded-[26px] hover:bg-near-black hover:text-white hover:scale-105 active:scale-95 transition-all duration-300 ease-in-out font-semibold text-near-black">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="bg-stone/50 border border-transparent px-6 py-2.5 rounded-[26px] hover:bg-near-black hover:text-white hover:scale-105 active:scale-95 transition-all duration-300 ease-in-out font-semibold text-near-black">Login</a>
                <a href="{{ route('register') }}" class="bg-electric-lime border border-transparent px-6 py-2.5 rounded-[26px] font-semibold text-white hover:bg-near-black hover:text-white hover:scale-105 active:scale-95 transition-all duration-300 ease-in-out">Register</a>
            @endif
        </div>
    </div>
</nav>
