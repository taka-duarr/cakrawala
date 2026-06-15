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
    <!-- Main Outer Container for 3-Column Layout -->
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
        
        <!-- Left + Center Column (xl:col-span-3) -->
        <div class="xl:col-span-3 space-y-6">
            
            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 flex items-center space-x-3 shadow-sm">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="font-medium text-xs">{{ session('success') }}</p>
            </div>
            @endif

            <!-- Today's Tasks Section -->
            <div id="quest" class="bg-white rounded-2xl border border-slate-100 p-6 soft-glow-indigo">
                <div class="flex justify-between items-center mb-1">
                    <h2 class="text-xl font-bold text-slate-800">Your today's tasks</h2>
                    <div class="flex items-center text-xs text-slate-400 font-semibold space-x-1.5">
                        <span>🔥 Streak Harian: 10/10</span>
                    </div>
                </div>
                <p class="text-xs text-slate-400 mb-6">Complete your daily goal with any activity.</p>
                              <!-- Quest Grid (Pastel Theme) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @forelse($availableMissions as $index => $mission)
                        @php
                            $color = $cardColors[$index % count($cardColors)];
                            $isCompleted = in_array($mission->id, $completedMissionIds);
                        @endphp
                        
                        @if($isCompleted)
                            <div class="shimmer-card border rounded-2xl p-6 flex flex-col justify-between transition-all duration-300 bg-emerald-50/30 border-emerald-100/50 hover:-translate-y-1 hover:shadow-lg">
                                <div class="space-y-4">
                                    <div class="flex justify-between items-start">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            {{ $mission->type ?? 'Daily' }}
                                        </span>
                                        <strong class="text-sm font-bold text-emerald-600">+{{ $mission->points_reward }} Pts</strong>
                                    </div>
                                    <div>
                                        <h4 class="font-extrabold text-slate-850 text-base leading-tight mb-2">{{ $mission->title }}</h4>
                                        <p class="text-xs text-slate-500 leading-relaxed">{{ $mission->description }}</p>
                                    </div>
                                </div>
                                <div class="mt-6 flex items-center justify-center space-x-1.5 py-2.5 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl text-xs font-bold">
                                    <span uk-icon="icon: check; ratio: 0.8"></span>
                                    <span>Selesai</span>
                                </div>
                            </div>
                        @else
                            <div class="shimmer-card border rounded-2xl p-6 flex flex-col justify-between transition-all duration-300 {{ $color['bg'] }} hover:-translate-y-1 hover:shadow-lg">
                                <div class="space-y-4">
                                    <div class="flex justify-between items-start">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $color['tag'] }}">
                                            {{ $mission->type ?? 'Daily' }}
                                        </span>
                                        <strong class="text-sm font-bold {{ $color['points'] }}">+{{ $mission->points_reward }} Pts</strong>
                                    </div>
                                    <div>
                                        <h4 class="font-extrabold text-slate-800 text-base leading-tight mb-2">{{ $mission->title }}</h4>
                                        <p class="text-xs text-slate-500 leading-relaxed">{{ $mission->description }}</p>
                                    </div>
                                </div>
                                <div class="mt-6 flex items-center justify-center py-2.5 bg-slate-100/80 text-slate-400 border border-slate-200/30 rounded-xl text-xs font-bold">
                                    <span>Belum Tercapai</span>
                                </div>
                            </div>
                        @endif
                    @empty
                    <div class="col-span-3 text-center py-12 text-slate-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        <p class="text-xs font-medium">Belum ada misi tersedia saat ini.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Aktivitas Terbaru (Recent Activities) Table -->
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden soft-glow-indigo">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">Aktivitas Terbaru</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100/80">
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center w-20">Tipe</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Aktivitas</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Poin</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/70">
                            @forelse($pointHistory as $history)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center">
                                        @if($history->type === 'kebaikan')
                                            <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100/80 flex items-center justify-center shadow-inner">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-rose-50 text-rose-600 border border-rose-100/80 flex items-center justify-center shadow-inner">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-semibold text-slate-800">
                                        {{ $history->points >= 0 ? "Mendapatkan poin dari " : "Penukaran poin untuk " }}{{ strtolower($history->source) }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-medium leading-none mt-1">Status: Disetujui</div>
                                </td>
                                <td class="px-6 py-4 text-center font-extrabold text-xs {{ $history->type === 'kebaikan' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $history->points >= 0 ? '+' : '' }}{{ $history->points }} Pts
                                </td>
                                <td class="px-6 py-4 text-right text-xs text-slate-400 font-medium whitespace-nowrap">
                                    {{ $history->created_at->diffForHumans() }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-12 text-slate-400 text-xs font-medium">Belum ada riwayat aktivitas.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Right Widgets Column (xl:col-span-1) -->
        <div class="xl:col-span-1 space-y-6">
            
            <!-- AI Insight Panel -->
            <div class="bg-indigo-600 text-white rounded-2xl p-6 shadow-lg shadow-indigo-100/50 flex flex-col justify-between min-h-[220px]">
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-bold uppercase tracking-wider bg-white/20 px-2 py-0.5 rounded-full">AI Student Insight</span>
                        <form method="GET" action="{{ route('student.dashboard') }}">
                            <input type="hidden" name="trigger_ai" value="1">
                            <button type="submit" class="text-xs font-bold text-white hover:underline transition">Refresh</button>
                        </form>
                    </div>
                    <h3 class="text-base font-bold">Bagaimana perkembangan belajarmu hari ini?</h3>
                    <p class="text-xs text-indigo-100 leading-relaxed font-sans line-clamp-4">
                        {{ $aiInsight ?? 'AI Insight belum digenerate. Klik refresh di atas untuk mendapatkan ringkasan profil belajarmu.' }}
                    </p>
                </div>
                <div class="mt-4 pt-4 border-t border-white/10 flex justify-between items-center text-[10px] font-semibold text-indigo-100">
                    <span>Recommends 3 new quests</span>
                    <a href="#quest" class="text-white hover:underline">Lihat Misi →</a>
                </div>
            </div>

            <!-- Statistics Indicators Grid (2x2) -->
            <div class="bg-white rounded-2xl border border-slate-100 p-5 soft-glow-indigo">
                <h3 class="text-sm font-bold text-slate-800 mb-4">Statistics</h3>
                <div class="grid grid-cols-2 gap-4">
                    <!-- Stat 1 -->
                    <div class="bg-slate-50/50 border border-slate-100 p-4 rounded-xl text-center">
                        <span class="text-[10px] text-slate-400 font-semibold block mb-1">Active days</span>
                        <strong class="text-base font-bold text-slate-800">155</strong>
                    </div>
                    <!-- Stat 2 -->
                    <div class="bg-slate-50/50 border border-slate-100 p-4 rounded-xl text-center">
                        <span class="text-[10px] text-slate-400 font-semibold block mb-1">Total sessions</span>
                        <strong class="text-base font-bold text-slate-800">200</strong>
                    </div>
                    <!-- Stat 3 -->
                    <div class="bg-slate-50/50 border border-slate-100 p-4 rounded-xl text-center">
                        <span class="text-[10px] text-slate-400 font-semibold block mb-1">Total points</span>
                        <strong class="text-base font-bold text-indigo-600">{{ $user->points }}</strong>
                    </div>
                    <!-- Stat 4 -->
                    <div class="bg-slate-50/50 border border-slate-100 p-4 rounded-xl text-center">
                        <span class="text-[10px] text-slate-400 font-semibold block mb-1">Longest streak</span>
                        <strong class="text-base font-bold text-amber-500">99</strong>
                    </div>
                </div>
            </div>

            <!-- Achievements / Lencana Karakter -->
            <div class="bg-white rounded-2xl border border-slate-100 p-5 soft-glow-indigo">
                <h3 class="text-sm font-bold text-slate-800 mb-4">Lencana Karakter</h3>
                <div class="flex flex-col gap-3">
                    @forelse($badges as $badge)
                    <div class="badge-glow px-3.5 py-2.5 bg-slate-50/50 border border-slate-100 rounded-xl flex items-center space-x-3 shadow-sm w-full cursor-default">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c.195-.39.736-.39.93 0l2.399 4.86 5.342.776c.433.063.606.592.293.898l-3.866 3.769 1.127 5.318c.092.433-.362.762-.75.558L12 17.15l-4.782 2.516c-.388.204-.842-.125-.75-.558l1.127-5.318-3.866-3.769c-.313-.306-.14-.835.293-.898l5.342-.776 2.399-4.86z"></path></svg>
                        <div>
                            <div class="text-xs font-bold text-slate-800 leading-tight">{{ $badge->title }}</div>
                            <div class="text-[9px] text-slate-400 font-medium leading-none mt-1">{{ $badge->description }}</div>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 font-medium text-center w-full py-4 bg-slate-50/50 rounded-xl border border-dashed border-slate-150">Belum ada lencana yang diraih. Selesaikan misi untuk meraih lencana!</p>
                    @endforelse
                </div>
            </div>

            <!-- Streak Calendar -->
            <div class="bg-white rounded-2xl border border-slate-100 p-5 soft-glow-indigo">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-bold text-slate-800">Streak calendar</h3>
                    <span class="text-[10px] font-semibold text-slate-400">June 2026</span>
                </div>

                @php
                    $days = [28,29,30,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28];
                    $streaks = [1,2,3,4,5,6,8,9,11,12,14,15,16,21,22];
                @endphp
                
                <div class="grid grid-cols-7 gap-y-3 gap-x-1 text-center text-[10px] font-semibold">
                    <span class="text-slate-400">M</span>
                    <span class="text-slate-400">T</span>
                    <span class="text-slate-400">W</span>
                    <span class="text-slate-400">T</span>
                    <span class="text-slate-400">F</span>
                    <span class="text-slate-400">S</span>
                    <span class="text-slate-400">S</span>

                    @foreach($days as $idx => $day)
                        @php
                            $isPrevMonth = $idx < 3;
                            $isStreak = in_array($day, $streaks) && !$isPrevMonth;
                        @endphp
                        <div class="flex items-center justify-center h-6 w-full">
                            <span class="flex items-center justify-center h-6 w-6 rounded-full 
                                {{ $isStreak ? 'bg-amber-400 text-white font-bold' : ($isPrevMonth ? 'text-slate-300' : 'text-slate-600') }}">
                                {{ $day }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Points Weekly Chart Widget -->
            <div class="bg-white rounded-2xl border border-slate-100 p-5 soft-glow-indigo">
                <h3 class="text-sm font-bold text-slate-800 mb-4">Points</h3>
                
                <div class="h-28 flex items-end justify-between px-2 pt-2 border-b border-slate-100">
                    <div class="flex flex-col items-center flex-1 space-y-1">
                        <span class="text-[8px] text-slate-400 font-bold">15</span>
                        <div class="w-2.5 bg-indigo-600 rounded-t-full animate-grow" style="height: 60px"></div>
                    </div>
                    <div class="flex flex-col items-center flex-1 space-y-1">
                        <span class="text-[8px] text-slate-400 font-bold">10</span>
                        <div class="w-2.5 bg-indigo-600 rounded-t-full animate-grow" style="height: 40px"></div>
                    </div>
                    <div class="flex flex-col items-center flex-1 space-y-1">
                        <span class="text-[8px] text-slate-400 font-bold">10</span>
                        <div class="w-2.5 bg-indigo-600 rounded-t-full animate-grow" style="height: 40px"></div>
                    </div>
                    <div class="flex flex-col items-center flex-1 space-y-1">
                        <span class="text-[8px] text-slate-400 font-bold">15</span>
                        <div class="w-2.5 bg-indigo-600 rounded-t-full animate-grow" style="height: 60px"></div>
                    </div>
                    <div class="flex flex-col items-center flex-1 space-y-1">
                        <span class="text-[8px] text-slate-400 font-bold">10</span>
                        <div class="w-2.5 bg-indigo-600 rounded-t-full animate-grow" style="height: 40px"></div>
                    </div>
                    <div class="flex flex-col items-center flex-1 space-y-1">
                        <span class="text-[8px] text-slate-400 font-bold">10</span>
                        <div class="w-2.5 bg-indigo-600 rounded-t-full animate-grow" style="height: 40px"></div>
                    </div>
                    <div class="flex flex-col items-center flex-1 space-y-1">
                        <span class="text-[8px] text-slate-400 font-bold">0</span>
                        <div class="w-2.5 bg-slate-200 rounded-t-full animate-grow" style="height: 4px"></div>
                    </div>
                </div>
                <div class="flex justify-between text-[9px] text-slate-400 font-bold px-2 pt-2 text-center">
                    <span class="flex-1">Mon</span>
                    <span class="flex-1">Tue</span>
                    <span class="flex-1">Wed</span>
                    <span class="flex-1">Thu</span>
                    <span class="flex-1">Fri</span>
                    <span class="flex-1">Sat</span>
                    <span class="flex-1">Sun</span>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>

