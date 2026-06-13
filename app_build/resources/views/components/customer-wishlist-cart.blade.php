@if (Auth::guard('customer')->check())
    <!-- Midtrans Snap Client Script -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <!-- Wishlist Drawer (Slide-Over) -->
    <div x-show="isWishlistOpen" class="fixed inset-0 z-50 overflow-hidden" style="display: none;" @keydown.window.escape="isWishlistOpen = false">
        <!-- Background overlay -->
        <div x-show="isWishlistOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 bg-[#0f1a15]/50 transition-opacity" 
             @click="isWishlistOpen = false"></div>

        <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
            <!-- Drawer Panel -->
            <div x-show="isWishlistOpen"
                 x-transition:enter="transform transition ease-in-out duration-300 sm:duration-300"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in-out duration-200 sm:duration-200"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="w-screen max-w-md bg-[#f4f3ed] text-near-black flex flex-col h-full border-l border-stone">
                
                <!-- Drawer Header -->
                <div class="px-6 py-6 border-b border-stone flex items-center justify-between">
                    <h2 class="text-xl font-bold tracking-tight text-[#0f1a15]">Wishlist Tersimpan</h2>
                    <button @click="isWishlistOpen = false" class="text-near-black hover:text-[#1e5e3a] focus:outline-none transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Drawer Body -->
                <div class="flex-1 overflow-y-auto px-6 py-6 space-y-4 no-scrollbar">
                    <template x-if="wishlistItems.length === 0">
                        <div class="text-center py-12">
                            <svg class="w-12 h-12 text-[#3f4e45] mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <p class="text-[#3f4e45]">Belum ada destinasi favorit yang disimpan.</p>
                        </div>
                    </template>

                    <template x-for="item in wishlistItems" :key="item.id">
                        <div class="bg-white rounded-[26px] p-4 flex gap-4 border border-stone transition-all duration-300 hover:scale-[1.01]">
                            <!-- Image thumbnail -->
                            <div class="w-20 h-20 rounded-[18px] overflow-hidden bg-stone/20 shrink-0">
                                <img :src="item.gambar" :alt="item.nama" class="w-full h-full object-cover">
                            </div>
                            
                            <!-- Item Details -->
                            <div class="flex-grow flex flex-col justify-between">
                                <div>
                                    <h3 class="font-bold text-sm text-[#0f1a15] line-clamp-1">
                                        <a :href="item.url" class="hover:text-[#1e5e3a]" x-text="item.nama"></a>
                                    </h3>
                                    <p class="text-xs text-[#3f4e45] font-semibold mt-0.5" x-text="'📍 ' + item.rute"></p>
                                </div>
                                <div class="flex justify-between items-end mt-2">
                                    <span class="font-bold text-sm text-[#0f1a15]" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(item.harga)"></span>
                                    
                                    <!-- Delete Button -->
                                    <button @click="toggleWishlist(item.paket_wisata_id)" class="text-[#eb3131] hover:bg-[#eb3131]/10 rounded-full p-1.5 transition" aria-label="Remove item">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart / Pending Order Pop-up (Modal) -->
    <div x-show="isCartOpen" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4" style="display: none;" @keydown.window.escape="isCartOpen = false">
        <!-- Background overlay -->
        <div x-show="isCartOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 bg-[#0f1a15]/50 transition-opacity" 
             @click="isCartOpen = false"></div>

        <!-- Modal Panel -->
        <div x-show="isCartOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-[#f4f3ed] rounded-[26px] max-w-lg w-full p-6 border border-stone text-near-black z-10 relative">
            
            <!-- Header -->
            <div class="flex justify-between items-center pb-4 border-b border-stone mb-6">
                <h3 class="text-xl font-bold tracking-tight text-[#0f1a15]">Keranjang Pemesanan</h3>
                <button @click="isCartOpen = false" class="text-near-black hover:text-[#1e5e3a] focus:outline-none transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Empty State -->
            <template x-if="!cartItem">
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-[#3f4e45] mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <p class="text-[#3f4e45] font-semibold text-lg">Keranjang Anda masih kosong.</p>
                    <p class="text-[#3f4e45]/80 text-sm mt-1">Yuk cari petualangan seru bersama Kelana!</p>
                    <button @click="isCartOpen = false" class="mt-6 bg-[#1e5e3a] text-white px-8 py-3 rounded-full font-bold hover:bg-near-black transition">
                        Cari Petualangan
                    </button>
                </div>
            </template>

            <!-- Active Item (Pending) -->
            <template x-if="cartItem">
                <div class="space-y-6">
                    <div class="bg-white rounded-[26px] p-5 border border-stone">
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-[10px] font-bold uppercase tracking-wider">Holding Order</span>
                        
                        <h4 class="text-lg font-bold text-[#0f1a15] mt-3" x-text="cartItem.paket_nama"></h4>
                        
                        <div class="grid grid-cols-2 gap-4 mt-4 text-sm">
                            <div>
                                <span class="text-xs text-[#3f4e45] font-semibold uppercase block">Keberangkatan</span>
                                <span class="font-bold text-[#0f1a15]" x-text="cartItem.tanggal_keberangkatan"></span>
                            </div>
                            <div>
                                <span class="text-xs text-[#3f4e45] font-semibold uppercase block">Jumlah Peserta</span>
                                <span class="font-bold text-[#0f1a15]" x-text="cartItem.jumlah_peserta + ' Pax'"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Price Details -->
                    <div class="border-t border-stone pt-4">
                        <div class="flex justify-between items-baseline font-semibold text-near-black">
                            <span class="text-sm font-bold text-[#3f4e45] uppercase tracking-wider">Total Harga</span>
                            <span class="text-2xl font-bold text-[#0f1a15]" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(cartItem.total_harga)"></span>
                        </div>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="grid grid-cols-2 gap-4 pt-2">
                        <button @click="cancelCartItem(cartItem.id_pemesanan)" 
                                class="w-full bg-stone/50 text-near-black hover:bg-[#eb3131]/10 hover:text-[#eb3131] py-3.5 rounded-[20px] font-semibold transition text-sm uppercase tracking-wider">
                            Batalkan Pesanan
                        </button>
                        <button @click="payCartItem(cartItem.snap_token)" 
                                class="w-full bg-[#1e5e3a] text-white py-3.5 rounded-[20px] font-semibold hover:bg-near-black transition text-sm uppercase tracking-wider">
                            Lanjutkan Pembayaran
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Reusable Javascript state definitions -->
    <script>
        function wishlistCartData() {
            return {
                isWishlistOpen: false,
                isCartOpen: false,
                wishlistItems: [],
                cartItem: null,
                isLoggedIn: true,
                init() {
                    this.fetchWishlist();
                    this.fetchCart();
                    // Listen for global custom events to allow components to interact with wishlist/cart
                    window.addEventListener('toggle-wishlist-global', (e) => {
                        this.toggleWishlist(e.detail.id);
                    });
                    window.addEventListener('open-wishlist-global', () => {
                        this.isWishlistOpen = true;
                    });
                    window.addEventListener('open-cart-global', () => {
                        this.isCartOpen = true;
                    });
                },
                fetchWishlist() {
                    fetch('{{ route('customer.wishlist.index') }}')
                        .then(res => {
                            if (!res.ok) throw new Error('Wishlist fetch error');
                            return res.json();
                        })
                        .then(data => {
                            this.wishlistItems = Array.isArray(data) ? data : [];
                            // Emit event to update localized heart button states
                            window.dispatchEvent(new CustomEvent('wishlist-loaded', { detail: this.wishlistItems }));
                        })
                        .catch(err => {
                            console.error('Error fetching wishlist:', err);
                            this.wishlistItems = [];
                        });
                },
                fetchCart() {
                    fetch('{{ route('customer.cart.index') }}')
                        .then(res => {
                            if (!res.ok) throw new Error('Cart fetch error');
                            return res.json();
                        })
                        .then(data => {
                            if (data && data.id_pemesanan) {
                                this.cartItem = data;
                            } else {
                                this.cartItem = null;
                            }
                        })
                        .catch(err => {
                            console.error('Error fetching cart:', err);
                            this.cartItem = null;
                        });
                },
                toggleWishlist(paketId) {
                    const exists = this.wishlistItems.some(item => item.paket_wisata_id == paketId);
                    const url = exists ? '{{ url('/wishlist') }}/' + paketId : '{{ route('customer.wishlist.store') }}';
                    const method = exists ? 'DELETE' : 'POST';
                    const body = exists ? null : JSON.stringify({ paket_wisata_id: paketId });

                    fetch(url, {
                        method: method,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: body
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            this.fetchWishlist();
                        }
                    })
                    .catch(err => console.error('Error toggling wishlist:', err));
                },
                cancelCartItem(pemesananId) {
                    if (!confirm('Apakah Anda yakin ingin membatalkan pesanan tertunda ini?')) return;
                    fetch('{{ url('/cart') }}/' + pemesananId, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            this.fetchCart();
                            window.location.reload();
                        } else {
                            alert(data.message || 'Gagal membatalkan pesanan.');
                        }
                    })
                    .catch(err => console.error('Error cancelling cart item:', err));
                },
                payCartItem(snapToken) {
                    if (typeof snap !== 'undefined' && snapToken) {
                        snap.pay(snapToken, {
                            onSuccess: (result) => {
                                alert('Pembayaran berhasil!');
                                this.fetchCart();
                                window.location.reload();
                            },
                            onPending: (result) => {
                                alert('Menunggu pembayaran...');
                            },
                            onError: (result) => {
                                alert('Pembayaran gagal!');
                            },
                            onClose: () => {
                                alert('Pop-up pembayaran ditutup.');
                            }
                        });
                    } else {
                        // Redirect to booking to try and pay or re-initiate
                        alert('Layanan pembayaran tidak tersedia. Mengalihkan ke dashboard...');
                        window.location.href = '{{ route('dashboard') }}';
                    }
                }
            };
        }
    </script>
@else
    <!-- Guest state placeholder script to prevent JS undefined errors -->
    <script>
        function wishlistCartData() {
            return {
                isWishlistOpen: false,
                isCartOpen: false,
                wishlistItems: [],
                cartItem: null,
                isLoggedIn: false,
                init() {
                    window.addEventListener('toggle-wishlist-global', () => {
                        window.location.href = '{{ route('login') }}';
                    });
                    window.addEventListener('open-wishlist-global', () => {
                        window.location.href = '{{ route('login') }}';
                    });
                    window.addEventListener('open-cart-global', () => {
                        window.location.href = '{{ route('login') }}';
                    });
                }
            };
        }
    </script>
@endif
