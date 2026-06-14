<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <a href="{{ route('admin.toko.index') }}" class="text-xs font-semibold text-violet-600 hover:text-violet-800 flex items-center space-x-1 mb-1.5">
                    <span uk-icon="icon: arrow-left; ratio: 0.8"></span>
                    <span>Kembali ke Manajemen Toko</span>
                </a>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">Histori Transaksi — {{ $toko->name }}</h2>
            </div>
            <div class="flex items-center space-x-3">
                <div class="bg-violet-50 border border-violet-100 rounded-2xl px-5 py-3 text-center">
                    <p class="text-[10px] text-violet-400 font-bold uppercase tracking-wider">Total Poin Diterima</p>
                    <p class="text-xl font-black text-violet-700">{{ number_format($totalPaid) }}</p>
                </div>
                <div class="bg-slate-50 border border-slate-100 rounded-2xl px-5 py-3 text-center">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Transaksi Lunas</p>
                    <p class="text-xl font-black text-slate-700">{{ $totalTx }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Filter -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <form action="{{ route('admin.toko.transactions', $toko->id) }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Status</label>
                        <select name="status" onchange="this.form.submit()" class="w-full text-xs rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500">
                            <option value="">Semua Status</option>
                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Dari Tanggal</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full text-xs rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full text-xs rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold py-2.5 rounded-xl transition">Filter</button>
                        @if(request('status') || request('date_from') || request('date_to'))
                            <a href="{{ route('admin.toko.transactions', $toko->id) }}" class="text-xs font-semibold text-rose-500 hover:text-rose-700 py-2.5 px-2">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Transactions Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100">
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nama Barang</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pembeli (Siswa)</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Poin</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/70">
                            @forelse($transactions as $tx)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 text-xs font-semibold text-slate-800">{{ $tx->item_name }}</td>
                                    <td class="px-6 py-4">
                                        @if($tx->student)
                                            <div class="text-xs font-semibold text-slate-800">{{ $tx->student->name }}</div>
                                            <div class="text-[10px] text-slate-400">{{ $tx->student->email }}</div>
                                        @else
                                            <span class="text-xs text-slate-400 italic">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center font-extrabold text-xs {{ $tx->status === 'paid' ? 'text-violet-700' : 'text-slate-400' }}">
                                        {{ $tx->points_amount }} Poin
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($tx->status === 'paid')
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">✓ Lunas</span>
                                        @elseif($tx->status === 'pending')
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-100">⏳ Menunggu</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-slate-100 text-slate-500">Kadaluarsa</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right text-[10px] text-slate-400 font-medium">
                                        {{ ($tx->paid_at ?? $tx->created_at)->translatedFormat('d M Y, H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-12 text-slate-400 text-xs">Belum ada transaksi untuk toko ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($transactions->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">{{ $transactions->links() }}</div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
