<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('admin.toko.index') }}" class="inline-flex items-center space-x-1 mb-2 px-3 py-1.5 border-2 border-slate-950 bg-white text-slate-950 hover:bg-[#E4FF1A] text-xs font-black shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all">
                    <span uk-icon="icon: arrow-left; ratio: 0.8"></span>
                    <span>Kembali ke Manajemen Toko</span>
                </a>
                <h2 class="font-extrabold text-2xl text-slate-950 leading-tight tracking-tight mt-2">Histori Transaksi — {{ $toko->name }}</h2>
            </div>
            <div class="flex items-center space-x-4">
                <div class="bg-[#E4FF1A] border-2 border-slate-950 px-5 py-3 text-center shadow-[4px_4px_0px_0px_rgba(15,23,42,1)]">
                    <p class="text-[10px] text-slate-950 font-bold uppercase tracking-wider">Total Poin Diterima</p>
                    <p class="text-xl font-black text-slate-950">{{ number_format($totalPaid) }}</p>
                </div>
                <div class="bg-white border-2 border-slate-950 px-5 py-3 text-center shadow-[4px_4px_0px_0px_rgba(15,23,42,1)]">
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Transaksi Lunas</p>
                    <p class="text-xl font-black text-slate-950">{{ $totalTx }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Filter -->
            <div class="bg-white border-2 border-slate-950 p-5 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)]">
                <form action="{{ route('admin.toko.transactions', $toko->id) }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-950 uppercase tracking-wider mb-1.5">Status</label>
                        <select name="status" onchange="this.form.submit()" class="w-full text-xs border-2 border-slate-950 px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-slate-950 focus:border-slate-950">
                            <option value="">Semua Status</option>
                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-950 uppercase tracking-wider mb-1.5">Dari Tanggal</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full text-xs border-2 border-slate-950 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-slate-950 focus:border-slate-950">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-950 uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full text-xs border-2 border-slate-950 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-slate-950 focus:border-slate-950">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 bg-slate-950 hover:bg-slate-900 text-white text-xs font-black py-2.5 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] hover:shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all">Filter</button>
                        @if(request('status') || request('date_from') || request('date_to'))
                            <a href="{{ route('admin.toko.transactions', $toko->id) }}" class="text-xs font-black text-rose-600 hover:text-rose-800 py-2.5 px-2">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Transactions Table -->
            <div class="bg-white border-2 border-slate-950 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#E4FF1A]/10 border-b-2 border-slate-950">
                                <th class="px-6 py-3.5 text-xs font-extrabold text-slate-950 uppercase tracking-wider">Nama Barang</th>
                                <th class="px-6 py-3.5 text-xs font-extrabold text-slate-950 uppercase tracking-wider">Pembeli (Siswa)</th>
                                <th class="px-6 py-3.5 text-xs font-extrabold text-slate-950 uppercase tracking-wider text-center">Poin</th>
                                <th class="px-6 py-3.5 text-xs font-extrabold text-slate-950 uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-3.5 text-xs font-extrabold text-slate-950 uppercase tracking-wider text-right">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-slate-950">
                            @forelse($transactions as $tx)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 text-xs font-black text-slate-950">{{ $tx->item_name }}</td>
                                    <td class="px-6 py-4">
                                        @if($tx->student)
                                            <div class="text-xs font-bold text-slate-950">{{ $tx->student->name }}</div>
                                            <div class="text-[10px] text-slate-500">{{ $tx->student->email }}</div>
                                        @else
                                            <span class="text-xs text-slate-400 italic">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center font-black text-xs text-slate-950">
                                        <span class="px-2 py-0.5 bg-[#E4FF1A] border-2 border-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] text-[10px]">
                                            {{ $tx->points_amount }} Pts
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($tx->status === 'paid')
                                            <span class="px-2.5 py-0.5 rounded-none text-[9px] font-black bg-[#EAFCEF] text-slate-950 border-2 border-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">✓ Lunas</span>
                                        @elseif($tx->status === 'pending')
                                            <span class="px-2.5 py-0.5 rounded-none text-[9px] font-black bg-[#FFF6EA] text-slate-950 border-2 border-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">⏳ Menunggu</span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-none text-[9px] font-black bg-[#FFEAEA] text-slate-950 border-2 border-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">Kadaluarsa</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right text-[10px] text-slate-500 font-bold">
                                        {{ ($tx->paid_at ?? $tx->created_at)->translatedFormat('d M Y, H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-12 text-slate-500 text-xs font-bold">Belum ada transaksi untuk toko ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($transactions->hasPages())
                    <div class="px-6 py-4 border-t-2 border-slate-950">{{ $transactions->links() }}</div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
