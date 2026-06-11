<x-guest-layout>
    <div class="py-12 px-6 max-w-xl mx-auto w-full" x-data="bookingForm()">
        <!-- Back Link -->
        <a href="{{ route('paket.detail', $jadwal->id_paket) }}" class="inline-flex items-center text-graphite hover:text-near-black font-semibold mb-8 transition duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Detail Paket
        </a>

        <!-- Booking Form Container -->
        <div class="bg-white rounded-3xl p-8 border border-stone">
            <h1 class="text-3xl font-black text-near-black tracking-tight mb-2">Form Pemesanan</h1>
            <p class="text-graphite mb-8 leading-relaxed">
                Isi jumlah peserta untuk melakukan pemesanan kursi pada jadwal ini.
            </p>

            <!-- Error Message Alert -->
            <div x-show="errorMessage" x-text="errorMessage" class="mb-6 p-4 bg-red-50 text-red-600 rounded-2xl border border-red-200 text-sm font-semibold" style="display: none;"></div>

            <!-- Booking Info Card -->
            <div class="bg-parchment-card p-5 rounded-2xl border border-stone mb-6">
                <span class="text-xs font-semibold text-graphite uppercase tracking-wider block mb-1">Paket yang dipilih</span>
                <p class="font-extrabold text-lg text-near-black mb-2">{{ $jadwal->paketWisata->nama_paket }}</p>
                
                <div class="flex justify-between items-center border-t border-stone pt-3 mt-3">
                    <div>
                        <span class="text-xs text-graphite uppercase tracking-wider block">Keberangkatan</span>
                        <p class="font-bold text-near-black">{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d M Y') }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-graphite uppercase tracking-wider block">Sisa Kuota</span>
                        <p class="font-bold text-near-black">{{ $jadwal->sisa_kuota }} Kursi</p>
                    </div>
                </div>
            </div>

            <!-- Alpine.js dynamic inputs -->
            <form @submit.prevent="submitBooking">
                @csrf
                <input type="hidden" name="id_jadwal" value="{{ $jadwal->id_jadwal }}">

                <!-- Jumlah Peserta -->
                <div class="mb-6">
                    <label for="jumlah_peserta" class="block text-sm font-bold text-near-black mb-2">Jumlah Peserta</label>
                    <input 
                        type="number" 
                        id="jumlah_peserta" 
                        name="jumlah_peserta" 
                        x-model.number="jumlah" 
                        min="1" 
                        max="{{ $jadwal->sisa_kuota }}" 
                        class="border border-stone rounded-3xl px-4 py-3 bg-white text-near-black focus:outline-none focus:border-near-black focus:ring-0 w-full font-medium"
                        required
                    >
                </div>

                <!-- Live Summary -->
                <div class="p-6 bg-parchment-card rounded-3xl border border-stone mb-8">
                    <span class="text-xs text-graphite uppercase tracking-wider block mb-1">Total Biaya Perjalanan</span>
                    <p class="font-black text-3xl text-near-black">
                        Rp <span x-text="formatRupiah(jumlah * harga)"></span>
                    </p>
                    <p class="text-xs text-graphite mt-2">*Harga sudah termasuk semua fasilitas tercantum.</p>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full py-4 bg-electric-lime text-near-black font-extrabold rounded-3xl hover:opacity-90 transition duration-200 flex items-center justify-center gap-2"
                    :disabled="loading"
                >
                    <svg x-show="loading" class="animate-spin h-5 w-5 text-near-black" fill="none" viewBox="0 0 24 24" style="display: none;">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="loading ? 'Memproses...' : 'Pesan Kursi & Bayar'"></span>
                </button>
            </form>
        </div>
    </div>

    <!-- Midtrans Snap JS -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <script>
        function bookingForm() {
            return {
                jumlah: 1,
                harga: {{ $jadwal->paketWisata->harga }},
                loading: false,
                errorMessage: '',

                formatRupiah(val) {
                    return new Intl.NumberFormat('id-ID').format(val);
                },

                submitBooking() {
                    this.loading = true;
                    this.errorMessage = '';

                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    fetch('{{ route("customer.booking.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            id_jadwal: {{ $jadwal->id_jadwal }},
                            jumlah_peserta: this.jumlah
                        })
                    })
                    .then(response => response.json().then(data => ({ status: response.status, body: data })))
                    .then(res => {
                        if (res.status === 201) {
                            const snapToken = res.body.snap_token;
                            
                            // Trigger Midtrans Snap
                            window.snap.pay(snapToken, {
                                onSuccess: (result) => {
                                    window.location.href = '{{ route("dashboard") }}';
                                },
                                onPending: (result) => {
                                    window.location.href = '{{ route("dashboard") }}';
                                },
                                onError: (result) => {
                                    this.loading = false;
                                    this.errorMessage = 'Terjadi kesalahan pembayaran. Silakan coba kembali.';
                                },
                                onClose: () => {
                                    window.location.href = '{{ route("dashboard") }}';
                                }
                            });
                        } else {
                            this.loading = false;
                            this.errorMessage = res.body.message || 'Gagal memproses pemesanan. Coba lagi.';
                        }
                    })
                    .catch(err => {
                        this.loading = false;
                        this.errorMessage = 'Koneksi internet bermasalah. Silakan coba lagi.';
                        console.error(err);
                    });
                }
            }
        }
    </script>
</x-guest-layout>
