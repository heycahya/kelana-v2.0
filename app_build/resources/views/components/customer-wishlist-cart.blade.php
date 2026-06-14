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

    <!-- Custom Cancel Confirmation Modal -->
    <div x-show="showCancelConfirmModal" class="fixed inset-0 z-[60] overflow-y-auto flex items-center justify-center p-4" style="display: none;" @keydown.window.escape="showCancelConfirmModal = false">
        <!-- Overlay -->
        <div x-show="showCancelConfirmModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 bg-[#0f1a15]/60 transition-opacity" 
             @click="showCancelConfirmModal = false"></div>

        <!-- Panel -->
        <div x-show="showCancelConfirmModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-[#f4f3ed] rounded-[26px] max-w-sm w-full p-6 border border-stone text-near-black z-10 relative text-center space-y-4 shadow-2xl">
            
            <div class="text-4xl">⚠️</div>
            <h3 class="text-lg font-bold text-[#0f1a15]">Batalkan Pesanan?</h3>
            <p class="text-xs text-graphite leading-relaxed">
                Apakah Anda yakin ingin membatalkan pesanan perjalanan ini? Kuota yang telah Anda kunci akan dikembalikan ke sistem.
            </p>

            <div class="grid grid-cols-2 gap-3 pt-2">
                <button @click="showCancelConfirmModal = false" class="w-full bg-stone/20 text-near-black py-2.5 rounded-[16px] font-semibold hover:bg-stone/30 transition text-xs">
                    Kembali
                </button>
                <button @click="confirmCancelCartItem()" class="w-full bg-red-600 text-white py-2.5 rounded-[16px] font-semibold hover:bg-red-700 transition text-xs">
                    Ya, Batalkan
                </button>
            </div>
        </div>
    </div>

    <!-- Floating Chat Widget Button -->
    <div class="fixed bottom-6 right-6 z-40">
        <button @click="isChatOpen = !isChatOpen; if(isChatOpen) { $nextTick(() => scrollChatToBottom()) }" 
                class="w-14 h-14 bg-[#1e5e3a] text-white rounded-full flex items-center justify-center shadow-lg hover:bg-[#154329] hover:scale-105 active:scale-95 transition-all duration-300 relative"
                title="Hubungi Kami (CS)">
            <!-- Chat bubble SVG -->
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 01.778-.332 48.294 48.294 0 005.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/>
            </svg>
            <!-- Unread Dot Indicator (if any admin message is unread) -->
            <span x-show="chatMessages.some(m => m.sender_type === 'admin' && !m.is_read)" class="absolute top-0 right-0 w-3.5 h-3.5 bg-red-500 border border-white rounded-full" style="display: none;"></span>
        </button>
    </div>

    <!-- Chat Card Box -->
    <div x-show="isChatOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-8 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-8 scale-95"
         class="fixed bottom-24 right-6 w-80 sm:w-96 max-h-[500px] h-[500px] bg-[#f4f3ed] rounded-[24px] border border-stone shadow-2xl z-50 flex flex-col overflow-hidden text-near-black"
         style="display: none;">
        
        <!-- Header -->
        <div class="px-5 py-4 bg-[#0b1611] text-white flex justify-between items-center shrink-0 border-b border-white/10">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-[#1e5e3a] text-white flex items-center justify-center font-bold text-xs uppercase">
                    CS
                </div>
                <div>
                    <h4 class="text-xs font-bold leading-tight">Kelana Customer Service</h4>
                    <span class="text-[9px] text-electric-lime font-bold block mt-0.5">🟢 Online - Admin Support</span>
                </div>
            </div>
            <button @click="isChatOpen = false" class="text-white/70 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Chat Stream -->
        <div class="flex-grow overflow-y-auto p-4 space-y-3.5 flex flex-col" id="customer-chat-container">
            <div class="bg-white text-near-black p-3.5 rounded-[18px] text-[11px] font-semibold leading-relaxed border border-stone/50 self-start rounded-bl-none">
                Halo! Ada yang bisa kami bantu seputar trip, pemesanan, atau rute perjalanan Kelana? 😊
            </div>
            <template x-for="msg in chatMessages" :key="msg.id">
                <div class="max-w-[80%] rounded-[18px] p-3 text-[11px] font-semibold leading-relaxed"
                     :class="msg.sender_type === 'customer' ? 'bg-[#1e5e3a] text-white self-end rounded-br-none' : 'bg-white text-near-black border border-stone/50 self-start rounded-bl-none'">
                    <span x-show="msg.sender_type !== 'customer'" class="block text-[8px] text-[#1e5e3a] font-bold mb-1" x-text="msg.sender_type === 'admin' ? 'Admin Support' : 'Trip Leader'"></span>
                    <p class="whitespace-pre-line" x-text="msg.message"></p>
                    <span class="block text-[8px] text-right mt-1"
                          :class="msg.sender_type === 'customer' ? 'text-white/60' : 'text-stone/70'"
                          x-text="formatChatTime(msg.created_at)"></span>
                </div>
            </template>
        </div>

        <!-- Input Footer -->
        <div class="p-4 bg-white border-t border-stone/50 shrink-0">
            <form @submit.prevent="sendChatMessage()" class="flex items-center gap-2">
                <input type="text" x-model="chatNewMessage" placeholder="Ketik pertanyaan Anda..." class="flex-grow p-3 rounded-full bg-[#f4f3ed]/60 border border-stone text-xs font-semibold outline-none focus:border-near-black focus:bg-white transition">
                <button type="submit" class="bg-[#1e5e3a] text-white hover:bg-near-black p-3 rounded-full text-xs font-bold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Reusable Javascript state definitions -->
    <script>
        function wishlistCartData() {
            return {
                isWishlistOpen: false,
                isCartOpen: false,
                isChatOpen: false,
                showCancelConfirmModal: false,
                pemesananIdToCancel: null,
                wishlistItems: [],
                cartItem: null,
                chatMessages: [],
                chatNewMessage: '',
                chatPollingInterval: null,
                isLoggedIn: true,
                init() {
                    this.fetchWishlist();
                    this.fetchCart();
                    this.pollChatMessages();
                    
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
                pollChatMessages() {
                    this.fetchChatMessages();
                    this.chatPollingInterval = setInterval(() => {
                        this.fetchChatMessages();
                    }, 3000);
                },
                fetchChatMessages() {
                    fetch('{{ route('customer.chat.messages') }}')
                        .then(res => res.json())
                        .then(data => {
                            this.chatMessages = Array.isArray(data) ? data : [];
                        })
                        .catch(err => console.error('Error fetching chat messages:', err));
                },
                sendChatMessage() {
                    if (!this.chatNewMessage.trim()) return;
                    const text = this.chatNewMessage;
                    this.chatNewMessage = '';
                    
                    fetch('{{ route('customer.chat.send') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ message: text })
                    })
                    .then(res => res.json())
                    .then(msg => {
                        this.chatMessages.push(msg);
                        this.$nextTick(() => this.scrollChatToBottom());
                    })
                    .catch(err => console.error('Error sending chat message:', err));
                },
                scrollChatToBottom() {
                    const el = document.getElementById('customer-chat-container');
                    if (el) {
                        el.scrollTop = el.scrollHeight;
                    }
                },
                formatChatTime(ts) {
                    if (!ts) return '';
                    const date = new Date(ts);
                    return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
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
                    this.pemesananIdToCancel = pemesananId;
                    this.showCancelConfirmModal = true;
                },
                confirmCancelCartItem() {
                    const id = this.pemesananIdToCancel;
                    if (!id) return;
                    this.showCancelConfirmModal = false;
                    fetch('{{ url('/cart') }}/' + id, {
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
                                alert('Terima kasih! Pembayaran Anda telah berhasil kami terima.');
                                this.fetchCart();
                                window.location.reload();
                            },
                            onPending: (result) => {
                                alert('Menunggu pembayaran Anda diselesaikan.');
                            },
                            onError: (result) => {
                                alert('Mohon maaf, pembayaran Anda gagal diproses. Silakan coba kembali.');
                            },
                            onClose: () => {
                                alert('Pop-up pembayaran telah ditutup.');
                            }
                        });
                    } else {
                        alert('Layanan pembayaran sedang terganggu. Mengalihkan ke dashboard Anda...');
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
