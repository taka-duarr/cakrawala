@php
    $cardColors = [
        ['bg' => 'bg-[#FFEAEA] text-slate-950 border-2 border-slate-950', 'text' => 'text-slate-950', 'btn' => 'bg-slate-950 hover:bg-[#E4FF1A] text-white hover:text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]', 'tag' => 'bg-white text-slate-950 border border-slate-950', 'points' => 'text-slate-950'],
        ['bg' => 'bg-[#FFF3EA] text-slate-950 border-2 border-slate-950', 'text' => 'text-slate-950', 'btn' => 'bg-slate-950 hover:bg-[#E4FF1A] text-white hover:text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]', 'tag' => 'bg-white text-slate-950 border border-slate-950', 'points' => 'text-slate-950'],
        ['bg' => 'bg-[#EAFCEF] text-slate-950 border-2 border-slate-950', 'text' => 'text-slate-950', 'btn' => 'bg-slate-950 hover:bg-[#E4FF1A] text-white hover:text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]', 'tag' => 'bg-white text-slate-950 border border-slate-950', 'points' => 'text-slate-950'],
        ['bg' => 'bg-white text-slate-950 border-2 border-slate-950', 'text' => 'text-slate-950', 'btn' => 'bg-slate-950 hover:bg-[#E4FF1A] text-white hover:text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]', 'tag' => 'bg-white text-slate-950 border border-slate-950', 'points' => 'text-slate-950']
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('student.my-classes') }}" class="text-xs font-black text-slate-950 hover:underline flex items-center space-x-1.5 mb-1.5 transition">
                    <span uk-icon="icon: arrow-left; ratio: 0.8"></span>
                    <span class="uppercase tracking-wider">Kembali ke Kelas Saya</span>
                </a>
                <h2 class="font-black text-2xl text-slate-950 uppercase tracking-tight leading-tight">Detail Mata Pelajaran</h2>
            </div>
            
            <div class="flex items-center space-x-3">
                <span class="bg-[#E4FF1A] border-2 border-slate-950 text-slate-950 text-xs font-black px-3 py-1.5 rounded-xl uppercase tracking-wider shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                    Kelas: {{ $assignment->classroom->name }}
                </span>
                <span class="bg-white border-2 border-slate-950 text-slate-950 text-xs font-black px-3 py-1.5 rounded-xl shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                    {{ $assignment->academicYear->name ?? '-' }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-100/30 min-h-screen">
        <div class="max-w-7xl mx-auto space-y-8">

            <!-- Alerts -->
            @if(session('success'))
                <div class="bg-[#EAFCEF] border-2 border-slate-950 text-emerald-800 rounded-xl p-4 flex items-center space-x-3 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] text-xs font-black uppercase tracking-wider">
                    <span uk-icon="icon: check; ratio: 0.9" class="text-emerald-700"></span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-[#FFEAEA] border-2 border-slate-950 text-rose-800 rounded-xl p-4 flex items-center space-x-3 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] text-xs font-black uppercase tracking-wider">
                    <span uk-icon="icon: warning; ratio: 0.9" class="text-rose-700"></span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Subject Banner -->
            <div class="bg-[#E4FF1A] border-4 border-slate-950 rounded-3xl shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] p-8 text-slate-950 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2.5">
                    <span class="text-[9px] font-black uppercase tracking-wider bg-slate-950 text-white px-2.5 py-0.5 rounded border border-slate-950">
                        {{ $assignment->subject->code ?? 'MAPEL' }}
                    </span>
                    <h1 class="text-3xl font-black uppercase tracking-tight leading-none">{{ $assignment->subject->name }}</h1>
                    <p class="text-slate-800 text-xs max-w-xl font-semibold leading-relaxed">
                        {{ $assignment->subject->description ?? 'Tidak ada deskripsi detail untuk mata pelajaran ini.' }}
                    </p>
                </div>
                <div class="bg-white border-2 border-slate-950 rounded-2xl p-5 min-w-[220px] text-right self-start md:self-auto shadow-[4px_4px_0px_0px_rgba(15,23,42,1)]">
                    <span class="text-[9px] text-slate-400 block uppercase font-bold tracking-wider mb-1">Guru Pengampu</span>
                    <strong class="text-base text-slate-950 font-black block leading-tight uppercase">{{ $assignment->teacher->name ?? '-' }}</strong>
                    <span class="text-[9px] text-slate-400 block uppercase font-bold tracking-wider mt-3.5 mb-1">Semester</span>
                    <strong class="text-xs text-slate-950 font-black block uppercase">{{ $assignment->semester->name ?? '-' }} @if($assignment->semester) ({{ $assignment->semester->is_active ? 'Aktif' : 'Non-aktif' }}) @endif</strong>
                </div>
            </div>

            <!-- TAB SWITCHER -->
            <div class="border-b-2 border-slate-950">
                <nav class="flex space-x-6" aria-label="Tabs">
                    <button onclick="switchMainTab('kbm')" id="btn-tab-kbm" class="main-tab-btn py-3 px-1 font-black text-xs border-b-4 border-slate-950 text-slate-950 transition flex items-center space-x-2 uppercase tracking-wider">
                        <span uk-icon="icon: grid; ratio: 0.85"></span>
                        <span>Materi & Tugas KBM (1-{{ $assignment->total_meetings ?? 16 }})</span>
                    </button>
                    <button onclick="switchMainTab('missions')" id="btn-tab-missions" class="main-tab-btn py-3 px-1 font-bold text-xs border-b-4 border-transparent text-slate-400 hover:text-slate-950 transition flex items-center space-x-2 uppercase tracking-wider">
                        <span uk-icon="icon: file-edit; ratio: 0.85"></span>
                        <span>Misi Karakter & Reputasi</span>
                    </button>
                    <button onclick="switchMainTab('classmates')" id="btn-tab-classmates" class="main-tab-btn py-3 px-1 font-bold text-xs border-b-4 border-transparent text-slate-400 hover:text-slate-950 transition flex items-center space-x-2 uppercase tracking-wider">
                        <span uk-icon="icon: users; ratio: 0.85"></span>
                        <span>Teman Sekelas</span>
                    </button>
                </nav>
            </div>

            <!-- TAB 1: KBM & PRESENSI CONTENT -->
            <div id="tab-content-kbm" class="main-tab-content space-y-6">
                @livewire('student.class-kbm-panel', ['assignmentId' => $assignment->id])
            </div>

            <!-- TAB 2: SUBJECT MISSIONS -->
            <div id="tab-content-missions" class="main-tab-content space-y-6 hidden">
                <div class="bg-white rounded-3xl border-4 border-slate-950 p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                    <h3 class="text-lg font-black text-slate-950 mb-1 uppercase tracking-tight">Misi & Tugas Karakter Mata Pelajaran</h3>
                    <p class="text-xs text-slate-400 mb-6 font-bold uppercase tracking-wider">Selesaikan misi kebaikan khusus untuk mata pelajaran {{ $assignment->subject->name }} untuk mengklaim reputasi poin.</p>

                    <div class="space-y-4">
                        @forelse($subjectMissions as $index => $mission)
                            @php
                                $color = $cardColors[$index % count($cardColors)];
                                $taken = $takenMissions->get($mission->id);
                            @endphp
                            <div class="p-5 border-2 border-slate-950 rounded-2xl {{ $taken && $taken->pivot->status === 'approved' ? 'bg-slate-50 opacity-70' : 'bg-white' }} shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] hover:shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-0.5 transition duration-150">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-3">
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2.5 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider {{ $color['tag'] }} border border-slate-950">
                                            {{ $mission->type ?? 'Class' }}
                                        </span>
                                        <span class="text-[10px] font-black text-slate-950 uppercase">+{{ $mission->points_reward }} Pts</span>
                                    </div>
                                    @if($mission->deadline)
                                        <span class="text-[10px] text-slate-400 font-bold uppercase flex items-center">
                                            <span uk-icon="icon: clock; ratio: 0.65" class="mr-1"></span>
                                            Tenggat: {{ \Carbon\Carbon::parse($mission->deadline)->format('d M Y, H:i') }}
                                        </span>
                                    @endif
                                </div>
                                
                                <h4 class="font-black text-sm text-slate-950 uppercase tracking-tight leading-snug mb-1">{{ $mission->title }}</h4>
                                <p class="text-xs text-slate-700 font-semibold leading-relaxed">{{ $mission->description }}</p>

                                <div class="mt-4 pt-4 border-t-2 border-slate-950 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wide">
                                        Metode Bukti: <span class="text-slate-950 font-black">{{ $mission->proof_type === 'none' ? 'Tanpa Bukti' : $mission->proof_type }}</span>
                                    </span>

                                    <div class="w-full sm:w-auto">
                                        @if(!$taken)
                                            <form method="POST" action="{{ route('student.mission.take', $mission->id) }}" onsubmit="let btn = this.querySelector('button[type=submit]'); if(btn) { btn.disabled = true; btn.innerHTML = 'Mengambil...'; }">
                                                @csrf
                                                <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-slate-950 hover:bg-[#E4FF1A] hover:text-slate-950 text-white border-2 border-slate-950 rounded-xl text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] transition">
                                                    Ambil Misi
                                                </button>
                                            </form>
                                        @else
                                            @if($taken->pivot->status === 'taken')
                                                <form method="POST" action="{{ route('student.mission.submit', $mission->id) }}" enctype="multipart/form-data" class="flex items-center gap-2" onsubmit="let btn = this.querySelector('button[type=submit]'); if(btn) { btn.disabled = true; btn.innerHTML = '<span class=\'animate-spin inline-block w-3 h-3 border-2 border-current border-t-transparent rounded-full align-middle\'></span>'; }">
                                                    @csrf
                                                    @if($mission->proof_type === 'file')
                                                        <input type="file" name="proof_file" required class="text-xs border-2 border-slate-950 rounded-xl px-2 py-1 bg-white focus:outline-none w-36 sm:w-44">
                                                    @elseif($mission->proof_type === 'text')
                                                        <input type="text" name="proof_text" placeholder="Tulis jawaban bukti..." required class="border-2 border-slate-950 rounded-xl px-3 py-1.5 text-xs bg-white focus:outline-none w-36 sm:w-44">
                                                    @elseif($mission->proof_type === 'link')
                                                        <input type="url" name="proof_url" placeholder="https://..." required class="border-2 border-slate-950 rounded-xl px-3 py-1.5 text-xs bg-white focus:outline-none w-36 sm:w-44">
                                                    @endif
                                                    <button type="submit" class="px-4 py-2 bg-slate-950 hover:bg-[#E4FF1A] hover:text-slate-950 text-white border-2 border-slate-950 rounded-xl text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] transition">
                                                        Kirim
                                                    </button>
                                                </form>
                                            @elseif($taken->pivot->status === 'pending_approval')
                                                <span class="px-3 py-1 bg-[#FFF3EA] border-2 border-slate-950 text-amber-700 text-xs font-black uppercase rounded-lg flex items-center space-x-1.5 shadow-[1.5px_1.5px_0px_0px_rgba(15,23,42,1)]">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse border border-slate-950"></span>
                                                    <span>⏳ Menunggu Verifikasi Guru</span>
                                                </span>
                                            @elseif($taken->pivot->status === 'approved')
                                                <span class="px-3 py-1 bg-[#EAFCEF] border-2 border-slate-950 text-emerald-700 text-xs font-black uppercase rounded-lg flex items-center space-x-1.5 shadow-[1.5px_1.5px_0px_0px_rgba(15,23,42,1)]">
                                                    <span uk-icon="icon: check; ratio: 0.75" class="text-emerald-700"></span>
                                                    <span>Misi Selesai</span>
                                                </span>
                                            @elseif($taken->pivot->status === 'rejected')
                                                <span class="px-3 py-1 bg-[#FFEAEA] border-2 border-slate-950 text-rose-700 text-xs font-black uppercase rounded-lg flex items-center space-x-1.5 shadow-[1.5px_1.5px_0px_0px_rgba(15,23,42,1)]">
                                                    <span uk-icon="icon: close; ratio: 0.75" class="text-rose-700"></span>
                                                    <span>Misi Ditolak</span>
                                                </span>
                                            @elseif($taken->pivot->status === 'revision')
                                                <div class="flex flex-col items-end gap-3 w-full">
                                                    <div class="bg-[#FFF3EA] border-2 border-slate-950 text-slate-950 rounded-xl p-3 text-xs w-full shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                                        <strong class="uppercase text-[9px] font-black text-amber-700 block mb-1">Catatan Revisi Guru:</strong> {{ $taken->pivot->notes ?? '-' }}
                                                    </div>
                                                    <form method="POST" action="{{ route('student.mission.submit', $mission->id) }}" enctype="multipart/form-data" class="flex items-center gap-2" onsubmit="let btn = this.querySelector('button[type=submit]'); if(btn) { btn.disabled = true; btn.innerHTML = '<span class=\'animate-spin inline-block w-3 h-3 border-2 border-current border-t-transparent rounded-full align-middle\'></span>'; }">
                                                        @csrf
                                                        @if($mission->proof_type === 'file')
                                                            <input type="file" name="proof_file" required class="text-xs border-2 border-slate-950 rounded-xl px-2 py-1 bg-white focus:outline-none w-36 sm:w-44">
                                                        @elseif($mission->proof_type === 'text')
                                                            <input type="text" name="proof_text" placeholder="Tulis perbaikan..." required class="border-2 border-slate-950 rounded-xl px-3 py-1.5 text-xs bg-white focus:outline-none w-36 sm:w-44">
                                                        @elseif($mission->proof_type === 'link')
                                                            <input type="url" name="proof_url" placeholder="https://..." required class="border-2 border-slate-950 rounded-xl px-3 py-1.5 text-xs bg-white focus:outline-none w-36 sm:w-44">
                                                        @endif
                                                        <button type="submit" class="px-4 py-2 bg-slate-950 hover:bg-[#E4FF1A] hover:text-slate-950 text-white border-2 border-slate-950 rounded-xl text-xs font-black uppercase tracking-wider shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] transition">
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
                            <div class="text-center py-12 text-slate-400 text-xs font-bold uppercase tracking-wider bg-slate-50 border-2 border-slate-950 rounded-2xl border-dashed">
                                ⚠️ Belum ada misi khusus mata pelajaran ini.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- TAB 3: CLASSMATES -->
            <div id="tab-content-classmates" class="main-tab-content space-y-6 hidden">
                <div class="bg-white rounded-3xl border-4 border-slate-950 p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] max-w-2xl mx-auto">
                    <h3 class="text-lg font-black text-slate-950 mb-1 uppercase tracking-tight">Daftar Rekan Sekelas</h3>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-6">Peringkat poin reputasi karakter seluruh rekan di kelas {{ $assignment->classroom->name }}.</p>
                    
                    <div class="divide-y-2 divide-slate-950 border-2 border-slate-950 rounded-2xl bg-white overflow-hidden shadow-[4px_4px_0px_0px_rgba(15,23,42,1)]">
                        @foreach($classmates as $index => $mate)
                            <div class="p-4 flex items-center justify-between hover:bg-slate-100 transition-colors {{ $mate->id === $user->id ? 'bg-[#E4FF1A]/10' : '' }}">
                                <div class="flex items-center space-x-3">
                                    <span class="text-xs font-black w-4 text-center {{ $index === 0 ? 'text-amber-500' : ($index === 1 ? 'text-slate-400' : ($index === 2 ? 'text-orange-500' : 'text-slate-300')) }}">
                                        {{ $index + 1 }}
                                    </span>
                                    <div class="w-8 h-8 bg-white border-2 border-slate-950 rounded-full flex items-center justify-center font-black text-slate-950 text-xs">
                                        {{ substr($mate->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="text-xs font-black text-slate-950 uppercase tracking-tight block leading-tight {{ $mate->id === $user->id ? 'text-slate-950' : '' }}">
                                            {{ $mate->name }}
                                            @if($mate->id === $user->id)
                                                <span class="text-[8px] bg-slate-950 text-white px-1.5 py-0.5 border border-slate-950 rounded font-black ml-1.5 uppercase">Anda</span>
                                            @endif
                                        </span>
                                        <span class="text-[9px] text-slate-400 font-bold uppercase mt-1 block leading-none">{{ $mate->current_level ?? 'Pemula' }}</span>
                                    </div>
                                </div>
                                <span class="text-xs font-black text-slate-950 whitespace-nowrap uppercase">{{ number_format($mate->points) }} Pts</span>
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
                btn.className = 'main-tab-btn py-3 px-1 font-bold text-xs border-b-4 border-transparent text-slate-400 hover:text-slate-950 transition flex items-center space-x-2 uppercase tracking-wider';
            });

            document.getElementById('tab-content-' + tabName).classList.remove('hidden');
            document.getElementById('btn-tab-' + tabName).className = 'main-tab-btn py-3 px-1 font-black text-xs border-b-4 border-slate-950 text-slate-950 transition flex items-center space-x-2 uppercase tracking-wider';
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
                    confirmButtonColor: '#0f172a'
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
                                confirmButtonColor: '#0f172a'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Absen Gagal',
                                text: data.message,
                                confirmButtonColor: '#0f172a'
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
                            confirmButtonColor: '#0f172a'
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
                        confirmButtonColor: '#0f172a'
                    });
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }
    </script>
</x-app-layout>
