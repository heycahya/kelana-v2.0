@extends('layouts.admin')

@section('content')
<div x-data="adminInbox()" x-init="initInbox()" class="flex h-[calc(100vh-140px)] border border-[#dfdfd6] bg-white rounded-[24px] overflow-hidden">
    <!-- LEFT PANEL: Contacts Drawer -->
    <aside class="w-80 border-r border-[#dfdfd6] flex flex-col bg-white">
        <div class="p-6 border-b border-[#dfdfd6]">
            <h2 class="text-xl font-bold text-near-black">Inbox Pesan CS</h2>
            <p class="text-xs text-graphite mt-1">Komunikasi dengan Customer & Trip Leader.</p>
            
            <!-- Search bar -->
            <div class="mt-4 relative">
                <input type="text" x-model="searchQuery" placeholder="Cari kontak..." class="w-full pl-9 pr-4 py-2.5 rounded-full border border-[#dfdfd6] text-xs font-semibold bg-[#f4f3ed]/60 outline-none focus:border-near-black transition">
                <svg class="w-4 h-4 text-graphite/50 absolute left-3 top-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

        <!-- Contacts List -->
        <div class="flex-grow overflow-y-auto divide-y divide-stone/40">
            <template x-for="contact in filteredContacts()" :key="contact.type + '-' + contact.id">
                <button @click="selectContact(contact)" 
                        class="w-full text-left p-5 flex items-start space-x-3.5 hover:bg-[#f4f3ed]/40 transition duration-150 relative"
                        :class="activeContact && activeContact.id === contact.id && activeContact.type === contact.type ? 'bg-[#f4f3ed]/80' : ''">
                    
                    <!-- Avatar or initial -->
                    <div class="relative shrink-0">
                        <template x-if="contact.avatar">
                            <img :src="contact.avatar" alt="Avatar" class="w-10 h-10 rounded-full object-cover border border-[#dfdfd6]/65">
                        </template>
                        <template x-if="!contact.avatar">
                            <div class="w-10 h-10 rounded-full bg-stone/20 border border-[#dfdfd6] flex items-center justify-center font-bold text-near-black text-sm uppercase" x-text="contact.name.substring(0, 2)"></div>
                        </template>
                        <!-- Active indicator -->
                        <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-[#1e5e3a] border-2 border-white rounded-full"></span>
                    </div>

                    <!-- Details -->
                    <div class="overflow-hidden flex-grow">
                        <div class="flex justify-between items-baseline">
                            <span class="font-bold text-sm text-near-black truncate" x-text="contact.name"></span>
                            <span class="text-[9px] font-bold uppercase px-2 py-0.5 rounded-full"
                                  :class="contact.type === 'customer' ? 'bg-[#1e5e3a]/10 text-[#1e5e3a]' : 'bg-near-black text-white'"
                                  x-text="contact.type === 'customer' ? 'Customer' : 'Leader'"></span>
                        </div>
                        <span class="text-xs text-graphite block truncate mt-0.5 font-medium" 
                              :class="contact.unread_count > 0 ? 'text-near-black font-extrabold' : ''"
                              x-text="contact.last_message ? contact.last_message : 'Belum ada percakapan.'"></span>
                    </div>

                    <!-- Unread badge -->
                    <template x-if="contact.unread_count > 0">
                        <span class="absolute top-5 right-5 w-5 h-5 bg-[#1e5e3a] text-white text-[9px] font-extrabold flex items-center justify-center rounded-full" x-text="contact.unread_count"></span>
                    </template>
                </button>
            </template>
        </div>
    </aside>

    <!-- RIGHT PANEL: Chat Thread -->
    <main class="flex-grow flex flex-col bg-[#f4f3ed]/30 relative">
        <template x-if="activeContact">
            <div class="flex flex-col h-full">
                <!-- Thread Header -->
                <div class="p-6 bg-white border-b border-[#dfdfd6] flex justify-between items-center z-10 shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <template x-if="activeContact.avatar">
                                <img :src="activeContact.avatar" alt="Avatar" class="w-10 h-10 rounded-full object-cover border border-[#dfdfd6]/65">
                            </template>
                            <template x-if="!activeContact.avatar">
                                <div class="w-10 h-10 rounded-full bg-stone/20 border border-[#dfdfd6] flex items-center justify-center font-bold text-near-black text-sm uppercase" x-text="activeContact.name.substring(0, 2)"></div>
                            </template>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-bold text-base text-near-black leading-tight" x-text="activeContact.name"></h3>
                                <span class="text-[9px] font-bold uppercase px-2 py-0.5 rounded-full"
                                      :class="activeContact.type === 'customer' ? 'bg-[#1e5e3a]/10 text-[#1e5e3a]' : 'bg-near-black text-white'"
                                      x-text="activeContact.type === 'customer' ? 'Customer' : 'Leader'"></span>
                            </div>
                            <span class="text-xs text-graphite mt-0.5 block" x-text="activeContact.email"></span>
                        </div>
                    </div>
                </div>

                <!-- Messages Stream -->
                <div class="flex-grow overflow-y-auto p-6 space-y-4 flex flex-col" id="message-container">
                    <template x-for="msg in messages" :key="msg.id">
                        <div class="max-w-[70%] rounded-[20px] p-4.5 text-xs font-semibold leading-relaxed"
                             :class="msg.sender_type === 'admin' ? 'bg-[#1e5e3a] text-white self-end rounded-br-none' : 'bg-white text-near-black border border-[#dfdfd6] self-start rounded-bl-none'">
                            <p class="whitespace-pre-line" x-text="msg.message"></p>
                            <span class="block text-[9px] text-right mt-1.5 font-bold uppercase tracking-wider"
                                  :class="msg.sender_type === 'admin' ? 'text-white/60' : 'text-stone/70'"
                                  x-text="formatTime(msg.created_at)"></span>
                        </div>
                    </template>
                </div>

                <!-- Input Footer -->
                <div class="p-6 bg-white border-t border-[#dfdfd6] shrink-0">
                    <form @submit.prevent="sendMessage()" class="flex items-center gap-3">
                        <input type="text" x-model="newMessage" placeholder="Tulis jawaban Anda di sini..." class="flex-grow p-4 rounded-[26px] bg-[#f4f3ed]/60 border border-[#dfdfd6] text-xs font-semibold outline-none focus:border-near-black focus:bg-white transition">
                        <button type="submit" class="bg-near-black text-white hover:bg-[#1e5e3a] px-6 py-4 rounded-[26px] text-xs font-bold transition flex items-center gap-1.5">
                            <span>Kirim</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </template>

        <template x-if="!activeContact">
            <div class="flex flex-col items-center justify-center h-full p-8 text-center">
                <svg class="w-16 h-16 text-stone/40 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 01.778-.332 48.294 48.294 0 005.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/>
                </svg>
                <h3 class="font-bold text-lg text-near-black">Mulai Percakapan</h3>
                <p class="text-xs text-graphite mt-1 max-w-xs leading-relaxed">Pilih salah satu kontak customer atau trip leader di sebelah kiri untuk melihat histori pesan dan mulai membalas.</p>
            </div>
        </template>
    </main>
</div>

<script>
    function adminInbox() {
        return {
            contacts: [],
            activeContact: null,
            messages: [],
            newMessage: '',
            searchQuery: '',
            pollingInterval: null,

            initInbox() {
                this.loadContacts();
                // Start polling contacts list
                this.pollingInterval = setInterval(() => {
                    this.loadContacts();
                    if (this.activeContact) {
                        this.loadMessages();
                    }
                }, 3000);
            },

            loadContacts() {
                fetch('{{ route("admin.messages.contacts") }}')
                    .then(r => r.json())
                    .then(data => {
                        this.contacts = data;
                    });
            },

            filteredContacts() {
                if (!this.searchQuery.trim()) return this.contacts;
                const query = this.searchQuery.toLowerCase();
                return this.contacts.filter(c => c.name.toLowerCase().includes(query) || c.email.toLowerCase().includes(query));
            },

            selectContact(contact) {
                this.activeContact = contact;
                this.messages = [];
                this.newMessage = '';
                this.loadMessages(true);
            },

            loadMessages(shouldScroll = false) {
                if (!this.activeContact) return;
                fetch(`/admin/messages/${this.activeContact.type}/${this.activeContact.id}`)
                    .then(r => r.json())
                    .then(data => {
                        this.messages = data;
                        if (shouldScroll) {
                            this.$nextTick(() => this.scrollToBottom());
                        }
                    });
            },

            sendMessage() {
                if (!this.newMessage.trim() || !this.activeContact) return;
                const text = this.newMessage;
                this.newMessage = '';

                fetch(`/admin/messages/${this.activeContact.type}/${this.activeContact.id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message: text })
                })
                .then(r => r.json())
                .then(msg => {
                    this.messages.push(msg);
                    this.$nextTick(() => this.scrollToBottom());
                    this.loadContacts(); // Refresh last message snippets
                });
            },

            scrollToBottom() {
                const el = document.getElementById('message-container');
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
