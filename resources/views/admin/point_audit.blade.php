<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">
            {{ __('Audit Log Poin') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-100/30 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Filter Card -->
            <div class="bg-white rounded-3xl border-4 border-slate-950 p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <form action="{{ route('admin.point-audit.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Aktor</label>
                        <select name="actor_id" onchange="this.form.submit()" class="w-full py-2.5 text-xs rounded-xl border-2 border-slate-950 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="">Semua Aktor</option>
                            @foreach($actors as $actor)
                                <option value="{{ $actor->id }}" {{ request('actor_id') == $actor->id ? 'selected' : '' }}>
                                    {{ $actor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Target Siswa</label>
                        <select name="target_user_id" onchange="this.form.submit()" class="w-full py-2.5 text-xs rounded-xl border-2 border-slate-950 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="">Semua Siswa</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ request('target_user_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Jenis Aksi</label>
                        <select name="action_type" onchange="this.form.submit()" class="w-full py-2.5 text-xs rounded-xl border-2 border-slate-950 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="">Semua Aksi</option>
                            <option value="adjust" {{ request('action_type') == 'adjust' ? 'selected' : '' }}>Adjust</option>
                            <option value="transfer_out" {{ request('action_type') == 'transfer_out' ? 'selected' : '' }}>Transfer Keluar</option>
                            <option value="transfer_in" {{ request('action_type') == 'transfer_in' ? 'selected' : '' }}>Transfer Masuk</option>
                            <option value="reset" {{ request('action_type') == 'reset' ? 'selected' : '' }}>Reset</option>
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
                        @if(request()->anyFilled(['actor_id','target_user_id','action_type','date_from','date_to']))
                            <a href="{{ route('admin.point-audit.index') }}" class="text-xs font-black text-rose-600 hover:bg-[#FFEAEA] py-3 px-4 rounded-xl border-2 border-transparent hover:border-slate-950 transition-all uppercase tracking-wider">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-3xl border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="px-6 py-4 border-b-4 border-slate-950 bg-slate-50">
                    <span class="text-xs font-black text-slate-950 uppercase tracking-wider">Total: {{ $audits->total() }} entri audit</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b-2 border-slate-950">
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider">Aktor</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider">Target Siswa</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider text-center">Jenis Aksi</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider text-center">Tipe Poin</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider text-center">Jumlah</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider">Catatan</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-slate-950">
                            @forelse($audits as $audit)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-950 text-xs">{{ $audit->actor->name ?? '-' }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $audit->actor->role->display_name ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-950 text-xs">{{ $audit->targetUser->name ?? '-' }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $audit->targetUser->classroom->name ?? 'Tanpa Kelas' }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $actionConfig = [
                                            'adjust'       => ['label' => 'Adjust',          'class' => 'bg-indigo-50 text-indigo-805 border-slate-950'],
                                            'transfer_out' => ['label' => 'Transfer Keluar',  'class' => 'bg-[#FFEAEA] text-rose-800 border-slate-950'],
                                            'transfer_in'  => ['label' => 'Transfer Masuk',   'class' => 'bg-[#EAFCEF] text-emerald-800 border-slate-950'],
                                            'reset'        => ['label' => 'Reset',            'class' => 'bg-amber-50 text-amber-800 border-slate-950'],
                                        ];
                                        $cfg = $actionConfig[$audit->action_type] ?? ['label' => $audit->action_type, 'class' => 'bg-slate-100 text-slate-600 border-slate-950'];
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wider border-2 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] {{ $cfg['class'] }}">
                                        {{ $cfg['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider border-2 border-slate-950
                                        {{ $audit->point_type === 'kebaikan' ? 'bg-[#EAFCEF] text-emerald-800' : 'bg-[#FFEAEA] text-rose-800' }}">
                                        {{ $audit->point_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center font-black text-xs
                                    {{ $audit->amount > 0 ? 'text-emerald-700' : 'text-rose-650' }}">
                                    {{ $audit->amount > 0 ? '+' : '' }}{{ $audit->amount }} Pts
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-700 max-w-xs truncate">{{ $audit->notes ?? '-' }}</td>
                                <td class="px-6 py-4 text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $audit->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-slate-400 text-xs font-bold uppercase">Belum ada data audit poin.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($audits->hasPages())
                    <div class="px-6 py-4 border-t-2 border-slate-950 bg-slate-50">
                        {{ $audits->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
