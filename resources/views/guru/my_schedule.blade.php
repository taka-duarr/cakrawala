<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('guru.dashboard') }}" class="text-xs font-black text-slate-950 hover:text-slate-700 flex items-center space-x-1 mb-2.5 uppercase tracking-wider">
                    <span uk-icon="icon: arrow-left; ratio: 0.8"></span>
                    <span>Kembali ke Dashboard</span>
                </a>
                <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">Jadwal Mengajar</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-100/40 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Kelas & Mapel yang Diampu -->
            <div class="bg-white rounded-3xl border-4 border-slate-950 p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="flex items-center justify-between mb-6 flex-wrap gap-4 border-b-2 border-slate-950 pb-4">
                    <div>
                        <h3 class="text-lg font-black text-slate-950 uppercase tracking-tight mb-1">Mata Pelajaran & Kelas yang Diampu</h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Daftar kelas akademik dan mata pelajaran yang ditugaskan kepada Anda.</p>
                    </div>
                    <!-- Toggle View Mode -->
                    <div class="bg-white p-1 rounded-xl flex space-x-1 border-2 border-slate-950">
                        <button type="button" id="btn-mode-calendar" onclick="switchViewMode('calendar')" class="px-3 py-1.5 rounded-lg text-[10px] font-black transition flex items-center space-x-1.5 bg-[#E4FF1A] text-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] border-2 border-slate-950">
                            <span uk-icon="icon: calendar; ratio: 0.75"></span>
                            <span>Kalender</span>
                        </button>
                        <button type="button" id="btn-mode-list" onclick="switchViewMode('list')" class="px-3 py-1.5 rounded-lg text-[10px] font-bold transition flex items-center space-x-1.5 text-slate-500 hover:text-slate-950 border-2 border-transparent">
                            <span uk-icon="icon: list; ratio: 0.75"></span>
                            <span>Daftar</span>
                        </button>
                    </div>
                </div>

                <!-- Calendar View Mode -->
                <div id="view-mode-calendar" class="space-y-4">
                    <!-- Day tabs -->
                    <div class="border-b-4 border-slate-950 mb-6">
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
                                <button type="button" onclick="switchCalendarTab('{{ $eng }}')" id="tab-day-{{ $eng }}" class="calendar-tab-btn py-2.5 px-4 font-bold text-xs border-b-4 transition {{ $todayEnglish === $eng ? 'border-slate-950 text-slate-950 font-black' : 'border-transparent text-slate-400 hover:text-slate-705' }}">
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
                                    $dayAssigns = $assignments->filter(fn($a) => $a->day_of_week === $eng);
                                @endphp
                                @forelse($dayAssigns as $assign)
                                    <a href="{{ route('guru.assignments.detail', $assign->id) }}" class="bg-white border-2 border-slate-950 hover:bg-[#E4FF1A]/10 rounded-2xl p-5 flex flex-col justify-between shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-1 hover:translate-x-0.5 hover:shadow-[6px_6px_0px_0px_rgba(15,23,42,1)] transition-all duration-200 group relative overflow-hidden">
                                        <div>
                                            <div class="flex justify-between items-center mb-3.5">
                                                <span class="bg-slate-950 text-white text-[9px] font-black px-2.5 py-0.5 rounded border border-slate-950 uppercase tracking-wider">
                                                    {{ $assign->classroom->name }}
                                                </span>
                                                <span class="text-[9px] font-black text-slate-950 bg-white border-2 border-slate-950 px-2 py-0.5 rounded shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                                    {{ $assign->start_time ? substr($assign->start_time, 0, 5) . ' - ' . substr($assign->end_time, 0, 5) : '00:00' }}
                                                </span>
                                            </div>
                                            <h4 class="text-sm font-black text-slate-950 uppercase tracking-tight group-hover:text-slate-850 transition-colors">{{ $assign->subject->name }}</h4>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">Kode: {{ $assign->subject->code ?? '-' }} · Semester {{ $assign->semester->name ?? '-' }}</p>
                                        </div>
                                        <div class="mt-5 pt-3.5 border-t-2 border-slate-950 flex justify-between items-center text-[9px] text-slate-500 font-bold uppercase tracking-wider">
                                            <span>Siswa terdaftar:</span>
                                            <span class="font-black text-slate-950 bg-white px-2 py-0.5 rounded border-2 border-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">{{ \App\Models\User::where('role_id', 5)->where('classroom_id', $assign->classroom_id)->count() }} Siswa</span>
                                        </div>
                                    </a>
                                @empty
                                    <div class="col-span-3 text-center py-12 text-slate-400 text-xs font-bold uppercase tracking-wider bg-slate-50/20 rounded-2xl border-2 border-dashed border-slate-950">
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
                        <h4 class="text-xs font-black text-slate-950 uppercase tracking-wider mb-4 flex items-center space-x-1.5">
                            <span class="w-2 h-2 bg-rose-600 border border-slate-950 rounded-full animate-pulse"></span>
                            <span>Hari Ini ({{ $days[\Carbon\Carbon::now()->format('l')] ?? 'Lainnya' }})</span>
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            @php
                                $todayEnglishReal = \Carbon\Carbon::now()->format('l');
                                $todayAssigns = $assignments->filter(fn($a) => $a->day_of_week === $todayEnglishReal);
                            @endphp
                            @forelse($todayAssigns as $assign)
                                <a href="{{ route('guru.assignments.detail', $assign->id) }}" class="bg-white border-2 border-slate-950 hover:bg-[#E4FF1A]/10 rounded-2xl p-5 flex flex-col justify-between shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-1 hover:translate-x-0.5 hover:shadow-[6px_6px_0px_0px_rgba(15,23,42,1)] transition-all duration-200 group">
                                    <div>
                                        <div class="flex justify-between items-center mb-3.5">
                                            <span class="bg-slate-950 text-white text-[9px] font-black px-2.5 py-0.5 rounded border border-slate-950 uppercase tracking-wider">
                                                {{ $assign->classroom->name }}
                                            </span>
                                            <span class="text-[9px] font-black text-slate-950 bg-white border-2 border-slate-950 px-2 py-0.5 rounded shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                                {{ $assign->start_time ? substr($assign->start_time, 0, 5) . ' - ' . substr($assign->end_time, 0, 5) : '00:00' }}
                                            </span>
                                        </div>
                                        <h4 class="text-sm font-black text-slate-950 uppercase tracking-tight group-hover:text-slate-850 transition-colors">{{ $assign->subject->name }}</h4>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">Kode: {{ $assign->subject->code ?? '-' }} · Semester {{ $assign->semester->name ?? '-' }}</p>
                                    </div>
                                    <div class="mt-5 pt-3.5 border-t-2 border-slate-950 flex justify-between items-center text-[9px] text-slate-500 font-bold uppercase tracking-wider">
                                        <span>Siswa terdaftar:</span>
                                        <span class="font-black text-slate-950 bg-white px-2 py-0.5 rounded border-2 border-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">{{ \App\Models\User::where('role_id', 5)->where('classroom_id', $assign->classroom_id)->count() }} Siswa</span>
                                    </div>
                                </a>
                            @empty
                                <div class="col-span-3 text-center py-8 text-slate-400 text-xs font-bold uppercase tracking-wider bg-slate-50/20 rounded-2xl border-2 border-dashed border-slate-950">
                                    Tidak ada jadwal mengajar untuk hari ini.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Other Days Section -->
                    <div>
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-wider mb-4 border-t-2 border-slate-950 pt-6">Hari Lainnya</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            @php
                                $otherAssigns = $assignments->filter(fn($a) => $a->day_of_week !== $todayEnglishReal);
                            @endphp
                            @forelse($otherAssigns as $assign)
                                <a href="{{ route('guru.assignments.detail', $assign->id) }}" class="bg-white border-2 border-slate-950 hover:bg-[#E4FF1A]/10 rounded-2xl p-5 flex flex-col justify-between shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-1 hover:translate-x-0.5 hover:shadow-[6px_6px_0px_0px_rgba(15,23,42,1)] transition-all duration-200 group">
                                    <div>
                                        <div class="flex justify-between items-center mb-3.5">
                                            <span class="bg-slate-950 text-white text-[9px] font-black px-2.5 py-0.5 rounded border border-slate-950 uppercase tracking-wider">
                                                {{ $assign->classroom->name }}
                                            </span>
                                            <span class="text-[9px] font-black text-slate-950 bg-white border-2 border-slate-950 px-2 py-0.5 rounded shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                                {{ $assign->getDayTranslation() ?? 'Tanpa Hari' }}
                                            </span>
                                        </div>
                                        <h4 class="text-sm font-black text-slate-950 uppercase tracking-tight group-hover:text-slate-850 transition-colors">{{ $assign->subject->name }}</h4>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">Kode: {{ $assign->subject->code ?? '-' }} · Jam: {{ $assign->start_time ? substr($assign->start_time, 0, 5) . ' - ' . substr($assign->end_time, 0, 5) : '-' }}</p>
                                    </div>
                                    <div class="mt-5 pt-3.5 border-t-2 border-slate-950 flex justify-between items-center text-[9px] text-slate-500 font-bold uppercase tracking-wider">
                                        <span>Siswa terdaftar:</span>
                                        <span class="font-black text-slate-950 bg-white px-2 py-0.5 rounded border-2 border-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">{{ \App\Models\User::where('role_id', 5)->where('classroom_id', $assign->classroom_id)->count() }} Siswa</span>
                                    </div>
                                </a>
                            @empty
                                <div class="col-span-3 text-center py-8 text-slate-400 text-xs font-bold uppercase tracking-wider bg-slate-50/20 rounded-2xl border-2 border-dashed border-slate-950">
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
                btnCal.className = 'px-3 py-1.5 rounded-lg text-[10px] font-black transition flex items-center space-x-1.5 bg-[#E4FF1A] text-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] border-2 border-slate-950';
                btnList.className = 'px-3 py-1.5 rounded-lg text-[10px] font-bold transition flex items-center space-x-1.5 text-slate-500 hover:text-slate-950 border-2 border-transparent';
            } else {
                calView.classList.add('hidden');
                listView.classList.remove('hidden');
                btnList.className = 'px-3 py-1.5 rounded-lg text-[10px] font-black transition flex items-center space-x-1.5 bg-[#E4FF1A] text-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] border-2 border-slate-950';
                btnCal.className = 'px-3 py-1.5 rounded-lg text-[10px] font-bold transition flex items-center space-x-1.5 text-slate-500 hover:text-slate-950 border-2 border-transparent';
            }
        }

        function switchCalendarTab(day) {
            document.querySelectorAll('.calendar-tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.calendar-tab-btn').forEach(btn => {
                btn.className = 'calendar-tab-btn py-2.5 px-4 font-bold text-xs border-b-4 border-transparent text-slate-400 hover:text-slate-705 transition';
            });

            document.getElementById('tab-content-' + day).classList.remove('hidden');
            document.getElementById('tab-day-' + day).className = 'calendar-tab-btn py-2.5 px-4 font-bold text-xs border-b-4 border-slate-950 text-slate-950 font-black transition';
        }
    </script>
</x-app-layout>
