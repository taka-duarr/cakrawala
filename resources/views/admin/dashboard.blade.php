<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-100/30 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Welcome Banner -->
            <div class="bg-[#E4FF1A] border-4 border-slate-950 rounded-3xl shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] p-8 text-slate-950 flex items-center justify-between">
                <div class="space-y-2">
                    <h1 class="text-3xl font-black uppercase tracking-tight mb-1">Selamat Datang, Admin!</h1>
                    <p class="text-slate-850 text-xs font-bold uppercase tracking-wider">Berikut adalah data statistik perkembangan dan aktivitas poin di sekolah CAKRAWALA.</p>
                </div>
                <div class="hidden md:block">
                    <svg class="w-20 h-20 text-slate-950 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.505 0-4.873-.77-6.843-2.082"></path></svg>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Total Siswa -->
                <div class="bg-white rounded-2xl border-2 border-slate-950 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] flex items-center p-6 hover:-translate-y-0.5 hover:shadow-[6px_6px_0px_0px_rgba(15,23,42,1)] transition-all duration-200">
                    <div class="p-4 bg-[#EAFCEF] text-slate-950 rounded-xl mr-4 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A2.25 2.25 0 0112.75 21.5h-1.5a2.25 2.25 0 01-2.25-2.263V19.13m4.5-16.128v.11a2.25 2.25 0 01-2.25 2.263h-1.5a2.25 2.25 0 01-2.25-2.264V3m4.5 0a2.25 2.25 0 00-4.5 0m4.5 0H9m-4.5 0A2.25 2.25 0 003 4.5h.75m0 0l3 3m-3-3l-3 3M3 4.5v3.13c0 1.113.285 2.16.786 3.07M3 7.628a9.38 9.38 0 012.625-.372 9.337 9.337 0 014.121.952 4.125 4.125 0 01-7.533 2.493M3.75 7.628v-.003c0-1.113.285-2.16.786-3.07"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Siswa Aktif</p>
                        <p class="text-2xl font-black text-slate-950 mt-1">{{ $totalSiswa }}</p>
                    </div>
                </div>

                <!-- Total Guru -->
                <div class="bg-white rounded-2xl border-2 border-slate-950 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] flex items-center p-6 hover:-translate-y-0.5 hover:shadow-[6px_6px_0px_0px_rgba(15,23,42,1)] transition-all duration-200">
                    <div class="p-4 bg-[#FFEAEA] text-slate-950 rounded-xl mr-4 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.19c0-.13-.01-.27-.01-.41a8.978 8.978 0 017.5-8.88m-7.49 9.29c.14.01.28.01.42.01a8.978 8.978 0 008.88-7.5M4.26 10.19a8.978 8.978 0 017.5 8.88m-7.49-9.29c.14-.01.28-.01.42-.01a8.978 8.978 0 008.88 7.5m-8.88 0c0 .14-.01.28-.01.42a8.978 8.978 0 007.5 8.88m-7.49-9.3c.14.01.28.01.42.01a8.978 8.978 0 008.88-7.5M12.18 19.48a8.978 8.978 0 017.5-8.88m-7.49 9.3c.14.01.28.01.42.01a8.978 8.978 0 008.88-7.5"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Guru Aktif</p>
                        <p class="text-2xl font-black text-slate-950 mt-1">{{ $totalGuru }}</p>
                    </div>
                </div>

                <!-- Total Kelas -->
                <div class="bg-white rounded-2xl border-2 border-slate-950 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] flex items-center p-6 hover:-translate-y-0.5 hover:shadow-[6px_6px_0px_0px_rgba(15,23,42,1)] transition-all duration-200">
                    <div class="p-4 bg-blue-50 text-slate-950 rounded-xl mr-4 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Kelas Terdaftar</p>
                        <p class="text-2xl font-black text-slate-950 mt-1">{{ $totalKelas }}</p>
                    </div>
                </div>

                <!-- Total Poin Sekolah -->
                <div class="bg-white rounded-2xl border-2 border-slate-950 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] flex items-center p-6 hover:-translate-y-0.5 hover:shadow-[6px_6px_0px_0px_rgba(15,23,42,1)] transition-all duration-200">
                    <div class="p-4 bg-[#E4FF1A] text-slate-950 rounded-xl mr-4 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.22.22a1.5 1.5 0 002.122 0L12 15M9 12h.01M15 12h.01M9 8.818h.01m5.99 0H15M9 15.182h.01M12 12a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm6 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total Poin Siswa</p>
                        <p class="text-2xl font-black text-slate-950 mt-1">{{ number_format($totalPoinSekolah) }} Pts</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Top 10 Siswa -->
                <div class="bg-white rounded-3xl border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                    <div class="p-6 border-b-4 border-slate-950 bg-white">
                        <h3 class="text-lg font-black text-slate-950 uppercase tracking-tight">Top 10 Siswa Terbaik</h3>
                        <p class="text-xs text-slate-400 mt-0.5 font-bold uppercase tracking-wider">Siswa dengan perolehan poin kebaikan tertinggi</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b-2 border-slate-950">
                                    <th class="px-6 py-3 text-xs font-black text-slate-950 uppercase tracking-wider text-center w-20">Rank</th>
                                    <th class="px-6 py-3 text-xs font-black text-slate-950 uppercase tracking-wider">Nama & Kelas</th>
                                    <th class="px-6 py-3 text-xs font-black text-slate-950 uppercase tracking-wider text-right">Total Poin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-2 divide-slate-950">
                                @forelse($topSiswa as $index => $siswa)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-3.5 text-center">
                                        @if($index == 0)
                                            <span class="bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 w-6 h-6 flex items-center justify-center rounded-full text-xs font-black shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] mx-auto">1</span>
                                        @elseif($index == 1)
                                            <span class="bg-white text-slate-950 border-2 border-slate-950 w-6 h-6 flex items-center justify-center rounded-full text-xs font-black shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] mx-auto">2</span>
                                        @elseif($index == 2)
                                            <span class="bg-orange-100 text-slate-950 border-2 border-slate-950 w-6 h-6 flex items-center justify-center rounded-full text-xs font-black shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] mx-auto">3</span>
                                        @else
                                            <span class="text-slate-950 text-xs font-black">{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3.5">
                                        <div class="font-bold text-slate-950 text-xs">{{ $siswa->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold mt-0.5 uppercase tracking-wider">{{ $siswa->classroom->name ?? 'Belum ada kelas' }} · Level {{ $siswa->current_level }}</div>
                                    </td>
                                    <td class="px-6 py-3.5 text-right font-black text-xs text-indigo-600">{{ number_format($siswa->points) }} Pts</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-8 text-slate-400 text-xs font-bold uppercase">Belum ada data siswa.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Top 10 Kelas -->
                <div class="bg-white rounded-3xl border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                    <div class="p-6 border-b-4 border-slate-950 bg-white">
                        <h3 class="text-lg font-black text-slate-950 uppercase tracking-tight">Top 10 Kelas Terbaik</h3>
                        <p class="text-xs text-slate-400 mt-0.5 font-bold uppercase tracking-wider">Akumulasi poin masing-masing kelas (CoTE System)</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b-2 border-slate-950">
                                    <th class="px-6 py-3 text-xs font-black text-slate-950 uppercase tracking-wider text-center w-20">Rank</th>
                                    <th class="px-6 py-3 text-xs font-black text-slate-950 uppercase tracking-wider">Nama Kelas</th>
                                    <th class="px-6 py-3 text-xs font-black text-slate-950 uppercase tracking-wider text-right">Poin Kelas</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-2 divide-slate-950">
                                @forelse($topKelas as $index => $kelas)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-3.5 text-center">
                                        @if($index == 0)
                                            <span class="bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 w-6 h-6 flex items-center justify-center rounded-full text-xs font-black shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] mx-auto">1</span>
                                        @elseif($index == 1)
                                            <span class="bg-white text-slate-950 border-2 border-slate-950 w-6 h-6 flex items-center justify-center rounded-full text-xs font-black shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] mx-auto">2</span>
                                        @elseif($index == 2)
                                            <span class="bg-orange-100 text-slate-950 border-2 border-slate-950 w-6 h-6 flex items-center justify-center rounded-full text-xs font-black shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] mx-auto">3</span>
                                        @else
                                            <span class="text-slate-950 text-xs font-black">{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3.5 font-bold text-slate-950 text-xs">{{ $kelas->name }}</td>
                                    <td class="px-6 py-3.5 text-right font-black text-xs text-indigo-600">{{ number_format($kelas->points) }} Pts</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-8 text-slate-400 text-xs font-bold uppercase">Belum ada data kelas.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Aktivitas Terbaru -->
            <div class="bg-white rounded-3xl border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="p-6 border-b-4 border-slate-950 bg-white">
                    <h3 class="text-lg font-black text-slate-950 uppercase tracking-tight">Aktivitas Poin Terbaru</h3>
                    <p class="text-xs text-slate-400 mt-0.5 font-bold uppercase tracking-wider">Histori transaksi poin siswa</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b-2 border-slate-950">
                                <th class="px-6 py-3 text-xs font-black text-slate-950 uppercase tracking-wider">Siswa</th>
                                <th class="px-6 py-3 text-xs font-black text-slate-950 uppercase tracking-wider">Aktivitas</th>
                                <th class="px-6 py-3 text-xs font-black text-slate-950 uppercase tracking-wider">Sumber</th>
                                <th class="px-6 py-3 text-xs font-black text-slate-950 uppercase tracking-wider text-center">Tipe</th>
                                <th class="px-6 py-3 text-xs font-black text-slate-950 uppercase tracking-wider text-right">Nilai Poin</th>
                                <th class="px-6 py-3 text-xs font-black text-slate-950 uppercase tracking-wider text-right">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-slate-950">
                            @forelse($aktivitasTerbaru as $history)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-3.5">
                                    <div class="font-bold text-slate-950 text-xs">{{ $history->user->name ?? 'N/A' }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold mt-0.5 uppercase tracking-wider">{{ $history->user->classroom->name ?? 'Belum ada kelas' }}</div>
                                </td>
                                <td class="px-6 py-3.5 text-xs font-bold text-slate-700">
                                    {{ $history->description ?? 'Penyesuaian poin' }}
                                </td>
                                <td class="px-6 py-3.5 text-[10px] font-black uppercase text-slate-400 tracking-wider">
                                    {{ $history->source }}
                                </td>
                                <td class="px-6 py-3.5 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider border-2 border-slate-950
                                        {{ $history->type === 'kebaikan' ? 'bg-[#EAFCEF] text-emerald-800' : 'bg-[#FFEAEA] text-rose-800' }}">
                                        {{ $history->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 text-right font-black text-xs {{ $history->type === 'kebaikan' ? 'text-emerald-700' : 'text-rose-600' }}">
                                    {{ $history->type === 'kebaikan' ? '+' : '-' }}{{ $history->points }} Pts
                                </td>
                                <td class="px-6 py-3.5 text-right text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                                    {{ $history->created_at->diffForHumans() }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-slate-400 text-xs font-bold uppercase">Belum ada aktivitas terekam.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
