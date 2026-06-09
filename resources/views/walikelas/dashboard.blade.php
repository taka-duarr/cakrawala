<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 leading-tight">
            {{ __('Dashboard Wali Kelas') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(isset($error))
            <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl p-6 shadow-sm">
                <p class="font-medium text-sm">{{ $error }}</p>
            </div>
            @else
            
            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-slate-700 to-indigo-950 rounded-2xl shadow-md p-8 text-white">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ $wali->name }}!</h1>
                        <p class="text-slate-200 text-sm">Wali Kelas untuk: <strong class="text-indigo-200">{{ $className }}</strong> · Pantau perkembangan karakter dan prestasi kelas Anda di sini.</p>
                    </div>
                    <div class="mt-4 md:mt-0 bg-white/10 backdrop-blur border border-white/20 rounded-xl px-5 py-3 text-right">
                        <span class="text-xs text-indigo-200 block">Peringkat Kelas</span>
                        <div class="flex items-center justify-end space-x-1.5 mt-0.5">
                            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 100-6 3 3 0 000 6zM19.5 8.25c0-1.518-1.232-2.75-2.75-2.75h-.75V3H8v2.5h-.75C5.732 5.5 4.5 6.732 4.5 8.25v.75c0 1.518 1.232 2.75 2.75 2.75h.75M19.5 8.25v.75c0 1.518-1.232 2.75-2.75 2.75h-.75M9 21h6M12 15v6"></path></svg>
                            <strong class="text-2xl text-white font-extrabold">Ke-{{ $myRank }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-2xl border border-slate-100 p-6 soft-glow-indigo">
                    <span class="text-xs font-semibold text-slate-500 block mb-1">Total Siswa</span>
                    <strong class="text-3xl text-slate-800 font-bold">{{ $students->count() }}</strong>
                </div>
                <div class="bg-white rounded-2xl border border-slate-100 p-6 soft-glow-emerald">
                    <span class="text-xs font-semibold text-slate-500 block mb-1">Total Poin Kebaikan</span>
                    <strong class="text-3xl text-emerald-600 font-bold">{{ $totalKebaikan }}</strong>
                </div>
                <div class="bg-white rounded-2xl border border-slate-100 p-6 soft-glow-rose">
                    <span class="text-xs font-semibold text-slate-500 block mb-1">Total Poin Pelanggaran</span>
                    <strong class="text-3xl text-rose-600 font-bold">{{ $totalPelanggaran }}</strong>
                </div>
                <div class="bg-white rounded-2xl border border-slate-100 p-6 soft-glow-indigo">
                    <span class="text-xs font-semibold text-slate-500 block mb-1">Rata-rata Kebaikan Kelas</span>
                    <strong class="text-3xl text-indigo-600 font-bold">{{ $avgKebaikan }} Pts</strong>
                </div>
            </div>

            <!-- AI Early Warning Card -->
            <div class="bg-white rounded-2xl border border-slate-100 p-8 soft-glow-indigo">
                <div class="flex justify-between items-start mb-6">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-slate-800 flex items-center">
                            <svg class="w-6 h-6 text-rose-500 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <span>AI Early Warning System</span>
                        </h3>
                        <p class="text-sm text-slate-500 mt-1">Menganalisis tren keaktifan siswa untuk mendeteksi penurunan motivasi secara dini.</p>
                    </div>
                    <form method="GET" action="{{ route('walikelas.dashboard') }}" onsubmit="let btn = this.querySelector('button[type=submit]'); if(btn) { btn.disabled = true; btn.innerHTML = '<span class=\'animate-spin inline-block w-3.5 h-3.5 border-2 border-current border-t-transparent rounded-full mr-1.5 align-middle\'></span> Menganalisis...'; }">
                        <input type="hidden" name="trigger_ai" value="1">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold transition flex items-center justify-center">
                            <svg class="w-4 h-4 text-white mr-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"></path></svg>
                            <span>Jalankan Analisis AI</span>
                        </button>
                    </form>
                </div>

                @if($aiWarning)
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 text-sm text-slate-700 leading-relaxed font-sans whitespace-pre-line shadow-inner">
                    {{ $aiWarning }}
                </div>
                @else
                <div class="text-center py-8 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                    <p class="text-sm text-slate-400">Analisis AI belum dijalankan. Klik tombol di atas untuk menganalisis tingkat kerawanan kelas.</p>
                </div>
                @endif
                      <!-- Pengajuan Penukaran Poin (Siswa Kelas) -->
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden soft-glow-indigo">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Pengajuan Penukaran Poin Kelas Anda</h3>
                        <p class="text-xs text-slate-400 mt-1 font-medium">Setujui penukaran hadiah untuk siswa di kelas {{ $className }}.</p>
                    </div>
                    @if($pendingClaims->count() > 0)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-100 animate-pulse">
                            {{ $pendingClaims->count() }} Menunggu
                        </span>
                    @endif
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100/80">
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Siswa</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Hadiah</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Biaya Poin</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/70">
                            @forelse($pendingClaims as $claim)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-indigo-50 border border-indigo-100/30 rounded-full flex items-center justify-center font-extrabold text-indigo-700 text-xs shadow-inner">
                                            {{ substr($claim->student_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800 text-xs leading-none mb-1">{{ $claim->student_name }}</div>
                                            <div class="text-[9px] text-slate-400 font-semibold uppercase tracking-wider">Kelas: {{ $claim->class_name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-semibold text-slate-800 mb-0.5">{{ $claim->reward_name }}</div>
                                    <div class="text-[10px] text-slate-400 font-medium">{{ \Carbon\Carbon::parse($claim->created_at)->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 text-center font-extrabold text-xs text-emerald-600">{{ $claim->points_cost }} Pts</td>
                                <td class="px-6 py-4 text-right">
                                    <form method="POST" action="{{ route('walikelas.rewards.approve', $claim->id) }}" onsubmit="let btn = this.querySelector('button[type=submit]'); if(btn) { btn.disabled = true; btn.innerHTML = '<span class=\'animate-spin inline-block w-3.5 h-3.5 border-2 border-current border-t-transparent rounded-full align-middle\'></span>'; }">
                                        @csrf
                                        <button type="submit" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-bold rounded-xl transition shadow-sm hover:shadow-md min-w-[110px] flex items-center justify-center">
                                            Setujui & Serahkan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-12 text-slate-400 text-xs font-medium">Tidak ada klaim hadiah pending dari kelas Anda.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Siswa Berisiko (Pelanggaran > 20) -->
                <div class="bg-white rounded-2xl border border-slate-100 p-6 soft-glow-rose lg:col-span-1">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Siswa Membutuhkan Perhatian</h3>
                    <div class="space-y-3">
                        @forelse($atRiskStudents as $student)
                        <div class="p-3 bg-rose-50 border border-rose-100 rounded-xl flex items-center justify-between">
                            <div>
                                <div class="font-semibold text-rose-800 text-sm mb-0.5">{{ $student->name }}</div>
                                <div class="text-[10px] text-rose-500 font-bold bg-rose-100/50 px-2 py-0.5 rounded-full inline-block">Level: {{ $student->current_level }}</div>
                            </div>
                            <span class="font-bold text-[10px] bg-rose-200 text-rose-800 px-2.5 py-1 rounded-full border border-rose-300/30">
                                {{ $student->points_pelanggaran }} Pelanggaran
                            </span>
                        </div>
                        @empty
                        <div class="text-center py-6 text-slate-400 text-xs font-medium bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                            ✅ Semua siswa di kelas beraktivitas secara positif.
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Monitoring Siswa Kelas -->
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden soft-glow-indigo lg:col-span-2">
                    <div class="p-6 border-b border-slate-100">
                        <h3 class="text-lg font-bold text-slate-800">Daftar Anggota Kelas ({{ $className }})</h3>
                        <p class="text-xs text-slate-400 mt-1 font-medium">Gunakan daftar ini untuk meninjau detail poin per siswa.</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-100/80">
                                    <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center w-16">No</th>
                                    <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nama Siswa</th>
                                    <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Level</th>
                                    <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Kebaikan</th>
                                    <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Pelanggaran</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100/70">
                                @forelse($students as $index => $student)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 text-center text-slate-400 font-bold text-xs">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-indigo-50 border border-indigo-100/30 rounded-full flex items-center justify-center font-extrabold text-indigo-700 text-xs shadow-inner">
                                                {{ substr($student->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-800 text-xs leading-none mb-1">{{ $student->name }}</div>
                                                <div class="text-[9px] text-slate-400 font-semibold">{{ $student->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100/80">
                                            {{ $student->current_level }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center font-extrabold text-xs text-emerald-600">{{ $student->points_kebaikan }} Pts</td>
                                    <td class="px-6 py-4 text-center font-extrabold text-xs text-rose-500">{{ $student->points_pelanggaran }} Pts</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-12 text-slate-400 text-xs font-medium">Belum ada siswa yang terdaftar di kelas ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @endif

        </div>
    </div>
</x-app-layout>
