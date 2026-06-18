<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">
            {{ __('Dashboard Wali Kelas') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-100/40 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(isset($error))
                <div class="bg-[#FFEAEA] border-2 border-slate-950 text-rose-800 p-6 rounded-3xl shadow-[4px_4px_0px_0px_rgba(15,23,42,1)]">
                    <p class="font-black text-xs uppercase tracking-wider">{{ $error }}</p>
                </div>
            @else
            
            <!-- Welcome Banner -->
            <div class="bg-[#E4FF1A] border-4 border-slate-950 rounded-3xl shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] p-8 text-slate-950 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-black mb-2 uppercase tracking-tight">Selamat Datang, {{ $wali->name }}!</h1>
                    <p class="text-slate-800 text-xs font-bold uppercase tracking-wider">Wali Kelas untuk: <strong class="text-slate-950 underline">{{ $className }}</strong> · Pantau perkembangan karakter dan prestasi kelas Anda di sini.</p>
                </div>
                <div class="bg-white border-2 border-slate-950 rounded-2xl p-5 min-w-[180px] text-right self-start md:self-auto shadow-[4px_4px_0px_0px_rgba(15,23,42,1)]">
                    <span class="text-[9px] text-slate-400 block uppercase font-bold tracking-wider mb-1">Peringkat Kelas</span>
                    <strong class="text-2xl text-slate-950 font-black block uppercase tracking-tight">Ke-{{ $myRank }}</strong>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl border-2 border-slate-950 p-6 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)]">
                    <span class="text-[10px] font-black text-slate-400 block mb-1.5 uppercase tracking-wider">Total Siswa</span>
                    <strong class="text-3xl text-slate-950 font-black block">{{ $students->count() }}</strong>
                </div>
                <div class="bg-[#E4FF1A]/20 rounded-2xl border-2 border-slate-950 p-6 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)]">
                    <span class="text-[10px] font-black text-slate-400 block mb-1.5 uppercase tracking-wider">Total Poin Kelas</span>
                    <strong class="text-3xl text-slate-950 font-black block">{{ number_format($totalKebaikan) }}</strong>
                </div>
                <div class="bg-white rounded-2xl border-2 border-slate-950 p-6 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)]">
                    <span class="text-[10px] font-black text-slate-400 block mb-1.5 uppercase tracking-wider">Rata-rata Kebaikan Kelas</span>
                    <strong class="text-3xl text-slate-950 font-black block">{{ number_format($avgKebaikan) }} Pts</strong>
                </div>
            </div>

            <!-- AI Early Warning Card -->
            <div class="bg-white rounded-3xl border-4 border-slate-950 p-8 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between mb-6 gap-4 border-b-2 border-slate-950 pb-4">
                    <div class="flex-1">
                        <h3 class="text-lg font-black text-slate-950 uppercase tracking-tight flex items-center space-x-2">
                            <span uk-icon="icon: warning; ratio: 0.95" class="text-rose-600"></span>
                            <span>AI Early Warning System</span>
                        </h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Menganalisis tren keaktifan siswa untuk mendeteksi penurunan motivasi secara dini.</p>
                    </div>
                    <form method="GET" action="{{ route('walikelas.dashboard') }}" onsubmit="let btn = this.querySelector('button[type=submit]'); if(btn) { btn.disabled = true; btn.innerHTML = '<span class=\'animate-spin inline-block w-3.5 h-3.5 border-2 border-current border-t-transparent rounded-full mr-1.5 align-middle\'></span> Menganalisis...'; }">
                        <input type="hidden" name="trigger_ai" value="1">
                        <button type="submit" class="px-4 py-2.5 bg-white hover:bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 text-xs font-black rounded-xl transition shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider flex items-center justify-center space-x-1.5">
                            <span uk-icon="icon: bolt; ratio: 0.8"></span>
                            <span>Jalankan Analisis AI</span>
                        </button>
                    </form>
                </div>

                @if($aiWarning)
                    <div class="bg-[#E4FF1A]/10 border-2 border-slate-950 rounded-2xl p-6 text-xs text-slate-950 font-bold uppercase tracking-wide leading-relaxed shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] whitespace-pre-line">
                        {{ $aiWarning }}
                    </div>
                @else
                    <div class="text-center py-8 bg-slate-50 border-2 border-dashed border-slate-950 rounded-2xl text-xs font-bold uppercase tracking-wider text-slate-400">
                        Analisis AI belum dijalankan. Klik tombol di atas untuk menganalisis tingkat kerawanan kelas.
                    </div>
                @endif
            </div>

            <!-- Pengajuan Penukaran Poin (Siswa Kelas) -->
            <div class="bg-white rounded-3xl border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="p-6 border-b-4 border-slate-950 bg-[#E4FF1A]/10 flex justify-between items-center flex-wrap gap-4">
                    <div>
                        <h3 class="text-lg font-black text-slate-950 uppercase tracking-tight">Pengajuan Penukaran Poin Kelas Anda</h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Setujui penukaran hadiah untuk siswa di kelas {{ $className }}.</p>
                    </div>
                    @if($pendingClaims->count() > 0)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded border-2 border-slate-950 text-[10px] font-black bg-amber-100 text-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider animate-pulse">
                            {{ $pendingClaims->count() }} Menunggu
                        </span>
                    @endif
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-950 text-white border-b-2 border-slate-950">
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider">Siswa</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider">Hadiah</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-center">Biaya Poin</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-950">
                            @forelse($pendingClaims as $claim)
                            <tr class="hover:bg-slate-50 transition-colors bg-white">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-white border-2 border-slate-950 rounded-full flex items-center justify-center font-black text-slate-950 text-xs shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                            {{ substr($claim->student_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-black text-slate-950 text-xs uppercase tracking-tight mb-0.5">{{ $claim->student_name }}</div>
                                            <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Kelas: {{ $claim->class_name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-black text-slate-950 uppercase tracking-tight mb-0.5">{{ $claim->reward_name }}</div>
                                    <div class="text-[9px] text-slate-400 font-black uppercase tracking-wider">{{ \Carbon\Carbon::parse($claim->created_at)->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-[#E4FF1A] border-2 border-slate-950 text-slate-950 text-[10px] font-black px-2.5 py-1 rounded shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider">
                                        {{ number_format($claim->points_cost) }} Pts
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form method="POST" action="{{ route('walikelas.rewards.approve', $claim->id) }}" onsubmit="let btn = this.querySelector('button[type=submit]'); if(btn) { btn.disabled = true; btn.innerHTML = '<span class=\'animate-spin inline-block w-3.5 h-3.5 border-2 border-current border-t-transparent rounded-full align-middle\'></span>'; }" class="inline-block">
                                        @csrf
                                        <button type="submit" class="px-3.5 py-1.5 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white border-2 border-slate-950 text-[10px] font-black rounded-lg transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] min-w-[110px] flex items-center justify-center uppercase tracking-wider">
                                            Setujui & Serahkan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-12 text-slate-400 text-xs font-bold uppercase tracking-wider bg-slate-50/20">Tidak ada klaim hadiah pending dari kelas Anda.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Siswa Berisiko (Poin Minus) -->
                <div class="bg-white rounded-3xl border-4 border-slate-950 p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] lg:col-span-1 flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-black text-slate-950 uppercase tracking-tight mb-4 border-b-2 border-slate-950 pb-2">Butuh Perhatian</h3>
                        <div class="space-y-3">
                            @forelse($atRiskStudents as $student)
                            <div class="p-3 bg-rose-50 border-2 border-slate-950 rounded-xl flex items-center justify-between shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                <div>
                                    <div class="font-black text-rose-800 text-xs uppercase tracking-tight mb-0.5">{{ $student->name }}</div>
                                    <div class="text-[9px] text-rose-500 font-bold bg-rose-100/50 px-2 py-0.5 rounded border border-rose-200 uppercase tracking-wider inline-block">Level: {{ $student->current_level }}</div>
                                </div>
                                <span class="font-black text-[9px] bg-rose-200 text-rose-800 px-2.5 py-1 rounded border-2 border-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider">
                                    {{ $student->points }} Pts
                                </span>
                            </div>
                            @empty
                            <div class="text-center py-6 text-slate-400 text-xs font-bold uppercase tracking-wider bg-slate-50 border-2 border-dashed border-slate-950 rounded-xl">
                                ✅ Semua siswa di kelas beraktivitas secara positif.
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Monitoring Siswa Kelas -->
                <div class="bg-white rounded-3xl border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] lg:col-span-2">
                    <div class="p-6 border-b-4 border-slate-950 bg-[#E4FF1A]/10">
                        <h3 class="text-lg font-black text-slate-950 uppercase tracking-tight">Daftar Anggota Kelas ({{ $className }})</h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Gunakan daftar ini untuk meninjau detail poin per siswa.</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-950 text-white border-b-2 border-slate-950">
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-center w-16">No</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider">Nama Siswa</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider">Level</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-center">Status Keaktifan</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-center">Total Poin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-950 bg-white">
                                @forelse($students as $index => $student)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 text-center text-slate-400 font-black text-xs">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-white border-2 border-slate-950 rounded-full flex items-center justify-center font-black text-slate-950 text-xs shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                                {{ substr($student->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-black text-slate-950 text-xs uppercase tracking-tight mb-0.5">{{ $student->name }}</div>
                                                <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">{{ $student->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 bg-white border-2 border-slate-950 rounded text-[9px] font-black uppercase tracking-wider shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                            {{ $student->current_level }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2.5 py-1.5 rounded border-2 border-slate-950 text-[9px] font-black uppercase tracking-wider shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] {{ $student->activity_color }}">
                                            {{ $student->activity_status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="bg-[#E4FF1A] border-2 border-slate-950 text-slate-950 text-[10px] font-black px-2.5 py-1 rounded shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider">
                                            {{ number_format($student->points) }} Pts
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-12 text-slate-400 text-xs font-bold uppercase tracking-wider bg-slate-50/20">Belum ada siswa yang terdaftar di kelas ini.</td>
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
