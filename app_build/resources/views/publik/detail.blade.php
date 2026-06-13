<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $paket->nama_paket }} - Kelana</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body class="bg-warm-cream font-sans text-near-black antialiased min-h-screen flex flex-col">
    @include('components.navbar')

    @php
        // Fallback images based on package name
        $fallbackDatabases = [
            'Bromo' => [
                'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1602002418082-a4443e081dd1?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=600&q=80',
            ],
            'Komodo' => [
                'https://images.unsplash.com/photo-1516690561799-46d8f74f9abf?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1506929562872-bb421503ef21?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1544735716-392fe2489ffa?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=600&q=80',
            ],
            'Rinjani' => [
                'https://images.unsplash.com/photo-1522199755839-a2bacb67c546?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1501555088652-021faa106b9b?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1454496522488-7a8e488e8606?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?auto=format&fit=crop&w=600&q=80',
            ],
            'Ijen' => [
                'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1447752875215-b2761acb3c5d?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1472214222541-d510753a8707?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1502082553048-f009c37129b9?auto=format&fit=crop&w=600&q=80',
            ],
        ];

        $fallbackSet = $fallbackDatabases['Bromo'];
        foreach ($fallbackDatabases as $key => $set) {
            if (stripos($paket->nama_paket, $key) !== false) {
                $fallbackSet = $set;
                break;
            }
        }

        $primaryImage = $paket->galleries->where('is_primary', true)->first()?->image_url ?? $fallbackSet[0];
        $secondaryImages = $paket->galleries->where('is_primary', false)->take(4);
        $allImages = collect([$primaryImage])->concat($secondaryImages->pluck('image_url'))->take(5)->toArray();

        // Ensure we have exactly 5 images for the grid
        while(count($allImages) < 5) {
            $allImages[] = $fallbackSet[count($allImages) % 5];
        }

        $firstSchedule = $activeSchedules->first();
        $leader = $firstSchedule?->tripLeader;
    @endphp

    <main class="max-w-[1400px] mx-auto px-6 py-6 flex-grow w-full" 
          x-data="{
              hargaPaket: {{ $paket->harga }},
              jumlahPeserta: 1,
              selectedScheduleId: '{{ $firstSchedule?->id_jadwal ?? '' }}',
              selectedScheduleText: '{{ $firstSchedule ? \Carbon\Carbon::parse($firstSchedule->tanggal_mulai)->format("d M Y") : "Select Date" }}',
              selectedScheduleQuota: {{ $firstSchedule?->sisa_kuota ?? 0 }},
              selectedAddons: [],
              showDropdown: false,
              addonsList: [
                  @foreach($addons as $addon)
                  { id: {{ $addon->id }}, harga: {{ $addon->harga }} },
                  @endforeach
              ],
              get addonTotal() {
                  let total = 0;
                  this.selectedAddons.forEach(id => {
                      let item = this.addonsList.find(a => a.id == id);
                      if (item) total += item.harga;
                  });
                  return total;
              },
              get grandTotal() {
                  return (this.hargaPaket + this.addonTotal) * this.jumlahPeserta;
              },
              formatRupiah(val) {
                  return new Intl.NumberFormat('id-ID').format(val);
              },
              submitBooking() {
                  if (!this.selectedScheduleId) {
                      alert('Please select a departure date first.');
                      return;
                  }
                  let url = '{{ route('customer.booking') }}?jadwal_id=' + this.selectedScheduleId + '&jumlah_peserta=' + this.jumlahPeserta;
                  if (this.selectedAddons.length > 0) {
                      url += '&addons=' + this.selectedAddons.join(',');
                  }
                  window.location.href = url;
              }
          }">
          
        <!-- Back Button with Text -->
        <a href="/" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full border border-transparent bg-stone/50 text-xs font-semibold uppercase tracking-wider text-near-black hover:bg-near-black hover:text-white transition-all duration-300 ease-in-out mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
            <span>Back</span>
        </a>

        <!-- Product Header Info -->
        <div class="mb-4">
            <h1 class="text-[36px] md:text-[50px] font-medium text-near-black tracking-tight leading-[1.1] mb-3">{{ $paket->nama_paket }}</h1>
            <div class="flex items-center gap-2 text-sm text-graphite font-medium">
                <span class="text-near-black font-semibold flex items-center">⭐ {{ number_format($leader->rating_akumulatif ?? 5.0, 1) }}</span>
                <span>(120 Reviews)</span>
                <span class="text-stone">|</span>
                <span>Meeting Point: <strong class="text-near-black font-semibold">{{ $paket->rute }}</strong></span>
            </div>
        </div>

        <!-- Split Layout Grid (Stretched columns for stable sticky sidebar) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Left Column: Content (Grid-Span-8) -->
            <div class="lg:col-span-8 w-full space-y-6">
                <!-- Premium Animated Image Slider / Carousel -->
                <div x-data="{ 
                    activeSlide: 0, 
                    images: {{ json_encode($allImages) }},
                    next() { this.activeSlide = (this.activeSlide + 1) % this.images.length },
                    prev() { this.activeSlide = (this.activeSlide - 1 + this.images.length) % this.images.length }
                }" class="relative mb-6 select-none w-full">
                    
                    <!-- Main Viewport -->
                    <div class="relative h-[300px] md:h-[450px] w-full rounded-[26px] overflow-hidden border border-stone bg-stone/10">
                        <!-- Sliding Track -->
                        <div class="flex h-full w-full transition-transform duration-[600ms] ease-[cubic-bezier(0.16,1,0.3,1)]"
                             :style="`transform: translateX(-${activeSlide * 100}%)`">
                            <template x-for="(img, index) in images" :key="index">
                                <div class="w-full h-full flex-shrink-0 relative">
                                    <img :src="img" class="w-full h-full object-cover select-none pointer-events-none" alt="Detail Trip Image">
                                    <div class="absolute inset-0 bg-black/5 pointer-events-none"></div>
                                </div>
                            </template>
                        </div>

                        <!-- Bottom shadow overlay for readable controls -->
                        <div class="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-near-black/30 to-transparent pointer-events-none"></div>

                        <!-- Navigation Buttons -->
                        <button @click="prev" type="button" class="absolute left-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white/30 backdrop-blur-md hover:bg-near-black hover:text-white border border-white/20 text-near-black flex items-center justify-center transition-all duration-300 hover:scale-105 z-20 focus:outline-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                        </button>
                        <button @click="next" type="button" class="absolute right-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white/30 backdrop-blur-md hover:bg-near-black hover:text-white border border-white/20 text-near-black flex items-center justify-center transition-all duration-300 hover:scale-105 z-20 focus:outline-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>

                        <!-- Slide Counter Tag -->
                        <div class="absolute top-4 right-4 bg-near-black/75 backdrop-blur-md px-3 py-1 rounded-full border border-stone/20 text-[10px] font-bold text-white tracking-widest uppercase z-20">
                            <span x-text="(activeSlide + 1)"></span> / <span x-text="images.length"></span>
                        </div>
                    </div>

                    <!-- Thumbnail Navigation List -->
                    <div class="flex gap-3 mt-3 overflow-x-auto pb-1 no-scrollbar">
                        <template x-for="(img, index) in images" :key="index">
                            <button @click="activeSlide = index"
                                    type="button"
                                    class="relative w-20 h-14 rounded-[14px] overflow-hidden border-2 transition-all duration-300 shrink-0 focus:outline-none"
                                    :class="activeSlide === index ? 'border-near-black scale-95 shadow-md' : 'border-stone/40 opacity-60 hover:opacity-100'">
                                <img :src="img" class="w-full h-full object-cover" alt="Thumbnail Preview">
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Tentang Perjalanan -->
                <div class="space-y-6">
                    <h3 class="text-2xl font-bold tracking-tight text-near-black">Overview</h3>
                    
                    <!-- Overview Attributes Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-near-black font-semibold text-sm">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-full bg-stone/20 flex items-center justify-center text-lg shrink-0">🕒</span>
                            <div>
                                <span class="text-graphite text-[10px] uppercase tracking-wider block font-bold">Trip Length</span>
                                <span class="text-near-black font-bold">3 Days 2 Nights</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-full bg-stone/20 flex items-center justify-center text-lg shrink-0">👥</span>
                            <div>
                                <span class="text-graphite text-[10px] uppercase tracking-wider block font-bold">Group Size</span>
                                <span class="text-near-black font-bold">Up to 15 participants</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-full bg-stone/20 flex items-center justify-center text-lg shrink-0">🌍</span>
                            <div>
                                <span class="text-graphite text-[10px] uppercase tracking-wider block font-bold">Experience Type</span>
                                <span class="text-near-black font-bold">Nature & Adventure Trip</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-full bg-stone/20 flex items-center justify-center text-lg shrink-0">💬</span>
                            <div>
                                <span class="text-graphite text-[10px] uppercase tracking-wider block font-bold">Languages</span>
                                <span class="text-near-black font-bold">English & Indonesian</span>
                            </div>
                        </div>
                    </div>

                    <p class="text-graphite leading-relaxed font-medium text-base whitespace-pre-line">{{ $paket->deskripsi }}</p>
                </div>

                <!-- Itinerary & Amenities -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-stone/50 pt-8 mt-8">
                    <div>
                        <h4 class="text-lg font-bold text-near-black mb-3 tracking-tight flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-electric-lime"></span> Itinerary Route
                        </h4>
                        <p class="text-near-black font-semibold leading-relaxed text-sm">{{ $paket->rute }}</p>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-near-black mb-3 tracking-tight flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-electric-lime"></span> Included Amenities
                        </h4>
                        <p class="text-graphite font-medium leading-relaxed text-sm whitespace-pre-line">{{ $paket->fasilitas }}</p>
                    </div>
                </div>

                <!-- Profil Trip Leader -->
                @if($leader)
                <div class="border-t border-stone/50 pt-8 mt-8">
                    <h4 class="text-lg font-bold text-near-black mb-6 tracking-tight">Your Trip Guide</h4>
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                        <img src="{{ $leader->avatar ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&q=80' }}" 
                             class="w-24 h-24 rounded-full object-cover border border-stone shrink-0" 
                             alt="{{ $leader->nama_leader }}">
                        <div class="text-center sm:text-left flex-grow">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                                <h3 class="text-xl font-medium text-near-black">{{ $leader->nama_leader }}</h3>
                                <span class="px-3 py-1 rounded-full bg-electric-lime/20 text-near-black text-xs font-bold w-fit mx-auto sm:mx-0">
                                    ⭐ {{ number_format($leader->rating_akumulatif ?? 5.0, 1) }} Certified Guide
                                </span>
                            </div>
                            <p class="text-graphite leading-relaxed text-sm font-medium mb-4">
                                {{ $leader->bio ?? 'Experienced certified guide ready to lead your adventure safely and comfortably.' }}
                            </p>
                            <div class="text-xs text-graphite font-semibold uppercase tracking-wider">Has guided +150 travelers</div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Peta Lokasi Meeting Point -->
                <div class="border-t border-stone/50 pt-8 mt-8">
                    <h3 class="text-2xl font-bold tracking-tight mb-2 text-near-black">Meeting Point Location</h3>
                    <p class="text-graphite text-sm font-medium mb-4">Please arrive at the meeting point on time. Detailed coordinates are displayed on the map below.</p>
                    <div id="map" class="w-full h-[350px] rounded-[20px] border border-stone z-0"></div>
                </div>
            </div>

            <!-- Right Column: Sidebar Container (Grid-Span-4) -->
            <div class="lg:col-span-4 w-full">
                <!-- Booking Card (Static) -->
                <div class="bg-parchment-card border border-stone rounded-[32px] p-6 flex flex-col w-full relative">
                    <!-- Price Tag Header -->
                    <div class="pb-4 border-b border-stone mb-4">
                        <span class="text-xs font-bold text-graphite uppercase tracking-wider block mb-1">Ticket Price</span>
                        <p class="text-[32px] font-semibold text-near-black tracking-tight leading-none">
                            Rp {{ number_format($paket->harga, 0, ',', '.') }} 
                            <span class="text-sm text-graphite font-medium">/ person</span>
                        </p>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Custom Floating Date Picker (Anti Jitter) -->
                        <div class="relative" @click.away="showDropdown = false">
                            <label class="block text-[10px] font-semibold text-graphite uppercase tracking-widest mb-2">Departure Date</label>
                            
                            <!-- Trigger Button -->
                            <button @click="showDropdown = !showDropdown" 
                                    type="button"
                                    class="w-full border border-stone rounded-[20px] px-5 py-4 bg-white text-left text-near-black hover:border-near-black transition-colors duration-200 flex justify-between items-center focus:outline-none">
                                <div>
                                    <span class="font-semibold text-sm" x-text="selectedScheduleText"></span>
                                    <span x-show="selectedScheduleId" class="text-xs text-graphite font-medium ml-2" x-text="'(Only ' + selectedScheduleQuota + ' seats left)'"></span>
                                </div>
                                <svg class="w-4 h-4 text-graphite transition-transform duration-200" :class="showDropdown ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Absolute Dropdown (Does not push content down) -->
                            <div x-show="showDropdown" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute z-50 left-0 right-0 mt-2 bg-white border border-stone rounded-[20px] overflow-hidden shadow-2xl max-h-60 overflow-y-auto"
                                 style="display: none;">
                                @forelse($activeSchedules as $jadwal)
                                    <div @click="selectedScheduleId = '{{ $jadwal->id_jadwal }}'; 
                                                 selectedScheduleText = '{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d M Y') }}'; 
                                                 selectedScheduleQuota = {{ $jadwal->sisa_kuota }};
                                                 showDropdown = false;"
                                         :class="selectedScheduleId == '{{ $jadwal->id_jadwal }}' ? 'bg-warm-cream text-near-black' : 'hover:bg-warm-cream/50'"
                                         class="px-5 py-4 cursor-pointer flex justify-between items-center transition-colors border-b border-stone/30 last:border-b-0">
                                        <div>
                                            <p class="font-semibold text-sm">{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d M Y') }}</p>
                                            <p class="text-xs text-graphite font-semibold mt-0.5">Only {{ $jadwal->sisa_kuota }} seats left</p>
                                        </div>
                                        <div x-show="selectedScheduleId == '{{ $jadwal->id_jadwal }}'" class="w-2.5 h-2.5 rounded-full bg-near-black"></div>
                                    </div>
                                @empty
                                    <div class="px-5 py-4 text-sm text-graphite font-medium text-center">No active schedules</div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Fixed-Size Participant Counter -->
                        <div>
                            <label class="block text-[10px] font-semibold text-graphite uppercase tracking-widest mb-2">Number of Participants</label>
                            <div class="flex items-center justify-between border border-stone rounded-[20px] p-2 bg-white w-full h-[52px]">
                                <button @click="if (jumlahPeserta > 1) jumlahPeserta--" type="button" class="w-10 h-9 rounded-[14px] bg-warm-cream text-near-black hover:bg-near-black hover:text-white transition flex items-center justify-center font-bold text-lg select-none">-</button>
                                <div class="flex items-center gap-1.5 justify-center flex-grow">
                                    <span class="font-semibold text-base text-near-black" x-text="jumlahPeserta"></span>
                                    <span class="text-xs text-graphite font-semibold uppercase">People</span>
                                </div>
                                <button @click="jumlahPeserta++" type="button" class="w-10 h-9 rounded-[14px] bg-warm-cream text-near-black hover:bg-near-black hover:text-white transition flex items-center justify-center font-bold text-lg select-none">+</button>
                            </div>
                        </div>

                        <!-- Optional Add-ons Selection Checklist -->
                        @if($addons->isNotEmpty())
                            <div class="border-t border-stone pt-4">
                                <label class="block text-[10px] font-semibold text-graphite uppercase tracking-widest mb-3">Optional Add-ons</label>
                                <div class="space-y-2.5">
                                    @foreach($addons as $addon)
                                        <div @click="let idx = selectedAddons.indexOf({{ $addon->id }}); if (idx > -1) { selectedAddons.splice(idx, 1); } else { selectedAddons.push({{ $addon->id }}); }"
                                             :class="selectedAddons.includes({{ $addon->id }}) ? 'border-electric-lime bg-white' : 'border-stone bg-white'"
                                             class="flex justify-between items-center p-3 rounded-[18px] border transition-all duration-200 cursor-pointer select-none">
                                            <div class="flex items-center gap-3">
                                                <!-- Circular Custom Checkmark -->
                                                <div :class="selectedAddons.includes({{ $addon->id }}) ? 'bg-electric-lime border-transparent' : 'bg-transparent border-stone'"
                                                     class="w-5 h-5 rounded-full border flex items-center justify-center transition-colors shrink-0">
                                                    <svg x-show="selectedAddons.includes({{ $addon->id }})" class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                    </svg>
                                                </div>
                                                <span class="text-sm font-semibold text-near-black">{{ $addon->nama_addon }}</span>
                                            </div>
                                            <span class="text-xs font-bold text-near-black">+ Rp {{ number_format($addon->harga, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Grand Total Display & Booking Button -->
                        <div class="border-t border-stone pt-4 mt-4">
                            <div class="flex justify-between items-baseline font-semibold text-near-black mb-4">
                                <span class="text-sm font-bold text-graphite uppercase tracking-wider">Total Cost</span>
                                <span class="text-2xl font-bold tracking-tight">Rp <span x-text="formatRupiah(grandTotal)"></span></span>
                            </div>

                            <button @click="submitBooking" 
                                    class="w-full bg-electric-lime text-white border border-transparent py-4 rounded-[20px] font-semibold hover:bg-near-black hover:text-white transition-all duration-300 ease-in-out hover:scale-[1.02] active:scale-95 text-sm uppercase tracking-wider">
                                Book Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Packages Section (Bottom of Page) -->
        <div class="mt-24 border-t border-stone pt-16">
            <h3 class="text-[30px] font-medium text-near-black tracking-tight text-center mb-12">Recommended Adventures</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full">
                @foreach($related as $relPaket)
                    <a href="{{ route('paket.detail', $relPaket->id_paket) }}" class="group relative flex flex-col justify-end w-full aspect-[3/4] rounded-[26px] overflow-hidden border border-stone bg-stone/20">
                        @php
                            $relImg = $relPaket->galleries->where('is_primary', true)->first()?->image_url 
                                ?? ($relPaket->galleries->first()?->image_url 
                                ?? 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&=80');
                        @endphp
                        <!-- Background Image -->
                        <img src="{{ $relImg }}" alt="{{ $relPaket->nama_paket }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        
                        <!-- Gradient Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-near-black/95 via-near-black/30 to-transparent"></div>
                        
                        <!-- Bottom Content -->
                        <div class="relative z-10 p-6">
                            <h4 class="text-white text-lg font-bold mb-1.5 leading-tight">{{ $relPaket->nama_paket }}</h4>
                            <p class="text-white/80 text-sm font-medium">
                                Rp {{ number_format($relPaket->harga, 0, ',', '.') }} <span class="text-white/60 text-xs font-normal">/ person</span>
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-near-black text-white pt-24 pb-8 px-6 mt-auto">
        <div class="max-w-[1400px] mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
            <div>
                <a href="/" class="text-3xl font-bold tracking-tight text-white mb-6 block">
                    Kelana
                </a>
                <p class="text-graphite leading-relaxed">Opening doors to extraordinary adventures with world-class comfort and safety standards.</p>
            </div>
            <div>
                <h4 class="text-lg font-medium mb-6 text-white">Company</h4>
                <ul class="space-y-4 text-graphite font-medium">
                    <li><a href="#" class="hover:text-white transition-colors">About Us</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Careers</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Blog</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-medium mb-6 text-white">Support</h4>
                <ul class="space-y-4 text-graphite font-medium">
                    <li><a href="#" class="hover:text-white transition-colors">Help Center</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Terms & Conditions</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-medium mb-6 text-white">Newsletter</h4>
                <p class="text-graphite mb-4 font-medium">Get the latest promo and destination updates.</p>
                <div class="flex items-center border border-graphite rounded-full p-1 focus-within:border-electric-lime transition-colors font-medium">
                    <input type="email" placeholder="Your Email" class="bg-transparent border-none outline-none focus:ring-0 text-white w-full px-4">
                    <button class="bg-electric-lime text-white w-10 h-10 rounded-full flex items-center justify-center shrink-0 hover:bg-near-black hover:text-white hover:scale-105 active:scale-95 transition-all duration-300 ease-in-out focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="max-w-[1400px] mx-auto mt-24 pt-8 border-t border-charcoal text-graphite text-sm flex flex-col md:flex-row justify-between items-center gap-4">
            <p>&copy; {{ date('Y') }} Kelana Travel. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- Script Inisialisasi Peta Leaflet -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var lat = {{ $paket->latitude ?? -7.942493 }};
            var lng = {{ $paket->longitude ?? 112.953012 }};
            var map = L.map('map', { scrollWheelZoom: false }).setView([lat, lng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);
            L.marker([lat, lng]).addTo(map)
                .bindPopup({!! json_encode('<b>Meeting Point ' . $paket->nama_paket . '</b><br>' . $paket->rute) !!})
                .openPopup();
        });
    </script>
</body>
</html>
