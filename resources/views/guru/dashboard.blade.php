<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">Dashboard Guru</h2>
            <button onclick="UIkit.modal('#modal-add-mission').show()" class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white text-xs font-black px-4 py-2.5 rounded-xl border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider">
                <span uk-icon="icon: plus; ratio: 0.8"></span>
                <span>Buat Misi Baru</span>
            </button>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-100/30 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Success/Error Alerts -->
            @if(session('success'))
                <div class="bg-[#EAFCEF] border-2 border-slate-950 text-emerald-800 px-4 py-3 rounded-xl text-xs font-black flex items-center space-x-2 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                    <span uk-icon="icon: check; ratio: 0.9"></span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-[#FFEAEA] border-2 border-slate-950 text-rose-800 px-4 py-3 rounded-xl text-xs font-black space-y-1 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                    <div class="flex items-center space-x-2">
                        <span uk-icon="icon: warning; ratio: 0.9"></span>
                        <span class="font-black">TERJADI KESALAHAN INPUT:</span>
                    </div>
                    <ul class="list-disc pl-5 space-y-0.5 mt-1 font-bold">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Welcome Banner -->
            <div class="bg-[#E4FF1A] border-4 border-slate-950 rounded-3xl shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] p-8 text-slate-950 flex items-center justify-between">
                <div class="space-y-2">
                    <h1 class="text-3xl font-black uppercase tracking-tight mb-1">Selamat Datang, {{ auth()->user()->name }}!</h1>
                    <p class="text-slate-800 text-xs font-bold uppercase tracking-wider">Kelola misi akademik/karakter, tinjau bukti penyelesaian, dan sesuaikan reputasi siswa.</p>
                </div>
                <div class="hidden md:block">
                    <svg class="w-20 h-20 text-slate-950 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl border-2 border-slate-950 p-6 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] flex items-center hover:-translate-y-0.5 transition-all duration-200">
                    <div class="p-4 bg-[#FFEAEA] text-slate-950 border-2 border-slate-950 rounded-xl mr-4 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Misi Menunggu Persetujuan</p>
                        <p class="text-3xl font-black text-slate-950 mt-1">{{ $pendingMissions->count() }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border-2 border-slate-950 p-6 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] flex items-center hover:-translate-y-0.5 transition-all duration-200">
                    <div class="p-4 bg-[#EAFCEF] text-slate-950 border-2 border-slate-950 rounded-xl mr-4 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total Siswa di Kelas Anda</p>
                        <p class="text-3xl font-black text-slate-950 mt-1">{{ $siswas->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Jadwal Mengajar Hari Ini -->
            <div class="bg-white rounded-3xl border-4 border-slate-950 p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="flex items-center justify-between mb-6 flex-wrap gap-4">
                    <div>
                        <h3 class="text-lg font-black text-slate-950 uppercase tracking-tight">Jadwal Mengajar Hari Ini</h3>
                        @php
                            $daysInd = [
                                'Monday' => 'Senin',
                                'Tuesday' => 'Selasa',
                                'Wednesday' => 'Rabu',
                                'Thursday' => 'Kamis',
                                'Friday' => 'Jumat',
                                'Saturday' => 'Sabtu',
                                'Sunday' => 'Minggu'
                            ];
                            $todayEng = \Carbon\Carbon::now()->format('l');
                            $todayInd = $daysInd[$todayEng] ?? $todayEng;
                        @endphp
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-0.5">Hari ini: <span class="text-slate-950 font-black">{{ $todayInd }}</span>. Kelola presensi kelas langsung dari jadwal.</p>
                    </div>
                    <div>
                        <a href="{{ route('guru.my-schedule') }}" class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white text-xs font-black px-4 py-2.5 rounded-xl border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider flex items-center space-x-2">
                            <span uk-icon="icon: calendar; ratio: 0.8"></span>
                            <span>Lihat Semua Jadwal</span>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @php
                        $todayAssigns = $assignments->filter(fn($a) => $a->day_of_week === $todayEng)->sortBy('start_time');
                    @endphp
                    @forelse($todayAssigns as $assign)
                        <a href="{{ route('guru.assignments.detail', $assign->id) }}" class="bg-white border-2 border-slate-950 rounded-xl p-4 flex flex-col justify-between shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] hover:shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-0.5 transition-all duration-200 group">
                            <div>
                                <div class="flex justify-between items-center mb-3">
                                    <span class="bg-slate-950 text-white text-[10px] font-black px-2 py-0.5 rounded border border-slate-950 uppercase tracking-wider">
                                        {{ $assign->classroom->name }}
                                    </span>
                                    <span class="text-[10px] font-black text-slate-950 bg-[#E4FF1A] border-2 border-slate-950 px-2 py-0.5 rounded-md shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                        {{ $assign->start_time ? substr($assign->start_time, 0, 5) . ' - ' . substr($assign->end_time, 0, 5) : '00:00' }}
                                    </span>
                                </div>
                                <h4 class="text-sm font-black text-slate-950 uppercase tracking-tight group-hover:text-slate-700 transition-colors">{{ $assign->subject->name }}</h4>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">Kode: {{ $assign->subject->code ?? '-' }} · Semester {{ $assign->semester->name ?? '-' }}</p>
                            </div>
                            <div class="mt-4 pt-3 border-t-2 border-slate-950 flex justify-between items-center text-[9px] text-slate-500 font-black uppercase tracking-wider">
                                <span>Siswa terdaftar:</span>
                                <span class="font-black text-slate-950 bg-white px-2 py-0.5 rounded-md border-2 border-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">{{ \App\Models\User::where('role_id', 5)->where('classroom_id', $assign->classroom_id)->count() }} Siswa</span>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-3 text-center py-8 text-slate-400 text-xs font-bold uppercase tracking-wider bg-slate-50/40 rounded-2xl border-2 border-dashed border-slate-950">
                            Tidak ada jadwal mengajar untuk hari ini ({{ $todayInd }}).
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Persetujuan Misi -->
            <div class="bg-white rounded-3xl border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="p-6 border-b-4 border-slate-950 flex justify-between items-center bg-[#E4FF1A]/10">
                    <div>
                        <h3 class="text-lg font-black text-slate-950 uppercase tracking-tight">Misi Menunggu Persetujuan</h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-0.5">Siswa yang telah menyerahkan bukti penyelesaian misi dan menanti verifikasi.</p>
                    </div>
                    @if($pendingMissions->count() > 0)
                        <span class="inline-flex items-center px-3 py-1 rounded-md text-[10px] font-black bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider animate-pulse">
                            {{ $pendingMissions->count() }} Menunggu
                        </span>
                    @endif
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-950 text-white border-b-2 border-slate-950">
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider">Siswa</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider">Misi</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-center">Hadiah Poin</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-center">Tipe Bukti</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-950">
                            @forelse($pendingMissions as $mission)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-white border-2 border-slate-950 rounded-full flex items-center justify-center font-black text-slate-950 text-xs shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                            {{ substr($mission->student->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-black text-slate-950 text-xs uppercase tracking-tight mb-0.5">{{ $mission->student->name }}</div>
                                            <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Kelas: {{ $mission->student->classroom->name ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-black text-slate-950 uppercase tracking-tight mb-0.5">{{ $mission->title }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $mission->type ?? 'Harian' }}</div>
                                </td>
                                <td class="px-6 py-4 text-center font-black text-xs text-slate-950">+{{ $mission->points_reward }} Pts</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-0.5 rounded border border-slate-950 text-[9px] font-black uppercase tracking-wider bg-white text-slate-950">
                                        {{ $mission->proof_type ?? 'none' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button 
                                        data-user-id="{{ $mission->student->id }}"
                                        data-mission-id="{{ $mission->id }}"
                                        data-student-name="{{ $mission->student->name }}"
                                        data-mission-title="{{ $mission->title }}"
                                        data-points-reward="{{ $mission->points_reward }}"
                                        data-proof-type="{{ $mission->proof_type }}"
                                        data-proof-url="{{ $mission->pivot->proof_url }}"
                                        data-proof-content="{{ $mission->pivot->proof_content }}"
                                        class="validate-mission-btn px-3 py-1.5 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white text-[10px] font-black rounded-lg border-2 border-slate-950 transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] inline-flex items-center space-x-1 uppercase tracking-wider"
                                    >
                                        <span>Periksa Bukti</span>
                                        <span uk-icon="icon: sign-in; ratio: 0.75"></span>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-12 text-slate-400 text-xs font-bold uppercase tracking-wider bg-slate-50/20">Tidak ada misi yang menunggu persetujuan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Peringkat & Manajemen Siswa -->
            <div class="bg-white rounded-3xl border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="p-6 border-b-4 border-slate-950 bg-[#E4FF1A]/10">
                    <h3 class="text-lg font-black text-slate-950 uppercase tracking-tight">Daftar & Peringkat Siswa</h3>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-0.5">Daftar seluruh siswa untuk penyesuaian poin manual dan pemberian lencana.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-950 text-white border-b-2 border-slate-950">
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-center w-20">Rank</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider">Nama Siswa</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-center">Kelas</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-center">Level</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-right">Total Poin</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-center w-48">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-950">
                            @forelse($siswas as $index => $siswa)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-center">
                                    @if($index == 0)
                                        <span class="bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 w-7 h-7 flex items-center justify-center rounded-full text-xs font-black shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] mx-auto">1</span>
                                    @elseif($index == 1)
                                        <span class="bg-white text-slate-950 border-2 border-slate-950 w-7 h-7 flex items-center justify-center rounded-full text-xs font-black shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] mx-auto">2</span>
                                    @elseif($index == 2)
                                        <span class="bg-[#FFF3EA] text-slate-950 border-2 border-slate-950 w-7 h-7 flex items-center justify-center rounded-full text-xs font-black shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] mx-auto">3</span>
                                    @else
                                        <span class="text-slate-400 text-xs font-black uppercase">{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-white border-2 border-slate-950 rounded-full flex items-center justify-center font-black text-slate-950 text-xs shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                            {{ substr($siswa->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-black text-slate-950 text-xs uppercase tracking-tight mb-0.5">{{ $siswa->name }}</div>
                                            <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">{{ $siswa->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center text-xs text-slate-500 font-black uppercase tracking-wider">{{ $siswa->classroom->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-0.5 rounded border border-slate-950 text-[10px] font-black bg-white text-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                        {{ $siswa->current_level ?? 'Pemula' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-black text-xs text-slate-950">{{ $siswa->points }} Pts</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button 
                                            data-user-id="{{ $siswa->id }}"
                                            data-student-name="{{ $siswa->name }}"
                                            class="adjust-points-btn bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white border-2 border-slate-950 text-[10px] font-black px-2.5 py-1.5 rounded-lg transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] flex items-center space-x-1 uppercase tracking-wider"
                                        >
                                            <span uk-icon="icon: plus; ratio: 0.65"></span>
                                            <span>Poin</span>
                                        </button>
                                        <button 
                                            data-user-id="{{ $siswa->id }}"
                                            data-student-name="{{ $siswa->name }}"
                                            class="manage-badges-btn bg-white hover:bg-slate-100 text-slate-950 border-2 border-slate-950 text-[10px] font-black px-2.5 py-1.5 rounded-lg transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] flex items-center space-x-1 uppercase tracking-wider"
                                        >
                                            <span uk-icon="icon: star; ratio: 0.65"></span>
                                            <span>Lencana</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-slate-400 text-xs font-bold uppercase tracking-wider bg-slate-50/20">Belum ada data siswa.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- MODAL BUAT MISI BARU -->
    <div id="modal-add-mission" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body bg-white border-4 border-slate-950 rounded-3xl p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-xl font-black text-slate-950 uppercase tracking-tight mb-4 flex items-center space-x-2">
                <span uk-icon="icon: file-edit; ratio: 1.1"></span>
                <span>Buat Misi Baru</span>
            </h2>
            <form action="{{ route('guru.missions.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-black text-slate-950 uppercase tracking-wider mb-1">Judul Misi</label>
                    <input type="text" name="title" required class="w-full text-xs rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-slate-950 focus:outline-none bg-white font-bold" placeholder="Contoh: Menyelesaikan Modul Fisika Bab 3">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-950 uppercase tracking-wider mb-1">Deskripsi Lengkap</label>
                    <textarea name="description" rows="3" required class="w-full text-xs rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-slate-950 focus:outline-none bg-white font-bold" placeholder="Jelaskan apa yang harus dikerjakan siswa..."></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-slate-950 uppercase tracking-wider mb-1">Hadiah Poin</label>
                        <input type="number" name="points_reward" value="10" min="1" required class="w-full text-xs rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-slate-950 focus:outline-none bg-white font-bold">
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-950 uppercase tracking-wider mb-1">Tipe Misi</label>
                        <select name="type" required class="w-full text-xs rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-slate-950 focus:outline-none bg-white font-bold">
                            <option value="daily">Harian (Daily)</option>
                            <option value="weekly">Mingguan (Weekly)</option>
                            <option value="class">Kelas (Class)</option>
                            <option value="school">Sekolah (School)</option>
                            <option value="special">Ujian Khusus (Special)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-slate-950 uppercase tracking-wider mb-1">Jenis Bukti</label>
                        <select name="proof_type" required class="w-full text-xs rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-slate-950 focus:outline-none bg-white font-bold">
                            <option value="none">Tanpa Bukti (None)</option>
                            <option value="file">Unggah Berkas/Foto (File)</option>
                            <option value="link">Tautan Web/Drive (Link)</option>
                            <option value="text">Teks Deskripsi (Text)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-950 uppercase tracking-wider mb-1">Tenggat Waktu (Deadline)</label>
                        <input type="datetime-local" name="deadline" class="w-full text-xs rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-slate-950 focus:outline-none bg-white font-bold">
                    </div>
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <button class="uk-modal-close bg-white hover:bg-slate-100 text-slate-950 border-2 border-slate-950 text-xs font-black px-4 py-2.5 rounded-xl transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider" type="button">Batal</button>
                    <button class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white text-xs font-black px-5 py-2.5 rounded-xl border-2 border-slate-950 transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider" type="submit">Buat Misi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL PERIKSA & VALIDASI MISI -->
    <div id="modal-validate-mission" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body bg-white border-4 border-slate-950 rounded-3xl p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-xl font-black text-slate-950 uppercase tracking-tight mb-4 flex items-center space-x-2">
                <span uk-icon="icon: check; ratio: 1.1"></span>
                <span>Validasi Penyelesaian Misi</span>
            </h2>
            <div class="bg-slate-50 border-2 border-slate-950 rounded-xl p-4 mb-4 space-y-2 text-xs font-bold uppercase tracking-wider shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                <div class="flex justify-between">
                    <span class="text-slate-500">Siswa:</span>
                    <strong id="val-student-name" class="text-slate-950 font-black"></strong>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Misi:</span>
                    <strong id="val-mission-title" class="text-slate-950 font-black"></strong>
                </div>
                <div class="flex justify-between border-t-2 border-slate-950 pt-2">
                    <span class="text-slate-500">Hadiah Poin:</span>
                    <strong id="val-points-reward" class="text-slate-950 font-black"></strong>
                </div>
            </div>

            <!-- Bukti Section -->
            <div class="border-2 border-slate-950 rounded-xl p-4 mb-4 bg-white shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                <h4 class="text-xs font-black text-slate-950 uppercase tracking-wider mb-2">Bukti yang Dikirimkan</h4>
                <div id="val-proof-container">
                    <!-- Dinamis terisi dari js -->
                </div>
            </div>

            <form action="{{ route('guru.missions.validate') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="user_id" id="val-user-id">
                <input type="hidden" name="mission_id" id="val-mission-id">

                <div>
                    <label class="block text-xs font-black text-slate-950 uppercase tracking-wider mb-1">Keputusan Validasi</label>
                    <select name="status" required class="w-full text-xs rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-slate-950 focus:outline-none bg-white font-bold">
                        <option value="approved">Setujui (Approve) - Berikan Poin</option>
                        <option value="revision">Minta Revisi (Revision) - Tulis Catatan</option>
                        <option value="rejected">Tolak Misi (Reject)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-950 uppercase tracking-wider mb-1">Catatan Evaluasi / Alasan</label>
                    <textarea name="notes" rows="2" class="w-full text-xs rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-slate-950 focus:outline-none bg-white font-bold" placeholder="Tulis catatan jika minta revisi atau menolak..."></textarea>
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <button class="uk-modal-close bg-white hover:bg-slate-100 text-slate-950 border-2 border-slate-950 text-xs font-black px-4 py-2.5 rounded-xl transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider" type="button">Batal</button>
                    <button class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white text-xs font-black px-5 py-2.5 rounded-xl border-2 border-slate-950 transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider" type="submit">Kirim Keputusan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL SESUAIKAN POIN MANUALL -->
    <div id="modal-adjust-points" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body bg-white border-4 border-slate-950 rounded-3xl p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-xl font-black text-slate-950 uppercase tracking-tight mb-4 flex items-center space-x-2">
                <span uk-icon="icon: plus-circle; ratio: 1.1"></span>
                <span>Sesuaikan Poin Karakter</span>
            </h2>
            <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-4">Berikan apresiasi poin atau kurangi poin siswa secara manual.</p>

            <div class="bg-slate-50 border-2 border-slate-950 rounded-xl p-3 mb-4 text-xs font-bold uppercase tracking-wider shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                <span class="text-slate-500">Siswa:</span>
                <strong id="adj-student-name" class="text-slate-950 font-black ml-1"></strong>
            </div>

            <form action="{{ route('guru.points.adjust') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="user_id" id="adj-user-id">
                <input type="hidden" name="type" id="adj-type" value="kebaikan">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-slate-950 uppercase tracking-wider mb-1">Operasi</label>
                        <select name="operation" id="adj-operation" required class="w-full text-xs rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-slate-950 focus:outline-none bg-white font-bold">
                            <option value="add">Tambah Poin (+)</option>
                            <option value="subtract">Kurang Poin (-)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-950 uppercase tracking-wider mb-1">Jumlah Poin</label>
                    <input type="number" name="amount" value="5" min="1" required class="w-full text-xs rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-slate-950 focus:outline-none bg-white font-bold">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-950 uppercase tracking-wider mb-1">Alasan Penyesuaian (Detail)</label>
                    <input type="text" name="description" required class="w-full text-xs rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-slate-950 focus:outline-none bg-white font-bold" placeholder="Contoh: Menolong merapikan ruang OSIS setelah rapat">
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <button class="uk-modal-close bg-white hover:bg-slate-100 text-slate-950 border-2 border-slate-950 text-xs font-black px-4 py-2.5 rounded-xl transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider" type="button">Batal</button>
                    <button class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white text-xs font-black px-5 py-2.5 rounded-xl border-2 border-slate-950 transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider" type="submit">Terapkan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL KELOLA LENCANA -->
    <div id="modal-manage-badges" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body bg-white border-4 border-slate-950 rounded-3xl p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-xl font-black text-slate-950 uppercase tracking-tight mb-4 flex items-center space-x-2">
                <span uk-icon="icon: star; ratio: 1.1"></span>
                <span>Kelola Lencana Siswa</span>
            </h2>
            <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-4">Semua lencana pencapaian khusus siswa dapat diberikan atau dicabut secara manual.</p>

            <div class="bg-slate-50 border-2 border-slate-950 rounded-xl p-3 mb-4 text-xs font-bold uppercase tracking-wider shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                <span class="text-slate-500">Siswa:</span>
                <strong id="badge-student-name" class="text-slate-950 font-black ml-1"></strong>
            </div>

            <form action="{{ route('guru.badges.toggle') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="user_id" id="badge-user-id">

                <div>
                    <label class="block text-xs font-black text-slate-950 uppercase tracking-wider mb-1">Aksi</label>
                    <select name="action" required class="w-full text-xs rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-slate-950 focus:outline-none bg-white font-bold">
                        <option value="award">Berikan Lencana (Award)</option>
                        <option value="revoke">Cabut Lencana (Revoke)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-950 uppercase tracking-wider mb-1">Pilih Lencana</label>
                    <select name="achievement_id" required class="w-full text-xs rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-slate-950 focus:outline-none bg-white font-bold">
                        @foreach($achievements as $badge)
                            <option value="{{ $badge->id }}">{{ $badge->title }} ({{ $badge->category }}) - {{ $badge->description }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <button class="uk-modal-close bg-white hover:bg-slate-100 text-slate-950 border-2 border-slate-950 text-xs font-black px-4 py-2.5 rounded-xl transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider" type="button">Batal</button>
                    <button class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white text-xs font-black px-5 py-2.5 rounded-xl border-2 border-slate-950 transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider" type="submit">Proses Lencana</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Javascript bindings for modals -->
    <script>


        document.addEventListener('DOMContentLoaded', () => {
            // Mission Validation Modal Populator
            document.querySelectorAll('.validate-mission-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    const missionId = this.getAttribute('data-mission-id');
                    const studentName = this.getAttribute('data-student-name');
                    const missionTitle = this.getAttribute('data-mission-title');
                    const pointsReward = this.getAttribute('data-points-reward');
                    const proofType = this.getAttribute('data-proof-type');
                    const proofUrl = this.getAttribute('data-proof-url');
                    const proofContent = this.getAttribute('data-proof-content');

                    document.getElementById('val-user-id').value = userId;
                    document.getElementById('val-mission-id').value = missionId;
                    document.getElementById('val-student-name').innerText = studentName;
                    document.getElementById('val-mission-title').innerText = missionTitle;
                    document.getElementById('val-points-reward').innerText = pointsReward + ' Pts';

                    const proofContainer = document.getElementById('val-proof-container');
                    let proofHtml = '';
                    if (proofType === 'none') {
                        proofHtml = '<span class="text-xs text-slate-500 font-medium">Tidak memerlukan bukti berkas/link.</span>';
                    } else {
                        if (proofUrl) {
                            proofHtml += `<div class="mb-2"><span class="text-xs font-bold text-slate-400 block">Tautan Bukti:</span> <a href="${proofUrl}" target="_blank" class="text-xs font-semibold text-indigo-600 hover:underline inline-flex items-center space-x-1 mt-1"><span>Buka Tautan</span> <span uk-icon="icon: link; ratio: 0.8"></span></a></div>`;
                        }
                        if (proofContent) {
                            proofHtml += `<div><span class="text-xs font-bold text-slate-400 block">Konten Bukti:</span> <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-700 whitespace-pre-line font-mono mt-1">${proofContent}</div></div>`;
                        }
                        if (!proofUrl && !proofContent) {
                            proofHtml = '<span class="text-xs text-rose-500 font-semibold">Bukti belum diunggah atau kosong.</span>';
                        }
                    }
                    proofContainer.innerHTML = proofHtml;

                    UIkit.modal('#modal-validate-mission').show();
                });
            });

            // Point Adjustment Modal Populator
            document.querySelectorAll('.adjust-points-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    const studentName = this.getAttribute('data-student-name');

                    document.getElementById('adj-user-id').value = userId;
                    document.getElementById('adj-student-name').innerText = studentName;
                    document.getElementById('adj-operation').value = 'add';
                    document.getElementById('adj-type').value = 'kebaikan';

                    UIkit.modal('#modal-adjust-points').show();
                });
            });



            // Manage Achievements Modal Populator
            document.querySelectorAll('.manage-badges-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    const studentName = this.getAttribute('data-student-name');

                    document.getElementById('badge-user-id').value = userId;
                    document.getElementById('badge-student-name').innerText = studentName;

                    UIkit.modal('#modal-manage-badges').show();
                });
            });
        });
    </script>
</x-app-layout>

