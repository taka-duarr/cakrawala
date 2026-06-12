<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">Jadwal Mengajar</h2>
            <a href="{{ route('guru.dashboard') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-4 py-2.5 rounded-xl transition flex items-center space-x-2">
                <span uk-icon="icon: arrow-left; ratio: 0.8"></span>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Kelas & Mapel yang Diampu -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 soft-glow-indigo">
                <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 mb-1">Mata Pelajaran & Kelas yang Diampu</h3>
                        <p class="text-xs text-slate-400 font-medium">Daftar kelas akademik dan mata pelajaran yang ditugaskan kepada Anda.</p>
                    </div>
                    <!-- Toggle View Mode -->
                    <div class="bg-slate-105 p-1 rounded-xl flex space-x-1 border border-slate-200">
                        <button type="button" id="btn-mode-calendar" onclick="switchViewMode('calendar')" class="px-3 py-1.5 rounded-lg text-[10px] font-bold transition flex items-center space-x-1.5 bg-white text-indigo-700 shadow-sm border border-slate-200/50">
                            <span uk-icon="icon: calendar; ratio: 0.75"></span>
                            <span>Kalender</span>
                        </button>
                        <button type="button" id="btn-mode-list" onclick="switchViewMode('list')" class="px-3 py-1.5 rounded-lg text-[10px] font-bold transition flex items-center space-x-1.5 text-slate-500 hover:text-slate-805">
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
                                <button type="button" onclick="switchCalendarTab('{{ $eng }}')" id="tab-day-{{ $eng }}" class="calendar-tab-btn py-2.5 px-4 font-bold text-xs border-b-2 transition {{ $todayEnglish === $eng ? 'border-indigo-600 text-indigo-700' : 'border-transparent text-slate-400 hover:text-slate-705 hover:border-slate-300' }}">
                                    {{ $ind }}
                                </button>
                            @endforeach
                        </nav>
                    </div>

                    <!-- Day content -->
                    @foreach($days as $eng => $ind)
                        <div id="tab-content-{{ $eng }}" class="calendar-tab-content {{ $todayEnglish === $eng ? '' : 'hidden' }}">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                @php
                                    $dayAssigns = $assignments->filter(fn($a) => $a->day_of_week === $eng);
                                @endphp
                                @forelse($dayAssigns as $assign)
                                    <a href="{{ route('guru.assignments.detail', $assign->id) }}" class="bg-slate-50/70 border border-slate-100 hover:border-indigo-200 rounded-xl p-4 flex flex-col justify-between hover:shadow-md transition group relative overflow-hidden">
                                        <div>
                                            <div class="flex justify-between items-center mb-2">
                                                <span class="bg-indigo-50 text-indigo-700 group-hover:bg-indigo-600 group-hover:text-white text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider transition-colors">
                                                    {{ $assign->classroom->name }}
                                                </span>
                                                <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50/50 px-2.5 py-0.5 rounded-md">
                                                    {{ $assign->start_time ? substr($assign->start_time, 0, 5) . ' - ' . substr($assign->end_time, 0, 5) : '00:00' }}
                                                </span>
                                            </div>
                                            <h4 class="text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">{{ $assign->subject->name }}</h4>
                                            <p class="text-[10px] text-slate-400 font-medium mt-1">Kode: {{ $assign->subject->code ?? '-' }} · Semester {{ $assign->semester->name ?? '-' }}</p>
                                        </div>
                                        <div class="mt-4 pt-3 border-t border-slate-100 flex justify-between items-center text-[9px] text-slate-500 font-semibold">
                                            <span>Siswa terdaftar:</span>
                                            <span class="font-bold text-slate-700 bg-white px-2 py-0.5 rounded-md border border-slate-100">{{ \App\Models\User::where('role_id', 5)->where('classroom_id', $assign->classroom_id)->count() }} Siswa</span>
                                        </div>
                                    </a>
                                @empty
                                    <div class="col-span-3 text-center py-8 text-slate-400 text-xs font-semibold bg-slate-50/40 rounded-2xl border border-dashed border-slate-200">
                                        Tidak ada jadwal mengajar pada hari {{ $ind }}.
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
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            @php
                                $todayEnglishReal = \Carbon\Carbon::now()->format('l');
                                $todayAssigns = $assignments->filter(fn($a) => $a->day_of_week === $todayEnglishReal);
                            @endphp
                            @forelse($todayAssigns as $assign)
                                <a href="{{ route('guru.assignments.detail', $assign->id) }}" class="bg-indigo-50/30 border border-indigo-100/60 hover:border-indigo-300 rounded-xl p-4 flex flex-col justify-between hover:shadow-md transition group">
                                    <div>
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="bg-indigo-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">
                                                {{ $assign->classroom->name }}
                                            </span>
                                            <span class="text-[10px] font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-md">
                                                {{ $assign->start_time ? substr($assign->start_time, 0, 5) . ' - ' . substr($assign->end_time, 0, 5) : '00:00' }}
                                            </span>
                                        </div>
                                        <h4 class="text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">{{ $assign->subject->name }}</h4>
                                        <p class="text-[10px] text-slate-400 font-medium mt-1">Kode: {{ $assign->subject->code ?? '-' }} · Semester {{ $assign->semester->name ?? '-' }}</p>
                                    </div>
                                    <div class="mt-4 pt-3 border-t border-slate-100 flex justify-between items-center text-[9px] text-slate-500 font-semibold">
                                        <span>Siswa terdaftar:</span>
                                        <span class="font-bold text-slate-700 bg-white px-2 py-0.5 rounded-md border border-slate-100">{{ \App\Models\User::where('role_id', 5)->where('classroom_id', $assign->classroom_id)->count() }} Siswa</span>
                                    </div>
                                </a>
                            @empty
                                <div class="col-span-3 text-center py-6 text-slate-400 text-xs font-semibold bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                                    Tidak ada jadwal mengajar untuk hari ini.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Other Days Section -->
                    <div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Hari Lainnya</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            @php
                                $otherAssigns = $assignments->filter(fn($a) => $a->day_of_week !== $todayEnglishReal);
                            @endphp
                            @forelse($otherAssigns as $assign)
                                <a href="{{ route('guru.assignments.detail', $assign->id) }}" class="bg-slate-50/70 border border-slate-100 hover:border-indigo-200 rounded-xl p-4 flex flex-col justify-between hover:shadow-md transition group">
                                    <div>
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="bg-indigo-50 text-indigo-700 group-hover:bg-indigo-600 group-hover:text-white text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider transition-colors">
                                                {{ $assign->classroom->name }}
                                            </span>
                                            <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">
                                                {{ $assign->getDayTranslation() ?? 'Tanpa Hari' }}
                                            </span>
                                        </div>
                                        <h4 class="text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">{{ $assign->subject->name }}</h4>
                                        <p class="text-[10px] text-slate-400 font-medium mt-1">Kode: {{ $assign->subject->code ?? '-' }} · Jam: {{ $assign->start_time ? substr($assign->start_time, 0, 5) . ' - ' . substr($assign->end_time, 0, 5) : '-' }}</p>
                                    </div>
                                    <div class="mt-4 pt-3 border-t border-slate-100 flex justify-between items-center text-[9px] text-slate-500 font-semibold">
                                        <span>Siswa terdaftar:</span>
                                        <span class="font-bold text-slate-700 bg-white px-2 py-0.5 rounded-md border border-slate-100">{{ \App\Models\User::where('role_id', 5)->where('classroom_id', $assign->classroom_id)->count() }} Siswa</span>
                                    </div>
                                </a>
                            @empty
                                <div class="col-span-3 text-center py-6 text-slate-400 text-xs font-semibold bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                                    Tidak ada jadwal mengajar di hari lainnya.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Javascript bindings for view toggles -->
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
                btnList.className = 'px-3 py-1.5 rounded-lg text-[10px] font-bold transition flex items-center space-x-1.5 text-slate-500 hover:text-slate-805';
            } else {
                calView.classList.add('hidden');
                listView.classList.remove('hidden');
                btnList.className = 'px-3 py-1.5 rounded-lg text-[10px] font-bold transition flex items-center space-x-1.5 bg-white text-indigo-700 shadow-sm border border-slate-200/50';
                btnCal.className = 'px-3 py-1.5 rounded-lg text-[10px] font-bold transition flex items-center space-x-1.5 text-slate-500 hover:text-slate-805';
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
</x-app-layout>
