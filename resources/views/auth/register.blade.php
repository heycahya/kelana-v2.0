<x-guest-layout>
    <div class="animate-fade-in">
        <!-- Typography Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-semibold text-near-black tracking-tight leading-tight mb-1">Create account</h1>
            <p class="text-graphite text-sm">Breathe in, let's explore together.</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-4">
                <x-input-label for="name" class="text-[13px] font-semibold text-near-black mb-1.5 block">
                    Full name
                </x-input-label>
                <input id="name" class="block w-full border-0 border-b-2 border-stone focus:border-near-black focus:ring-0 px-0 py-2 bg-transparent text-sm font-medium transition-all duration-300 placeholder-stone/40 outline-none" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
                <x-input-error :messages="$errors->get('name')" class="mt-1.5 text-xs text-red-600 font-medium" />
            </div>

            <!-- Email Address -->
            <div class="mb-4">
                <x-input-label for="email" class="text-[13px] font-semibold text-near-black mb-1.5 block">
                    Email address
                </x-input-label>
                <input id="email" class="block w-full border-0 border-b-2 border-stone focus:border-near-black focus:ring-0 px-0 py-2 bg-transparent text-sm font-medium transition-all duration-300 placeholder-stone/40 outline-none" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="name@domain.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-600 font-medium" />
            </div>

            <!-- Password -->
            <div class="mb-4 relative" x-data="{ show: false }">
                <x-input-label for="password" class="text-[13px] font-semibold text-near-black mb-1.5 block">
                    Password
                </x-input-label>
                <input id="password" class="block w-full border-0 border-b-2 border-stone focus:border-near-black focus:ring-0 px-0 py-2 bg-transparent text-sm font-medium transition-all duration-300 pr-10 placeholder-stone/40 outline-none"
                                ::type="show ? 'text' : 'password'"
                                name="password"
                                required autocomplete="new-password" placeholder="••••••••" />
                <button type="button" @click="show = !show" class="absolute bottom-2.5 right-0 flex items-center text-graphite hover:text-near-black focus:outline-none transition-colors">
                    <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                </button>
                <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-red-600 font-medium" />
            </div>

            <!-- Confirm Password -->
            <div class="mb-6 relative" x-data="{ show: false }">
                <x-input-label for="password_confirmation" class="text-[13px] font-semibold text-near-black mb-1.5 block">
                    Confirm password
                </x-input-label>
                <input id="password_confirmation" class="block w-full border-0 border-b-2 border-stone focus:border-near-black focus:ring-0 px-0 py-2 bg-transparent text-sm font-medium transition-all duration-300 pr-10 placeholder-stone/40 outline-none"
                                ::type="show ? 'text' : 'password'"
                                name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                <button type="button" @click="show = !show" class="absolute bottom-2.5 right-0 flex items-center text-graphite hover:text-near-black focus:outline-none transition-colors">
                    <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                </button>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-xs text-red-600 font-medium" />
            </div>

            <!-- Premium Animated Submit Button -->
            <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3.5 bg-electric-lime border border-transparent rounded-[26px] text-sm font-semibold text-white hover:bg-near-black hover:text-white hover:scale-[1.02] active:scale-95 transition-all duration-300 ease-in-out">
                Sign up
            </button>
            
            <!-- Google Sign Up (Soft-Filled Button with hover/scale animation) -->
            <a href="{{ route('google.redirect') }}" class="w-full inline-flex justify-center items-center px-6 py-3.5 bg-stone/50 border border-transparent rounded-[26px] text-sm font-semibold text-near-black hover:bg-near-black hover:text-white hover:scale-[1.01] transition-all duration-300 ease-in-out mt-3 mb-6 gap-2.5">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Sign up with Google
            </a>

            <!-- Login Link -->
            <div class="text-center pt-4 border-t border-stone/30">
                <span class="text-xs text-graphite font-medium">Already have an account?</span>
                <a class="text-xs text-near-black font-bold hover:underline focus:outline-none transition-colors ml-1" href="{{ route('login') }}">
                    Log in
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>
