@extends('layouts.admin')

@section('content')
<div class="space-y-8" x-data="{ 
    saved: false, 
    sandbox: true, 
    bookingOpen: true, 
    autoInvoice: false,
    saveSettings() {
        this.saved = true;
        setTimeout(() => this.saved = false, 4000);
    }
}">
    <!-- Header -->
    <div class="border-b border-[#dfdfd6] pb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-near-black">System Settings</h1>
            <p class="text-graphite text-sm mt-1">Konfigurasi global Kelana ERP suite dan integrasi payment gateway.</p>
        </div>
        
        <!-- Toast Notification -->
        <div x-show="saved" x-transition class="bg-[#1e5e3a] text-white px-5 py-3 rounded-full text-xs font-bold flex items-center space-x-2">
            <span>✅ Konfigurasi berhasil disimpan!</span>
        </div>
    </div>

    <!-- Form Container -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Column 1 & 2: Main settings forms -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white p-8 rounded-[24px] border border-[#dfdfd6] space-y-6">
                <h3 class="font-bold text-base text-near-black border-b border-[#dfdfd6] pb-3">Integrasi Midtrans Payment</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col">
                        <label class="font-semibold text-near-black mb-2 text-xs">Sandbox Client Key</label>
                        <input type="text" value="SB-Mid-client-J8sY7x6Z" class="w-full p-4 rounded-[24px] bg-[#f4f3ed]/60 border border-[#dfdfd6] focus:border-[#1e5e3a] focus:ring-0 text-near-black font-semibold text-xs">
                    </div>
                    <div class="flex flex-col">
                        <label class="font-semibold text-near-black mb-2 text-xs">Sandbox Server Key</label>
                        <input type="password" value="SB-Mid-server-PqRStUvWxYz123" class="w-full p-4 rounded-[24px] bg-[#f4f3ed]/60 border border-[#dfdfd6] focus:border-[#1e5e3a] focus:ring-0 text-near-black font-semibold text-xs">
                    </div>
                </div>

                <div class="flex items-center justify-between bg-[#f4f3ed]/40 p-4.5 rounded-[20px] border border-[#dfdfd6]">
                    <div>
                        <span class="font-bold text-xs text-near-black block">Sandbox Environment</span>
                        <span class="text-[10px] text-graphite font-normal">Gunakan server sandbox untuk simulasi pembayaran transaksi booking.</span>
                    </div>
                    <button type="button" @click="sandbox = !sandbox" 
                            class="w-12 h-6.5 rounded-full p-1 transition-colors duration-200 focus:outline-none"
                            :class="sandbox ? 'bg-[#1e5e3a]' : 'bg-stone-300'">
                        <div class="bg-white w-4.5 h-4.5 rounded-full shadow-md transform transition-transform duration-200"
                             :class="sandbox ? 'translate-x-5.5' : 'translate-x-0'"></div>
                    </button>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[24px] border border-[#dfdfd6] space-y-6">
                <h3 class="font-bold text-base text-near-black border-b border-[#dfdfd6] pb-3">General ERP Rules</h3>
                
                <div class="flex items-center justify-between p-2">
                    <div>
                        <span class="font-bold text-xs text-near-black block">Penerimaan Booking Otomatis</span>
                        <span class="text-[10px] text-graphite font-normal">Izinkan customer langsung memesan tiket dan membuat invoice pembayaran.</span>
                    </div>
                    <button type="button" @click="bookingOpen = !bookingOpen" 
                            class="w-12 h-6.5 rounded-full p-1 transition-colors duration-200 focus:outline-none"
                            :class="bookingOpen ? 'bg-[#1e5e3a]' : 'bg-stone-300'">
                        <div class="bg-white w-4.5 h-4.5 rounded-full shadow-md transform transition-transform duration-200"
                             :class="bookingOpen ? 'translate-x-5.5' : 'translate-x-0'"></div>
                    </button>
                </div>

                <div class="flex items-center justify-between p-2 border-t border-[#dfdfd6]/50 pt-4">
                    <div>
                        <span class="font-bold text-xs text-near-black block">Kirim E-Tiket Otomatis via Email</span>
                        <span class="text-[10px] text-graphite font-normal">Kirim file PDF E-Tiket ke email pelanggan segera setelah status pembayaran lunas.</span>
                    </div>
                    <button type="button" @click="autoInvoice = !autoInvoice" 
                            class="w-12 h-6.5 rounded-full p-1 transition-colors duration-200 focus:outline-none"
                            :class="autoInvoice ? 'bg-[#1e5e3a]' : 'bg-stone-300'">
                        <div class="bg-white w-4.5 h-4.5 rounded-full shadow-md transform transition-transform duration-200"
                             :class="autoInvoice ? 'translate-x-5.5' : 'translate-x-0'"></div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Column 3: Sidebar settings -->
        <div class="space-y-8">
            <div class="bg-white p-8 rounded-[24px] border border-[#dfdfd6] space-y-6">
                <h3 class="font-bold text-base text-near-black border-b border-[#dfdfd6] pb-3">Admin Profile</h3>
                
                <div class="flex flex-col">
                    <label class="font-semibold text-near-black mb-2 text-xs">Nama Admin</label>
                    <input type="text" value="Admin Kelana" class="w-full p-4 rounded-[24px] bg-[#f4f3ed]/60 border border-[#dfdfd6] focus:border-[#1e5e3a] focus:ring-0 text-near-black font-semibold text-xs">
                </div>

                <div class="flex flex-col">
                    <label class="font-semibold text-near-black mb-2 text-xs">Email / Username</label>
                    <input type="text" value="admin.kelana" class="w-full p-4 rounded-[24px] bg-[#f4f3ed]/60 border border-[#dfdfd6] focus:border-[#1e5e3a] focus:ring-0 text-near-black font-semibold text-xs">
                </div>

                <div class="flex flex-col">
                    <label class="font-semibold text-near-black mb-2 text-xs">Password Baru (Kosongkan jika tidak diganti)</label>
                    <input type="password" placeholder="••••••••" class="w-full p-4 rounded-[24px] bg-[#f4f3ed]/60 border border-[#dfdfd6] focus:border-[#1e5e3a] focus:ring-0 text-near-black font-semibold text-xs">
                </div>
            </div>

            <!-- Save Action Button -->
            <button type="button" @click="saveSettings()" class="w-full bg-[#1e5e3a] hover:bg-near-black text-white font-bold text-xs py-4.5 rounded-full transition duration-150 uppercase tracking-wider">
                Simpan Konfigurasi
            </button>
        </div>

    </div>
</div>
@endsection
