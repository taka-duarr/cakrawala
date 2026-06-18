<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('guru.dashboard') }}" class="text-xs font-black text-slate-950 hover:text-slate-700 flex items-center space-x-1 mb-2.5 uppercase tracking-wider">
                    <span uk-icon="icon: arrow-left; ratio: 0.8"></span>
                    <span>Kembali ke Dashboard</span>
                </a>
                <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">Detail Kelas & Mata Pelajaran</h2>
            </div>
            
            <div class="flex items-center space-x-3">
                <span class="bg-[#E4FF1A] border-2 border-slate-950 text-slate-950 text-xs font-black px-3 py-1.5 rounded-xl uppercase tracking-wider shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                    Kelas: {{ $assignment->classroom->name }}
                </span>
                <span class="bg-white border-2 border-slate-950 text-slate-950 text-xs font-black px-3 py-1.5 rounded-xl uppercase tracking-wider shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                    Hari: {{ $assignment->getDayTranslation() ?? '-' }} ({{ $assignment->start_time ? substr($assignment->start_time, 0, 5) . ' - ' . substr($assignment->end_time, 0, 5) : 'N/A' }})
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-100/30 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
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

            <!-- Subject Banner -->
            <div class="bg-[#E4FF1A] border-4 border-slate-950 rounded-3xl shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] p-8 text-slate-950 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider bg-slate-950 text-white px-2.5 py-0.5 rounded border border-slate-950 inline-block mb-2">
                        Mata Pelajaran (Subject)
                    </span>
                    <h1 class="text-3xl font-black uppercase tracking-tight text-slate-950 mb-1.5">{{ $assignment->subject->name }}</h1>
                    <p class="text-slate-800 text-xs max-w-xl font-bold uppercase tracking-wider leading-relaxed">
                        {{ $assignment->subject->description ?? 'Tidak ada deskripsi detail untuk mata pelajaran ini.' }}
                    </p>
                </div>
                <div class="bg-white border-2 border-slate-950 rounded-2xl p-5 min-w-[200px] text-right self-start md:self-auto shadow-[4px_4px_0px_0px_rgba(15,23,42,1)]">
                    <span class="text-[9px] text-slate-400 block uppercase font-bold tracking-wider mb-1">Kode Mapel</span>
                    <strong class="text-xl text-slate-950 font-black tracking-wider block">{{ $assignment->subject->code ?? 'N/A' }}</strong>
                    <span class="text-[9px] text-slate-400 block uppercase font-bold tracking-wider mt-2.5 mb-1">Semester</span>
                    <strong class="text-xs text-slate-950 font-black block uppercase tracking-wider">{{ $assignment->semester->name ?? '-' }} @if($assignment->semester) ({{ $assignment->semester->is_active ? 'Aktif' : 'Non-aktif' }}) @endif</strong>
                </div>
            </div>

            <!-- TAB SWITCHER -->
            <div class="border-b-4 border-slate-950">
                <nav class="flex space-x-6" aria-label="Tabs">
                    <button onclick="switchMainTab('kbm')" id="btn-tab-kbm" class="main-tab-btn py-3 px-1 font-black text-sm border-b-4 border-slate-950 text-slate-950 transition flex items-center space-x-2">
                        <span uk-icon="icon: grid; ratio: 0.85"></span>
                        <span>Jadwal & Pertemuan KBM</span>
                    </button>
                    <button onclick="switchMainTab('grading')" id="btn-tab-grading" class="main-tab-btn py-3 px-1 font-bold text-sm border-b-4 border-transparent text-slate-400 hover:text-slate-700 transition flex items-center space-x-2">
                        <span uk-icon="icon: file-text; ratio: 0.85"></span>
                        <span>Penilaian Tugas</span>
                        @if($assignmentSubmissions->count() > 0)
                            <span class="bg-[#E4FF1A] border border-slate-950 text-slate-950 text-[9px] font-black px-1.5 py-0.5 rounded shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                {{ $assignmentSubmissions->count() }}
                            </span>
                        @endif
                    </button>
                    <button onclick="switchMainTab('students')" id="btn-tab-students" class="main-tab-btn py-3 px-1 font-bold text-sm border-b-4 border-transparent text-slate-400 hover:text-slate-700 transition flex items-center space-x-2">
                        <span uk-icon="icon: users; ratio: 0.85"></span>
                        <span>Daftar Siswa & Rekap Nilai</span>
                    </button>
                </nav>
            </div>

            <!-- TAB 1: KBM CONTENT -->
            <div id="tab-content-kbm" class="main-tab-content space-y-6">
                <!-- 16 Meetings Grid Selection -->
                <div class="bg-white rounded-3xl border-4 border-slate-950 p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                    <h3 class="text-lg font-black text-slate-950 uppercase tracking-tight mb-1">Pilih Pertemuan KBM (Maks {{ $assignment->total_meetings ?? 16 }} Pertemuan)</h3>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-6">Pilih nomor pertemuan di bawah untuk membuka presensi, menambahkan materi pembelajaran, mengunggah tugas, atau memantau kehadiran siswa.</p>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-8 gap-4">
                        @for($i = 1; $i <= ($assignment->total_meetings ?? 16); $i++)
                            @php
                                $session = $sessions->firstWhere('meeting_number', $i);
                            @endphp
                            <button type="button" onclick="showMeeting({{ $i }})" id="btn-meeting-{{ $i }}" class="meeting-select-btn p-3.5 rounded-xl border-2 border-slate-950 text-center transition-all duration-200 {{ $session ? ($session->status === 'open' ? 'bg-[#EAFCEF] text-slate-950' : 'bg-[#FFEAEA] text-slate-950') : 'bg-white text-slate-400' }} shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                <div class="text-[9px] font-black uppercase tracking-wider">Pertemuan</div>
                                <div class="text-xl font-black mt-0.5">{{ $i }}</div>
                                <div class="text-[9px] font-black uppercase tracking-wider mt-1">
                                    @if($session)
                                        {{ $session->status === 'open' ? 'Aktif' : 'Selesai' }}
                                    @else
                                        Belum Buka
                                    @endif
                                </div>
                            </button>
                        @endfor
                    </div>
                </div>

                <!-- Meeting Panels Container -->
                @for($i = 1; $i <= ($assignment->total_meetings ?? 16); $i++)
                    @php
                        $session = $sessions->firstWhere('meeting_number', $i);
                    @endphp
                    <div id="meeting-panel-{{ $i }}" class="meeting-detail-panel hidden space-y-6">
                        
                        @if($session)
                            <!-- Session Detail Card -->
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                
                                <!-- Main Info & Action (Col 2) -->
                                <div class="lg:col-span-2 space-y-6">
                                    <div class="bg-white rounded-3xl border-4 border-slate-950 p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                                        <div class="flex items-center justify-between border-b-2 border-slate-950 pb-4 mb-4 flex-wrap gap-2">
                                            <div>
                                                <h3 class="text-lg font-black text-slate-950 uppercase tracking-tight">Sesi Pertemuan Ke-{{ $i }}</h3>
                                                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-0.5">Dibuka pada: {{ \Carbon\Carbon::parse($session->session_date)->translatedFormat('l, d F Y') }}</p>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="px-2.5 py-0.5 rounded border border-slate-950 text-[10px] font-black uppercase tracking-wider {{ $session->status === 'open' ? 'bg-[#EAFCEF] text-emerald-800 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' : 'bg-slate-100 text-slate-600' }}">
                                                    Sesi {{ $session->status === 'open' ? 'Aktif / Terbuka' : 'Selesai / Ditutup' }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                            <div class="bg-slate-50 border-2 border-slate-950 rounded-xl p-3.5 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                                <span class="text-[9px] text-slate-400 font-black uppercase tracking-wider block">Mode Absensi</span>
                                                <strong class="text-xs text-slate-950 font-black uppercase tracking-wider mt-1 block">
                                                    {{ $session->mode === 'qr_location' ? 'Scan QR & Cek Lokasi' : 'Klik Hadir & Cek Lokasi' }}
                                                </strong>
                                            </div>
                                            <div class="bg-slate-50 border-2 border-slate-950 rounded-xl p-3.5 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                                <span class="text-[9px] text-slate-400 font-black uppercase tracking-wider block">Lokasi Presensi</span>
                                                <strong class="text-xs text-slate-950 font-black uppercase tracking-wider mt-1 block">
                                                    {{ $session->schoolLocation->name ?? 'Semua Lokasi Aktif' }}
                                                </strong>
                                            </div>
                                            <div class="bg-slate-50 border-2 border-slate-950 rounded-xl p-3.5 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                                <span class="text-[9px] text-slate-400 font-black uppercase tracking-wider block">Tenggat Absen</span>
                                                <strong class="text-xs text-rose-600 font-black uppercase tracking-wider mt-1 block">
                                                    {{ \Carbon\Carbon::parse($session->deadline)->translatedFormat('d F Y, H:i') }} WIB
                                                </strong>
                                            </div>
                                        </div>

                                        @if($session->status === 'open')
                                            <div class="bg-slate-50 border-2 border-slate-950 rounded-2xl p-5 flex flex-col md:flex-row items-center justify-between gap-4 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)]">
                                                <div class="space-y-1 text-center md:text-left">
                                                    <span class="text-xs font-black text-slate-950 uppercase tracking-tight block">Kode QR Presensi Pertemuan {{ $i }}</span>
                                                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Siswa dapat melakukan scan QR link di bawah dari browser HP untuk mencatat kehadiran mereka.</p>
                                                    <a href="{{ route('student.sessions.scan-page', $session->qr_token) }}" target="_blank" class="text-xs font-black text-indigo-600 hover:text-indigo-800 hover:underline inline-flex items-center space-x-1 mt-2">
                                                        <span>Buka Halaman Scan QR Presensi</span>
                                                        <span uk-icon="icon: link; ratio: 0.75"></span>
                                                    </a>
                                                </div>
                                                
                                                <form action="{{ route('guru.sessions.close', $session->id) }}" method="POST" class="w-full md:w-auto">
                                                    @csrf
                                                    <button type="submit" class="w-full md:w-auto px-5 py-2.5 bg-rose-600 hover:bg-slate-950 text-white border-2 border-slate-950 text-xs font-black rounded-xl shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider flex items-center justify-center space-x-1.5">
                                                        <span uk-icon="icon: lock; ratio: 0.85"></span>
                                                        <span>Tutup Sesi Absensi</span>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="bg-slate-100 text-slate-500 rounded-xl p-4 text-xs font-black uppercase tracking-wider text-center border-2 border-dashed border-slate-950">
                                                Sesi absensi pertemuan ini sudah ditutup. Siswa tidak dapat melakukan presensi lagi.
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Materials & Assignments Card -->
                                    <div class="bg-white rounded-3xl border-4 border-slate-950 p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                                        <div class="flex justify-between items-center border-b-2 border-slate-950 pb-3.5 mb-4">
                                            <h4 class="font-black text-slate-950 uppercase tracking-tight text-sm">Bahan Pembelajaran & Tugas</h4>
                                            <div class="flex items-center space-x-1.5">
                                                <button onclick="openMaterialModal({{ $session->id }}, '{{ $i }}')" class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white text-[10px] font-black px-3 py-1.5 rounded-lg border-2 border-slate-950 transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] flex items-center space-x-1 uppercase tracking-wider">
                                                    <span uk-icon="icon: plus; ratio: 0.7"></span>
                                                    <span>Materi</span>
                                                </button>
                                                <button onclick="openAssignmentModal({{ $session->id }}, '{{ $i }}')" class="bg-white hover:bg-slate-100 text-slate-950 border-2 border-slate-950 text-[10px] font-black px-3 py-1.5 rounded-lg transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] flex items-center space-x-1 uppercase tracking-wider">
                                                    <span uk-icon="icon: plus; ratio: 0.7"></span>
                                                    <span>Tugas</span>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <!-- Materials Column -->
                                            <div class="space-y-3">
                                                <h5 class="text-xs font-black text-slate-950 uppercase tracking-wider mb-2 flex items-center space-x-1.5">
                                                    <span uk-icon="icon: copy; ratio: 0.75" class="text-slate-950"></span>
                                                    <span>Materi Pembelajaran</span>
                                                </h5>
                                                @forelse($session->materials as $mat)
                                                    <div class="bg-slate-50 border-2 border-slate-950 rounded-xl p-3 flex items-center justify-between shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                                        <div class="truncate mr-3">
                                                            <span class="text-xs font-black text-slate-950 block truncate uppercase tracking-tight">{{ $mat->title }}</span>
                                                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">PDF/Materi File</span>
                                                        </div>
                                                        <a href="{{ $mat->file_path }}" target="_blank" class="p-1.5 text-slate-950 hover:bg-[#E4FF1A] border-2 border-slate-950 rounded-lg transition shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]" title="Unduh Materi">
                                                            <span uk-icon="icon: download; ratio: 0.8"></span>
                                                        </a>
                                                    </div>
                                                @empty
                                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider italic">Belum ada file materi pembelajaran yang diunggah untuk pertemuan ini.</p>
                                                @endforelse
                                            </div>

                                            <!-- Assignments Column -->
                                            <div class="space-y-3">
                                                <h5 class="text-xs font-black text-slate-950 uppercase tracking-wider mb-2 flex items-center space-x-1.5">
                                                    <span uk-icon="icon: file-edit; ratio: 0.75" class="text-slate-950"></span>
                                                    <span>Tugas Pertemuan</span>
                                                </h5>
                                                @forelse($session->assignments as $asg)
                                                    <div class="bg-white border-2 border-slate-950 rounded-xl p-4 space-y-2.5 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                                                        <div class="flex justify-between items-start">
                                                            <div>
                                                                <h6 class="text-xs font-black text-slate-950 uppercase tracking-tight">{{ $asg->title }}</h6>
                                                                <span class="text-[9px] text-emerald-700 font-black inline-block uppercase tracking-wider bg-[#EAFCEF] border border-slate-950 px-1.5 py-0.5 rounded mt-1">+{{ $asg->points_reward }} Pts</span>
                                                            </div>
                                                            @if($asg->file_path)
                                                                <a href="{{ $asg->file_path }}" target="_blank" class="p-1.5 text-slate-950 hover:bg-[#E4FF1A] border-2 border-slate-950 rounded-lg transition shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]" title="Unduh Lampiran Tugas">
                                                                    <span uk-icon="icon: download; ratio: 0.75"></span>
                                                                </a>
                                                            @endif
                                                        </div>
                                                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider leading-normal">{{ $asg->description }}</p>
                                                        <div class="text-[9px] text-rose-700 font-black border-t-2 border-slate-950 pt-2 flex items-center space-x-1 uppercase tracking-wider">
                                                            <span uk-icon="icon: clock; ratio: 0.7"></span>
                                                            <span>Tenggat: {{ \Carbon\Carbon::parse($asg->deadline)->translatedFormat('d F, H:i') }} WIB</span>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider italic">Belum ada tugas untuk pertemuan ini.</p>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Attendance Statistics List (Col 1) -->
                                <div class="bg-white rounded-3xl border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] lg:col-span-1">
                                    <div class="p-6 border-b-4 border-slate-950 bg-[#E4FF1A]/10">
                                        <h4 class="font-black text-slate-950 uppercase tracking-tight text-sm">Kehadiran Siswa</h4>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">Hadir: {{ $session->present_count }} / {{ $students->count() }} Siswa</p>
                                    </div>
                                    
                                    <div class="divide-y divide-slate-950 max-h-[480px] overflow-y-auto bg-white">
                                        @foreach($students as $student)
                                            @php
                                                $att = $session->attendances->firstWhere('student_id', $student->id);
                                            @endphp
                                            <div class="p-4 flex items-center justify-between text-xs hover:bg-slate-50 transition">
                                                <div class="truncate mr-3">
                                                    <span class="font-black text-slate-950 block truncate uppercase tracking-tight">{{ $student->name }}</span>
                                                    @if($att && $att->status === 'hadir')
                                                        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block mt-0.5">
                                                            {{ \Carbon\Carbon::parse($att->created_at)->format('H:i') }} · Jarak: {{ $att->distance_meters ?? 0 }}m
                                                        </span>
                                                    @else
                                                        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block mt-0.5">Belum absen</span>
                                                    @endif
                                                </div>
                                                
                                                <span class="px-2 py-0.5 rounded border border-slate-950 text-[9px] font-black uppercase tracking-wider {{ $att && $att->status === 'hadir' ? 'bg-[#EAFCEF] text-emerald-800' : 'bg-slate-100 text-slate-400' }}">
                                                    {{ $att && $att->status === 'hadir' ? 'Hadir' : 'Alpa' }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Placeholder Belum Dibuka Card -->
                            <div class="bg-white border-4 border-slate-950 rounded-3xl p-12 text-center max-w-xl mx-auto space-y-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                                <div class="w-16 h-16 bg-[#FFEAEA] border-2 border-slate-950 text-slate-950 rounded-2xl flex items-center justify-center mx-auto shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                                    <span uk-icon="icon: lock; ratio: 1.5"></span>
                                </div>
                                <div class="space-y-2">
                                    <h3 class="text-xl font-black text-slate-950 uppercase tracking-tight">Pertemuan Ke-{{ $i }} Belum Dibuka</h3>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider leading-relaxed">Buka sesi presensi untuk mengizinkan siswa mencatat kehadiran mereka pada jam pelajaran ini serta bagikan materi pelajaran.</p>
                                </div>
                                <button onclick="openOpenSessionModal({{ $i }})" class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white text-xs font-black px-6 py-3.5 rounded-xl border-2 border-slate-950 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider inline-flex items-center space-x-1.5">
                                    <span uk-icon="icon: plus; ratio: 0.8"></span>
                                    <span>Buka Sesi Presensi Pertemuan {{ $i }}</span>
                                </button>
                            </div>
                        @endif

                    </div>
                @endfor

            </div>

            <!-- TAB 2: GRADING CONTENT -->
            <div id="tab-content-grading" class="main-tab-content space-y-6 hidden">
                <div class="bg-white rounded-3xl border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                    <div class="p-6 border-b-4 border-slate-950 bg-[#E4FF1A]/10">
                        <h3 class="text-lg font-black text-slate-950 uppercase tracking-tight">Tinjau Pengumpulan Tugas</h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-0.5">Siswa yang telah mengumpulkan tugas di mata pelajaran ini dan memerlukan penilaian/validasi nilai.</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-950 text-white border-b-2 border-slate-950">
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider">Siswa</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider">Judul Tugas</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-center">Hadiah Poin</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider">Berkas/Jawaban</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-950">
                                @forelse($assignmentSubmissions as $sub)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-black text-slate-950 text-xs uppercase tracking-tight">{{ $sub->student->name }}</div>
                                            <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Diserahkan: {{ \Carbon\Carbon::parse($sub->updated_at)->translatedFormat('d M, H:i') }} WIB</div>
                                        </td>
                                        <td class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-tight">
                                            {{ $sub->assignment->title }}
                                        </td>
                                        <td class="px-6 py-4 text-center font-black text-xs text-slate-950">
                                            +{{ $sub->assignment->points_reward }} Pts
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($sub->file_path)
                                                <a href="{{ $sub->file_path }}" target="_blank" class="text-xs font-black text-indigo-650 hover:text-indigo-850 hover:underline flex items-center space-x-1 uppercase tracking-wider">
                                                    <span uk-icon="icon: link; ratio: 0.75"></span>
                                                    <span>Unduh Berkas</span>
                                                </a>
                                            @endif
                                            @if($sub->text_content)
                                                <p class="text-[10px] text-slate-500 italic mt-1 font-mono max-w-xs truncate">{{ $sub->text_content }}</p>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button onclick="openGradeModal({{ json_encode($sub) }}, '{{ $sub->student->name }}', '{{ $sub->assignment->title }}')" class="px-3 py-1.5 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white text-[10px] font-black rounded-lg border-2 border-slate-950 transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] inline-flex items-center space-x-1 uppercase tracking-wider">
                                                <span>Beri Nilai</span>
                                                <span uk-icon="icon: check; ratio: 0.75"></span>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-12 text-slate-400 text-xs font-bold uppercase tracking-wider bg-slate-50/20">Tidak ada tugas siswa yang menunggu penilaian saat ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 3: STUDENTS CONTENT -->
            <div id="tab-content-students" class="main-tab-content space-y-6 hidden">
                <div class="bg-white rounded-3xl border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                    <div class="p-6 border-b-4 border-slate-950 bg-[#E4FF1A]/10">
                        <div>
                            <h3 class="text-lg font-black text-slate-950 uppercase tracking-tight">Rekapitulasi Siswa & Nilai</h3>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-0.5">Daftar siswa beserta poin akademik dan data kehadiran mereka.</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-950 text-white border-b-2 border-slate-950">
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-center w-16">No</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider">Nama Siswa</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-center">Tingkat</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-center">Kehadiran (Hadir)</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-right">Total Poin</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-center w-32">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-950">
                                @forelse($students as $index => $student)
                                    @php
                                        // Count presence for active teaching assignment sessions
                                        $presenceCount = \App\Models\Attendance::whereIn('attendance_session_id', $sessions->pluck('id'))->where('student_id', $student->id)->where('status', 'hadir')->count();
                                    @endphp
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-4 text-center text-slate-400 font-black text-xs">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 bg-white border-2 border-slate-950 rounded-full flex items-center justify-center font-black text-slate-950 text-xs shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                                    {{ substr($student->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="font-black text-slate-950 text-xs uppercase tracking-tight mb-0.5">{{ $student->name }}</div>
                                                    <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">{{ $student->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-2.5 py-0.5 rounded border border-slate-950 text-[10px] font-black bg-white text-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                                {{ $student->current_level ?? 'Pemula' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center text-xs font-black text-slate-950 uppercase tracking-wider">
                                            {{ $presenceCount }} Pertemuan
                                        </td>
                                        <td class="px-6 py-4 text-right font-black text-xs text-slate-950">
                                            {{ number_format($student->points) }} Pts
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <button 
                                                data-user-id="{{ $student->id }}"
                                                data-student-name="{{ $student->name }}"
                                                class="adjust-points-btn bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white border-2 border-slate-950 text-[10px] font-black px-2.5 py-1.5 rounded-lg transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] flex items-center justify-center space-x-1 mx-auto uppercase tracking-wider"
                                            >
                                                <span uk-icon="icon: plus; ratio: 0.65"></span>
                                                <span>Poin</span>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-12 text-slate-400 text-xs font-bold uppercase tracking-wider bg-slate-50/20">Belum ada siswa di kelas ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>



    <!-- MODAL 1: BUKA SESI PRESENSI -->
    <div id="modal-open-session" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-3xl border-4 border-slate-950 p-6 bg-white shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]" style="width: 650px; max-width: calc(100% - 2rem);">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h3 class="text-lg font-black text-slate-950 mb-4 uppercase tracking-tight" id="open-session-title">Buka Sesi Presensi</h3>
            
            <form action="{{ route('guru.sessions.store', $assignment->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="meeting_number" id="form-meeting-number">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-slate-950 mb-1.5 uppercase tracking-wider">Tanggal Pertemuan</label>
                        <input type="date" name="session_date" id="form-session-date" required class="w-full text-xs font-bold rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-0 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-950 mb-1.5 uppercase tracking-wider">Batas Waktu Presensi</label>
                        <input type="datetime-local" name="deadline" id="form-deadline" required class="w-full text-xs font-bold rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-0 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-slate-950 mb-1.5 uppercase tracking-wider">Mode Presensi</label>
                        <select name="mode" required class="w-full text-xs font-bold rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-0 focus:outline-none pr-8">
                            <option value="qr_location">Scan QR & Cek Lokasi</option>
                            <option value="button_location">Klik Hadir & Cek Lokasi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-950 mb-1.5 uppercase tracking-wider">Lokasi Presensi (Geofencing)</label>
                        <select name="school_location_id" class="w-full text-xs font-bold rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-0 focus:outline-none pr-8">
                            <option value="">Semua Lokasi Aktif</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Bagian Materi Pembelajaran -->
                <div class="border-t-2 border-slate-950 pt-4 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-black text-slate-950 uppercase tracking-wider flex items-center space-x-1">
                            <span uk-icon="icon: copy; ratio: 0.75"></span>
                            <span>Materi Pembelajaran (Opsional)</span>
                        </span>
                        <button type="button" onclick="addMaterialInput()" class="bg-white hover:bg-slate-100 text-slate-950 border-2 border-slate-950 text-[10px] font-black px-2.5 py-1 rounded-lg transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] flex items-center space-x-1 uppercase tracking-wider">
                            <span uk-icon="icon: plus; ratio: 0.65"></span>
                            <span>Tambah Materi</span>
                        </button>
                    </div>
                    <div id="material-inputs-container" class="space-y-2">
                        <div id="no-materials-placeholder" class="text-[11px] text-slate-400 font-bold uppercase tracking-wider italic py-1">
                            Belum ada materi ditambahkan. Klik "+ Tambah Materi" di kanan untuk mengunggah materi pelajaran.
                        </div>
                    </div>
                </div>

                <!-- Bagian Tugas Kelas -->
                <div class="border-t-2 border-slate-950 pt-4 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-black text-slate-950 uppercase tracking-wider flex items-center space-x-1">
                            <span uk-icon="icon: file-edit; ratio: 0.75"></span>
                            <span>Tugas Kelas (Opsional)</span>
                        </span>
                        <button type="button" onclick="addAssignmentInput()" class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white border-2 border-slate-950 text-[10px] font-black px-2.5 py-1 rounded-lg transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] flex items-center space-x-1 uppercase tracking-wider">
                            <span uk-icon="icon: plus; ratio: 0.65"></span>
                            <span>Tambah Tugas</span>
                        </button>
                    </div>
                    <div id="assignment-inputs-container" class="space-y-3">
                        <div id="no-assignments-placeholder" class="text-[11px] text-slate-400 font-bold uppercase tracking-wider italic py-1">
                            Belum ada tugas ditambahkan. Klik "+ Tambah Tugas" di kanan untuk menambahkan tugas.
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4 border-t-2 border-slate-950">
                    <button class="uk-modal-close border-2 border-slate-950 text-slate-950 hover:bg-slate-100 text-xs font-black px-4 py-2 rounded-xl transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider" type="button">Batal</button>
                    <button class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white border-2 border-slate-950 text-xs font-black px-5 py-2 rounded-xl transition shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider" type="submit">Buka Sesi Presensi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: TAMBAH MATERI PEMBELAJARAN KE SESI AKTIF -->
    <div id="modal-add-material-existing" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-3xl border-4 border-slate-950 p-6 bg-white shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h3 class="text-lg font-black text-slate-950 mb-4 uppercase tracking-tight" id="add-material-existing-title">Tambah Materi Pembelajaran</h3>
            
            <form id="form-add-material-existing" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-black text-slate-950 mb-1.5 uppercase tracking-wider">Judul Materi</label>
                    <input type="text" name="title" required placeholder="Contoh: Bab 2 Larutan Elektrolit" class="w-full text-xs font-bold rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-0 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-950 mb-1.5 uppercase tracking-wider">File Dokumen (PDF, PPT, DOCX, dll)</label>
                    <input type="file" name="file" required class="w-full text-xs font-bold text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-2 file:border-slate-950 file:text-[10px] file:font-black file:bg-white file:text-slate-950 hover:file:bg-slate-100">
                </div>
                
                <div class="flex justify-end space-x-3 pt-4 border-t-2 border-slate-950">
                    <button class="uk-modal-close border-2 border-slate-950 text-slate-950 hover:bg-slate-100 text-xs font-black px-4 py-2 rounded-xl transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider" type="button">Batal</button>
                    <button class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white border-2 border-slate-950 text-xs font-black px-5 py-2 rounded-xl transition shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider" type="submit">Simpan Materi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 3: TAMBAH TUGAS KE SESI AKTIF -->
    <div id="modal-add-assignment-existing" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-3xl border-4 border-slate-950 p-6 bg-white shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]" style="width: 580px; max-width: calc(100% - 2rem);">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h3 class="text-lg font-black text-slate-950 mb-4 uppercase tracking-tight" id="add-assignment-existing-title">Tambah Tugas Baru</h3>
            
            <form id="form-add-assignment-existing" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-slate-950 mb-1.5 uppercase tracking-wider">Judul Tugas</label>
                        <input type="text" name="title" required placeholder="Contoh: Latihan 2.1 Kalorimeter" class="w-full text-xs font-bold rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-0 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-950 mb-1.5 uppercase tracking-wider">Poin Hadiah Tugas</label>
                        <input type="number" name="points_reward" value="15" min="1" required class="w-full text-xs font-bold rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-0 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-950 mb-1.5 uppercase tracking-wider">Deskripsi Tugas & Panduan</label>
                    <textarea name="description" rows="3" required placeholder="Tulis instruksi lengkap tugas..." class="w-full text-xs font-bold rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-0 focus:outline-none"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-slate-950 mb-1.5 uppercase tracking-wider">Batas Tenggat Pengumpulan</label>
                        <input type="datetime-local" name="deadline" required class="w-full text-xs font-bold rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-0 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-950 mb-1.5 uppercase tracking-wider">Lampiran Soal/Materi (Opsional)</label>
                        <input type="file" name="file" class="w-full text-xs font-bold text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-2 file:border-slate-950 file:text-[10px] file:font-black file:bg-white file:text-slate-950 hover:file:bg-slate-100">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4 border-t-2 border-slate-950">
                    <button class="uk-modal-close border-2 border-slate-950 text-slate-950 hover:bg-slate-100 text-xs font-black px-4 py-2 rounded-xl transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider" type="button">Batal</button>
                    <button class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white border-2 border-slate-950 text-xs font-black px-5 py-2 rounded-xl transition shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider" type="submit">Tambah Tugas</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 4: BERI NILAI TUGAS SISWA -->
    <div id="modal-grade-submission" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-3xl border-4 border-slate-950 p-6 bg-white shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-black text-slate-950 mb-4 uppercase tracking-tight">Penilaian Tugas Siswa</h2>
            
            <div class="bg-slate-50 border-2 border-slate-950 rounded-2xl p-4 mb-4 space-y-2 text-xs shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                <div class="flex justify-between">
                    <span class="text-slate-400 font-bold uppercase tracking-wider">Siswa:</span>
                    <strong id="grade-student-name" class="text-slate-950 font-black uppercase tracking-tight"></strong>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400 font-bold uppercase tracking-wider">Tugas:</span>
                    <strong id="grade-assignment-title" class="text-slate-950 font-black uppercase tracking-tight"></strong>
                </div>
            </div>

            <form id="form-grade-submission" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-black text-slate-950 mb-1.5 uppercase tracking-wider">Keputusan Penilaian</label>
                    <select name="status" required class="w-full text-xs font-bold rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-0 focus:outline-none pr-8">
                        <option value="approved">Setujui (Approve) - Berikan Poin Tugas</option>
                        <option value="revision">Minta Revisi (Revision) - Tulis Komentar</option>
                        <option value="rejected">Tolak Tugas (Reject)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-950 mb-1.5 uppercase tracking-wider">Komentar / Feedback</label>
                    <textarea name="notes" rows="2" class="w-full text-xs font-bold rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-0 focus:outline-none" placeholder="Tulis catatan jika minta revisi atau memberikan apresiasi..."></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t-2 border-slate-950">
                    <button class="uk-modal-close border-2 border-slate-950 text-slate-950 hover:bg-slate-100 text-xs font-black px-4 py-2 rounded-xl transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider" type="button">Batal</button>
                    <button class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white border-2 border-slate-950 text-xs font-black px-5 py-2 rounded-xl transition shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider" type="submit">Kirim Penilaian</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL SESUAIKAN POIN MANUALL -->
    <div id="modal-adjust-points" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-3xl border-4 border-slate-950 p-6 bg-white shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-black text-slate-950 mb-1 uppercase tracking-tight flex items-center space-x-2">
                <span uk-icon="icon: plus-circle; ratio: 1.1"></span>
                <span>Sesuaikan Poin Karakter</span>
            </h2>
            <p class="text-[10px] text-slate-400 mb-4 font-bold uppercase tracking-wider">Berikan apresiasi poin atau kurangi poin siswa secara manual.</p>

            <div class="bg-slate-50 border-2 border-slate-950 rounded-2xl p-3 mb-4 text-xs shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                <span class="text-slate-400 font-bold uppercase tracking-wider">Siswa:</span>
                <strong id="adj-student-name" class="text-slate-950 font-black uppercase tracking-tight ml-1"></strong>
            </div>

            <form action="{{ route('guru.points.adjust') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="user_id" id="adj-user-id">
                <input type="hidden" name="type" id="adj-type" value="kebaikan">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-slate-950 mb-1.5 uppercase tracking-wider">Operasi</label>
                        <select name="operation" id="adj-operation" required class="w-full text-xs font-bold rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-0 focus:outline-none pr-8">
                            <option value="add">Tambah Poin (+)</option>
                            <option value="subtract">Kurang Poin (-)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-950 mb-1.5 uppercase tracking-wider">Jumlah Poin</label>
                    <input type="number" name="amount" value="5" min="1" required class="w-full text-xs font-bold rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-0 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-950 mb-1.5 uppercase tracking-wider">Alasan Penyesuaian (Detail)</label>
                    <input type="text" name="description" required class="w-full text-xs font-bold rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-0 focus:outline-none" placeholder="Contoh: Keaktifan di kelas saat diskusi">
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t-2 border-slate-950">
                    <button class="uk-modal-close border-2 border-slate-950 text-slate-950 hover:bg-slate-100 text-xs font-black px-4 py-2 rounded-xl transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider" type="button">Batal</button>
                    <button class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white border-2 border-slate-950 text-xs font-black px-5 py-2 rounded-xl transition shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider" type="submit">Terapkan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        // Tab Switcher
        function switchMainTab(tabName) {
            document.querySelectorAll('.main-tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.main-tab-btn').forEach(btn => {
                btn.className = 'main-tab-btn py-3 px-1 font-bold text-sm border-b-4 border-transparent text-slate-400 hover:text-slate-700 transition flex items-center space-x-2';
            });

            document.getElementById('tab-content-' + tabName).classList.remove('hidden');
            document.getElementById('btn-tab-' + tabName).className = 'main-tab-btn py-3 px-1 font-black text-sm border-b-4 border-slate-950 text-slate-950 transition flex items-center space-x-2';
        }

        // Meeting details toggle
        let currentMeetingNumber = null;
        function showMeeting(num) {
            currentMeetingNumber = num;
            
            // Hide all panels
            document.querySelectorAll('.meeting-detail-panel').forEach(p => p.classList.add('hidden'));
            
            // Reset button classes
            document.querySelectorAll('.meeting-select-btn').forEach(btn => {
                btn.classList.remove('ring-4', 'ring-slate-950', 'scale-[1.03]');
            });

            // Show current panel
            const panel = document.getElementById('meeting-panel-' + num);
            if (panel) {
                panel.classList.remove('hidden');
            }

            // Highlight selected button
            const selectedBtn = document.getElementById('btn-meeting-' + num);
            if (selectedBtn) {
                selectedBtn.classList.add('ring-4', 'ring-slate-950', 'scale-[1.03]');
            }
        }

        // Open Session Modal Populator
        function openOpenSessionModal(num) {
            document.getElementById('form-meeting-number').value = num;
            document.getElementById('open-session-title').innerText = 'Buka Sesi Presensi Pertemuan ke-' + num;
            
            // Set date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('form-session-date').value = today;
            
            // Set deadline to today + 2 hours
            const now = new Date();
            now.setHours(now.getHours() + 2);
            const offset = now.getTimezoneOffset() * 60000;
            const localISOTime = (new Date(now.getTime() - offset)).toISOString().slice(0, 16);
            document.getElementById('form-deadline').value = localISOTime;

            UIkit.modal('#modal-open-session').show();
        }

        // Dynamic material fields in open session modal
        function addMaterialInput() {
            const placeholder = document.getElementById('no-materials-placeholder');
            if (placeholder) {
                placeholder.remove();
            }
            const container = document.getElementById('material-inputs-container');
            const newRow = document.createElement('div');
            newRow.className = 'grid grid-cols-1 md:grid-cols-2 gap-3 material-row pt-2 border-t-2 border-slate-950';
            newRow.innerHTML = `
                <div>
                    <input type="text" name="material_titles[]" placeholder="Judul Materi" class="w-full text-xs font-bold rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-0 focus:outline-none">
                </div>
                <div class="flex items-center space-x-2">
                    <input type="file" name="materials[]" class="w-full text-xs font-bold text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-2 file:border-slate-950 file:text-[10px] file:font-black file:bg-white file:text-slate-950 hover:file:bg-slate-100">
                    <button type="button" onclick="removeMaterialRow(this)" class="text-rose-500 hover:text-rose-700 p-1 flex items-center justify-center">
                        <span uk-icon="icon: trash; ratio: 0.8"></span>
                    </button>
                </div>
            `;
            container.appendChild(newRow);
        }

        // Remove material row
        function removeMaterialRow(button) {
            button.closest('.material-row').remove();
            const container = document.getElementById('material-inputs-container');
            if (container.querySelectorAll('.material-row').length === 0) {
                if (!document.getElementById('no-materials-placeholder')) {
                    const placeholder = document.createElement('div');
                    placeholder.id = 'no-materials-placeholder';
                    placeholder.className = 'text-[11px] text-slate-400 font-bold uppercase tracking-wider italic py-1';
                    placeholder.innerText = 'Belum ada materi ditambahkan. Klik "+ Tambah Materi" di kanan untuk mengunggah materi pelajaran.';
                    container.appendChild(placeholder);
                }
            }
        }

        // Dynamic assignment fields in open session modal
        function addAssignmentInput() {
            const placeholder = document.getElementById('no-assignments-placeholder');
            if (placeholder) {
                placeholder.remove();
            }
            const container = document.getElementById('assignment-inputs-container');
            const index = container.querySelectorAll('.assignment-card').length;
            
            const card = document.createElement('div');
            card.className = 'assignment-card p-4 rounded-2xl border-2 border-slate-950 bg-slate-50/50 space-y-3 relative shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]';
            
            // Set default deadline to today + 24 hours
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const offset = tomorrow.getTimezoneOffset() * 60000;
            const defaultDeadline = (new Date(tomorrow.getTime() - offset)).toISOString().slice(0, 16);

            card.innerHTML = `
                <div class="flex justify-between items-center pb-2 border-b-2 border-slate-950">
                    <span class="text-xs font-black text-slate-950 uppercase tracking-wider">Tugas #${index + 1}</span>
                    <button type="button" onclick="removeAssignmentCard(this)" class="text-rose-500 hover:text-rose-700 flex items-center space-x-0.5 text-[10px] font-black uppercase tracking-wider">
                        <span uk-icon="icon: trash; ratio: 0.75"></span>
                        <span>Hapus</span>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[9px] font-black text-slate-950 mb-1 uppercase tracking-wider">Judul Tugas</label>
                        <input type="text" name="assignment_titles[]" placeholder="Contoh: Latihan Soal Logika" required class="w-full text-xs font-bold rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-0 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[9px] font-black text-slate-950 mb-1 uppercase tracking-wider">Poin Hadiah Tugas</label>
                        <input type="number" name="assignment_points[]" value="15" min="1" required class="w-full text-xs font-bold rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-0 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-[9px] font-black text-slate-950 mb-1 uppercase tracking-wider">Deskripsi & Instruksi Tugas</label>
                    <textarea name="assignment_descriptions[]" rows="2" placeholder="Tulis instruksi tugas di sini..." required class="w-full text-xs font-bold rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-0 focus:outline-none"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[9px] font-black text-slate-950 mb-1 uppercase tracking-wider">Lampiran Tugas (File - Optional)</label>
                        <input type="file" name="assignment_files[]" class="w-full text-xs font-bold text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-2 file:border-slate-950 file:text-[10px] file:font-black file:bg-white file:text-slate-950 hover:file:bg-slate-100">
                    </div>
                    <div>
                        <label class="block text-[9px] font-black text-slate-950 mb-1 uppercase tracking-wider">Batas Tenggat Tugas</label>
                        <input type="datetime-local" name="assignment_deadlines[]" value="${defaultDeadline}" required class="w-full text-xs font-bold rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-0 focus:outline-none">
                    </div>
                </div>
            `;
            container.appendChild(card);
            
            reindexAssignmentCards();
        }

        // Remove assignment card
        function removeAssignmentCard(button) {
            button.closest('.assignment-card').remove();
            const container = document.getElementById('assignment-inputs-container');
            if (container.querySelectorAll('.assignment-card').length === 0) {
                if (!document.getElementById('no-assignments-placeholder')) {
                    const placeholder = document.createElement('div');
                    placeholder.id = 'no-assignments-placeholder';
                    placeholder.className = 'text-[11px] text-slate-400 font-bold uppercase tracking-wider italic py-1';
                    placeholder.innerText = 'Belum ada tugas ditambahkan. Klik "+ Tambah Tugas" di kanan untuk menambahkan tugas.';
                    container.appendChild(placeholder);
                }
            } else {
                reindexAssignmentCards();
            }
        }

        // Reindex assignment card labels
        function reindexAssignmentCards() {
            const cards = document.querySelectorAll('#assignment-inputs-container .assignment-card');
            cards.forEach((card, index) => {
                const badge = card.querySelector('.text-slate-950.uppercase.tracking-wider');
                if (badge) {
                    badge.innerText = 'Tugas #' + (index + 1);
                }
            });
        }

        // Open Material Modal (existing session)
        function openMaterialModal(sessionId, num) {
            document.getElementById('add-material-existing-title').innerText = 'Tambah Materi Pembelajaran Pertemuan ke-' + num;
            document.getElementById('form-add-material-existing').action = `/guru/sessions/${sessionId}/materials/store`;
            UIkit.modal('#modal-add-material-existing').show();
        }

        // Open Assignment Modal (existing session)
        function openAssignmentModal(sessionId, num) {
            document.getElementById('add-assignment-existing-title').innerText = 'Tambah Tugas Pertemuan ke-' + num;
            document.getElementById('form-add-assignment-existing').action = `/guru/sessions/${sessionId}/assignments/store`;
            
            // Pre-fill deadline to +24 hours
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const offset = tomorrow.getTimezoneOffset() * 60000;
            const localISOTime = (new Date(tomorrow.getTime() - offset)).toISOString().slice(0, 16);
            document.getElementById('form-add-assignment-existing').querySelector('[name="deadline"]').value = localISOTime;

            UIkit.modal('#modal-add-assignment-existing').show();
        }

        // Open Grade Submission Modal
        function openGradeModal(submission, studentName, assignmentTitle) {
            document.getElementById('grade-student-name').innerText = studentName;
            document.getElementById('grade-assignment-title').innerText = assignmentTitle;
            document.getElementById('form-grade-submission').action = `/guru/submissions/${submission.id}/grade`;
            UIkit.modal('#modal-grade-submission').show();
        }

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

        // Auto select first meeting on load
        document.addEventListener('DOMContentLoaded', () => {
            // Check if there is an active session to highlight
            let activeMeeting = null;
            @foreach($sessions as $sess)
                @if($sess->status === 'open')
                    activeMeeting = {{ $sess->meeting_number }};
                @endif
            @endforeach

            if (activeMeeting) {
                showMeeting(activeMeeting);
            } else {
                // Default to meeting 1
                showMeeting(1);
            }
        });
    </script>
</x-app-layout>
