<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">
            {{ __('Audit Log Poin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Filter Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <form action="{{ route('admin.point-audit.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Aktor</label>
                        <select name="actor_id" onchange="this.form.submit()" class="w-full py-2 text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Aktor</option>
                            @foreach($actors as $actor)
                                <option value="{{ $actor->id }}" {{ request('actor_id') == $actor->id ? 'selected' : '' }}>
                                    {{ $actor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Target Siswa</label>
                        <select name="target_user_id" onchange="this.form.submit()" class="w-full py-2 text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Siswa</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ request('target_user_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Jenis Aksi</label>
                        <select name="action_type" onchange="this.form.submit()" class="w-full py-2 text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Aksi</option>
                            <option value="adjust" {{ request('action_type') == 'adjust' ? 'selected' : '' }}>Adjust</option>
                            <option value="transfer_out" {{ request('action_type') == 'transfer_out' ? 'selected' : '' }}>Transfer Keluar</option>
                            <option value="transfer_in" {{ request('action_type') == 'transfer_in' ? 'selected' : '' }}>Transfer Masuk</option>
                            <option value="reset" {{ request('action_type') == 'reset' ? 'selected' : '' }}>Reset</option>
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
                        @if(request()->anyFilled(['actor_id','target_user_id','action_type','date_from','date_to']))
                            <a href="{{ route('admin.point-audit.index') }}" class="text-xs font-semibold text-rose-600 hover:text-rose-700 py-2.5 px-3">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <span class="text-xs font-bold text-slate-500">Total: {{ $audits->total() }} entri audit</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100/80">
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Aktor</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Target Siswa</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Jenis Aksi</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Tipe Poin</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Jumlah</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Catatan</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/70">
                            @forelse($audits as $audit)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800 text-xs">{{ $audit->actor->name ?? '-' }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $audit->actor->role->display_name ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800 text-xs">{{ $audit->targetUser->name ?? '-' }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $audit->targetUser->classroom->name ?? 'Tanpa Kelas' }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $actionConfig = [
                                            'adjust'       => ['label' => 'Adjust',          'class' => 'bg-indigo-50 text-indigo-700 border-indigo-100'],
                                            'transfer_out' => ['label' => 'Transfer Keluar',  'class' => 'bg-rose-50 text-rose-700 border-rose-100'],
                                            'transfer_in'  => ['label' => 'Transfer Masuk',   'class' => 'bg-emerald-50 text-emerald-700 border-emerald-100'],
                                            'reset'        => ['label' => 'Reset',            'class' => 'bg-amber-50 text-amber-700 border-amber-100'],
                                        ];
                                        $cfg = $actionConfig[$audit->action_type] ?? ['label' => $audit->action_type, 'class' => 'bg-slate-100 text-slate-600 border-slate-200'];
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider border {{ $cfg['class'] }}">
                                        {{ $cfg['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider
                                        {{ $audit->point_type === 'kebaikan' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                        {{ $audit->point_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center font-black text-sm
                                    {{ $audit->amount > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $audit->amount > 0 ? '+' : '' }}{{ $audit->amount }}
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500 max-w-xs truncate">{{ $audit->notes ?? '-' }}</td>
                                <td class="px-6 py-4 text-[10px] text-slate-400 font-medium">{{ $audit->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-slate-400 text-xs font-medium">Belum ada data audit poin.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($audits->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $audits->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
