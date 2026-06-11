@php
    $cardColors = [
        ['bg' => 'bg-rose-50/50 border-rose-100/40', 'text' => 'text-rose-900', 'btn' => 'bg-rose-600 hover:bg-rose-700 text-white', 'tag' => 'bg-rose-100 text-rose-700', 'points' => 'text-rose-600'],
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
                <a href="{{ route('student.my-classes') }}" class="text-xs font-semibold text-indigo-650 hover:text-indigo-700 flex items-center space-x-1 mb-1.5 transition">
                    <span uk-icon="icon: arrow-left; ratio: 0.8"></span>
                    <span>Kembali ke Kelas Saya</span>
                </a>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">Detail Mata Pelajaran</h2>
            </div>
            
            <div class="flex items-center space-x-2">
                <span class="bg-indigo-50 border border-indigo-150 text-indigo-700 text-xs font-bold px-3 py-1.5 rounded-xl uppercase tracking-wider">
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

            <!-- Success/Error Alerts -->
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 flex items-center space-x-3 shadow-sm text-xs font-semibold">
                    <span uk-icon="icon: check; ratio: 0.9" class="text-emerald-600"></span>
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

            <!-- Subject Card Banner -->
            <div class="bg-gradient-to-r from-indigo-900 to-slate-800 rounded-2xl shadow-md p-8 text-white flex flex-col md:flex-row md:items-center justify-between gap-6" style="background: linear-gradient(135deg, #1e1b4b, #0f172a);">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider bg-white/20 px-2.5 py-0.5 rounded-full inline-block mb-2 text-indigo-200">
                        {{ $assignment->subject->code ?? 'MAPEL' }}
                    </span>
                    <h1 class="text-3xl font-extrabold mb-1.5">{{ $assignment->subject->name }}</h1>
                    <p class="text-slate-200 text-sm max-w-xl font-medium leading-relaxed">
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

            <!-- Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left Column: Misi Mapel -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 soft-glow-indigo">
                        <h3 class="text-lg font-bold text-slate-800 mb-1">Misi & Tugas Mata Pelajaran</h3>
                        <p class="text-xs text-slate-400 mb-6 font-medium">Selesaikan misi spesifik dari mata pelajaran ini untuk mengklaim poin reputasi Anda.</p>

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
                                    <p class="text-xs text-slate-500 leading-relaxed">{{ $mission->description }}</p>

                                    <!-- Action section -->
                                    <div class="mt-4 pt-4 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                        <span class="text-[10px] text-slate-450 font-bold uppercase tracking-wide">
                                            Metode Bukti: <span class="text-indigo-650">{{ $mission->proof_type === 'none' ? 'Tanpa Bukti' : $mission->proof_type }}</span>
                                        </span>

                                        <div class="w-full sm:w-auto">
                                            @if(!$taken)
                                                <!-- Belum diambil -->
                                                <form method="POST" action="{{ route('student.mission.take', $mission->id) }}" onsubmit="let btn = this.querySelector('button[type=submit]'); if(btn) { btn.disabled = true; btn.innerHTML = '<span class=\'animate-spin inline-block w-3 h-3 border-2 border-current border-t-transparent rounded-full mr-1.5 align-middle\'></span> Mengambil...'; }">
                                                    @csrf
                                                    <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                                                        Ambil Misi
                                                    </button>
                                                </form>
                                            @else
                                                @if($taken->pivot->status === 'taken')
                                                    <!-- Sudah diambil, kirim bukti -->
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
                                                    <span class="px-3 py-1 bg-emerald-50 border border-emerald-150 text-emerald-700 text-xs font-semibold rounded-lg flex items-center space-x-1">
                                                        <span uk-icon="icon: check; ratio: 0.75" class="text-emerald-600"></span>
                                                        <span>Misi Selesai</span>
                                                    </span>
                                                @elseif($taken->pivot->status === 'rejected')
                                                    <span class="px-3 py-1 bg-rose-50 border border-rose-150 text-rose-700 text-xs font-semibold rounded-lg flex items-center space-x-1">
                                                        <span uk-icon="icon: close; ratio: 0.75" class="text-rose-600"></span>
                                                        <span>Misi Ditolak</span>
                                                    </span>
                                                @elseif($taken->pivot->status === 'revision')
                                                    <!-- Misi butuh revisi -->
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
                                <div class="text-center py-12 text-slate-400 text-xs font-semibold bg-slate-50/50 rounded-2xl border border-dashed border-slate-150">
                                    ⚠️ Belum ada misi khusus untuk mata pelajaran ini.
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

                <!-- Right Column: Guru, Absen & Teman Sekelas -->
                <div class="space-y-6 lg:col-span-1">

                    <!-- Profil Guru Card -->
                    <div class="bg-white rounded-2xl border border-slate-100 p-6 soft-glow-indigo">
                        <h4 class="font-bold text-slate-800 text-sm mb-4">Informasi Guru Pengampu</h4>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-indigo-50 border border-indigo-100 rounded-full flex items-center justify-center font-extrabold text-indigo-700 text-xs shadow-inner">
                                {{ substr($assignment->teacher->name ?? 'G', 0, 1) }}
                            </div>
                            <div>
                                <h5 class="font-bold text-slate-800 text-xs leading-none mb-1">{{ $assignment->teacher->name ?? '-' }}</h5>
                                <p class="text-[10px] text-slate-400 font-semibold">{{ $assignment->teacher->email ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Presensi Mapel -->
                    <div class="bg-white rounded-2xl border border-slate-100 p-6 soft-glow-indigo">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="p-2.5 bg-rose-50 text-rose-650 rounded-xl border border-rose-100/55">
                                <span uk-icon="icon: check; ratio: 1.1"></span>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm">Presensi / Absensi Mapel</h4>
                                <p class="text-[10px] text-slate-400 font-semibold">Absensi Anda untuk mata pelajaran ini.</p>
                            </div>
                        </div>
                        
                        <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 mb-4 text-center">
                            <span class="text-xs text-slate-500 font-medium block">Absensi Hari Ini</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-rose-50 text-rose-700 border border-rose-100/80 mt-1.5 uppercase tracking-wide">
                                Belum Dibuka
                            </span>
                        </div>

                        <button disabled class="w-full py-2.5 bg-slate-200 text-slate-400 rounded-xl text-xs font-bold transition cursor-not-allowed">
                            Lakukan Presensi Mapel
                        </button>
                        <span class="block text-[9px] text-slate-400 text-center font-medium mt-1.5">* Fitur absensi mapel akan diimplementasikan di fase berikutnya.</span>
                    </div>

                    <!-- Teman Sekelas / Leaderboard Kelas -->
                    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden soft-glow-indigo">
                        <div class="p-5 border-b border-slate-100">
                            <h4 class="font-bold text-slate-800 text-sm">Peringkat Keaktifan Kelas</h4>
                            <p class="text-[10px] text-slate-400 mt-0.5">Daftar teman sekelas Anda di kelas {{ $assignment->classroom->name }}.</p>
                        </div>
                        
                        <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                            @foreach($classmates as $index => $mate)
                                <div class="p-4 flex items-center justify-between hover:bg-slate-50/50 transition-colors {{ $mate->id === $user->id ? 'bg-indigo-50/40' : '' }}">
                                    <div class="flex items-center space-x-3">
                                        <span class="text-xs font-bold w-4 text-center {{ $index === 0 ? 'text-amber-500' : ($index === 1 ? 'text-slate-400' : ($index === 2 ? 'text-orange-400' : 'text-slate-300')) }}">
                                            {{ $index + 1 }}
                                        </span>
                                        <div class="w-7 h-7 bg-indigo-50 border border-indigo-100/30 rounded-full flex items-center justify-center font-bold text-indigo-700 text-[10px]">
                                            {{ substr($mate->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <span class="text-xs font-semibold text-slate-800 block leading-tight {{ $mate->id === $user->id ? 'font-bold text-indigo-650' : '' }}">
                                                {{ $mate->name }}
                                                @if($mate->id === $user->id)
                                                    <span class="text-[8px] bg-indigo-100 text-indigo-700 px-1 py-0.2 rounded">Anda</span>
                                                @endif
                                            </span>
                                            <span class="text-[9px] text-slate-450 block font-medium mt-0.5">{{ $mate->current_level ?? 'Pemula' }}</span>
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
    </div>
</x-app-layout>
