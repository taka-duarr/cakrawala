<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">
            {{ __('Histori Transaksi Poin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Filter Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <form action="{{ route('admin.point-history.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Siswa</label>
                        <select name="user_id" onchange="this.form.submit()" class="w-full py-2 text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Siswa</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ request('user_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Tipe</label>
                        <select name="type" onchange="this.form.submit()" class="w-full py-2 text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Tipe</option>
                            <option value="kebaikan" {{ request('type') == 'kebaikan' ? 'selected' : '' }}>Poin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Dari Tanggal</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full py-2 text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Sampai Tanggal</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full py-2 text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition">Filter</button>
                        @if(request()->anyFilled(['user_id','type','date_from','date_to']))
                            <a href="{{ route('admin.point-history.index') }}" class="text-xs font-semibold text-rose-600 hover:text-rose-700 py-2.5 px-3">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500">Total: {{ $histories->total() }} transaksi</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100/80">
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Siswa</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Jumlah Poin</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Tipe</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sumber</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/70">
                            @forelse($histories as $h)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800 text-xs">{{ $h->user->name ?? '-' }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $h->user->email ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-black text-sm {{ $h->points >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $h->points >= 0 ? '+' : '' }}{{ $h->points }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider
                                        {{ $h->type === 'kebaikan' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }}">
                                        {{ $h->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold text-slate-600">{{ $h->source }}</td>
                                <td class="px-6 py-4 text-xs text-slate-500 max-w-xs truncate">{{ $h->description ?? '-' }}</td>
                                <td class="px-6 py-4 text-[10px] text-slate-400 font-medium">{{ $h->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-slate-400 text-xs font-medium">Belum ada riwayat transaksi poin.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($histories->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $histories->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
