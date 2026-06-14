@extends('layouts.leader')

@section('content')
<div class="space-y-8" x-data="leaderChat()" x-init="initChat()">
    <!-- Header -->
    <div class="border-b border-[#dfdfd6] pb-6">
        <h1 class="text-3xl font-bold tracking-tight text-near-black">Chat Ops Support</h1>
        <p class="text-graphite text-sm mt-1">Koordinasi langsung dengan Kantor Pusat Admin dan Customer Anda.</p>
    </div>

    <!-- 2-Column Chat Layout -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-stretch h-[calc(100vh-260px)]">
        
        <!-- Left Column: Contact List (Spans 4 columns) -->
        <div class="md:col-span-4 bg-white rounded-[24px] border border-[#dfdfd6] flex flex-col overflow-hidden">
            <div class="p-5 border-b border-[#dfdfd6] bg-white shrink-0">
                <h3 class="font-bold text-near-black text-xs uppercase tracking-wider text-graphite/60">Daftar Kontak</h3>
            </div>
            
            <div class="flex-grow overflow-y-auto divide-y divide-[#dfdfd6] no-scrollbar">
                <!-- Contact 1: Admin HQ -->
                <button @click="selectContact('admin', 1, 'Kelana HQ (Admin Support)', 'HQ')"
                        class="w-full text-left p-4.5 flex items-center space-x-3.5 transition-all duration-200 focus:outline-none"
                        :class="activeContact.type === 'admin' && activeContact.id === 1 ? 'bg-[#f4f3ed]/60 text-near-black font-bold border-l-4 border-[#1e5e3a]' : 'hover:bg-[#f4f3ed]/30 text-graphite'">
                    <div class="w-10 h-10 rounded-full bg-[#0b1611] text-white flex items-center justify-center font-black text-xs shrink-0">
                        HQ
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold text-near-black truncate">Kelana HQ (Admin Support)</p>
                        <p class="text-[10px] text-stone-500 truncate mt-0.5">Pusat Operasional Kelana</p>
                    </div>
                </button>

                <!-- Contacts: Customers -->
                @forelse($customers as $customer)
                    <button @click="selectContact('customer', {{ $customer->id_customer }}, '{{ $customer->nama_customer }}', '{{ strtoupper(substr($customer->nama_customer, 0, 1)) }}')"
                            class="w-full text-left p-4.5 flex items-center space-x-3.5 transition-all duration-200 focus:outline-none"
                            :class="activeContact.type === 'customer' && activeContact.id === {{ $customer->id_customer }} ? 'bg-[#f4f3ed]/60 text-near-black font-bold border-l-4 border-[#1e5e3a]' : 'hover:bg-[#f4f3ed]/30 text-graphite'">
                        <div class="w-10 h-10 rounded-full bg-[#1e5e3a] text-white flex items-center justify-center font-black text-xs shrink-0">
                            {{ strtoupper(substr($customer->nama_customer, 0, 1)) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-xs font-bold text-near-black truncate">{{ $customer->nama_customer }}</p>
                            <p class="text-[10px] text-stone-500 truncate mt-0.5">Peserta Trip Anda</p>
                        </div>
                    </button>
                @empty
                    <div class="p-6 text-center text-stone-400 font-semibold text-xs leading-normal">
                        Belum ada customer berstatus PAID di jadwal Anda.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right Column: Chat Box (Spans 8 columns) -->
        <div class="md:col-span-8 bg-white rounded-[24px] border border-[#dfdfd6] flex flex-col overflow-hidden">
            <!-- Chat Header -->
            <div class="p-6 border-b border-[#dfdfd6] bg-white flex justify-between items-center shrink-0">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-[#1e5e3a] text-white flex items-center justify-center font-extrabold text-sm border border-[#dfdfd6]/20"
                         :class="activeContact.type === 'admin' ? 'bg-[#0b1611]' : 'bg-[#1e5e3a]'"
                         x-text="activeContact.initial">
                    </div>
                    <div>
                        <h3 class="font-bold text-near-black text-sm leading-tight" x-text="activeContact.name"></h3>
                        <span class="text-[10px] text-[#1e5e3a] font-bold block mt-0.5" x-text="activeContact.type === 'admin' ? '🟢 Online - Siap Melayani Ops' : '🟢 Peserta Trip'"></span>
                    </div>
                </div>
            </div>

            <!-- Messages stream -->
            <div class="flex-grow overflow-y-auto p-6 space-y-4 flex flex-col bg-[#f4f3ed]/25" id="msg-container">
                <template x-for="msg in messages" :key="msg.id">
                    <div class="max-w-[75%] rounded-[20px] p-4.5 text-xs font-semibold leading-relaxed"
                         :class="msg.sender_type === 'trip_leader' ? 'bg-[#1e5e3a] text-white self-end rounded-br-none' : 'bg-white text-near-black border border-[#dfdfd6] self-start rounded-bl-none'">
                        <p class="whitespace-pre-line" x-text="msg.message"></p>
                        <span class="block text-[9px] text-right mt-1.5 font-bold uppercase tracking-wider"
                              :class="msg.sender_type === 'trip_leader' ? 'text-white/60' : 'text-stone/70'"
                              x-text="formatTime(msg.created_at)"></span>
                    </div>
                </template>
            </div>

            <!-- Input Footer -->
            <div class="p-6 bg-white border-t border-[#dfdfd6] shrink-0">
                <form @submit.prevent="sendMessage()" class="flex items-center gap-3">
                    <input type="text" x-model="newMessage" placeholder="Ketik pesan..." class="flex-grow p-4 rounded-[26px] bg-[#f4f3ed]/60 border border-[#dfdfd6] text-xs font-semibold outline-none focus:border-near-black focus:bg-white transition">
                    <button type="submit" class="bg-near-black text-white hover:bg-[#1e5e3a] px-6 py-4 rounded-[26px] text-xs font-bold transition flex items-center gap-1.5">
                        <span>Kirim</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
    function leaderChat() {
        return {
            messages: [],
            newMessage: '',
            pollingInterval: null,
            activeContact: {
                type: 'admin',
                id: 1,
                name: 'Kelana HQ (Admin Support)',
                initial: 'HQ'
            },

            initChat() {
                this.loadMessages(true);
                // Poll for new messages
                this.pollingInterval = setInterval(() => {
                    this.loadMessages();
                }, 3000);
            },

            selectContact(type, id, name, initial) {
                this.activeContact = { type, id, name, initial };
                this.messages = [];
                this.loadMessages(true);
            },

            loadMessages(shouldScroll = false) {
                const url = `{{ route("leader.chat.messages") }}?contact_type=${this.activeContact.type}&contact_id=${this.activeContact.id}`;
                fetch(url)
                    .then(r => r.json())
                    .then(data => {
                        this.messages = data;
                        if (shouldScroll) {
                            this.$nextTick(() => this.scrollToBottom());
                        }
                    });
            },

            sendMessage() {
                if (!this.newMessage.trim()) return;
                const text = this.newMessage;
                this.newMessage = '';

                fetch('{{ route("leader.chat.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ 
                        message: text,
                        contact_type: this.activeContact.type,
                        contact_id: this.activeContact.id
                    })
                })
                .then(r => r.json())
                .then(msg => {
                    this.messages.push(msg);
                    this.$nextTick(() => this.scrollToBottom());
                });
            },

            scrollToBottom() {
                const el = document.getElementById('msg-container');
                if (el) {
                    el.scrollTop = el.scrollHeight;
                }
            },

            formatTime(timestamp) {
                if (!timestamp) return '';
                const date = new Date(timestamp);
                return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' - ' + date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
            }
        };
    }
</script>
@endsection
