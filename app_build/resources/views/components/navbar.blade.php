<!-- Premium Navigation Bar -->
<nav x-data="wishlistCartData()" class="w-full bg-warm-cream border-b border-stone sticky top-0 z-50">
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
        <div class="flex items-center space-x-4">
            @if (Auth::guard('customer')->check())
                <!-- Wishlist (Heart) -->
                <a href="#" @click.prevent="isWishlistOpen = true" class="text-graphite hover:text-electric-lime p-2 transition-colors relative" aria-label="Wishlist">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <span x-show="wishlistItems.length > 0" class="absolute -top-0.5 -right-0.5 min-w-5 h-5 flex items-center justify-center bg-[#1e5e3a] text-white rounded-full text-[9px] font-bold px-1" x-text="wishlistItems.length" style="display: none;"></span>
                </a>
                
                <!-- Cart/Bookings -->
                <a href="#" @click.prevent="isCartOpen = true" class="text-graphite hover:text-electric-lime p-2 transition-colors relative" aria-label="Bookings">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span x-show="cartItem" class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-[#1e5e3a] rounded-full" style="display: none;"></span>
                </a>

                <!-- Profile Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.away="open = false" class="flex items-center focus:outline-none">
                        <div class="w-9 h-9 rounded-full bg-electric-lime text-white flex items-center justify-center font-bold border border-stone hover:border-near-black transition-all duration-300">
                            {{ strtoupper(substr(Auth::guard('customer')->user()->name ?? 'C', 0, 1)) }}
                        </div>
                    </button>
                    
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-[#0f1a15] border border-white/10 rounded-xl py-2 shadow-2xl z-50 text-sm"
                         style="display: none;">
                        <div class="px-4 py-2 text-xs text-white/50 border-b border-white/10">
                            Signed in as <br/>
                            <span class="font-semibold text-white truncate block">{{ Auth::guard('customer')->user()->email }}</span>
                        </div>
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 text-white hover:bg-white/10 transition-colors">My Bookings</a>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-white hover:bg-white/10 transition-colors">Profile Settings</a>
                        <div class="border-t border-white/10 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full text-left block px-4 py-2.5 text-red-400 hover:bg-white/10 transition-colors">
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
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

    <!-- Reusable Drawer & Modal components -->
    @include('components.customer-wishlist-cart')
</nav>
