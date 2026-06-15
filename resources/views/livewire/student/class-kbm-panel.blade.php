<div wire:poll.10s class="space-y-6">
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
                <button type="button" wire:click="selectMeeting({{ $i }})" id="btn-meeting-{{ $i }}" class="meeting-select-btn p-3 rounded-xl border text-center transition-all duration-200 {{ $activeMeeting == $i ? 'ring-2 ring-indigo-600 scale-[1.03]' : '' }} {{ $session ? ($att && $att->status === 'hadir' ? 'bg-emerald-50 border-emerald-300 text-emerald-800 shadow-sm shadow-emerald-50' : 'bg-indigo-50/50 border-indigo-200 text-indigo-700 hover:bg-indigo-100') : 'bg-slate-50 border-slate-200 text-slate-400 hover:bg-slate-100' }}">
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
        <div id="meeting-panel-{{ $i }}" class="meeting-detail-panel {{ $activeMeeting == $i ? '' : 'hidden' }} space-y-6">
            
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
                                                    
                                                    <form action="{{ route('student.assignments.submit', $asg->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3 border-t border-amber-200/50 pt-3" onsubmit="let btn = this.querySelector('button[type=submit]'); if(btn) { btn.disabled = true; btn.innerHTML = '<span class=\'animate-spin inline-block w-3 h-3 border-2 border-current border-t-transparent rounded-full align-middle\'></span> Mengirim...'; }">
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
