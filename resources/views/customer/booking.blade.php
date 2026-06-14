<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Secure Checkout - Kelana</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-warm-cream font-sans text-near-black antialiased min-h-screen flex flex-col">

    <!-- Checkout Container -->
    <main class="max-w-7xl mx-auto px-4 py-12 flex-grow w-full" x-data="bookingForm()">
        
        <!-- Secure Checkout Header -->
        <header class="flex justify-between items-center pb-6 mb-10 border-b border-stone/50">
            <a href="/" class="text-2xl font-bold tracking-tight text-electric-lime hover:opacity-85 transition">Kelana</a>
            <div class="flex items-center gap-1.5 text-graphite font-bold text-[10px] uppercase tracking-widest">
                <svg class="w-4.5 h-4.5 text-electric-lime" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
                <span>Secure Checkout</span>
            </div>
        </header>

        <!-- Dynamic Stepper -->
        <div class="mb-14">
            <div class="flex items-center justify-between max-w-xl mx-auto px-4 relative">
                <!-- Connecting Line behind -->
                <div class="absolute left-10 right-10 top-5 -translate-y-1/2 h-[2px] bg-stone -z-10">
                    <div class="h-full bg-electric-lime transition-all duration-[600ms] ease-out" 
                         :style="`width: ${currentStep === 1 ? '0%' : (currentStep === 2 ? '50%' : '100%')}`"></div>
                </div>
                
                <!-- Step 1 -->
                <div class="flex flex-col items-center gap-2">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs border-2 transition-all duration-300"
                         :class="currentStep >= 1 ? 'bg-electric-lime text-white border-electric-lime scale-110 shadow-md' : 'bg-white text-graphite border-stone'">
                        1
                    </div>
                    <span class="text-xs font-semibold tracking-tight transition-colors duration-300"
                          :class="currentStep >= 1 ? 'text-near-black font-bold' : 'text-graphite'">Detail Pesanan</span>
                </div>
                
                <!-- Step 2 -->
                <div class="flex flex-col items-center gap-2">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs border-2 transition-all duration-300"
                         :class="currentStep >= 2 ? 'bg-electric-lime text-white border-electric-lime scale-110 shadow-md' : 'bg-white text-graphite border-stone'">
                        2
                    </div>
                    <span class="text-xs font-semibold tracking-tight transition-colors duration-300"
                          :class="currentStep >= 2 ? 'text-near-black font-bold' : 'text-graphite'">Pembayaran</span>
                </div>
                
                <!-- Step 3 -->
                <div class="flex flex-col items-center gap-2">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs border-2 transition-all duration-300"
                         :class="currentStep >= 3 ? 'bg-electric-lime text-white border-electric-lime scale-110 shadow-md' : 'bg-white text-graphite border-stone'">
                        3
                    </div>
                    <span class="text-xs font-semibold tracking-tight transition-colors duration-300"
                          :class="currentStep >= 3 ? 'text-near-black font-bold' : 'text-graphite'">E-Ticket</span>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mb-8">
            <a href="{{ route('paket.detail', $jadwal->id_paket) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full bg-stone/50 text-[11px] font-bold uppercase tracking-wider text-near-black hover:bg-near-black hover:text-white transition-all duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                <span>Back to Trip Details</span>
            </a>
        </div>

        <!-- Error Message Alert -->
        <div x-show="errorMessage" x-text="errorMessage" class="mb-8 p-4 bg-coral-alert/10 text-coral-alert rounded-2xl border border-coral-alert/20 text-sm font-semibold" style="display: none;"></div>

        <!-- Split Layout Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- KOLOM KIRI (Detail Form) -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Card 1: Detail Pemesan -->
                <div class="bg-white rounded-3xl p-8 border border-stone/60">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-full bg-electric-lime/10 flex items-center justify-center text-electric-lime shrink-0">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold tracking-tight text-near-black">Contact Details</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-stone/20 rounded-2xl p-6 border border-stone/40">
                        <div>
                            <span class="text-[10px] font-bold text-graphite uppercase tracking-wider block mb-1">Full Name</span>
                            <p class="font-bold text-near-black text-sm">{{ auth()->user()->nama_customer }}</p>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-graphite uppercase tracking-wider block mb-1">Email Address</span>
                            <p class="font-bold text-near-black text-sm">{{ auth()->user()->email }}</p>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-graphite uppercase tracking-wider block mb-1">Phone Number</span>
                            <p class="font-bold text-near-black text-sm">{{ auth()->user()->no_telp ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Peserta & Add-ons -->
                <div class="bg-white rounded-3xl p-8 border border-stone/60">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-full bg-electric-lime/10 flex items-center justify-center text-electric-lime shrink-0">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold tracking-tight text-near-black">Participants & Add-ons</h2>
                    </div>

                    <!-- Stepper Jumlah Peserta -->
                    <div class="mb-8 border-b border-stone/40 pb-8">
                        <label class="block text-[10px] font-bold text-graphite uppercase tracking-widest mb-3">Number of Participants (Pax)</label>
                        <div class="flex items-center gap-5">
                            <div class="flex items-center border border-stone rounded-full p-1.5 bg-white">
                                <button type="button" @click="if (jumlah > 1) { jumlah--; clampAddonQuantities(); }" class="w-10 h-10 rounded-full bg-stone/40 text-near-black hover:bg-near-black hover:text-white flex items-center justify-center font-bold text-lg select-none transition-all duration-200">-</button>
                                <span x-text="jumlah" class="text-lg font-bold w-12 text-center text-near-black"></span>
                                <button type="button" @click="if (jumlah < {{ $jadwal->sisa_kuota }}) { jumlah++; }" class="w-10 h-10 rounded-full bg-stone/40 text-near-black hover:bg-near-black hover:text-white flex items-center justify-center font-bold text-lg select-none transition-all duration-200">+</button>
                            </div>
                            <div class="text-xs text-graphite font-semibold">
                                <p class="text-near-black">Rp <span x-text="formatRupiah(harga)"></span> / person</p>
                                <p class="mt-0.5 text-stone-500">{{ $jadwal->sisa_kuota }} seats left for this trip</p>
                            </div>
                        </div>
                    </div>

                    <!-- Add-ons Selection -->
                    @if($addons->isNotEmpty())
                        <div>
                            <h3 class="text-xs font-bold text-graphite uppercase tracking-widest mb-4">Enhance Your Adventure</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($addons as $addon)
                                    <div @click="toggleAddon({{ $addon->id }}, {{ $addon->harga }})"
                                         :class="isAddonSelected({{ $addon->id }}) ? 'border-electric-lime bg-electric-lime/5 ring-1 ring-electric-lime' : 'border-stone hover:bg-stone/10 bg-white'"
                                         class="flex flex-col justify-between p-5 rounded-[22px] border transition-all duration-300 cursor-pointer select-none relative">
                                        <div>
                                            <div class="flex justify-between items-start mb-2">
                                                <p class="font-bold text-near-black text-sm leading-snug">{{ $addon->nama_addon }}</p>
                                                <!-- Circular Checkbox -->
                                                <div :class="isAddonSelected({{ $addon->id }}) ? 'bg-electric-lime border-transparent' : 'bg-white border-stone'"
                                                     class="w-5 h-5 rounded-full border flex items-center justify-center transition-colors shrink-0">
                                                    <svg x-show="isAddonSelected({{ $addon->id }})" class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <p class="text-xs text-graphite font-medium leading-relaxed mb-4">{{ $addon->deskripsi }}</p>
                                        </div>
                                        
                                        <div class="flex justify-between items-center mt-auto pt-2 border-t border-stone/30">
                                            <span class="text-xs font-bold text-electric-lime">Rp {{ number_format($addon->harga, 0, ',', '.') }}</span>
                                            
                                            <!-- Stop propagation to prevent toggling the parent card when clicking stepper -->
                                            <div class="flex items-center gap-2.5 bg-white border border-stone rounded-full p-1"
                                                 x-show="isAddonSelected({{ $addon->id }})" 
                                                 x-transition
                                                 @click.stop>
                                                <button type="button" @click="changeAddonQty({{ $addon->id }}, -1)" class="w-6 h-6 rounded-full bg-stone/40 text-near-black hover:bg-near-black hover:text-white flex items-center justify-center font-bold text-xs select-none transition">-</button>
                                                <span x-text="getAddonQty({{ $addon->id }})" class="text-xs font-bold w-4 text-center text-near-black"></span>
                                                <button type="button" @click="changeAddonQty({{ $addon->id }}, 1)" class="w-6 h-6 rounded-full bg-stone/40 text-near-black hover:bg-near-black hover:text-white flex items-center justify-center font-bold text-xs select-none transition">+</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- KOLOM KANAN (Order Summary) -->
            <div class="lg:col-span-4 lg:sticky lg:top-8">
                
                @php
                    $fallbackDatabases = [
                        'Bromo' => [
                            'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?auto=format&fit=crop&w=600&q=80',
                        ],
                        'Komodo' => [
                            'https://images.unsplash.com/photo-1516690561799-46d8f74f9abf?auto=format&fit=crop&w=600&q=80',
                        ],
                        'Rinjani' => [
                            'https://images.unsplash.com/photo-1522199755839-a2bacb67c546?auto=format&fit=crop&w=600&q=80',
                        ],
                        'Ijen' => [
                            'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=600&q=80',
                        ],
                    ];

                    $fallbackSet = $fallbackDatabases['Bromo'];
                    foreach ($fallbackDatabases as $key => $set) {
                        if (stripos($jadwal->paketWisata->nama_paket, $key) !== false) {
                            $fallbackSet = $set;
                            break;
                        }
                    }

                    $thumbnail = $jadwal->paketWisata->galleries->where('is_primary', true)->first()?->image_url ?? $fallbackSet[0];
                @endphp

                <!-- Summary Card -->
                <div class="bg-white rounded-3xl p-8 border border-stone/60">
                    <h2 class="text-xs font-bold text-graphite uppercase tracking-widest mb-6">Reservation Invoice</h2>

                    <!-- Trip Thumbnail & Title -->
                    <div class="flex gap-4 items-center pb-6 border-b border-stone/40 mb-6">
                        <img src="{{ $thumbnail }}" alt="{{ $jadwal->paketWisata->nama_paket }}" class="w-20 h-20 rounded-2xl object-cover border border-stone shrink-0">
                        <div>
                            <span class="text-[9px] font-bold bg-electric-lime/10 text-electric-lime px-2.5 py-1 rounded-full uppercase tracking-wider">Trip Package</span>
                            <h3 class="font-bold text-near-black text-sm mt-1.5 leading-tight">{{ $jadwal->paketWisata->nama_paket }}</h3>
                            <p class="text-xs text-graphite mt-1.5 flex items-center gap-1.5 font-medium">
                                <svg class="w-4 h-4 text-electric-lime" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d M Y') }}
                            </p>
                            <p class="text-xs text-graphite mt-0.5 flex items-center gap-1.5 font-medium">
                                <svg class="w-4 h-4 text-electric-lime" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $jadwal->sisa_kuota }} Seats Left
                            </p>
                        </div>
                    </div>

                    <!-- Struk Kalkulasi -->
                    <div class="space-y-4 pb-6 border-b border-stone/40 mb-6 text-sm font-medium">
                        <!-- Base Ticket Cost -->
                        <div class="flex justify-between items-center text-graphite">
                            <div class="flex flex-col">
                                <span class="text-near-black font-semibold">Base Ticket Cost</span>
                                <span class="text-xs text-stone-500">Rp <span x-text="formatRupiah(harga)"></span> x <span x-text="jumlah"></span> pax</span>
                            </div>
                            <span class="font-semibold text-near-black">Rp <span x-text="formatRupiah(jumlah * harga)"></span></span>
                        </div>

                        <!-- Addons cost list -->
                        <template x-for="addon in selectedAddons" :key="addon.id">
                            <div class="flex justify-between items-center text-graphite transition-all">
                                <div class="flex flex-col">
                                    <span class="text-near-black font-semibold text-xs" x-text="addon.nama"></span>
                                    <span class="text-[10px] text-stone-500">Rp <span x-text="formatRupiah(addon.price)"></span> x <span x-text="addon.kuantitas"></span></span>
                                </div>
                                <span class="text-xs font-semibold text-near-black">Rp <span x-text="formatRupiah(addon.price * addon.kuantitas)"></span></span>
                            </div>
                        </template>

                        <!-- Total Addons sum -->
                        <div class="flex justify-between items-center text-graphite pt-3 border-t border-dashed border-stone/40 mt-2" x-show="totalAddons > 0">
                            <span class="text-xs font-bold text-graphite">Total Add-ons</span>
                            <span class="text-xs font-bold text-near-black">Rp <span x-text="formatRupiah(totalAddons)"></span></span>
                        </div>

                        <!-- Promo Discount -->
                        <div class="flex justify-between items-center text-[#1e5e3a] pt-3 border-t border-dashed border-stone/40 mt-2" x-show="promoDiscount > 0" x-transition>
                            <div class="flex flex-col">
                                <span class="text-[#1e5e3a] font-bold text-xs">Promo Discount (<span x-text="appliedPromo"></span>)</span>
                            </div>
                            <span class="font-bold">- Rp <span x-text="formatRupiah(promoDiscount)"></span></span>
                        </div>
                    </div>

                    <!-- Promo Code Input -->
                    <div class="pb-6 border-b border-stone/40 mb-6">
                        <label class="block text-[10px] font-bold text-graphite uppercase tracking-widest mb-2">Have a Promo Code?</label>
                        <div class="flex gap-2">
                            <input type="text" x-model="promoCode" placeholder="Contoh: MERDEKA20" class="flex-grow p-3 rounded-full border border-stone text-xs font-semibold outline-none focus:border-electric-lime bg-warm-cream/20">
                            <button type="button" @click="applyPromo" class="bg-near-black text-white hover:bg-electric-lime px-5 py-2.5 rounded-full text-xs font-bold transition">
                                Apply
                            </button>
                        </div>
                        
                        <!-- Success / Error messages -->
                        <div x-show="promoSuccess" class="text-xs text-[#1e5e3a] font-bold mt-2" x-text="promoSuccess" style="display: none;"></div>
                        <div x-show="promoError" class="text-xs text-coral-alert font-bold mt-2" x-text="promoError" style="display: none;"></div>
                        
                        <!-- Applied Promo Chip -->
                        <div x-show="appliedPromo" class="mt-2.5 flex items-center justify-between bg-electric-lime/5 border border-electric-lime/20 rounded-full px-4 py-1.5" style="display: none;">
                            <span class="text-[10px] text-electric-lime font-bold uppercase tracking-wider">Applied: <strong x-text="appliedPromo"></strong></span>
                            <button type="button" @click="clearPromo" class="text-xs font-bold text-coral-alert hover:text-red-700">Remove ×</button>
                        </div>
                    </div>

                    <!-- Total Bayar Akhir & Action Button -->
                    <div>
                        <div class="flex justify-between items-baseline mb-6 font-bold text-near-black">
                            <span class="text-xs uppercase tracking-wider text-graphite font-bold">Total Payment</span>
                            <span class="text-3xl tracking-tight text-electric-lime">Rp <span x-text="formatRupiah(grandTotal)"></span></span>
                        </div>

                        <button @click="submitBooking" 
                                class="w-full bg-electric-lime text-white border border-transparent py-4 rounded-full font-bold hover:bg-near-black hover:text-white transition-all duration-300 ease-in-out hover:scale-[1.02] active:scale-95 text-xs uppercase tracking-widest flex items-center justify-center gap-2.5"
                                :disabled="loading">
                            <svg x-show="loading" class="animate-spin h-5 w-5 text-current" fill="none" viewBox="0 0 24 24" style="display: none;">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="loading ? 'Processing Reservation...' : 'Lanjut ke Pembayaran'"></span>
                        </button>

                        <div class="bg-stone/20 rounded-2xl p-4 border border-stone/40 mt-4 flex items-start gap-2.5 text-[11px] text-graphite font-semibold leading-relaxed">
                            <svg class="w-4 h-4 text-electric-lime shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0110.1 21a3.745 3.745 0 01-3.297-1.042A3.746 3.746 0 013.593 16.66c-.964-.678-1.593-1.8-1.593-3.068 0-1.268.63-2.39 1.593-3.068a3.746 3.746 0 011.043-3.297 3.745 3.745 0 013.296-1.042C10.1 3 11.69 3.033 13.046 3.097" />
                            </svg>
                            <p>
                                Pembayaran 100% aman melalui gerbang resmi Midtrans. E-Ticket akan segera diterbitkan setelah status transaksi dikonfirmasi.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Midtrans Snap JS -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <script>
        function bookingForm() {
            return {
                currentStep: 1,
                init() {
                    this.clampAddonQuantities();
                },
                jumlah: {{ request()->query('jumlah_peserta', 1) }},
                harga: {{ $jadwal->paketWisata->harga }},
                loading: false,
                errorMessage: '',
                promoCode: '',
                appliedPromo: '',
                promoSuccess: '',
                promoError: '',
                addonsList: [
                    @foreach($addons as $addon)
                    { id: {{ $addon->id }}, nama: '{{ $addon->nama_addon }}', harga: {{ $addon->harga }} },
                    @endforeach
                ],
                selectedAddons: [
                    @if(request()->query('addons'))
                        @foreach(explode(',', request()->query('addons')) as $addonId)
                            @php
                                $addonModel = \App\Models\AddOn::find($addonId);
                            @endphp
                            @if($addonModel)
                            {
                                id: {{ (int)$addonId }},
                                nama: '{{ $addonModel->nama_addon }}',
                                price: {{ $addonModel->harga }},
                                kuantitas: 1
                            },
                            @endif
                        @endforeach
                    @endif
                ],

                formatRupiah(val) {
                    return new Intl.NumberFormat('id-ID').format(val);
                },

                isAddonSelected(id) {
                    return this.selectedAddons.some(a => a.id === id);
                },

                getAddonQty(id) {
                    const found = this.selectedAddons.find(a => a.id === id);
                    return found ? found.kuantitas : 1;
                },

                toggleAddon(id, price) {
                    const index = this.selectedAddons.findIndex(a => a.id === id);
                    if (index > -1) {
                        this.selectedAddons.splice(index, 1);
                    } else {
                        const addonItem = this.addonsList.find(a => a.id === id);
                        this.selectedAddons.push({ 
                            id: id, 
                            nama: addonItem ? addonItem.nama : '',
                            price: price, 
                            kuantitas: 1 
                        });
                    }
                },

                changeAddonQty(id, delta) {
                    const addon = this.selectedAddons.find(a => a.id === id);
                    if (addon) {
                        const addonName = addon.nama.toLowerCase();
                        let maxQty = this.jumlah;
                        if (addonName.includes('drone')) {
                            maxQty = 1;
                        }
                        addon.kuantitas = Math.max(1, Math.min(maxQty, addon.kuantitas + delta));
                    }
                },

                clampAddonQuantities() {
                    this.selectedAddons.forEach(addon => {
                        const addonName = addon.nama.toLowerCase();
                        let maxQty = this.jumlah;
                        if (addonName.includes('drone')) {
                            maxQty = 1;
                        }
                        addon.kuantitas = Math.max(1, Math.min(maxQty, addon.kuantitas));
                    });
                },

                get totalAddons() {
                    return this.selectedAddons.reduce((sum, a) => sum + (a.price * a.kuantitas), 0);
                },

                get promoDiscount() {
                    const basePrice = this.jumlah * this.harga;
                    if (this.appliedPromo === 'MERDEKA20') {
                        return basePrice * 0.20;
                    } else if (this.appliedPromo === 'RINJANIPAS') {
                        return basePrice * 0.10;
                    } else if (this.appliedPromo === 'KOMODOLUX') {
                        return 100000;
                    }
                    return 0;
                },

                get grandTotal() {
                    const total = (this.jumlah * this.harga) + this.totalAddons - this.promoDiscount;
                    return Math.max(0, total);
                },

                applyPromo() {
                    this.promoError = '';
                    this.promoSuccess = '';
                    
                    const code = this.promoCode.trim().toUpperCase();
                    if (!code) {
                        this.promoError = 'Silakan masukkan kode promo.';
                        return;
                    }

                    if (code === 'MERDEKA20') {
                        this.appliedPromo = code;
                        this.promoSuccess = 'Kode promo MERDEKA20 berhasil diterapkan! Diskon 20% diperoleh.';
                    } else if (code === 'RINJANIPAS') {
                        this.appliedPromo = code;
                        this.promoSuccess = 'Kode promo RINJANIPAS berhasil diterapkan! Diskon 10% diperoleh.';
                    } else if (code === 'KOMODOLUX') {
                        this.appliedPromo = code;
                        this.promoSuccess = 'Kode promo KOMODOLUX berhasil diterapkan! Diskon Rp 100.000 diperoleh.';
                    } else {
                        this.promoError = 'Kode promo tidak valid.';
                        this.appliedPromo = '';
                    }
                },

                clearPromo() {
                    this.promoCode = '';
                    this.appliedPromo = '';
                    this.promoSuccess = '';
                    this.promoError = '';
                },

                submitBooking() {
                    this.loading = true;
                    this.errorMessage = '';

                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const addonsPayload = this.selectedAddons.map(a => ({
                        id: a.id,
                        kuantitas: a.kuantitas
                    }));

                    fetch('{{ route("customer.booking.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            id_jadwal: {{ $jadwal->id_jadwal }},
                            jumlah_peserta: this.jumlah,
                            addons: addonsPayload,
                            promo_code: this.appliedPromo
                        })
                    })
                    .then(response => response.json().then(data => ({ status: response.status, body: data })))
                    .then(res => {
                        if (res.status === 201) {
                            const snapToken = res.body.snap_token;
                            
                            // Set step ke Pembayaran
                            this.currentStep = 2;
                            
                            // Trigger Midtrans Snap
                            window.snap.pay(snapToken, {
                                onSuccess: (result) => {
                                    this.currentStep = 3;
                                    window.location.href = '{{ route("dashboard") }}';
                                },
                                onPending: (result) => {
                                    this.currentStep = 3;
                                    window.location.href = '{{ route("dashboard") }}';
                                },
                                onError: (result) => {
                                    this.loading = false;
                                    this.currentStep = 1;
                                    this.errorMessage = 'Payment failed. Please try again.';
                                },
                                onClose: () => {
                                    this.loading = false;
                                    this.currentStep = 1;
                                }
                            });
                        } else {
                            this.loading = false;
                            this.errorMessage = res.body.message || 'Failed to process booking. Please try again.';
                        }
                    })
                    .catch(err => {
                        this.loading = false;
                        this.errorMessage = 'Network error. Please try again.';
                        console.error(err);
                    });
                }
            }
        }
    </script>
</body>
</html>
