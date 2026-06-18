<div wire:poll.10s class="space-y-6">
    <!-- 16 Meetings Grid -->
    <div class="bg-white rounded-3xl border-4 border-slate-950 p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
        <h3 class="text-lg font-black text-slate-950 uppercase tracking-tight mb-1">Daftar Pertemuan KBM</h3>
        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-6">Pilih nomor pertemuan di bawah untuk mengunduh materi belajar, mengumpulkan tugas, atau melakukan presensi kehadiran.</p>
        
        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-8 gap-4">
            @for($i = 1; $i <= ($assignment->total_meetings ?? 16); $i++)
                @php
                    $session = $sessions->firstWhere('meeting_number', $i);
                    $att = $session ? $myAttendances->get($session->id) : null;
                @endphp
                <button type="button" wire:click="selectMeeting({{ $i }})" id="btn-meeting-{{ $i }}" class="meeting-select-btn p-3.5 rounded-xl border-2 border-slate-950 text-center transition-all duration-200 {{ $activeMeeting == $i ? 'ring-4 ring-slate-950 scale-[1.03] bg-[#E4FF1A] text-slate-950' : ($session ? ($att && $att->status === 'hadir' ? 'bg-[#EAFCEF] text-slate-950' : 'bg-indigo-50 text-slate-950') : 'bg-white text-slate-450') }} shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                    <div class="text-[9px] font-black uppercase tracking-wider">Pertemuan</div>
                    <div class="text-xl font-black mt-0.5">{{ $i }}</div>
                    <div class="text-[9px] font-black uppercase tracking-wider mt-1">
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
                        <div class="bg-white rounded-3xl border-4 border-slate-950 p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                            <div class="flex items-center justify-between border-b-2 border-slate-950 pb-4 mb-4 flex-wrap gap-2">
                                <div>
                                    <h3 class="text-lg font-black text-slate-950 uppercase tracking-tight">Materi & Tugas Pertemuan Ke-{{ $i }}</h3>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-0.5">Tanggal KBM: {{ \Carbon\Carbon::parse($session->session_date)->translatedFormat('l, d F Y') }}</p>
                                </div>
                                <span class="px-2.5 py-0.5 rounded border-2 border-slate-950 text-[10px] font-black uppercase tracking-wider shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] {{ $att && $att->status === 'hadir' ? 'bg-[#EAFCEF] text-emerald-805' : 'bg-[#FFEAEA] text-rose-805' }}">
                                    Kehadiran: {{ $att && $att->status === 'hadir' ? 'Hadir' : 'Alpa' }}
                                </span>
                            </div>

                            <!-- Materials List -->
                            <div class="space-y-3">
                                <h4 class="text-xs font-black text-slate-950 uppercase tracking-wider mb-2 flex items-center space-x-1.5">
                                    <span uk-icon="icon: copy; ratio: 0.75" class="text-slate-950"></span>
                                    <span>Materi Pembelajaran</span>
                                </h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @forelse($session->materials as $mat)
                                        <div class="bg-white border-2 border-slate-950 rounded-xl p-4 flex items-center justify-between shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                            <div class="truncate mr-3">
                                                <span class="text-xs font-black text-slate-950 block truncate uppercase tracking-tight">{{ $mat->title }}</span>
                                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Materi Pertemuan</span>
                                            </div>
                                            <a href="{{ $mat->file_path }}" target="_blank" class="p-2 bg-white hover:bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 rounded-lg transition shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]" title="Unduh Materi">
                                                <span uk-icon="icon: download; ratio: 0.85"></span>
                                            </a>
                                        </div>
                                    @empty
                                        <div class="col-span-2 py-4">
                                            <p class="text-xs text-slate-450 font-bold uppercase tracking-wider italic">Tidak ada dokumen materi pembelajaran untuk pertemuan ini.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Assignments List -->
                        <div class="bg-white rounded-3xl border-4 border-slate-950 p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                            <h4 class="text-xs font-black text-slate-950 uppercase tracking-wider mb-4 flex items-center space-x-1.5 border-b-2 border-slate-950 pb-2">
                                <span uk-icon="icon: file-edit; ratio: 0.75" class="text-slate-950"></span>
                                <span>Tugas Pertemuan Ke-{{ $i }}</span>
                            </h4>

                            @forelse($session->assignments as $asg)
                                @php
                                    $sub = $assignmentSubmissions->get($asg->id);
                                @endphp
                                <div class="bg-white border-2 border-slate-950 rounded-2xl p-5 space-y-4 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)]">
                                    <div class="flex justify-between items-start flex-wrap gap-2 border-b-2 border-slate-950 pb-3">
                                        <div>
                                            <h5 class="text-sm font-black text-slate-950 uppercase tracking-tight">{{ $asg->title }}</h5>
                                            <span class="text-[9px] text-emerald-800 font-black bg-[#EAFCEF] border border-slate-950 px-1.5 py-0.5 rounded shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider mt-1 inline-block">+{{ $asg->points_reward }} Pts Karakter</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-[9px] text-rose-700 font-black uppercase tracking-wider block">Tenggat: {{ \Carbon\Carbon::parse($asg->deadline)->translatedFormat('d F, H:i') }} WIB</span>
                                            @if($asg->file_path)
                                                <a href="{{ $asg->file_path }}" target="_blank" class="text-[10px] font-black text-slate-950 hover:underline inline-flex items-center space-x-0.5 mt-1.5 uppercase tracking-wider">
                                                    <span>Unduh Soal Tugas</span>
                                                    <span uk-icon="icon: download; ratio: 0.7"></span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                    <p class="text-xs text-slate-500 font-bold uppercase tracking-wider leading-normal">{{ $asg->description }}</p>

                                    <!-- Submission Form or Status -->
                                    <div class="bg-slate-50 border-2 border-slate-950 rounded-xl p-4 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                        <h6 class="text-[10px] font-black text-slate-450 uppercase tracking-wider mb-3">Status Pengumpulan Tugas Anda</h6>
                                        
                                        @if(!$sub)
                                            @if(\Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($asg->deadline)))
                                                <div class="text-xs text-rose-800 font-black bg-[#FFEAEA] border-2 border-slate-950 rounded-lg p-2.5 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider">
                                                    ⚠️ Batas pengumpulan tugas telah berakhir. Anda tidak dapat mengumpulkan tugas lagi.
                                                </div>
                                            @else
                                                <form action="{{ route('student.assignments.submit', $asg->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3" onsubmit="let btn = this.querySelector('button[type=submit]'); if(btn) { btn.disabled = true; btn.innerHTML = '<span>Mengirim...</span>'; }">
                                                    @csrf
                                                    <div>
                                                        <label class="block text-[9px] font-black text-slate-950 mb-1.5 uppercase tracking-wider">Catatan/Jawaban Teks</label>
                                                        <textarea name="text_content" rows="2" placeholder="Tulis jawaban teks atau tautan Google Drive Anda di sini..." class="w-full text-xs font-bold rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-0 focus:outline-none"></textarea>
                                                    </div>
                                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                                        <div>
                                                            <label class="block text-[9px] font-black text-slate-950 mb-1.5 uppercase tracking-wider">Unggah Berkas Jawaban (PDF, ZIP, Gambar)</label>
                                                            <input type="file" name="file" class="text-xs font-bold text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-2 file:border-slate-950 file:text-[10px] file:font-black file:bg-white file:text-slate-950 hover:file:bg-slate-100">
                                                        </div>
                                                        <button type="submit" class="px-5 py-2.5 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white border-2 border-slate-950 text-xs font-black rounded-xl shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition self-end uppercase tracking-wider">
                                                            Kumpulkan Tugas
                                                        </button>
                                                    </div>
                                                </form>
                                            @endif
                                        @else
                                            @if($sub->status === 'pending')
                                                <div class="bg-white border-2 border-slate-950 text-slate-950 p-3 rounded-lg text-xs font-black flex items-center space-x-1.5 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider">
                                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse block"></span>
                                                    <span>Tugas telah dikumpulkan. Menunggu penilaian & verifikasi dari Guru.</span>
                                                </div>
                                            @elseif($sub->status === 'approved')
                                                <div class="bg-[#EAFCEF] border-2 border-slate-950 text-emerald-800 p-4 rounded-xl space-y-2 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                                    <div class="text-xs font-black flex items-center space-x-1 text-emerald-800 uppercase tracking-wider">
                                                        <span uk-icon="icon: check; ratio: 0.8"></span>
                                                        <span>Tugas Disetujui & Dinilai!</span>
                                                    </div>
                                                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider leading-normal">
                                                        Anda mendapatkan <strong>+{{ $sub->points_awarded ?? $asg->points_reward }} Pts</strong> reputasi karakter disiplin.<br>
                                                        Catatan Guru: <span class="text-slate-950 font-black">"{{ $sub->notes ?? 'Sangat baik!' }}"</span>
                                                    </p>
                                                </div>
                                            @elseif($sub->status === 'revision')
                                                <div class="bg-amber-50 border-2 border-slate-950 rounded-xl p-4 space-y-3 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] text-slate-950">
                                                    <div class="text-xs font-black text-amber-800 uppercase tracking-wider">
                                                        ⚠️ Tugas Butuh Revisi
                                                    </div>
                                                    <p class="text-[11px] font-bold uppercase tracking-wider leading-normal">
                                                        Umpan balik Guru: <span class="text-slate-950 font-black">"{{ $sub->notes ?? '-' }}"</span>
                                                    </p>
                                                    
                                                    <form action="{{ route('student.assignments.submit', $asg->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3 border-t-2 border-slate-950 pt-3" onsubmit="let btn = this.querySelector('button[type=submit]'); if(btn) { btn.disabled = true; btn.innerHTML = '<span>Mengirim...</span>'; }">
                                                        @csrf
                                                        <div>
                                                            <label class="block text-[9px] font-black text-slate-950 mb-1.5 uppercase tracking-wider">Catatan/Teks Perbaikan</label>
                                                            <textarea name="text_content" rows="2" placeholder="Tulis catatan perbaikan di sini..." class="w-full text-xs font-bold rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-0 focus:outline-none">{{ $sub->text_content }}</textarea>
                                                        </div>
                                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                                            <div>
                                                                <label class="block text-[9px] font-black text-slate-950 mb-1.5 uppercase tracking-wider">Unggah Ulang Berkas Jawaban (Opsional)</label>
                                                                <input type="file" name="file" class="text-xs font-bold text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-2 file:border-slate-950 file:text-[10px] file:font-black file:bg-white file:text-slate-950 hover:file:bg-slate-100">
                                                            </div>
                                                            <button type="submit" class="px-5 py-2.5 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white border-2 border-slate-950 text-xs font-black rounded-xl shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition self-end uppercase tracking-wider">
                                                                Kirim Ulang Jawaban
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            @elseif($sub->status === 'rejected')
                                                <div class="bg-[#FFEAEA] border-2 border-slate-950 text-rose-800 p-3 rounded-lg text-xs font-black shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider">
                                                    ❌ Tugas ditolak oleh guru. Catatan: "{{ $sub->notes ?? '-' }}"
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-slate-450 font-bold uppercase tracking-wider italic">Tidak ada tugas yang ditambahkan pada pertemuan ini.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Check-In and Session Status (Col 1) -->
                    <div class="space-y-6 lg:col-span-1">
                        <div class="bg-white rounded-3xl border-4 border-slate-950 p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                            <h4 class="font-black text-slate-950 text-sm mb-4 uppercase tracking-tight">Presensi Kehadiran</h4>

                            @if($att && $att->status === 'hadir')
                                <div class="bg-[#EAFCEF] border-2 border-slate-950 rounded-xl p-4 text-center space-y-2 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                    <div class="w-10 h-10 bg-white border-2 border-slate-950 text-emerald-800 rounded-full flex items-center justify-center mx-auto shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                        <span uk-icon="icon: check; ratio: 1.1"></span>
                                    </div>
                                    <h5 class="text-xs font-black text-slate-950 uppercase tracking-tight">Anda Dinyatakan Hadir</h5>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider leading-normal">
                                        Absen pukul: {{ \Carbon\Carbon::parse($att->created_at)->format('H:i') }} WIB<br>
                                        Mendapatkan <strong>+{{ $att->points_awarded }} Pts</strong> kedisiplinan.
                                    </p>
                                </div>
                            @else
                                @if($session->status === 'open' && \Carbon\Carbon::now()->lt(\Carbon\Carbon::parse($session->deadline)))
                                    
                                    @if($session->mode === 'qr_location')
                                        <!-- QR Code Mode Alert -->
                                        <div class="bg-white border-2 border-slate-950 rounded-xl p-4 text-center space-y-2 text-xs shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                            <span uk-icon="icon: camera; ratio: 1.2" class="text-slate-950"></span>
                                            <h5 class="font-black text-slate-950 uppercase tracking-tight">Mode Scan QR Sekolah</h5>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider leading-relaxed">
                                                Mata pelajaran ini memerlukan Scan QR Code yang diproyeksikan oleh Guru di depan kelas untuk verifikasi lokasi GPS Anda.
                                            </p>
                                        </div>
                                    @else
                                        <!-- Button Mode Check-In -->
                                        <div class="bg-white border-2 border-slate-950 rounded-2xl p-4 text-center space-y-3 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                            <span uk-icon="icon: location; ratio: 1.2" class="text-slate-950"></span>
                                            <h5 class="text-xs font-black text-slate-950 uppercase tracking-tight">Klik Kehadiran GPS</h5>
                                            <p class="text-[9px] text-slate-400 leading-relaxed font-bold uppercase">
                                                Pastikan GPS / lokasi browser HP Anda aktif. Jarak koordinat GPS akan divalidasi ke radius lokasi sekolah.
                                            </p>
                                            
                                            <button type="button" onclick="performGeolocationCheckIn({{ $session->id }})" id="btn-checkin-{{ $session->id }}" class="w-full py-2.5 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white border-2 border-slate-950 rounded-xl text-xs font-black transition shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] flex items-center justify-center space-x-1 uppercase tracking-wider">
                                                <span uk-icon="icon: sign-in; ratio: 0.85"></span>
                                                <span>Kirim Presensi Kehadiran</span>
                                            </button>
                                            <div id="checkin-loader-{{ $session->id }}" class="hidden text-xs text-slate-950 font-black items-center justify-center space-x-2 py-1.5 bg-white border-2 border-slate-950 rounded-xl shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                                <span class="animate-spin inline-block w-4 h-4 border-2 border-slate-950 border-t-transparent rounded-full align-middle"></span>
                                                <span>Mengambil GPS & Verifikasi...</span>
                                            </div>
                                        </div>
                                    @endif

                                @else
                                    <div class="bg-[#FFEAEA] border-2 border-slate-950 rounded-xl p-4 text-center space-y-2 text-xs font-bold text-rose-800 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider">
                                        <span uk-icon="icon: warning; ratio: 1.1"></span>
                                        <h5 class="font-black">Sesi Absensi Berakhir</h5>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider leading-normal">
                                            Anda dinyatakan <strong>Alpa / Tidak Hadir</strong> pada pertemuan KBM ini karena sesi telah ditutup atau melewati batas tenggat.
                                        </p>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                </div>
            @else
                <div class="bg-white rounded-3xl border-4 border-slate-950 p-12 text-center max-w-xl mx-auto space-y-4 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                    <div class="w-16 h-16 bg-[#FFEAEA] border-2 border-slate-950 text-slate-950 rounded-2xl flex items-center justify-center mx-auto shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                        <span uk-icon="icon: lock; ratio: 1.5"></span>
                    </div>
                    <h3 class="text-lg font-black text-slate-950 uppercase tracking-tight">Pertemuan Ke-{{ $i }} Belum Buka</h3>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider leading-relaxed">Guru pengampu mata pelajaran belum membuka absensi presensi atau membagikan materi pembelajaran untuk pertemuan ini.</p>
                </div>
            @endif

        </div>
    @endfor
</div>
