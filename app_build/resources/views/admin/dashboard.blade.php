@extends('layouts.admin')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
    
    <!-- LEFT COLUMN (Spans 9 columns on large screens) -->
    <div class="xl:col-span-9 space-y-8">
        
        <!-- Dashboard Header & Date Filter from Mockup -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-near-black tracking-tight font-sans">Dashboard</h2>
                <p class="text-graphite text-xs mt-0.5">An any way to manage sales with care and precision.</p>
            </div>
            
            <!-- Datepicker mock from Mockup -->
            <div class="flex items-center space-x-2 bg-white border border-[#dfdfd6] rounded-full px-4 py-2 text-xs font-semibold text-graphite cursor-pointer hover:bg-[#f4f3ed] transition duration-150">
                <svg class="w-3.5 h-3.5 text-graphite" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>January 2026 - May 2026</span>
                <svg class="w-3.5 h-3.5 text-stone-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </div>
        </div>

        <!-- 3-Card Stats Row from Mockup -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Card 1: Green Update Card -->
            <div class="bg-[#0b1611] text-white p-6 rounded-[24px] border border-[#dfdfd6]/10 flex flex-col justify-between h-40 relative overflow-hidden">
                <div class="flex items-start justify-between z-10">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#8e8e93] flex items-center bg-white/5 px-2.5 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#eb3131] mr-1.5 animate-pulse"></span>
                        Update
                    </span>
                </div>
                <div class="z-10">
                    <span class="text-[9px] text-[#8e8e93] block">Feb 12th 2026</span>
                    <p class="text-sm font-semibold text-white mt-1 leading-snug">Sales revenue increased <span class="text-[#b4df5b]">40%</span> in 1 week</p>
                </div>
                <a href="{{ route('admin.laporan.index') }}" class="text-[10px] text-[#8e8e93] hover:text-white font-bold inline-flex items-center z-10 transition duration-150">
                    See Statistics
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <!-- Card 2: Net Income -->
            <div class="bg-white p-6 rounded-[24px] border border-[#dfdfd6] flex flex-col justify-between h-40">
                <div class="flex justify-between items-start">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-graphite/60">Net Income</span>
                    <a href="{{ route('admin.laporan.index') }}" class="text-[#8e8e93] hover:text-near-black text-xs font-bold leading-none tracking-widest" title="View details">•••</a>
                </div>
                <div class="mt-2">
                    <h3 class="text-3xl font-extrabold text-near-black tracking-tight">$ {{ number_format($revenue / 15000, 0, '.', '.') }}</h3>
                </div>
                <div class="flex items-center space-x-1 text-xs font-semibold text-[#1e5e3a] mt-1">
                    <span>↗</span>
                    <span>+35% from last month</span>
                </div>
            </div>

            <!-- Card 3: Total Return -->
            <div class="bg-white p-6 rounded-[24px] border border-[#dfdfd6] flex flex-col justify-between h-40">
                <div class="flex justify-between items-start">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-graphite/60">Total Return</span>
                    <a href="{{ route('admin.laporan.index') }}" class="text-[#8e8e93] hover:text-near-black text-xs font-bold leading-none tracking-widest" title="View details">•••</a>
                </div>
                <div class="mt-2">
                    <h3 class="text-3xl font-extrabold text-near-black tracking-tight">$ {{ number_format(($revenue * 0.16) / 15000, 0, '.', '.') }}</h3>
                </div>
                <div class="flex items-center space-x-1 text-xs font-semibold text-[#eb3131] mt-1">
                    <span>↘</span>
                    <span>-24% from last month</span>
                </div>
            </div>

        </div>

        <!-- Split Grid: Transaction (Left) & Charts (Right) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Transaction List (Left) -->
            <div class="bg-white p-6 rounded-[24px] border border-[#dfdfd6] space-y-6">
                <div class="flex justify-between items-center">
                    <h3 class="text-sm font-bold text-near-black tracking-tight">Transaction</h3>
                    <a href="{{ route('admin.transaksi.index') }}" class="text-[#8e8e93] hover:text-near-black text-xs font-bold leading-none tracking-widest" title="Manage transactions">•••</a>
                </div>
                
                <div class="space-y-4">
                    <!-- Siohioma Transaction List Design -->
                    @forelse($recent_bookings as $booking)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <!-- Icon representation of a trip/tour -->
                                <div class="w-10 h-10 rounded-full bg-[#f4f3ed] flex items-center justify-center border border-[#dfdfd6] text-near-black text-lg">
                                    🏕️
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-near-black leading-tight">{{ $booking->customer->nama_customer ?? 'Guest' }}</h4>
                                    <span class="text-[10px] text-graphite block mt-0.5">{{ $booking->jadwalTrip->paketWisata->nama_paket ?? 'Trip Package' }}</span>
                                    <span class="text-[9px] text-[#8e8e93] block">{{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y') }}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                @if($booking->status_pembayaran === 'PAID')
                                    <span class="text-[10px] font-bold text-[#1e5e3a] block">Completed</span>
                                @elseif($booking->status_pembayaran === 'PENDING')
                                    <span class="text-[10px] font-bold text-yellow-600 block">Pending</span>
                                @else
                                    <span class="text-[10px] font-bold text-red-600 block">{{ $booking->status_pembayaran }}</span>
                                @endif
                                <span class="text-[9px] font-mono text-stone-500">{{ $booking->booking_code }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center text-stone-400 font-semibold text-xs">
                            Belum ada transaksi pemesanan.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Visual Charts (Right) -->
            <div class="space-y-6">
                <!-- Bar Chart: Revenue -->
                <div class="bg-white p-6 rounded-[24px] border border-[#dfdfd6] space-y-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-graphite/60">Revenue</h3>
                            <div class="flex items-baseline space-x-2 mt-1">
                                <span class="text-xl font-extrabold text-near-black">$ {{ number_format($revenue / 15000, 0, '.', '.') }}</span>
                                <span class="text-[9px] text-[#1e5e3a] font-bold">↗ +35% from last month</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 text-[9px] font-bold text-graphite">
                            <span class="flex items-center"><span class="w-2 h-2 rounded-sm bg-[#0b1611] mr-1"></span> Income</span>
                            <span class="flex items-center"><span class="w-2 h-2 rounded-sm bg-[#b4df5b] mr-1"></span> Expenses</span>
                        </div>
                    </div>

                    <!-- CSS double vertical bars representing revenue from Siohioma Mockup -->
                    <div class="flex items-end justify-between h-28 pt-4 px-2">
                        <div class="flex flex-col items-center space-y-2">
                            <div class="flex space-x-1 items-end h-20">
                                <div class="bg-[#0b1611] w-2 h-14 rounded-full"></div>
                                <div class="bg-[#b4df5b] w-2 h-8 rounded-full"></div>
                            </div>
                            <span class="text-[9px] font-bold text-graphite/40">Jan</span>
                        </div>
                        <div class="flex flex-col items-center space-y-2">
                            <div class="flex space-x-1 items-end h-20">
                                <div class="bg-[#0b1611] w-2 h-16 rounded-full"></div>
                                <div class="bg-[#b4df5b] w-2 h-12 rounded-full"></div>
                            </div>
                            <span class="text-[9px] font-bold text-graphite/40">Feb</span>
                        </div>
                        <div class="flex flex-col items-center space-y-2">
                            <div class="flex space-x-1 items-end h-20">
                                <div class="bg-[#0b1611] w-2 h-12 rounded-full"></div>
                                <div class="bg-[#b4df5b] w-2 h-10 rounded-full"></div>
                            </div>
                            <span class="text-[9px] font-bold text-graphite/40">Mar</span>
                        </div>
                        <div class="flex flex-col items-center space-y-2">
                            <div class="flex space-x-1 items-end h-20">
                                <div class="bg-[#0b1611] w-2 h-18 rounded-full"></div>
                                <div class="bg-[#b4df5b] w-2 h-14 rounded-full"></div>
                            </div>
                            <span class="text-[9px] font-bold text-graphite/40">Apr</span>
                        </div>
                        <div class="flex flex-col items-center space-y-2">
                            <div class="flex space-x-1 items-end h-20">
                                <div class="bg-[#0b1611] w-2 h-15 rounded-full"></div>
                                <div class="bg-[#b4df5b] w-2 h-11 rounded-full"></div>
                            </div>
                            <span class="text-[9px] font-bold text-graphite/40">May</span>
                        </div>
                    </div>
                </div>

                <!-- Horizontal Progress Bars: Sales Report -->
                <div class="bg-white p-6 rounded-[24px] border border-[#dfdfd6] space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-sm font-bold text-near-black">Sales Report</h3>
                        <a href="{{ route('admin.laporan.index') }}" class="text-[#8e8e93] hover:text-near-black text-xs font-bold leading-none tracking-widest" title="View rekap">•••</a>
                    </div>

                    <div class="space-y-4 pt-2">
                        <!-- Item 1 -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-[10px] font-semibold text-graphite">
                                <span>Product Launched</span>
                                <span class="font-bold text-near-black">233</span>
                            </div>
                            <div class="w-full bg-[#f4f3ed] h-2.5 rounded-full overflow-hidden border border-[#dfdfd6]/60">
                                <div class="bg-[#3f4e45] h-full" style="width: 58%"></div>
                            </div>
                        </div>

                        <!-- Item 2 -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-[10px] font-semibold text-graphite">
                                <span>Ongoing Product</span>
                                <span class="font-bold text-near-black">23</span>
                            </div>
                            <div class="w-full bg-[#f4f3ed] h-2.5 rounded-full overflow-hidden border border-[#dfdfd6]/60">
                                <div class="bg-[#b4df5b] h-full" style="width: 15%"></div>
                            </div>
                        </div>

                        <!-- Item 3 -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-[10px] font-semibold text-graphite">
                                <span>Product Sold</span>
                                <span class="font-bold text-near-black">482</span>
                            </div>
                            <div class="w-full bg-[#f4f3ed] h-2.5 rounded-full overflow-hidden border border-[#dfdfd6]/60">
                                <div class="bg-[#1e5e3a] h-full" style="width: 85%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- RIGHT COLUMN (Spans 3 columns on large screens) -->
    <div class="xl:col-span-3 space-y-6">
        
        <!-- Total View Performance card with Donut Chart -->
        <div class="bg-white p-6 rounded-[24px] border border-[#dfdfd6] space-y-6">
            <h3 class="text-xs font-bold text-near-black uppercase tracking-wider text-center">Total View Performance</h3>
            
            <!-- Donut Chart representation via SVG -->
            <div class="relative flex justify-center items-center">
                <svg class="w-36 h-36 transform -rotate-90">
                    <!-- Outer arc track -->
                    <circle cx="72" cy="72" r="54" stroke="#f4f3ed" stroke-width="18" fill="transparent" />
                    <!-- Arcs representing percentages (16%, 23%, 68%) -->
                    <circle cx="72" cy="72" r="54" stroke="#eb3131" stroke-width="18" fill="transparent" stroke-dasharray="339.292" stroke-dashoffset="284.992" /> <!-- 16% (orange/red) -->
                    <circle cx="72" cy="72" r="54" stroke="#1dc479" stroke-width="18" fill="transparent" stroke-dasharray="339.292" stroke-dashoffset="261.255" transform="rotate(57.6, 72, 72)" /> <!-- 23% (green) -->
                    <circle cx="72" cy="72" r="54" stroke="#1e5e3a" stroke-width="18" fill="transparent" stroke-dasharray="339.292" stroke-dashoffset="108.573" transform="rotate(140.4, 72, 72)" /> <!-- 68% (dark green) -->
                </svg>
                <div class="absolute text-center">
                    <span class="text-[9px] uppercase font-bold text-graphite/40 tracking-wider">Total Count</span>
                    <span class="block text-xl font-black text-near-black tracking-tight leading-none mt-0.5">565K</span>
                </div>
            </div>

            <p class="text-[10px] text-graphite text-center leading-normal px-2">Here are some tips on how to improve your score.</p>
            
            <a href="{{ route('admin.jadwal.index') }}" class="block text-center py-3 rounded-full border border-[#dfdfd6] bg-white text-xs font-bold text-near-black hover:bg-warm-cream transition duration-150">
                Guide Views
            </a>

            <!-- Mini stats row -->
            <div class="flex justify-between items-center text-[9px] font-bold text-graphite border-t border-[#dfdfd6] pt-4">
                <span class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-[#b4df5b] mr-1"></span> View Count</span>
                <span class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-[#1e5e3a] mr-1"></span> Percentage</span>
                <span class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-[#eb3131] mr-1"></span> Sales</span>
            </div>
        </div>

    </div>

</div>
@endsection
