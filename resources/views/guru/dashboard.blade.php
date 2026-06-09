<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Dashboard Guru</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Welcome Banner -->
            <div class="bg-green-600 rounded-xl shadow-lg p-8 text-white flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-1">Selamat Datang, {{ auth()->user()->name }}!</h1>
                    <p class="text-green-100">Kelola misi dan pantau perkembangan siswa dari sini.</p>
                </div>
                <div class="hidden md:block">
                    <svg class="w-24 h-24 text-green-300 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm flex items-center p-6">
                    <div class="p-4 bg-yellow-100 text-yellow-600 rounded-lg mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Menunggu Persetujuan</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $pendingMissions->count() }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm flex items-center p-6">
                    <div class="p-4 bg-blue-100 text-blue-600 rounded-lg mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Total Siswa</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $siswas->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Persetujuan Misi -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden soft-glow-indigo">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Misi Menunggu Persetujuan</h3>
                        <p class="text-xs text-slate-400 mt-1 font-medium">Siswa yang sudah mengirim bukti penyelesaian misi.</p>
                    </div>
                    @if($pendingMissions->count() > 0)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-100 animate-pulse">
                            {{ $pendingMissions->count() }} Menunggu
                        </span>
                    @endif
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100/80">
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Siswa</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Misi</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Hadiah Poin</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Bukti</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/70">
                            @forelse($pendingMissions as $mission)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-indigo-50 border border-indigo-100/30 rounded-full flex items-center justify-center font-extrabold text-indigo-700 text-xs shadow-inner">
                                            {{ substr($mission->student->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800 text-xs leading-none mb-1">{{ $mission->student->name }}</div>
                                            <div class="text-[9px] text-slate-400 font-semibold uppercase tracking-wider">Kelas: {{ $mission->student->class_name ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-semibold text-slate-800 mb-0.5">{{ $mission->title }}</div>
                                    <div class="text-[10px] text-slate-400 font-medium capitalize">{{ $mission->type ?? 'Harian' }}</div>
                                </td>
                                <td class="px-6 py-4 text-center font-extrabold text-xs text-emerald-600">+{{ $mission->points_reward }} Pts</td>
                                <td class="px-6 py-4 text-center">
                                    @if($mission->pivot->proof_url)
                                        <a href="{{ $mission->pivot->proof_url }}" target="_blank" class="inline-flex items-center space-x-1 px-2.5 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-[10px] font-bold rounded-lg transition border border-indigo-150">
                                            <span>Lihat Bukti</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        </a>
                                    @else
                                        <span class="text-[10px] text-slate-400 font-semibold">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form method="POST" action="{{ route('guru.mission.approve', [$mission->student->id, $mission->id]) }}" onsubmit="let btn = this.querySelector('button[type=submit]'); if(btn) { btn.disabled = true; btn.innerHTML = '<span class=\'animate-spin inline-block w-3.5 h-3.5 border-2 border-current border-t-transparent rounded-full align-middle\'></span>'; }">
                                        @csrf
                                        <button type="submit" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-bold rounded-xl transition shadow-sm hover:shadow-md min-w-[85px] flex items-center justify-center">
                                            Setujui Misi
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-12 text-slate-400 text-xs font-medium">Tidak ada misi yang menunggu persetujuan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Peringkat Siswa -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden soft-glow-indigo">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">Peringkat Siswa</h3>
                    <p class="text-xs text-slate-400 mt-1 font-medium">Daftar semua siswa berdasarkan poin kebaikan.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100/80">
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center w-20">Rank</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nama Siswa</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Kelas</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Level</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Poin Kebaikan</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Poin Pelanggaran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/70">
                            @forelse($siswas as $index => $siswa)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-center">
                                    @if($index == 0)
                                        <span class="bg-amber-100 text-amber-800 border border-amber-200/50 w-6 h-6 flex items-center justify-center rounded-full text-xs font-black shadow-sm mx-auto">1</span>
                                    @elseif($index == 1)
                                        <span class="bg-slate-100 text-slate-700 border border-slate-200/50 w-6 h-6 flex items-center justify-center rounded-full text-xs font-black shadow-sm mx-auto">2</span>
                                    @elseif($index == 2)
                                        <span class="bg-orange-100 text-orange-800 border border-orange-200/50 w-6 h-6 flex items-center justify-center rounded-full text-xs font-black shadow-sm mx-auto">3</span>
                                    @else
                                        <span class="text-slate-400 text-xs font-bold">{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-indigo-50 border border-indigo-100/30 rounded-full flex items-center justify-center font-extrabold text-indigo-700 text-xs shadow-inner">
                                            {{ substr($siswa->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800 text-xs leading-none mb-1">{{ $siswa->name }}</div>
                                            <div class="text-[9px] text-slate-400 font-semibold">{{ $siswa->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center text-xs text-slate-500 font-semibold">{{ $siswa->class_name ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100/80">
                                        {{ $siswa->current_level ?? 'Pemula' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-extrabold text-xs text-emerald-600">{{ $siswa->points_kebaikan }} Pts</td>
                                <td class="px-6 py-4 text-right font-extrabold text-xs text-rose-500">{{ $siswa->points_pelanggaran }} Pts</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-slate-400 text-xs font-medium">Belum ada data siswa.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
