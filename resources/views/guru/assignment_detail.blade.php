<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('guru.dashboard') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center space-x-1 mb-1.5 transition">
                    <span uk-icon="icon: arrow-left; ratio: 0.8"></span>
                    <span>Kembali ke Dashboard</span>
                </a>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">Detail Kelas & Mata Pelajaran</h2>
            </div>
            
            <div class="flex items-center space-x-2">
                <span class="bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1.5 rounded-xl uppercase tracking-wider">
                    Kelas: {{ $assignment->classroom->name }}
                </span>
                <span class="bg-slate-100 border border-slate-200 text-slate-700 text-xs font-bold px-3 py-1.5 rounded-xl">
                    Hari: {{ $assignment->getDayTranslation() ?? '-' }} ({{ $assignment->start_time ? substr($assignment->start_time, 0, 5) . ' - ' . substr($assignment->end_time, 0, 5) : 'N/A' }})
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-xs font-semibold flex items-center space-x-2 shadow-sm">
                    <span uk-icon="icon: check; ratio: 0.9"></span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-xs font-semibold space-y-1 shadow-sm">
                    <div class="flex items-center space-x-2">
                        <span uk-icon="icon: warning; ratio: 0.9"></span>
                        <span class="font-bold">Terjadi kesalahan input:</span>
                    </div>
                    <ul class="list-disc pl-5 space-y-0.5 mt-1 font-medium">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Subject Banner -->
            <div class="bg-gradient-to-r from-indigo-900 to-slate-900 rounded-2xl shadow-md p-8 text-white flex flex-col md:flex-row md:items-center justify-between gap-6" style="background: linear-gradient(135deg, #1e1b4b, #0f172a);">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider bg-white/20 px-2.5 py-0.5 rounded-full inline-block mb-2 text-indigo-200">
                        Mata Pelajaran (Subject)
                    </span>
                    <h1 class="text-3xl font-extrabold mb-1.5">{{ $assignment->subject->name }}</h1>
                    <p class="text-slate-300 text-sm max-w-xl font-medium leading-relaxed">
                        {{ $assignment->subject->description ?? 'Tidak ada deskripsi detail untuk mata pelajaran ini.' }}
                    </p>
                </div>
                <div class="bg-white/10 backdrop-blur border border-white/20 rounded-2xl p-5 min-w-[200px] text-right self-start md:self-auto">
                    <span class="text-[10px] text-indigo-200 block uppercase font-bold tracking-wider mb-1">Kode Mapel</span>
                    <strong class="text-xl text-white font-extrabold tracking-wider block">{{ $assignment->subject->code ?? 'N/A' }}</strong>
                    <span class="text-[10px] text-indigo-200 block uppercase font-bold tracking-wider mt-2.5 mb-1">Semester</span>
                    <strong class="text-xs text-white font-bold block">{{ $assignment->semester->name ?? '-' }} @if($assignment->semester) ({{ $assignment->semester->is_active ? 'Aktif' : 'Non-aktif' }}) @endif</strong>
                </div>
            </div>

            <!-- TAB SWITCHER -->
            <div class="border-b border-slate-200">
                <nav class="flex space-x-6" aria-label="Tabs">
                    <button onclick="switchMainTab('kbm')" id="btn-tab-kbm" class="main-tab-btn py-3 px-1 font-bold text-sm border-b-2 border-indigo-600 text-indigo-700 transition flex items-center space-x-2">
                        <span uk-icon="icon: grid; ratio: 0.85"></span>
                        <span>Jadwal & Pertemuan KBM</span>
                    </button>
                    <button onclick="switchMainTab('grading')" id="btn-tab-grading" class="main-tab-btn py-3 px-1 font-bold text-sm border-b-2 border-transparent text-slate-400 hover:text-slate-700 hover:border-slate-300 transition flex items-center space-x-2">
                        <span uk-icon="icon: file-text; ratio: 0.85"></span>
                        <span>Penilaian Tugas</span>
                        @if($assignmentSubmissions->count() > 0)
                            <span class="bg-amber-100 text-amber-800 text-[9px] font-bold px-1.5 py-0.5 rounded-full border border-amber-200">
                                {{ $assignmentSubmissions->count() }}
                            </span>
                        @endif
                    </button>
                    <button onclick="switchMainTab('students')" id="btn-tab-students" class="main-tab-btn py-3 px-1 font-bold text-sm border-b-2 border-transparent text-slate-400 hover:text-slate-700 hover:border-slate-300 transition flex items-center space-x-2">
                        <span uk-icon="icon: users; ratio: 0.85"></span>
                        <span>Daftar Siswa & Rekap Nilai</span>
                    </button>
                </nav>
            </div>

            <!-- TAB 1: KBM CONTENT -->
            <div id="tab-content-kbm" class="main-tab-content space-y-6">
                <!-- 16 Meetings Grid Selection -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 soft-glow-indigo">
                    <h3 class="text-base font-bold text-slate-800 mb-2">Pilih Pertemuan KBM (Maks {{ $assignment->total_meetings ?? 16 }} Pertemuan)</h3>
                    <p class="text-xs text-slate-400 mb-4">Pilih nomor pertemuan di bawah untuk membuka presensi, menambahkan materi pembelajaran, mengunggah tugas, atau memantau kehadiran siswa.</p>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-8 gap-3">
                        @for($i = 1; $i <= ($assignment->total_meetings ?? 16); $i++)
                            @php
                                $session = $sessions->firstWhere('meeting_number', $i);
                            @endphp
                            <button type="button" onclick="showMeeting({{ $i }})" id="btn-meeting-{{ $i }}" class="meeting-select-btn p-3.5 rounded-xl border text-center transition-all duration-200 {{ $session ? ($session->status === 'open' ? 'bg-emerald-50 border-emerald-300 text-emerald-800 shadow-sm shadow-emerald-50' : 'bg-indigo-50/50 border-indigo-200 text-indigo-700 hover:bg-indigo-100') : 'bg-slate-50 border-slate-200 text-slate-400 hover:bg-slate-100' }}">
                                <div class="text-[9px] font-bold uppercase tracking-wider">Pertemuan</div>
                                <div class="text-xl font-black mt-0.5">{{ $i }}</div>
                                <div class="text-[9px] font-semibold mt-1">
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
                                    <div class="bg-white rounded-2xl border border-slate-100 p-6 soft-glow-indigo">
                                        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4 flex-wrap gap-2">
                                            <div>
                                                <h3 class="text-lg font-bold text-slate-800">Sesi Pertemuan Ke-{{ $i }}</h3>
                                                <p class="text-xs text-slate-400 font-medium">Dibuka pada: {{ \Carbon\Carbon::parse($session->session_date)->translatedFormat('l, d F Y') }}</p>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $session->status === 'open' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-slate-100 text-slate-600' }}">
                                                    Sesi {{ $session->status === 'open' ? 'Aktif / Terbuka' : 'Selesai / Ditutup' }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                            <div class="bg-slate-50/80 rounded-xl p-3.5 border border-slate-100/50">
                                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block">Mode Absensi</span>
                                                <strong class="text-xs text-slate-700 font-bold mt-1 block">
                                                    {{ $session->mode === 'qr_location' ? 'Scan QR & Cek Lokasi' : 'Klik Hadir & Cek Lokasi' }}
                                                </strong>
                                            </div>
                                            <div class="bg-slate-50/80 rounded-xl p-3.5 border border-slate-100/50">
                                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block">Lokasi Presensi</span>
                                                <strong class="text-xs text-slate-700 font-bold mt-1 block">
                                                    {{ $session->schoolLocation->name ?? 'Semua Lokasi Aktif' }}
                                                </strong>
                                            </div>
                                            <div class="bg-slate-50/80 rounded-xl p-3.5 border border-slate-100/50">
                                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block">Tenggat Absen</span>
                                                <strong class="text-xs text-rose-700 font-bold mt-1 block">
                                                    {{ \Carbon\Carbon::parse($session->deadline)->translatedFormat('d F Y, H:i') }} WIB
                                                </strong>
                                            </div>
                                        </div>

                                        @if($session->status === 'open')
                                            <div class="bg-slate-50 rounded-2xl border border-slate-200/60 p-5 flex flex-col md:flex-row items-center justify-between gap-4">
                                                <div class="space-y-1 text-center md:text-left">
                                                    <span class="text-xs font-bold text-slate-700 block">Kode QR Presensi Pertemuan {{ $i }}</span>
                                                    <p class="text-[10px] text-slate-400 font-medium">Siswa dapat melakukan scan QR link di bawah dari browser HP untuk mencatat kehadiran mereka.</p>
                                                    <a href="{{ route('student.sessions.scan-page', $session->qr_token) }}" target="_blank" class="text-xs font-bold text-indigo-600 hover:underline inline-flex items-center space-x-1 mt-2">
                                                        <span>Buka Halaman Scan QR Presensi</span>
                                                        <span uk-icon="icon: link; ratio: 0.75"></span>
                                                    </a>
                                                </div>
                                                
                                                <form action="{{ route('guru.sessions.close', $session->id) }}" method="POST" class="w-full md:w-auto">
                                                    @csrf
                                                    <button type="submit" class="w-full md:w-auto px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-100 transition flex items-center justify-center space-x-1.5">
                                                        <span uk-icon="icon: lock; ratio: 0.85"></span>
                                                        <span>Tutup Sesi Absensi</span>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="bg-slate-100/60 text-slate-500 rounded-xl p-4 text-xs font-semibold text-center border border-dashed border-slate-200">
                                                Sesi absensi pertemuan ini sudah ditutup. Siswa tidak dapat melakukan presensi lagi.
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Materials & Assignments Card -->
                                    <div class="bg-white rounded-2xl border border-slate-100 p-6 soft-glow-indigo">
                                        <div class="flex justify-between items-center border-b border-slate-100 pb-3.5 mb-4">
                                            <h4 class="font-bold text-slate-800 text-sm">Bahan Pembelajaran & Tugas</h4>
                                            <div class="flex items-center space-x-1.5">
                                                <button onclick="openMaterialModal({{ $session->id }}, '{{ $i }}')" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-[10px] font-bold px-3 py-1.5 rounded-xl border border-indigo-100 transition flex items-center space-x-1">
                                                    <span uk-icon="icon: plus; ratio: 0.7"></span>
                                                    <span>Materi</span>
                                                </button>
                                                <button onclick="openAssignmentModal({{ $session->id }}, '{{ $i }}')" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-bold px-3 py-1.5 rounded-xl border border-slate-200 transition flex items-center space-x-1">
                                                    <span uk-icon="icon: plus; ratio: 0.7"></span>
                                                    <span>Tugas</span>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <!-- Materials Column -->
                                            <div class="space-y-3">
                                                <h5 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 flex items-center space-x-1.5">
                                                    <span uk-icon="icon: copy; ratio: 0.75" class="text-indigo-600"></span>
                                                    <span>Materi Pembelajaran</span>
                                                </h5>
                                                @forelse($session->materials as $mat)
                                                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 flex items-center justify-between">
                                                        <div class="truncate mr-3">
                                                            <span class="text-xs font-bold text-slate-800 block truncate">{{ $mat->title }}</span>
                                                            <span class="text-[9px] text-slate-400 font-medium">PDF/Materi File</span>
                                                        </div>
                                                        <a href="{{ $mat->file_path }}" target="_blank" class="p-1.5 text-indigo-600 hover:bg-indigo-50 border border-indigo-100/50 rounded-lg transition" title="Unduh Materi">
                                                            <span uk-icon="icon: download; ratio: 0.8"></span>
                                                        </a>
                                                    </div>
                                                @empty
                                                    <p class="text-xs text-slate-400 italic">Belum ada file materi pembelajaran yang diunggah untuk pertemuan ini.</p>
                                                @endforelse
                                            </div>

                                            <!-- Assignments Column -->
                                            <div class="space-y-3">
                                                <h5 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 flex items-center space-x-1.5">
                                                    <span uk-icon="icon: file-edit; ratio: 0.75" class="text-indigo-600"></span>
                                                    <span>Tugas Pertemuan</span>
                                                </h5>
                                                @forelse($session->assignments as $asg)
                                                    <div class="bg-indigo-50/30 border border-indigo-100/60 rounded-xl p-4 space-y-2.5">
                                                        <div class="flex justify-between items-start">
                                                            <div>
                                                                <h6 class="text-xs font-bold text-slate-800">{{ $asg->title }}</h6>
                                                                <span class="text-[9px] text-emerald-600 font-black block mt-0.5">+{{ $asg->points_reward }} Poin Kebaikan</span>
                                                            </div>
                                                            @if($asg->file_path)
                                                                <a href="{{ $asg->file_path }}" target="_blank" class="p-1 text-slate-500 hover:text-indigo-600 hover:bg-white border border-slate-200 rounded-lg transition" title="Unduh Lampiran Tugas">
                                                                    <span uk-icon="icon: download; ratio: 0.75"></span>
                                                                </a>
                                                            @endif
                                                        </div>
                                                        <p class="text-[10px] text-slate-500 leading-normal">{{ $asg->description }}</p>
                                                        <div class="text-[9px] text-rose-700 font-bold border-t border-slate-100 pt-2 flex items-center space-x-1">
                                                            <span uk-icon="icon: clock; ratio: 0.7"></span>
                                                            <span>Tenggat: {{ \Carbon\Carbon::parse($asg->deadline)->translatedFormat('d F, H:i') }} WIB</span>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <p class="text-xs text-slate-400 italic">Belum ada tugas untuk pertemuan ini.</p>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Attendance Statistics List (Col 1) -->
                                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden soft-glow-indigo lg:col-span-1">
                                    <div class="p-6 border-b border-slate-100">
                                        <h4 class="font-bold text-slate-800 text-sm">Kehadiran Siswa</h4>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">Hadir: {{ $session->present_count }} / {{ $students->count() }} Siswa</p>
                                    </div>
                                    
                                    <div class="divide-y divide-slate-100/60 max-h-[480px] overflow-y-auto">
                                        @foreach($students as $student)
                                            @php
                                                $att = $session->attendances->firstWhere('student_id', $student->id);
                                            @endphp
                                            <div class="p-4 flex items-center justify-between text-xs hover:bg-slate-50/50 transition">
                                                <div class="truncate mr-3">
                                                    <span class="font-bold text-slate-800 block truncate">{{ $student->name }}</span>
                                                    @if($att && $att->status === 'hadir')
                                                        <span class="text-[9px] text-slate-400 font-medium block mt-0.5">
                                                            {{ \Carbon\Carbon::parse($att->created_at)->format('H:i') }} · Jarak: {{ $att->distance_meters ?? 0 }}m
                                                        </span>
                                                    @else
                                                        <span class="text-[9px] text-slate-400 font-medium block mt-0.5">Belum absen</span>
                                                    @endif
                                                </div>
                                                
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase {{ $att && $att->status === 'hadir' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-slate-100 text-slate-400' }}">
                                                    {{ $att && $att->status === 'hadir' ? 'Hadir' : 'Alpa' }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Placeholder Belum Dibuka Card -->
                            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center max-w-xl mx-auto space-y-4 soft-glow-indigo">
                                <div class="w-16 h-16 bg-indigo-50 border border-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto shadow-inner">
                                    <span uk-icon="icon: lock; ratio: 1.5"></span>
                                </div>
                                <div class="space-y-1">
                                    <h3 class="text-lg font-bold text-slate-800">Pertemuan Ke-{{ $i }} Belum Dibuka</h3>
                                    <p class="text-xs text-slate-400 font-medium">Buka sesi presensi untuk mengizinkan siswa mencatat kehadiran mereka pada jam pelajaran ini serta bagikan materi pelajaran.</p>
                                </div>
                                <button onclick="openOpenSessionModal({{ $i }})" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-6 py-3 rounded-xl shadow-md shadow-indigo-100 transition inline-flex items-center space-x-1.5">
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
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden soft-glow-indigo">
                    <div class="p-6 border-b border-slate-100">
                        <h3 class="text-lg font-bold text-slate-800">Tinjau Pengumpulan Tugas</h3>
                        <p class="text-xs text-slate-400 mt-1 font-medium">Siswa yang telah mengumpulkan tugas di mata pelajaran ini dan memerlukan penilaian/validasi nilai.</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-100/80">
                                    <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Siswa</th>
                                    <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Judul Tugas</th>
                                    <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Hadiah Poin</th>
                                    <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Berkas/Jawaban</th>
                                    <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100/70">
                                @forelse($assignmentSubmissions as $sub)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-800 text-xs">{{ $sub->student->name }}</div>
                                            <div class="text-[9px] text-slate-400 font-semibold mt-0.5">Diserahkan: {{ \Carbon\Carbon::parse($sub->updated_at)->translatedFormat('d M, H:i') }} WIB</div>
                                        </td>
                                        <td class="px-6 py-4 text-xs font-semibold text-slate-700">
                                            {{ $sub->assignment->title }}
                                        </td>
                                        <td class="px-6 py-4 text-center font-extrabold text-xs text-emerald-600">
                                            +{{ $sub->assignment->points_reward }} Pts
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($sub->file_path)
                                                <a href="{{ $sub->file_path }}" target="_blank" class="text-xs font-bold text-indigo-600 hover:underline flex items-center space-x-1">
                                                    <span uk-icon="icon: link; ratio: 0.75"></span>
                                                    <span>Unduh Berkas</span>
                                                </a>
                                            @endif
                                            @if($sub->text_content)
                                                <p class="text-[10px] text-slate-500 italic mt-1 font-mono max-w-xs truncate">{{ $sub->text_content }}</p>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button onclick="openGradeModal({{ json_encode($sub) }}, '{{ $sub->student->name }}', '{{ $sub->assignment->title }}')" class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-bold rounded-xl transition shadow-sm hover:shadow-md inline-flex items-center space-x-1">
                                                <span>Beri Nilai</span>
                                                <span uk-icon="icon: check; ratio: 0.75"></span>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-12 text-slate-400 text-xs font-medium">Tidak ada tugas siswa yang menunggu penilaian saat ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 3: STUDENTS CONTENT -->
            <div id="tab-content-students" class="main-tab-content space-y-6 hidden">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden soft-glow-indigo">
                    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Rekapitulasi Siswa & Nilai</h3>
                            <p class="text-xs text-slate-400 mt-1 font-medium">Daftar siswa beserta poin akademik dan data kehadiran mereka.</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-100/80">
                                    <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center w-16">No</th>
                                    <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nama Siswa</th>
                                    <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Tingkat</th>
                                    <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Kehadiran (Hadir)</th>
                                    <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Total Poin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100/70">
                                @forelse($students as $index => $student)
                                    @php
                                        // Count presence for active teaching assignment sessions
                                        $presenceCount = \App\Models\Attendance::whereIn('attendance_session_id', $sessions->pluck('id'))->where('student_id', $student->id)->where('status', 'hadir')->count();
                                    @endphp
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
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100/80">
                                                {{ $student->current_level ?? 'Pemula' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center text-xs font-semibold text-slate-700">
                                            {{ $presenceCount }} Pertemuan
                                        </td>
                                        <td class="px-6 py-4 text-right font-extrabold text-xs text-indigo-600">
                                            {{ number_format($student->points) }} Pts
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-12 text-slate-400 text-xs font-medium">Belum ada siswa di kelas ini.</td>
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
        <div class="uk-modal-dialog uk-modal-body rounded-2xl p-0" style="width: 720px; max-width: calc(100% - 2rem);">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h4 class="font-bold text-slate-800" id="open-session-title">Buka Sesi Presensi KBM</h4>
                <button class="uk-modal-close-default" type="button" uk-close></button>
            </div>
            
            <form action="{{ route('guru.sessions.store', $assignment->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf
                <input type="hidden" name="meeting_number" id="form-meeting-number">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Tanggal Pertemuan</label>
                        <input type="date" name="session_date" id="form-session-date" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Batas Tenggat Presensi</label>
                        <input type="datetime-local" name="deadline" id="form-deadline" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Mode Presensi Siswa</label>
                        <select name="mode" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="button_location">Klik Tombol Hadir (+ Cek Lokasi GPS)</option>
                            <option value="qr_location">Scan QR Code (+ Cek Lokasi GPS)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Titik Lokasi Absensi</label>
                        <select name="school_location_id" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Lokasi Sekolah Aktif (Multi Lokasi)</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->name }} (Radius: {{ $loc->radius }}m)</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- UPLOAD MULTI MATERI -->
                <div class="border-t border-slate-100 pt-4">
                    <h5 class="text-xs font-bold text-indigo-750 uppercase tracking-wider mb-2 flex items-center justify-between">
                        <span>Unggah Materi Pembelajaran (Opsional)</span>
                        <button type="button" onclick="addMaterialInput()" class="text-[10px] font-bold text-indigo-600 hover:underline inline-flex items-center space-x-0.5">
                            <span uk-icon="icon: plus; ratio: 0.65"></span>
                            <span>Tambah Materi</span>
                        </button>
                    </h5>
                    <div id="material-inputs-container" class="space-y-3">
                        <div id="no-materials-placeholder" class="text-[11px] text-slate-400 font-medium italic py-1">
                            Belum ada materi ditambahkan. Klik "+ Tambah Materi" di kanan untuk mengunggah materi pelajaran.
                        </div>
                    </div>
                </div>

                <!-- TUGAS PERTEMUAN (OPSIONAL) -->
                <div class="border-t border-slate-100 pt-4">
                    <h5 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 flex items-center justify-between">
                        <span>Tugas Pertemuan (Opsional)</span>
                        <button type="button" onclick="addAssignmentInput()" class="text-[10px] font-bold text-indigo-600 hover:underline inline-flex items-center space-x-0.5">
                            <span uk-icon="icon: plus; ratio: 0.65"></span>
                            <span>Tambah Tugas</span>
                        </button>
                    </h5>
                    
                    <div id="assignment-inputs-container" class="space-y-4">
                        <div id="no-assignments-placeholder" class="text-[11px] text-slate-400 font-medium italic py-1">
                            Belum ada tugas ditambahkan. Klik "+ Tambah Tugas" di kanan untuk menambahkan tugas.
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-2 pt-3 border-t border-slate-100">
                    <button class="uk-modal-close bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-4 py-2 rounded-xl transition" type="button">Batal</button>
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl transition shadow-md shadow-indigo-100" type="submit">Buka Sesi KBM</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: TAMBAH MATERI PEMBELAJARAN KE SESI AKTIF -->
    <div id="modal-add-material-existing" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-2xl p-6">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h3 class="text-base font-bold text-slate-800 mb-4" id="add-material-existing-title">Tambah Materi Pembelajaran</h3>
            
            <form id="form-add-material-existing" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Judul Materi</label>
                    <input type="text" name="title" required placeholder="Contoh: Bab 2 Larutan Elektrolit" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">File Dokumen (PDF, PPT, DOCX, dll)</label>
                    <input type="file" name="file" required class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                </div>
                
                <div class="flex justify-end space-x-2 pt-2">
                    <button class="uk-modal-close bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-4 py-2 rounded-xl transition" type="button">Batal</button>
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-5 py-2 rounded-xl transition shadow-md shadow-indigo-100" type="submit">Simpan Materi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 3: TAMBAH TUGAS KE SESI AKTIF -->
    <div id="modal-add-assignment-existing" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-2xl p-6" style="width: 580px; max-width: calc(100% - 2rem);">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h3 class="text-base font-bold text-slate-800 mb-4" id="add-assignment-existing-title">Tambah Tugas Baru</h3>
            
            <form id="form-add-assignment-existing" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Judul Tugas</label>
                        <input type="text" name="title" required placeholder="Contoh: Latihan 2.1 Kalorimeter" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Poin Hadiah Tugas</label>
                        <input type="number" name="points_reward" value="15" min="1" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Deskripsi Tugas & Panduan</label>
                    <textarea name="description" rows="3" required placeholder="Tulis instruksi lengkap tugas..." class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Batas Tenggat Pengumpulan</label>
                        <input type="datetime-local" name="deadline" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Lampiran Soal/Materi (Opsional)</label>
                        <input type="file" name="file" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-2 pt-2">
                    <button class="uk-modal-close bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-4 py-2 rounded-xl transition" type="button">Batal</button>
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-5 py-2 rounded-xl transition shadow-md shadow-indigo-100" type="submit">Tambah Tugas</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 4: BERI NILAI TUGAS SISWA -->
    <div id="modal-grade-submission" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-2xl p-6">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-bold text-slate-800 mb-4">Penilaian Tugas Siswa</h2>
            
            <div class="bg-slate-50/80 border border-slate-200 rounded-xl p-4 mb-4 space-y-2 text-xs">
                <div class="flex justify-between">
                    <span class="text-slate-400 font-medium">Siswa:</span>
                    <strong id="grade-student-name" class="text-slate-800"></strong>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400 font-medium">Tugas:</span>
                    <strong id="grade-assignment-title" class="text-slate-800"></strong>
                </div>
            </div>

            <form id="form-grade-submission" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Keputusan Penilaian</label>
                    <select name="status" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="approved">Setujui (Approve) - Berikan Poin Tugas</option>
                        <option value="revision">Minta Revisi (Revision) - Tulis Komentar</option>
                        <option value="rejected">Tolak Tugas (Reject)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Komentar / Feedback</label>
                    <textarea name="notes" rows="2" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Tulis catatan jika minta revisi atau memberikan apresiasi..."></textarea>
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <button class="uk-modal-close bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-4 py-2 rounded-xl transition" type="button">Batal</button>
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-5 py-2 rounded-xl transition shadow-md shadow-indigo-100" type="submit">Kirim Penilaian</button>
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
                btn.className = 'main-tab-btn py-3 px-1 font-bold text-sm border-b-2 border-transparent text-slate-400 hover:text-slate-700 hover:border-slate-300 transition flex items-center space-x-2';
            });

            document.getElementById('tab-content-' + tabName).classList.remove('hidden');
            document.getElementById('btn-tab-' + tabName).className = 'main-tab-btn py-3 px-1 font-bold text-sm border-b-2 border-indigo-600 text-indigo-700 transition flex items-center space-x-2';
        }

        // Meeting details toggle
        let currentMeetingNumber = null;
        function showMeeting(num) {
            currentMeetingNumber = num;
            
            // Hide all panels
            document.querySelectorAll('.meeting-detail-panel').forEach(p => p.classList.add('hidden'));
            
            // Reset button classes
            document.querySelectorAll('.meeting-select-btn').forEach(btn => {
                btn.classList.remove('ring-2', 'ring-indigo-600', 'scale-[1.03]');
            });

            // Show current panel
            const panel = document.getElementById('meeting-panel-' + num);
            if (panel) {
                panel.classList.remove('hidden');
            }

            // Highlight selected button
            const selectedBtn = document.getElementById('btn-meeting-' + num);
            if (selectedBtn) {
                selectedBtn.classList.add('ring-2', 'ring-indigo-600', 'scale-[1.03]');
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
            newRow.className = 'grid grid-cols-1 md:grid-cols-2 gap-3 material-row pt-2 border-t border-slate-100/60';
            newRow.innerHTML = `
                <div>
                    <input type="text" name="material_titles[]" placeholder="Judul Materi" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="flex items-center space-x-2">
                    <input type="file" name="materials[]" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                    <button type="button" onclick="removeMaterialRow(this)" class="text-rose-500 hover:text-rose-700 p-1">
                        <span uk-icon="icon: trash; ratio: 0.8"></span>
                    </button>
                </div>
            `;
            container.appendChild(newRow);
        }

        function removeMaterialRow(button) {
            button.closest('.material-row').remove();
            const container = document.getElementById('material-inputs-container');
            if (container.querySelectorAll('.material-row').length === 0) {
                if (!document.getElementById('no-materials-placeholder')) {
                    const placeholder = document.createElement('div');
                    placeholder.id = 'no-materials-placeholder';
                    placeholder.className = 'text-[11px] text-slate-400 font-medium italic py-1';
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
            card.className = 'assignment-card p-4 rounded-xl border border-slate-200 bg-slate-50/50 space-y-3 relative';
            
            // Set default deadline to today + 24 hours
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const offset = tomorrow.getTimezoneOffset() * 60000;
            const defaultDeadline = (new Date(tomorrow.getTime() - offset)).toISOString().slice(0, 16);

            card.innerHTML = `
                <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                    <span class="text-xs font-bold text-indigo-600">Tugas #${index + 1}</span>
                    <button type="button" onclick="removeAssignmentCard(this)" class="text-rose-500 hover:text-rose-700 flex items-center space-x-0.5 text-[10px] font-bold">
                        <span uk-icon="icon: trash; ratio: 0.75"></span>
                        <span>Hapus</span>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 mb-1">Judul Tugas</label>
                        <input type="text" name="assignment_titles[]" placeholder="Contoh: Latihan Soal Logika" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 mb-1">Poin Hadiah Tugas</label>
                        <input type="number" name="assignment_points[]" value="15" min="1" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-[9px] font-bold text-slate-400 mb-1">Deskripsi & Instruksi Tugas</label>
                    <textarea name="assignment_descriptions[]" rows="2" placeholder="Tulis instruksi tugas di sini..." required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 mb-1">Lampiran Tugas (File - Optional)</label>
                        <input type="file" name="assignment_files[]" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 mb-1">Batas Tenggat Tugas</label>
                        <input type="datetime-local" name="assignment_deadlines[]" value="${defaultDeadline}" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
            `;
            container.appendChild(card);
            
            reindexAssignmentCards();
        }

        function removeAssignmentCard(button) {
            button.closest('.assignment-card').remove();
            const container = document.getElementById('assignment-inputs-container');
            if (container.querySelectorAll('.assignment-card').length === 0) {
                if (!document.getElementById('no-assignments-placeholder')) {
                    const placeholder = document.createElement('div');
                    placeholder.id = 'no-assignments-placeholder';
                    placeholder.className = 'text-[11px] text-slate-400 font-medium italic py-1';
                    placeholder.innerText = 'Belum ada tugas ditambahkan. Klik "+ Tambah Tugas" di kanan untuk menambahkan tugas.';
                    container.appendChild(placeholder);
                }
            } else {
                reindexAssignmentCards();
            }
        }

        function reindexAssignmentCards() {
            const cards = document.querySelectorAll('#assignment-inputs-container .assignment-card');
            cards.forEach((card, index) => {
                const badge = card.querySelector('.text-indigo-600');
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
