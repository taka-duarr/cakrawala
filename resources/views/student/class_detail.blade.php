@php
    $cardColors = [
        ['bg' => 'bg-rose-50/50 border-rose-100/40', 'text' => 'text-rose-900', 'btn' => 'bg-rose-600 hover:bg-rose-700 text-white', 'tag' => 'bg-rose-105 text-rose-700', 'points' => 'text-rose-600'],
        ['bg' => 'bg-orange-50/50 border-orange-100/40', 'text' => 'text-orange-900', 'btn' => 'bg-orange-600 hover:bg-orange-700 text-white', 'tag' => 'bg-orange-100 text-orange-700', 'points' => 'text-orange-600'],
        ['bg' => 'bg-indigo-50/50 border-indigo-100/40', 'text' => 'text-indigo-900', 'btn' => 'bg-indigo-600 hover:bg-indigo-700 text-white', 'tag' => 'bg-indigo-100 text-indigo-700', 'points' => 'text-indigo-600'],
        ['bg' => 'bg-amber-50/50 border-amber-100/40', 'text' => 'text-amber-900', 'btn' => 'bg-amber-600 hover:bg-amber-700 text-white', 'tag' => 'bg-amber-100 text-amber-700', 'points' => 'text-amber-600'],
        ['bg' => 'bg-emerald-50/50 border-emerald-100/40', 'text' => 'text-emerald-900', 'btn' => 'bg-emerald-600 hover:bg-emerald-700 text-white', 'tag' => 'bg-emerald-100 text-emerald-700', 'points' => 'text-emerald-600']
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('student.my-classes') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 flex items-center space-x-1 mb-1.5 transition">
                    <span uk-icon="icon: arrow-left; ratio: 0.8"></span>
                    <span>Kembali ke Kelas Saya</span>
                </a>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">Detail Mata Pelajaran</h2>
            </div>
            
            <div class="flex items-center space-x-2">
                <span class="bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1.5 rounded-xl uppercase tracking-wider">
                    Kelas: {{ $assignment->classroom->name }}
                </span>
                <span class="bg-slate-100 border border-slate-200 text-slate-700 text-xs font-bold px-3 py-1.5 rounded-xl">
                    {{ $assignment->academicYear->name ?? '-' }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 flex items-center space-x-3 shadow-sm text-xs font-semibold">
                    <span uk-icon="icon: check; ratio: 0.9" class="text-emerald-600"></span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-4 flex items-center space-x-3 shadow-sm text-xs font-semibold">
                    <span uk-icon="icon: warning; ratio: 0.9" class="text-rose-600"></span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Subject Banner -->
            <div class="bg-gradient-to-r from-indigo-900 to-slate-800 rounded-2xl shadow-md p-8 text-white flex flex-col md:flex-row md:items-center justify-between gap-6" style="background: linear-gradient(135deg, #1e1b4b, #0f172a);">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider bg-white/20 px-2.5 py-0.5 rounded-full inline-block mb-2 text-indigo-205">
                        {{ $assignment->subject->code ?? 'MAPEL' }}
                    </span>
                    <h1 class="text-3xl font-extrabold mb-1.5">{{ $assignment->subject->name }}</h1>
                    <p class="text-slate-300 text-sm max-w-xl font-medium leading-relaxed">
                        {{ $assignment->subject->description ?? 'Tidak ada deskripsi detail untuk mata pelajaran ini.' }}
                    </p>
                </div>
                <div class="bg-white/10 backdrop-blur border border-white/20 rounded-2xl p-5 min-w-[200px] text-right self-start md:self-auto">
                    <span class="text-[10px] text-indigo-200 block uppercase font-bold tracking-wider mb-1">Guru Pengampu</span>
                    <strong class="text-lg text-white font-extrabold block leading-tight">{{ $assignment->teacher->name ?? '-' }}</strong>
                    <span class="text-[10px] text-indigo-200 block uppercase font-bold tracking-wider mt-2.5 mb-1">Semester</span>
                    <strong class="text-xs text-white font-bold block">{{ $assignment->semester->name ?? '-' }} @if($assignment->semester) ({{ $assignment->semester->is_active ? 'Aktif' : 'Non-aktif' }}) @endif</strong>
                </div>
            </div>

            <!-- TAB SWITCHER -->
            <div class="border-b border-slate-200">
                <nav class="flex space-x-6" aria-label="Tabs">
                    <button onclick="switchMainTab('kbm')" id="btn-tab-kbm" class="main-tab-btn py-3 px-1 font-bold text-sm border-b-2 border-indigo-600 text-indigo-700 transition flex items-center space-x-2">
                        <span uk-icon="icon: grid; ratio: 0.85"></span>
                        <span>Materi & Tugas KBM (1-{{ $assignment->total_meetings ?? 16 }})</span>
                    </button>
                    <button onclick="switchMainTab('missions')" id="btn-tab-missions" class="main-tab-btn py-3 px-1 font-bold text-sm border-b-2 border-transparent text-slate-400 hover:text-slate-700 hover:border-slate-300 transition flex items-center space-x-2">
                        <span uk-icon="icon: file-edit; ratio: 0.85"></span>
                        <span>Misi Karakter & Reputasi</span>
                    </button>
                    <button onclick="switchMainTab('classmates')" id="btn-tab-classmates" class="main-tab-btn py-3 px-1 font-bold text-sm border-b-2 border-transparent text-slate-400 hover:text-slate-700 hover:border-slate-300 transition flex items-center space-x-2">
                        <span uk-icon="icon: users; ratio: 0.85"></span>
                        <span>Teman Sekelas</span>
                    </button>
                </nav>
            </div>

            <!-- TAB 1: KBM & PRESENSI CONTENT -->
            <div id="tab-content-kbm" class="main-tab-content space-y-6">
                <!-- 16 Meetings Grid -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 soft-glow-indigo">
                    <h3 class="text-base font-bold text-slate-800 mb-2">Daftar Pertemuan KBM</h3>
                    <p class="text-xs text-slate-400 mb-4">Pilih nomor pertemuan di bawah untuk mengunduh materi belajar, mengumpulkan tugas, atau melakukan presensi kehadiran.</p>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-8 gap-3">
                        @for($i = 1; $i <= ($assignment->total_meetings ?? 16); $i++)
                            @php
                                $session = $sessions->firstWhere('meeting_number', $i);
                                $att = $session ? $myAttendances->get($session->id) : null;
                            @endphp
                            <button type="button" onclick="showMeeting({{ $i }})" id="btn-meeting-{{ $i }}" class="meeting-select-btn p-3 rounded-xl border text-center transition-all duration-200 {{ $session ? ($att && $att->status === 'hadir' ? 'bg-emerald-50 border-emerald-300 text-emerald-800 shadow-sm shadow-emerald-50' : 'bg-indigo-50/50 border-indigo-200 text-indigo-700 hover:bg-indigo-100') : 'bg-slate-50 border-slate-200 text-slate-400 hover:bg-slate-100' }}">
                                <div class="text-[9px] font-bold uppercase tracking-wider">Pertemuan</div>
                                <div class="text-xl font-black mt-0.5">{{ $i }}</div>
                                <div class="text-[9px] font-semibold mt-1">
                                    @if($session)
                                        @if($att && $att->status === 'hadir')
                                            Hadir
                                        @else
                                            {{ $session->status === 'open' ? 'Buka' : 'Tutup' }}
                                        @endif
                                    @else
                                        Belum Buka
                                    @endif
                                </div>
                            </button>
                        @endfor
                    </div>
                </div>

                <!-- Panels Container -->
                @for($i = 1; $i <= ($assignment->total_meetings ?? 16); $i++)
                    @php
                        $session = $sessions->firstWhere('meeting_number', $i);
                        $att = $session ? $myAttendances->get($session->id) : null;
                    @endphp
                    <div id="meeting-panel-{{ $i }}" class="meeting-detail-panel hidden space-y-6">
                        
                        @if($session)
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                
                                <!-- Left/Main Info and Files (Col 2) -->
                                <div class="lg:col-span-2 space-y-6">
                                    <!-- Session Header -->
                                    <div class="bg-white rounded-2xl border border-slate-100 p-6 soft-glow-indigo">
                                        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4 flex-wrap gap-2">
                                            <div>
                                                <h3 class="text-lg font-bold text-slate-800">Materi & Tugas Pertemuan Ke-{{ $i }}</h3>
                                                <p class="text-xs text-slate-400 font-medium">Tanggal KBM: {{ \Carbon\Carbon::parse($session->session_date)->translatedFormat('l, d F Y') }}</p>
                                            </div>
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $att && $att->status === 'hadir' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }}">
                                                Kehadiran Anda: {{ $att && $att->status === 'hadir' ? 'Hadir' : 'Alpa' }}
                                            </span>
                                        </div>

                                        <!-- Materials List -->
                                        <div class="space-y-3">
                                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 flex items-center space-x-1.5">
                                                <span uk-icon="icon: copy; ratio: 0.75" class="text-indigo-600"></span>
                                                <span>Materi Pembelajaran</span>
                                            </h4>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                @forelse($session->materials as $mat)
                                                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 flex items-center justify-between">
                                                        <div class="truncate mr-3">
                                                            <span class="text-xs font-bold text-slate-800 block truncate">{{ $mat->title }}</span>
                                                            <span class="text-[9px] text-slate-400 font-semibold">Materi Pertemuan</span>
                                                        </div>
                                                        <a href="{{ $mat->file_path }}" target="_blank" class="p-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg transition" title="Unduh Materi">
                                                            <span uk-icon="icon: download; ratio: 0.85"></span>
                                                        </a>
                                                    </div>
                                                @empty
                                                    <div class="col-span-2 py-4">
                                                        <p class="text-xs text-slate-400 italic">Tidak ada dokumen materi pembelajaran untuk pertemuan ini.</p>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Assignments List -->
                                    <div class="bg-white rounded-2xl border border-slate-100 p-6 soft-glow-indigo">
                                        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4 flex items-center space-x-1.5">
                                            <span uk-icon="icon: file-edit; ratio: 0.75" class="text-indigo-600"></span>
                                            <span>Tugas Pertemuan Ke-{{ $i }}</span>
                                        </h4>

                                        @forelse($session->assignments as $asg)
                                            @php
                                                $sub = $assignmentSubmissions->get($asg->id);
                                            @endphp
                                            <div class="bg-slate-50/50 border border-slate-100 rounded-2xl p-5 space-y-4">
                                                <div class="flex justify-between items-start flex-wrap gap-2 border-b border-slate-100 pb-3">
                                                    <div>
                                                        <h5 class="text-sm font-bold text-slate-800">{{ $asg->title }}</h5>
                                                        <span class="text-[9px] text-emerald-600 font-extrabold block mt-0.5">+{{ $asg->points_reward }} Pts Karakter</span>
                                                    </div>
                                                    <div class="text-right">
                                                        <span class="text-[9px] text-rose-700 font-bold block">Tenggat: {{ \Carbon\Carbon::parse($asg->deadline)->translatedFormat('d F, H:i') }} WIB</span>
                                                        @if($asg->file_path)
                                                            <a href="{{ $asg->file_path }}" target="_blank" class="text-[10px] font-bold text-indigo-600 hover:underline inline-flex items-center space-x-0.5 mt-1">
                                                                <span>Unduh Soal Tugas</span>
                                                                <span uk-icon="icon: download; ratio: 0.7"></span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>

                                                <p class="text-xs text-slate-500 leading-normal">{{ $asg->description }}</p>

                                                <!-- Submission Form or Status -->
                                                <div class="bg-white border border-slate-100 rounded-xl p-4">
                                                    <h6 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-3">Status Pengumpulan Tugas Anda</h6>
                                                    
                                                    @if(!$sub)
                                                        @if(\Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($asg->deadline)))
                                                            <div class="text-xs text-rose-700 font-bold bg-rose-50 border border-rose-100 rounded-lg p-2.5">
                                                                ⚠️ Batas pengumpulan tugas telah berakhir. Anda tidak dapat mengumpulkan tugas lagi.
                                                            </div>
                                                        @else
                                                            <form action="{{ route('student.assignments.submit', $asg->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3" onsubmit="let btn = this.querySelector('button[type=submit]'); if(btn) { btn.disabled = true; btn.innerHTML = '<span class=\'animate-spin inline-block w-3 h-3 border-2 border-current border-t-transparent rounded-full mr-1.5 align-middle\'></span> Mengirim...'; }">
                                                                @csrf
                                                                <div>
                                                                    <label class="block text-[9px] font-bold text-slate-400 mb-1">Catatan/Jawaban Teks</label>
                                                                    <textarea name="text_content" rows="2" placeholder="Tulis jawaban teks atau tautan Google Drive Anda di sini..." class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                                                </div>
                                                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                                                    <div>
                                                                        <label class="block text-[9px] font-bold text-slate-400 mb-1">Unggah Berkas Jawaban (PDF, ZIP, Gambar)</label>
                                                                        <input type="file" name="file" class="text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                                                                    </div>
                                                                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-100 transition self-end">
                                                                        Kumpulkan Tugas
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        @endif
                                                    @else
                                                        @if($sub->status === 'pending')
                                                            <div class="bg-amber-50 border border-amber-200 text-amber-700 p-3 rounded-lg text-xs font-semibold flex items-center space-x-1.5">
                                                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                                                <span>Tugas telah dikumpulkan. Menunggu penilaian & verifikasi dari Guru.</span>
                                                            </div>
                                                        @elseif($sub->status === 'approved')
                                                            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl space-y-2">
                                                                <div class="text-xs font-bold flex items-center space-x-1 text-emerald-700">
                                                                    <span uk-icon="icon: check; ratio: 0.8"></span>
                                                                    <span>Tugas Disetujui & Dinilai!</span>
                                                                </div>
                                                                <p class="text-[10px] text-slate-500 leading-normal">
                                                                    Anda mendapatkan <strong>+{{ $sub->points_awarded ?? $asg->points_reward }} Pts</strong> reputasi karakter disiplin.<br>
                                                                    Catatan Guru: <span class="italic">"{{ $sub->notes ?? 'Sangat baik!' }}"</span>
                                                                </p>
                                                            </div>
                                                        @elseif($sub->status === 'revision')
                                                            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 space-y-3">
                                                                <div class="text-xs font-bold text-amber-800">
                                                                    ⚠️ Tugas Butuh Revisi
                                                                </div>
                                                                <p class="text-[11px] text-slate-600 leading-normal">
                                                                    Umpan balik dari Guru: <span class="italic font-semibold text-slate-800">"{{ $sub->notes ?? '-' }}"</span>
                                                                </p>
                                                                
                                                                <form action="{{ route('student.assignments.submit', $asg->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3 border-t border-amber-200/50 pt-3" onsubmit="let btn = this.querySelector('button[type=submit]'); if(btn) { btn.disabled = true; btn.innerHTML = '<span class=\'animate-spin inline-block w-3 h-3 border-2 border-current border-t-transparent rounded-full mr-1.5 align-middle\'></span> Mengirim...'; }">
                                                                    @csrf
                                                                    <div>
                                                                        <label class="block text-[9px] font-bold text-slate-400 mb-1">Catatan/Teks Perbaikan</label>
                                                                        <textarea name="text_content" rows="2" placeholder="Tulis catatan perbaikan di sini..." class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">{{ $sub->text_content }}</textarea>
                                                                    </div>
                                                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                                                        <div>
                                                                            <label class="block text-[9px] font-bold text-slate-400 mb-1">Unggah Ulang Berkas Jawaban (Optional)</label>
                                                                            <input type="file" name="file" class="text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                                                                        </div>
                                                                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-100 transition self-end">
                                                                            Kirim Ulang Jawaban
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        @elseif($sub->status === 'rejected')
                                                            <div class="bg-rose-50 border border-rose-200 text-rose-800 p-3 rounded-lg text-xs font-semibold">
                                                                ❌ Tugas ditolak oleh guru. Catatan: "{{ $sub->notes ?? '-' }}"
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-xs text-slate-400 italic">Tidak ada tugas yang ditambahkan pada pertemuan ini.</p>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- Check-In and Session Status (Col 1) -->
                                <div class="space-y-6 lg:col-span-1">
                                    <div class="bg-white rounded-2xl border border-slate-100 p-6 soft-glow-indigo">
                                        <h4 class="font-bold text-slate-800 text-sm mb-4">Presensi Kehadiran</h4>

                                        @if($att && $att->status === 'hadir')
                                            <div class="bg-emerald-50/50 border border-emerald-200 rounded-xl p-4 text-center space-y-2">
                                                <div class="w-10 h-10 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center mx-auto shadow-inner">
                                                    <span uk-icon="icon: check; ratio: 1.1"></span>
                                                </div>
                                                <h5 class="text-xs font-bold text-slate-800">Anda Dinyatakan Hadir</h5>
                                                <p class="text-[10px] text-slate-400 font-semibold leading-normal">
                                                    Absen pukul: {{ \Carbon\Carbon::parse($att->created_at)->format('H:i') }} WIB<br>
                                                    Mendapatkan <strong>+{{ $att->points_awarded }} Pts</strong> kedisiplinan.
                                                </p>
                                            </div>
                                        @else
                                            @if($session->status === 'open' && \Carbon\Carbon::now()->lt(\Carbon\Carbon::parse($session->deadline)))
                                                
                                                @if($session->mode === 'qr_location')
                                                    <!-- QR Code Mode Alert -->
                                                    <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4 text-center space-y-2 text-xs">
                                                        <span uk-icon="icon: camera; ratio: 1.2" class="text-indigo-600"></span>
                                                        <h5 class="font-bold text-slate-800">Mode Scan QR Sekolah</h5>
                                                        <p class="text-[10px] text-slate-400 font-semibold leading-relaxed">
                                                            Mata pelajaran ini memerlukan Scan QR Code yang diproyeksikan oleh Guru di depan kelas untuk verifikasi lokasi GPS Anda.
                                                        </p>
                                                    </div>
                                                @else
                                                    <!-- Button Mode Check-In -->
                                                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 text-center space-y-3">
                                                        <span uk-icon="icon: location; ratio: 1.2" class="text-indigo-600"></span>
                                                        <h5 class="text-xs font-bold text-slate-800">Klik Kehadiran GPS</h5>
                                                        <p class="text-[10px] text-slate-400 leading-relaxed font-semibold">
                                                            Pastikan GPS / lokasi browser HP Anda aktif. Jarak koordinat GPS akan divalidasi ke radius lokasi sekolah.
                                                        </p>
                                                        
                                                        <button type="button" onclick="performGeolocationCheckIn({{ $session->id }})" id="btn-checkin-{{ $session->id }}" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-indigo-100 flex items-center justify-center space-x-1">
                                                            <span uk-icon="icon: sign-in; ratio: 0.85"></span>
                                                            <span>Kirim Presensi Kehadiran</span>
                                                        </button>
                                                        <div id="checkin-loader-{{ $session->id }}" class="hidden text-xs text-indigo-700 font-semibold items-center justify-center space-x-2 py-1.5 bg-indigo-50 border border-indigo-100 rounded-xl">
                                                            <span class="animate-spin inline-block w-4 h-4 border-2 border-indigo-600 border-t-transparent rounded-full align-middle"></span>
                                                            <span>Mengambil GPS & Verifikasi...</span>
                                                        </div>
                                                    </div>
                                                @endif

                                            @else
                                                <div class="bg-rose-50 border border-rose-200 rounded-xl p-4 text-center space-y-2 text-xs font-semibold text-rose-700">
                                                    <span uk-icon="icon: warning; ratio: 1.1"></span>
                                                    <h5 class="font-bold">Sesi Absensi Berakhir</h5>
                                                    <p class="text-[10px] text-slate-400 leading-normal">
                                                        Anda dinyatakan <strong>Alpa / Tidak Hadir</strong> pada pertemuan KBM ini karena sesi telah ditutup atau melewati batas tenggat.
                                                    </p>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                            </div>
                        @else
                            <div class="bg-white rounded-2xl border border-slate-100 p-12 text-center max-w-xl mx-auto space-y-4 soft-glow-indigo">
                                <div class="w-16 h-16 bg-slate-50 border border-slate-200 text-slate-400 rounded-2xl flex items-center justify-center mx-auto shadow-inner">
                                    <span uk-icon="icon: lock; ratio: 1.5"></span>
                                </div>
                                <h3 class="text-lg font-bold text-slate-800">Pertemuan Ke-{{ $i }} Belum Buka</h3>
                                <p class="text-xs text-slate-400 font-medium">Guru pengampu mata pelajaran belum membuka absensi presensi atau membagikan materi pembelajaran untuk pertemuan ini.</p>
                            </div>
                        @endif

                    </div>
                @endfor

            </div>

            <!-- TAB 2: SUBJECT MISSIONS -->
            <div id="tab-content-missions" class="main-tab-content space-y-6 hidden">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 soft-glow-indigo">
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Misi & Tugas Karakter Mata Pelajaran</h3>
                    <p class="text-xs text-slate-400 mb-6 font-medium font-semibold">Selesaikan misi kebaikan khusus untuk mata pelajaran {{ $assignment->subject->name }} untuk mengklaim reputasi poin.</p>

                    <div class="space-y-4">
                        @forelse($subjectMissions as $index => $mission)
                            @php
                                $color = $cardColors[$index % count($cardColors)];
                                $taken = $takenMissions->get($mission->id);
                            @endphp
                            <div class="p-5 border border-slate-100 rounded-2xl {{ $taken && $taken->pivot->status === 'approved' ? 'bg-slate-50/50 opacity-70' : 'bg-slate-50/30' }} hover:shadow-sm transition-all duration-300">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-3">
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider {{ $color['tag'] }}">
                                            {{ $mission->type ?? 'Class' }}
                                        </span>
                                        <span class="text-[10px] font-extrabold text-emerald-600">+{{ $mission->points_reward }} Pts</span>
                                    </div>
                                    @if($mission->deadline)
                                        <span class="text-[10px] text-slate-400 font-semibold flex items-center">
                                            <span uk-icon="icon: clock; ratio: 0.65" class="mr-1"></span>
                                            Tenggat: {{ \Carbon\Carbon::parse($mission->deadline)->format('d M Y, H:i') }}
                                        </span>
                                    @endif
                                </div>
                                
                                <h4 class="font-extrabold text-sm text-slate-800 leading-snug mb-1">{{ $mission->title }}</h4>
                                <p class="text-xs text-slate-500 leading-relaxed font-semibold">{{ $mission->description }}</p>

                                <div class="mt-4 pt-4 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wide">
                                        Metode Bukti: <span class="text-indigo-600">{{ $mission->proof_type === 'none' ? 'Tanpa Bukti' : $mission->proof_type }}</span>
                                    </span>

                                    <div class="w-full sm:w-auto">
                                        @if(!$taken)
                                            <form method="POST" action="{{ route('student.mission.take', $mission->id) }}" onsubmit="let btn = this.querySelector('button[type=submit]'); if(btn) { btn.disabled = true; btn.innerHTML = 'Mengambil...'; }">
                                                @csrf
                                                <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                                                    Ambil Misi
                                                </button>
                                            </form>
                                        @else
                                            @if($taken->pivot->status === 'taken')
                                                <form method="POST" action="{{ route('student.mission.submit', $mission->id) }}" enctype="multipart/form-data" class="flex items-center gap-2" onsubmit="let btn = this.querySelector('button[type=submit]'); if(btn) { btn.disabled = true; btn.innerHTML = '<span class=\'animate-spin inline-block w-3 h-3 border-2 border-current border-t-transparent rounded-full align-middle\'></span>'; }">
                                                    @csrf
                                                    @if($mission->proof_type === 'file')
                                                        <input type="file" name="proof_file" required class="text-xs border border-slate-200 rounded-xl px-2 py-1 bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 w-36 sm:w-44">
                                                    @elseif($mission->proof_type === 'text')
                                                        <input type="text" name="proof_text" placeholder="Tulis jawaban bukti..." required class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 w-36 sm:w-44">
                                                    @elseif($mission->proof_type === 'link')
                                                        <input type="url" name="proof_url" placeholder="https://..." required class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 w-36 sm:w-44">
                                                    @endif
                                                    <button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                                                        Kirim
                                                    </button>
                                                </form>
                                            @elseif($taken->pivot->status === 'pending_approval')
                                                <span class="px-3 py-1 bg-amber-50 border border-amber-100 text-amber-700 text-xs font-semibold rounded-lg flex items-center space-x-1">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                    <span>⏳ Menunggu Verifikasi Guru</span>
                                                </span>
                                            @elseif($taken->pivot->status === 'approved')
                                                <span class="px-3 py-1 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold rounded-lg flex items-center space-x-1">
                                                    <span uk-icon="icon: check; ratio: 0.75" class="text-emerald-600"></span>
                                                    <span>Misi Selesai</span>
                                                </span>
                                            @elseif($taken->pivot->status === 'rejected')
                                                <span class="px-3 py-1 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold rounded-lg flex items-center space-x-1">
                                                    <span uk-icon="icon: close; ratio: 0.75" class="text-rose-600"></span>
                                                    <span>Misi Ditolak</span>
                                                </span>
                                            @elseif($taken->pivot->status === 'revision')
                                                <div class="flex flex-col items-end gap-2 w-full">
                                                    <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl p-3 text-xs w-full">
                                                        <strong>Catatan Revisi Guru:</strong> {{ $taken->pivot->notes ?? '-' }}
                                                    </div>
                                                    <form method="POST" action="{{ route('student.mission.submit', $mission->id) }}" enctype="multipart/form-data" class="flex items-center gap-2" onsubmit="let btn = this.querySelector('button[type=submit]'); if(btn) { btn.disabled = true; btn.innerHTML = '<span class=\'animate-spin inline-block w-3 h-3 border-2 border-current border-t-transparent rounded-full align-middle\'></span>'; }">
                                                        @csrf
                                                        @if($mission->proof_type === 'file')
                                                            <input type="file" name="proof_file" required class="text-xs border border-slate-200 rounded-xl px-2 py-1 bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 w-36 sm:w-44">
                                                        @elseif($mission->proof_type === 'text')
                                                            <input type="text" name="proof_text" placeholder="Tulis perbaikan..." required class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 w-36 sm:w-44">
                                                        @elseif($mission->proof_type === 'link')
                                                            <input type="url" name="proof_url" placeholder="https://..." required class="border border-slate-200 rounded-xl px-3 py-1.5 text-xs bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 w-36 sm:w-44">
                                                        @endif
                                                        <button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                                                            Kirim Ulang
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12 text-slate-400 text-xs font-semibold bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                                ⚠️ Belum ada misi khusus mata pelajaran ini.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- TAB 3: CLASSMATES -->
            <div id="tab-content-classmates" class="main-tab-content space-y-6 hidden">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 soft-glow-indigo max-w-2xl mx-auto">
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Daftar Rekan Sekelas</h3>
                    <p class="text-xs text-slate-400 font-medium mb-6">Peringkat poin reputasi karakter seluruh rekan di kelas {{ $assignment->classroom->name }}.</p>
                    
                    <div class="divide-y divide-slate-100">
                        @foreach($classmates as $index => $mate)
                            <div class="p-4 flex items-center justify-between hover:bg-slate-50/50 transition-colors {{ $mate->id === $user->id ? 'bg-indigo-50/40 rounded-xl border border-indigo-100/30' : '' }}">
                                <div class="flex items-center space-x-3">
                                    <span class="text-xs font-extrabold w-4 text-center {{ $index === 0 ? 'text-amber-500' : ($index === 1 ? 'text-slate-400' : ($index === 2 ? 'text-orange-400' : 'text-slate-300')) }}">
                                        {{ $index + 1 }}
                                    </span>
                                    <div class="w-8 h-8 bg-indigo-50 border border-indigo-100/30 rounded-full flex items-center justify-center font-bold text-indigo-700 text-xs">
                                        {{ substr($mate->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="text-xs font-bold text-slate-850 block leading-tight {{ $mate->id === $user->id ? 'text-indigo-600' : '' }}">
                                            {{ $mate->name }}
                                            @if($mate->id === $user->id)
                                                <span class="text-[8px] bg-indigo-100 text-indigo-700 px-1 py-0.2 rounded font-black ml-1 uppercase">Anda</span>
                                            @endif
                                        </span>
                                        <span class="text-[9px] text-slate-400 block font-medium mt-0.5">{{ $mate->current_level ?? 'Pemula' }}</span>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-slate-700 whitespace-nowrap">{{ number_format($mate->points) }} Pts</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- GEOLOCATION LOGIC -->
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

        // Panel Switcher
        function showMeeting(num) {
            document.querySelectorAll('.meeting-detail-panel').forEach(p => p.classList.add('hidden'));
            document.querySelectorAll('.meeting-select-btn').forEach(btn => {
                btn.classList.remove('ring-2', 'ring-indigo-600', 'scale-[1.03]');
            });

            const panel = document.getElementById('meeting-panel-' + num);
            if (panel) {
                panel.classList.remove('hidden');
            }

            const selectedBtn = document.getElementById('btn-meeting-' + num);
            if (selectedBtn) {
                selectedBtn.classList.add('ring-2', 'ring-indigo-600', 'scale-[1.03]');
            }
        }

        // HTML5 Geolocation Check-In
        function performGeolocationCheckIn(sessionId) {
            const btn = document.getElementById('btn-checkin-' + sessionId);
            const loader = document.getElementById('checkin-loader-' + sessionId);

            if (!navigator.geolocation) {
                Swal.fire({
                    icon: 'error',
                    title: 'Browser Tidak Didukung',
                    text: 'Browser Anda tidak mendukung layanan lokasi/GPS.',
                    confirmButtonColor: '#4f46e5'
                });
                return;
            }

            btn.classList.add('hidden');
            loader.classList.remove('hidden');
            loader.classList.add('flex');

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    // Send coordinates via AJAX POST
                    fetch(`/student/sessions/${sessionId}/check-in`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            latitude: lat,
                            longitude: lng
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        loader.classList.add('hidden');
                        loader.classList.remove('flex');
                        btn.classList.remove('hidden');

                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Absen Berhasil!',
                                text: data.message,
                                confirmButtonColor: '#4f46e5'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Absen Gagal',
                                text: data.message,
                                confirmButtonColor: '#4f46e5'
                            });
                        }
                    })
                    .catch(err => {
                        loader.classList.add('hidden');
                        loader.classList.remove('flex');
                        btn.classList.remove('hidden');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan jaringan atau server saat melakukan absensi.',
                            confirmButtonColor: '#4f46e5'
                        });
                    });
                },
                function(error) {
                    loader.classList.add('hidden');
                    loader.classList.remove('flex');
                    btn.classList.remove('hidden');

                    let errMsg = 'Gagal mengakses GPS.';
                    if (error.code === error.PERMISSION_DENIED) {
                        errMsg = 'Izin akses lokasi ditolak oleh pengguna. Harap aktifkan izin lokasi di browser Anda.';
                    } else if (error.code === error.POSITION_UNAVAILABLE) {
                        errMsg = 'Posisi lokasi tidak tersedia. Coba lagi di luar ruangan.';
                    } else if (error.code === error.TIMEOUT) {
                        errMsg = 'Waktu permintaan lokasi habis.';
                    }
                    Swal.fire({
                        icon: 'warning',
                        title: 'Akses Lokasi Gagal',
                        text: errMsg,
                        confirmButtonColor: '#4f46e5'
                    });
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Highlight active session if there is one
            let activeMeeting = null;
            @foreach($sessions as $sess)
                @if($sess->status === 'open')
                    activeMeeting = {{ $sess->meeting_number }};
                @endif
            @endforeach

            if (activeMeeting) {
                showMeeting(activeMeeting);
            } else {
                showMeeting(1);
            }
        });
    </script>
</x-app-layout>
