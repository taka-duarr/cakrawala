<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">Kelas Saya</h2>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Daftar kelas akademik dan mata pelajaran yang sedang Anda ikuti.</p>
            </div>
            
            @if($classroom)
            <div class="flex items-center space-x-3">
                <span class="bg-[#E4FF1A] border-2 border-slate-950 text-slate-950 text-xs font-black px-3 py-1.5 rounded-xl uppercase tracking-wider shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                    Kelas: {{ $classroom->name }}
                </span>
                @if($classroom->jurusan)
                <span class="bg-white border-2 border-slate-950 text-slate-950 text-xs font-black px-3 py-1.5 rounded-xl uppercase tracking-wider shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                    {{ $classroom->jurusan->name }}
                </span>
                @endif
            </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12 bg-slate-100/30 min-h-screen">
        <div class="max-w-7xl mx-auto space-y-8">
            
            @if(!$classroom)
            <div class="bg-[#FFEAEA] border-4 border-slate-950 text-slate-950 p-8 rounded-3xl shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] text-center space-y-4">
                <div class="w-14 h-14 bg-rose-500 text-white border-2 border-slate-950 rounded-2xl flex items-center justify-center shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] mx-auto">
                    <span uk-icon="icon: warning; ratio: 1.4"></span>
                </div>
                <h3 class="font-black text-lg uppercase tracking-tight">Anda Belum Terdaftar di Kelas</h3>
                <p class="text-xs font-semibold leading-relaxed max-w-md mx-auto">Silakan hubungi Admin atau Wali Kelas Anda untuk mendaftarkan Anda ke kelas akademik aktif.</p>
            </div>
            @else
            <!-- Class Banner -->
            <div class="bg-[#E4FF1A] border-4 border-slate-950 rounded-3xl shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] p-8 text-slate-950 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2.5">
                    <span class="text-[9px] font-black uppercase tracking-wider bg-slate-950 text-white px-2.5 py-0.5 rounded border border-slate-950">
                        Kelas Akademik Aktif
                    </span>
                    <h1 class="text-3xl font-black uppercase tracking-tight leading-none">{{ $classroom->name }}</h1>
                    <p class="text-slate-800 text-xs max-w-xl font-semibold leading-relaxed">
                        Selamat belajar! Selesaikan tugas dan misi yang diberikan oleh masing-masing guru mata pelajaran untuk mengumpulkan poin reputasi karakter Anda.
                    </p>
                </div>
                <div class="bg-white border-2 border-slate-950 rounded-2xl p-5 min-w-[220px] text-right self-start md:self-auto shadow-[4px_4px_0px_0px_rgba(15,23,42,1)]">
                    <span class="text-[9px] text-slate-400 block uppercase font-bold tracking-wider mb-1">Skor Keaktifan Kelas</span>
                    <strong class="text-2xl text-slate-950 font-black block leading-none">{{ number_format($classroom->points) }} Pts</strong>
                    <span class="text-[8px] text-slate-400 block font-bold uppercase mt-1">Total poin seluruh anggota kelas.</span>
                </div>
            </div>

            <!-- List Mata Pelajaran -->
            <div class="bg-white rounded-3xl border-4 border-slate-950 p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
                    <div>
                        <h3 class="text-lg font-black text-slate-950 uppercase tracking-tight">Mata Pelajaran & Guru Pengampu</h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-0.5">Lihat jadwal kelas Anda, presensi, materi, dan tugas KBM.</p>
                    </div>
                    <!-- Toggle View Mode -->
                    <div class="flex space-x-2">
                        <button type="button" id="btn-mode-calendar" onclick="switchViewMode('calendar')" class="px-3 py-1.5 border-2 border-slate-950 rounded-xl text-[10px] font-black uppercase tracking-wider transition flex items-center space-x-1.5 bg-[#E4FF1A] text-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                            <span uk-icon="icon: calendar; ratio: 0.75"></span>
                            <span>Kalender</span>
                        </button>
                        <button type="button" id="btn-mode-list" onclick="switchViewMode('list')" class="px-3 py-1.5 border-2 border-slate-950 rounded-xl text-[10px] font-black uppercase tracking-wider transition flex items-center space-x-1.5 bg-white text-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] hover:bg-slate-50">
                            <span uk-icon="icon: list; ratio: 0.75"></span>
                            <span>Daftar</span>
                        </button>
                    </div>
                </div>
                
                <!-- Calendar View Mode -->
                <div id="view-mode-calendar" class="space-y-6">
                    <!-- Day tabs -->
                    <div class="border-b-2 border-slate-950">
                        <nav class="flex space-x-2" aria-label="Tabs">
                            @php
                                $days = [
                                    'Monday' => 'Senin',
                                    'Tuesday' => 'Selasa',
                                    'Wednesday' => 'Rabu',
                                    'Thursday' => 'Kamis',
                                    'Friday' => 'Jumat'
                                ];
                                $todayEnglish = \Carbon\Carbon::now()->format('l');
                                if (!in_array($todayEnglish, array_keys($days))) {
                                    $todayEnglish = 'Monday';
                                }
                            @endphp
                            @foreach($days as $eng => $ind)
                                <button type="button" onclick="switchCalendarTab('{{ $eng }}')" id="tab-day-{{ $eng }}" class="calendar-tab-btn py-2.5 px-4 font-black text-xs uppercase border-b-4 transition {{ $todayEnglish === $eng ? 'border-slate-950 text-slate-950' : 'border-transparent text-slate-400 hover:text-slate-700' }}">
                                    {{ $ind }}
                                </button>
                            @endforeach
                        </nav>
                    </div>

                    <!-- Day content -->
                    @foreach($days as $eng => $ind)
                        <div id="tab-content-{{ $eng }}" class="calendar-tab-content {{ $todayEnglish === $eng ? '' : 'hidden' }}">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                                @php
                                    $dayAssigns = collect($assignments)->filter(fn($a) => $a->day_of_week === $eng)->sortBy('start_time');
                                @endphp
                                @forelse($dayAssigns as $assign)
                                    <a href="{{ route('student.class-detail', $assign->id) }}" class="bg-white border-2 border-slate-950 rounded-2xl p-5 flex flex-col justify-between shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] hover:shadow-[5px_5px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-0.5 transition duration-150 group">
                                        <div class="space-y-4">
                                            <div class="flex justify-between items-center">
                                                <span class="bg-[#E4FF1A] text-slate-950 border border-slate-950 text-[9px] font-black px-2 py-0.5 rounded uppercase tracking-wider shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                                    {{ $assign->subject->code ?? 'MAPEL' }}
                                                </span>
                                                <span class="text-[9px] font-black text-white bg-slate-950 border border-slate-950 px-2 py-0.5 rounded shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                                    {{ $assign->start_time ? substr($assign->start_time, 0, 5) . ' - ' . substr($assign->end_time, 0, 5) : '00:00' }}
                                                </span>
                                            </div>
                                            
                                            <div>
                                                <h4 class="text-base font-black text-slate-950 group-hover:text-slate-700 transition-colors uppercase tracking-tight line-clamp-1">{{ $assign->subject->name }}</h4>
                                                <div class="flex items-center space-x-2 mt-3">
                                                    <div class="w-6 h-6 bg-[#FFEAEA] border-2 border-slate-950 text-slate-950 rounded-full flex items-center justify-center font-black text-[10px]">
                                                        {{ substr($assign->teacher->name ?? 'G', 0, 1) }}
                                                    </div>
                                                    <span class="text-xs text-slate-650 font-bold line-clamp-1">Guru: {{ $assign->teacher->name ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-6 pt-4 border-t-2 border-slate-950 flex justify-between items-center text-[9px] text-slate-400 font-bold uppercase tracking-wide">
                                            <span>Periode:</span>
                                            <span class="text-slate-950 bg-white px-2 py-0.5 rounded border border-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">{{ $assign->academicYear->name ?? '-' }}</span>
                                        </div>
                                    </a>
                                @empty
                                    <div class="col-span-3 text-center py-10 text-slate-400 text-xs font-bold uppercase tracking-wider bg-slate-50 rounded-2xl border-2 border-slate-950 border-dashed">
                                        Tidak ada jadwal KBM pada hari {{ $ind }}.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- List View Mode -->
                <div id="view-mode-list" class="space-y-6 hidden">
                    <!-- Today Section -->
                    <div>
                        <h4 class="text-xs font-black text-slate-950 uppercase tracking-wider mb-4 flex items-center space-x-2">
                            <span class="w-2.5 h-2.5 bg-[#E4FF1A] border border-slate-950 rounded-full animate-ping"></span>
                            <span>Hari Ini ({{ $days[\Carbon\Carbon::now()->format('l')] ?? 'Lainnya' }})</span>
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            @php
                                $todayEnglishReal = \Carbon\Carbon::now()->format('l');
                                $todayAssigns = collect($assignments)->filter(fn($a) => $a->day_of_week === $todayEnglishReal)->sortBy('start_time');
                            @endphp
                            @forelse($todayAssigns as $assign)
                                <a href="{{ route('student.class-detail', $assign->id) }}" class="bg-white border-2 border-slate-950 rounded-2xl p-5 flex flex-col justify-between shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] hover:shadow-[5px_5px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-0.5 transition duration-150 group">
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-center">
                                            <span class="bg-[#E4FF1A] text-slate-950 border border-slate-950 text-[9px] font-black px-2 py-0.5 rounded uppercase tracking-wider shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                                {{ $assign->subject->code ?? 'MAPEL' }}
                                            </span>
                                            <span class="text-[9px] font-black text-white bg-slate-950 border border-slate-950 px-2 py-0.5 rounded shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                                {{ $assign->start_time ? substr($assign->start_time, 0, 5) . ' - ' . substr($assign->end_time, 0, 5) : '00:00' }}
                                            </span>
                                        </div>
                                        
                                        <div>
                                            <h4 class="text-base font-black text-slate-950 group-hover:text-slate-700 transition-colors uppercase tracking-tight line-clamp-1">{{ $assign->subject->name }}</h4>
                                            <div class="flex items-center space-x-2 mt-3">
                                                <div class="w-6 h-6 bg-[#FFEAEA] border-2 border-slate-950 text-slate-950 rounded-full flex items-center justify-center font-black text-[10px]">
                                                    {{ substr($assign->teacher->name ?? 'G', 0, 1) }}
                                                </div>
                                                <span class="text-xs text-slate-650 font-bold line-clamp-1">Guru: {{ $assign->teacher->name ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-6 pt-4 border-t-2 border-slate-950 flex justify-between items-center text-[9px] text-slate-400 font-bold uppercase tracking-wide">
                                        <span>Periode:</span>
                                        <span class="text-slate-950 bg-white px-2 py-0.5 rounded border border-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">{{ $assign->academicYear->name ?? '-' }}</span>
                                    </div>
                                </a>
                            @empty
                                <div class="col-span-3 text-center py-8 text-slate-400 text-xs font-bold uppercase tracking-wider bg-slate-50 rounded-2xl border-2 border-slate-950 border-dashed">
                                    Tidak ada jadwal KBM untuk Anda hari ini.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Other Days Section -->
                    <div>
                        <h4 class="text-xs font-black text-slate-950 uppercase tracking-wider mb-4">Hari Lainnya</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            @php
                                $dayKeys = array_keys($days);
                                $otherAssigns = collect($assignments)
                                    ->filter(fn($a) => $a->day_of_week !== $todayEnglishReal)
                                    ->sortBy(fn($a) => array_search($a->day_of_week, $dayKeys) . $a->start_time);
                            @endphp
                            @forelse($otherAssigns as $assign)
                                <a href="{{ route('student.class-detail', $assign->id) }}" class="bg-white border-2 border-slate-950 rounded-2xl p-5 flex flex-col justify-between shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] hover:shadow-[5px_5px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-0.5 transition duration-150 group">
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-center">
                                            <span class="bg-[#E4FF1A] text-slate-950 border border-slate-950 text-[9px] font-black px-2 py-0.5 rounded uppercase tracking-wider shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                                {{ $assign->subject->code ?? 'MAPEL' }}
                                            </span>
                                            <span class="text-[9px] font-black text-slate-950 bg-slate-100 border border-slate-950 px-2 py-0.5 rounded shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase">
                                                {{ $assign->getDayTranslation() ?? 'Tanpa Hari' }}
                                            </span>
                                        </div>
                                        
                                        <div>
                                            <h4 class="text-base font-black text-slate-950 group-hover:text-slate-700 transition-colors uppercase tracking-tight line-clamp-1">{{ $assign->subject->name }}</h4>
                                            <div class="flex items-center space-x-2 mt-3">
                                                <div class="w-6 h-6 bg-[#FFEAEA] border-2 border-slate-950 text-slate-950 rounded-full flex items-center justify-center font-black text-[10px]">
                                                    {{ substr($assign->teacher->name ?? 'G', 0, 1) }}
                                                </div>
                                                <span class="text-xs text-slate-650 font-bold line-clamp-1">Guru: {{ $assign->teacher->name ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-6 pt-4 border-t-2 border-slate-950 flex justify-between items-center text-[9px] text-slate-400 font-bold uppercase tracking-wide">
                                        <span>Jam:</span>
                                        <span class="text-slate-950 bg-white px-2 py-0.5 rounded border border-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">{{ $assign->start_time ? substr($assign->start_time, 0, 5) . ' - ' . substr($assign->end_time, 0, 5) : '-' }}</span>
                                    </div>
                                </a>
                            @empty
                                <div class="col-span-3 text-center py-8 text-slate-400 text-xs font-bold uppercase tracking-wider bg-slate-50 rounded-2xl border-2 border-slate-950 border-dashed">
                                    Tidak ada jadwal KBM di hari lainnya.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function switchViewMode(mode) {
                    const calView = document.getElementById('view-mode-calendar');
                    const listView = document.getElementById('view-mode-list');
                    const btnCal = document.getElementById('btn-mode-calendar');
                    const btnList = document.getElementById('btn-mode-list');

                    if (mode === 'calendar') {
                        calView.classList.remove('hidden');
                        listView.classList.add('hidden');
                        btnCal.className = 'px-3 py-1.5 border-2 border-slate-950 rounded-xl text-[10px] font-black uppercase tracking-wider transition flex items-center space-x-1.5 bg-[#E4FF1A] text-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]';
                        btnList.className = 'px-3 py-1.5 border-2 border-slate-950 rounded-xl text-[10px] font-black uppercase tracking-wider transition flex items-center space-x-1.5 bg-white text-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] hover:bg-slate-50';
                    } else {
                        calView.classList.add('hidden');
                        listView.classList.remove('hidden');
                        btnList.className = 'px-3 py-1.5 border-2 border-slate-950 rounded-xl text-[10px] font-black uppercase tracking-wider transition flex items-center space-x-1.5 bg-[#E4FF1A] text-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]';
                        btnCal.className = 'px-3 py-1.5 border-2 border-slate-950 rounded-xl text-[10px] font-black uppercase tracking-wider transition flex items-center space-x-1.5 bg-white text-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] hover:bg-slate-50';
                    }
                }

                function switchCalendarTab(day) {
                    document.querySelectorAll('.calendar-tab-content').forEach(el => el.classList.add('hidden'));
                    document.querySelectorAll('.calendar-tab-btn').forEach(btn => {
                        btn.className = 'calendar-tab-btn py-2.5 px-4 font-black text-xs uppercase border-b-4 border-transparent text-slate-400 hover:text-slate-700 transition';
                    });

                    document.getElementById('tab-content-' + day).classList.remove('hidden');
                    document.getElementById('tab-day-' + day).className = 'calendar-tab-btn py-2.5 px-4 font-black text-xs uppercase border-b-4 border-slate-950 text-slate-950 transition';
                }
            </script>
            @endif
            
        </div>
    </div>
</x-app-layout>
