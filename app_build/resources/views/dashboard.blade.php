<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-near-black leading-tight tracking-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert for non-customer roles -->
            @if(($role ?? 'Customer') !== 'Customer')
                <div class="bg-white overflow-hidden border border-stone rounded-3xl p-8 mb-6">
                    <h3 class="text-2xl font-semibold text-near-black mb-2">Welcome, {{ Auth::user()->name }}!</h3>
                    <p class="text-graphite">You are logged in as <span class="font-semibold text-near-black">{{ $role }}</span>.</p>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left/Main Panel: Active E-Tickets (2 Columns wide on lg) -->
                    <div class="lg:col-span-2">
                        <h3 class="text-2xl font-semibold text-near-black mb-6 tracking-tight">Active Trip E-Tickets</h3>

                        @forelse($activeTrips ?? [] as $trip)
                            <div class="bg-white rounded-3xl border border-near-black p-6 mb-6">
                                <div class="flex justify-between items-center border-b border-stone pb-4 mb-6">
                                    <div>
                                        <p class="text-xs text-graphite uppercase tracking-wider">Booking Code</p>
                                        <h4 class="text-2xl font-semibold text-near-black tracking-tight">{{ $trip->booking_code }}</h4>
                                    </div>
                                    <span class="px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wider
                                        {{ $trip->status_pembayaran === 'PAID' ? 'bg-electric-lime text-white' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $trip->status_pembayaran }}
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <p class="text-xs text-graphite uppercase tracking-wider">Trip Destination</p>
                                        <p class="font-semibold text-lg text-near-black">{{ $trip->jadwal->paketWisata->nama_paket }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-graphite uppercase tracking-wider">Departure</p>
                                        <p class="font-semibold text-lg text-near-black">{{ \Carbon\Carbon::parse($trip->jadwal->tanggal_mulai)->format('d M Y') }}</p>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <p class="text-xs text-graphite uppercase tracking-wider">Registered Participants</p>
                                        <p class="font-semibold text-near-black text-lg">
                                            {{ $trip->jumlah_peserta }} People 
                                            <span class="text-sm font-medium text-graphite">
                                                (Present at location: <span class="text-near-black font-semibold">{{ $trip->jumlah_hadir ?? 0 }}</span>)
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-16 bg-white border border-stone rounded-3xl">
                                <p class="text-graphite text-lg">No active e-tickets at this time.</p>
                                <a href="/" class="inline-block mt-4 px-6 py-2.5 bg-electric-lime text-white font-semibold rounded-[26px] hover:bg-near-black hover:text-white hover:scale-105 active:scale-95 transition-all duration-300 ease-in-out">
                                    Explore Travel Packages
                                </a>
                            </div>
                        @endforelse
                    </div>

                    <!-- Right Panel: Past Trips History -->
                    <div>
                        <h3 class="text-2xl font-semibold text-near-black mb-6 tracking-tight">Trip History</h3>

                        <div class="space-y-4">
                            @forelse($pastTrips ?? [] as $trip)
                                <div class="bg-white rounded-3xl p-5 border border-stone">
                                    <div class="flex justify-between items-start mb-3">
                                        <span class="text-xs text-graphite font-semibold uppercase tracking-wider">
                                            {{ \Carbon\Carbon::parse($trip->jadwal->tanggal_mulai)->format('d M Y') }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-stone text-near-black">
                                            COMPLETED
                                        </span>
                                    </div>
                                    <h4 class="font-semibold text-near-black tracking-tight mb-1">
                                        {{ $trip->jadwal->paketWisata->nama_paket }}
                                    </h4>
                                    <p class="text-xs text-graphite font-medium">Booking Code: {{ $trip->booking_code }}</p>
                                    <p class="text-xs text-graphite mt-1 font-medium">
                                        Total Paid: <span class="font-semibold text-near-black">Rp {{ number_format($trip->total_harga, 0, ',', '.') }}</span>
                                    </p>
                                </div>
                            @empty
                                <div class="text-center py-10 bg-white border border-stone rounded-3xl">
                                    <p class="text-graphite text-sm font-medium">No trip history yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
