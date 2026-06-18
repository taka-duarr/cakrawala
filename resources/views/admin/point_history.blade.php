<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">
            {{ __('Histori Transaksi Poin') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-100/30 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Filter Card -->
            <div class="bg-white rounded-3xl border-4 border-slate-950 p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <form action="{{ route('admin.point-history.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Siswa</label>
                        <select name="user_id" onchange="this.form.submit()" class="w-full py-2.5 text-xs rounded-xl border-2 border-slate-950 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="">Semua Siswa</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ request('user_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tipe</label>
                        <select name="type" onchange="this.form.submit()" class="w-full py-2.5 text-xs rounded-xl border-2 border-slate-950 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="">Semua Tipe</option>
                            <option value="kebaikan" {{ request('type') == 'kebaikan' ? 'selected' : '' }}>Poin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Dari Tanggal</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full py-2.5 text-xs rounded-xl border-2 border-slate-950 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Sampai Tanggal</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full py-2.5 text-xs rounded-xl border-2 border-slate-950 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                    </div>
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="w-full md:w-auto bg-slate-950 hover:bg-[#E4FF1A] text-white hover:text-slate-950 text-xs font-black px-5 py-3 rounded-xl border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider">Filter</button>
                        @if(request()->anyFilled(['user_id','type','date_from','date_to']))
                            <a href="{{ route('admin.point-history.index') }}" class="text-xs font-black text-rose-600 hover:bg-[#FFEAEA] py-3 px-4 rounded-xl border-2 border-transparent hover:border-slate-950 transition-all uppercase tracking-wider">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-3xl border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="px-6 py-4 border-b-4 border-slate-950 bg-slate-50">
                    <span class="text-xs font-black text-slate-950 uppercase tracking-wider">Total: {{ $histories->total() }} transaksi</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b-2 border-slate-950">
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider">Siswa</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider text-center">Jumlah Poin</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider text-center">Tipe</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider">Sumber</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-slate-950">
                            @forelse($histories as $h)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-955 text-xs">{{ $h->user->name ?? '-' }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $h->user->email ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-black text-xs {{ $h->points >= 0 ? 'text-emerald-700' : 'text-rose-650' }}">
                                        {{ $h->points >= 0 ? '+' : '' }}{{ $h->points }} Pts
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wider border-2 border-slate-950
                                        {{ $h->type === 'kebaikan' ? 'bg-[#EAFCEF] text-emerald-800' : 'bg-[#FFEAEA] text-rose-800' }}">
                                        {{ $h->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-700">{{ $h->source }}</td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-700 max-w-xs truncate">{{ $h->description ?? '-' }}</td>
                                <td class="px-6 py-4 text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $h->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-slate-400 text-xs font-bold uppercase">Belum ada riwayat transaksi poin.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($histories->hasPages())
                    <div class="px-6 py-4 border-t-2 border-slate-950 bg-slate-50">
                        {{ $histories->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
