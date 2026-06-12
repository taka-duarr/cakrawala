<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">Kelas Saya</h2>
                <p class="text-xs text-slate-400 font-medium">Daftar kelas akademik dan mata pelajaran yang sedang Anda ikuti.</p>
            </div>
            
            @if($classroom)
            <div class="flex items-center space-x-2">
                <span class="bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1.5 rounded-xl uppercase tracking-wider">
                    Kelas: {{ $classroom->name }}
                </span>
                @if($classroom->jurusan)
                <span class="bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1.5 rounded-xl uppercase tracking-wider">
                    {{ $classroom->jurusan->name }}
                </span>
                @endif
            </div>
            @endif
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto space-y-6">
            
            @if(!$classroom)
            <div class="bg-amber-50 border border-amber-200 text-amber-700 p-6 rounded-2xl shadow-sm text-center">
                <span uk-icon="icon: warning; ratio: 1.5" class="text-amber-500 mb-2"></span>
                <h3 class="font-bold text-sm text-slate-800">Anda Belum Terdaftar di Kelas</h3>
                <p class="text-xs text-slate-500 mt-1">Silakan hubungi Admin atau Wali Kelas Anda untuk mendaftarkan Anda ke kelas akademik aktif.</p>
            </div>
            @else
            <!-- Class Banner -->
            <div class="bg-gradient-to-r from-indigo-900 to-indigo-750 rounded-2xl shadow-md p-8 text-white flex flex-col md:flex-row md:items-center justify-between gap-6" style="background: linear-gradient(135deg, #1e1b4b, #312e81);">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider bg-white/20 px-2.5 py-0.5 rounded-full inline-block mb-2 text-indigo-200">
                        Kelas Akademik Aktif
                    </span>
                    <h1 class="text-3xl font-extrabold mb-1.5">{{ $classroom->name }}</h1>
                    <p class="text-indigo-100 text-xs max-w-xl font-medium leading-relaxed">
                        Selamat belajar! Selesaikan tugas dan misi yang diberikan oleh masing-masing guru mata pelajaran untuk mengumpulkan poin reputasi karakter Anda.
                    </p>
                </div>
                <div class="bg-white/10 backdrop-blur border border-white/20 rounded-2xl p-5 min-w-[200px] text-right self-start md:self-auto">
                    <span class="text-[10px] text-indigo-200 block uppercase font-bold tracking-wider mb-1">Skor Keaktifan Kelas</span>
                    <strong class="text-2xl text-white font-extrabold block">{{ number_format($classroom->points) }} Pts</strong>
                    <span class="text-[9px] text-indigo-200 block font-medium mt-1">Total akumulasi poin kebaikan seluruh anggota kelas.</span>
                </div>
            </div>

            <!-- List Mata Pelajaran -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 soft-glow-indigo">
                <div class="flex items-center justify-between mb-6 flex-wrap gap-2">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 mb-1">Mata Pelajaran & Guru Pengampu</h3>
                        <p class="text-xs text-slate-400 font-medium">Lihat jadwal kelas Anda, presensi, materi, dan tugas KBM.</p>
                    </div>
                    <!-- Toggle View Mode -->
                    <div class="bg-slate-105 p-1 rounded-xl flex space-x-1 border border-slate-205">
                        <button type="button" id="btn-mode-calendar" onclick="switchViewMode('calendar')" class="px-3 py-1.5 rounded-lg text-[10px] font-bold transition flex items-center space-x-1.5 bg-white text-indigo-700 shadow-sm border border-slate-200/50">
                            <span uk-icon="icon: calendar; ratio: 0.75"></span>
                            <span>Kalender</span>
                        </button>
                        <button type="button" id="btn-mode-list" onclick="switchViewMode('list')" class="px-3 py-1.5 rounded-lg text-[10px] font-bold transition flex items-center space-x-1.5 text-slate-500 hover:text-slate-800">
                            <span uk-icon="icon: list; ratio: 0.75"></span>
                            <span>Daftar</span>
                        </button>
                    </div>
                </div>
                
                <!-- Calendar View Mode -->
                <div id="view-mode-calendar" class="space-y-4">
                    <!-- Day tabs -->
                    <div class="border-b border-slate-100">
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
                                <button type="button" onclick="switchCalendarTab('{{ $eng }}')" id="tab-day-{{ $eng }}" class="calendar-tab-btn py-2.5 px-4 font-bold text-xs border-b-2 transition {{ $todayEnglish === $eng ? 'border-indigo-600 text-indigo-700' : 'border-transparent text-slate-400 hover:text-slate-700 hover:border-slate-300' }}">
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
                                    <a href="{{ route('student.class-detail', $assign->id) }}" class="bg-slate-50/70 border border-slate-100 hover:border-indigo-200 rounded-2xl p-5 flex flex-col justify-between hover:shadow-md transition-all duration-300 group hover:-translate-y-0.5 relative overflow-hidden">
                                        <div class="space-y-4">
                                            <div class="flex justify-between items-center">
                                                <span class="bg-indigo-50 text-indigo-700 group-hover:bg-indigo-600 group-hover:text-white text-[9px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider transition-colors">
                                                    {{ $assign->subject->code ?? 'MAPEL' }}
                                                </span>
                                                <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50/50 px-2 py-0.5 rounded-md">
                                                    {{ $assign->start_time ? substr($assign->start_time, 0, 5) . ' - ' . substr($assign->end_time, 0, 5) : '00:00' }}
                                                </span>
                                            </div>
                                            
                                            <div>
                                                <h4 class="text-base font-extrabold text-slate-800 group-hover:text-indigo-600 transition-colors line-clamp-1">{{ $assign->subject->name }}</h4>
                                                <div class="flex items-center space-x-2 mt-2">
                                                    <div class="w-6 h-6 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center font-bold text-[10px]">
                                                        {{ substr($assign->teacher->name ?? 'G', 0, 1) }}
                                                    </div>
                                                    <span class="text-xs text-slate-500 font-semibold line-clamp-1">Guru: {{ $assign->teacher->name ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-6 pt-4 border-t border-slate-100 flex justify-between items-center text-[9px] text-slate-500 font-bold uppercase tracking-wide">
                                            <span>Periode:</span>
                                            <span class="text-slate-700 bg-white px-2 py-0.5 rounded-md border border-slate-100 shadow-sm">{{ $assign->academicYear->name ?? '-' }}</span>
                                        </div>
                                    </a>
                                @empty
                                    <div class="col-span-3 text-center py-10 text-slate-400 text-xs font-semibold bg-slate-50/40 rounded-2xl border border-dashed border-slate-200">
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
                        <h4 class="text-xs font-bold text-indigo-700 uppercase tracking-wider mb-3 flex items-center space-x-1.5">
                            <span class="w-1.5 h-1.5 bg-indigo-600 rounded-full animate-ping"></span>
                            <span>Hari Ini ({{ $days[\Carbon\Carbon::now()->format('l')] ?? 'Lainnya' }})</span>
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            @php
                                $todayEnglishReal = \Carbon\Carbon::now()->format('l');
                                $todayAssigns = collect($assignments)->filter(fn($a) => $a->day_of_week === $todayEnglishReal)->sortBy('start_time');
                            @endphp
                            @forelse($todayAssigns as $assign)
                                <a href="{{ route('student.class-detail', $assign->id) }}" class="bg-indigo-50/50 border border-indigo-100/60 hover:border-indigo-300 rounded-2xl p-5 flex flex-col justify-between hover:shadow-md transition-all duration-300 group hover:-translate-y-0.5">
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-center">
                                            <span class="bg-indigo-600 text-white text-[9px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">
                                                {{ $assign->subject->code ?? 'MAPEL' }}
                                            </span>
                                            <span class="text-[10px] font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-md">
                                                {{ $assign->start_time ? substr($assign->start_time, 0, 5) . ' - ' . substr($assign->end_time, 0, 5) : '00:00' }}
                                            </span>
                                        </div>
                                        
                                        <div>
                                            <h4 class="text-base font-extrabold text-slate-800 group-hover:text-indigo-600 transition-colors line-clamp-1">{{ $assign->subject->name }}</h4>
                                            <div class="flex items-center space-x-2 mt-2">
                                                <div class="w-6 h-6 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center font-bold text-[10px]">
                                                    {{ substr($assign->teacher->name ?? 'G', 0, 1) }}
                                                </div>
                                                <span class="text-xs text-slate-500 font-semibold line-clamp-1">Guru: {{ $assign->teacher->name ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-6 pt-4 border-t border-slate-100 flex justify-between items-center text-[9px] text-slate-500 font-bold uppercase tracking-wide">
                                        <span>Periode:</span>
                                        <span class="text-slate-700 bg-white px-2 py-0.5 rounded-md border border-slate-100 shadow-sm">{{ $assign->academicYear->name ?? '-' }}</span>
                                    </div>
                                </a>
                            @empty
                                <div class="col-span-3 text-center py-8 text-slate-400 text-xs font-semibold bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                                    Tidak ada jadwal KBM untuk Anda hari ini.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Other Days Section -->
                    <div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Hari Lainnya</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            @php
                                $dayKeys = array_keys($days);
                                $otherAssigns = collect($assignments)
                                    ->filter(fn($a) => $a->day_of_week !== $todayEnglishReal)
                                    ->sortBy(fn($a) => array_search($a->day_of_week, $dayKeys) . $a->start_time);
                            @endphp
                            @forelse($otherAssigns as $assign)
                                <a href="{{ route('student.class-detail', $assign->id) }}" class="bg-slate-50/70 border border-slate-100 hover:border-indigo-200 rounded-2xl p-5 flex flex-col justify-between hover:shadow-md transition-all duration-300 group hover:-translate-y-0.5">
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-center">
                                            <span class="bg-indigo-50 text-indigo-700 group-hover:bg-indigo-600 group-hover:text-white text-[9px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider transition-colors">
                                                {{ $assign->subject->code ?? 'MAPEL' }}
                                            </span>
                                            <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">
                                                {{ $assign->getDayTranslation() ?? 'Tanpa Hari' }}
                                            </span>
                                        </div>
                                        
                                        <div>
                                            <h4 class="text-base font-extrabold text-slate-800 group-hover:text-indigo-600 transition-colors line-clamp-1">{{ $assign->subject->name }}</h4>
                                            <div class="flex items-center space-x-2 mt-2">
                                                <div class="w-6 h-6 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center font-bold text-[10px]">
                                                    {{ substr($assign->teacher->name ?? 'G', 0, 1) }}
                                                </div>
                                                <span class="text-xs text-slate-500 font-semibold line-clamp-1">Guru: {{ $assign->teacher->name ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-6 pt-4 border-t border-slate-100 flex justify-between items-center text-[9px] text-slate-500 font-bold uppercase tracking-wide">
                                        <span>Jam:</span>
                                        <span class="text-slate-700 bg-white px-2 py-0.5 rounded-md border border-slate-100 shadow-sm">{{ $assign->start_time ? substr($assign->start_time, 0, 5) . ' - ' . substr($assign->end_time, 0, 5) : '-' }}</span>
                                    </div>
                                </a>
                            @empty
                                <div class="col-span-3 text-center py-8 text-slate-400 text-xs font-semibold bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
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
                        btnCal.className = 'px-3 py-1.5 rounded-lg text-[10px] font-bold transition flex items-center space-x-1.5 bg-white text-indigo-700 shadow-sm border border-slate-200/50';
                        btnList.className = 'px-3 py-1.5 rounded-lg text-[10px] font-bold transition flex items-center space-x-1.5 text-slate-500 hover:text-slate-800';
                    } else {
                        calView.classList.add('hidden');
                        listView.classList.remove('hidden');
                        btnList.className = 'px-3 py-1.5 rounded-lg text-[10px] font-bold transition flex items-center space-x-1.5 bg-white text-indigo-700 shadow-sm border border-slate-200/50';
                        btnCal.className = 'px-3 py-1.5 rounded-lg text-[10px] font-bold transition flex items-center space-x-1.5 text-slate-500 hover:text-slate-800';
                    }
                }

                function switchCalendarTab(day) {
                    document.querySelectorAll('.calendar-tab-content').forEach(el => el.classList.add('hidden'));
                    document.querySelectorAll('.calendar-tab-btn').forEach(btn => {
                        btn.className = 'calendar-tab-btn py-2.5 px-4 font-bold text-xs border-b-2 border-transparent text-slate-400 hover:text-slate-705 hover:border-slate-300 transition';
                    });

                    document.getElementById('tab-content-' + day).classList.remove('hidden');
                    document.getElementById('tab-day-' + day).className = 'calendar-tab-btn py-2.5 px-4 font-bold text-xs border-b-2 border-indigo-600 text-indigo-700 transition';
                }
            </script>
            @endif
            
        </div>
    </div>
</x-app-layout>
