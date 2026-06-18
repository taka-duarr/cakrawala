@php
    $cardColors = [
        ['bg' => 'bg-[#FFEAEA] text-slate-950 border-2 border-slate-950', 'text' => 'text-slate-950', 'btn' => 'bg-slate-950 hover:bg-[#E4FF1A] text-white hover:text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]', 'tag' => 'bg-white text-slate-950 border border-slate-950', 'points' => 'text-slate-950'],
        ['bg' => 'bg-[#FFF3EA] text-slate-950 border-2 border-slate-950', 'text' => 'text-slate-950', 'btn' => 'bg-slate-950 hover:bg-[#E4FF1A] text-white hover:text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]', 'tag' => 'bg-white text-slate-950 border border-slate-950', 'points' => 'text-slate-950'],
        ['bg' => 'bg-[#EAFCEF] text-slate-950 border-2 border-slate-950', 'text' => 'text-slate-950', 'btn' => 'bg-slate-950 hover:bg-[#E4FF1A] text-white hover:text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]', 'tag' => 'bg-white text-slate-950 border border-slate-950', 'points' => 'text-slate-950'],
        ['bg' => 'bg-white text-slate-950 border-2 border-slate-950', 'text' => 'text-slate-950', 'btn' => 'bg-slate-950 hover:bg-[#E4FF1A] text-white hover:text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]', 'tag' => 'bg-white text-slate-950 border border-slate-950', 'points' => 'text-slate-950']
    ];
@endphp

<x-app-layout>
    <!-- Main Outer Container for 3-Column Layout -->
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
        
        <!-- Left + Center Column (xl:col-span-3) -->
        <div class="xl:col-span-3 space-y-6">
            
            @if(session('success'))
            <div class="bg-[#EAFCEF] border-2 border-slate-950 text-emerald-800 rounded-xl p-4 flex items-center space-x-3 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                <p class="font-bold text-xs uppercase tracking-wider">{{ session('success') }}</p>
            </div>
            @endif

            <!-- Today's Tasks Section -->
            <div id="quest" class="bg-white rounded-3xl border-4 border-slate-950 p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="flex justify-between items-center mb-1">
                    <h2 class="text-xl font-black text-slate-950 uppercase tracking-tight">Misi Harian Anda</h2>
                    <div class="flex items-center text-xs text-slate-950 font-black uppercase tracking-wider">
                        <span>🔥 Streak Harian: 10/10</span>
                    </div>
                </div>
                <p class="text-xs text-slate-400 mb-6 font-bold uppercase tracking-wider">Selesaikan misi kebaikan harian untuk meningkatkan karakter.</p>
                
                <!-- Quest Grid (Pastel Theme) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @forelse($availableMissions as $index => $mission)
                        @php
                            $color = $cardColors[$index % count($cardColors)];
                            $isCompleted = in_array($mission->id, $completedMissionIds);
                        @endphp
                        
                        @if($isCompleted)
                            <div class="bg-[#EAFCEF] border-2 border-slate-950 rounded-2xl p-6 flex flex-col justify-between transition-all duration-300 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] hover:shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-0.5">
                                <div class="space-y-4">
                                    <div class="flex justify-between items-start">
                                        <span class="px-2.5 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider bg-white text-emerald-700 border border-slate-950">
                                            {{ $mission->type ?? 'Daily' }}
                                        </span>
                                        <strong class="text-sm font-black text-emerald-700">+{{ $mission->points_reward }} Pts</strong>
                                    </div>
                                    <div>
                                        <h4 class="font-black text-slate-950 text-base leading-tight mb-2 uppercase tracking-tight">{{ $mission->title }}</h4>
                                        <p class="text-xs text-slate-700 font-semibold leading-relaxed">{{ $mission->description }}</p>
                                    </div>
                                </div>
                                <div class="mt-6 flex items-center justify-center space-x-1.5 py-2.5 bg-white border-2 border-slate-950 text-emerald-700 rounded-xl text-xs font-black uppercase shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                    <span uk-icon="icon: check; ratio: 0.8"></span>
                                    <span>Selesai</span>
                                </div>
                            </div>
                        @else
                            <div class="border-2 border-slate-950 rounded-2xl p-6 flex flex-col justify-between transition-all duration-300 {{ $color['bg'] }} shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] hover:shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-0.5">
                                <div class="space-y-4">
                                    <div class="flex justify-between items-start">
                                        <span class="px-2.5 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider {{ $color['tag'] }}">
                                            {{ $mission->type ?? 'Daily' }}
                                        </span>
                                        <strong class="text-sm font-black {{ $color['points'] }}">+{{ $mission->points_reward }} Pts</strong>
                                    </div>
                                    <div>
                                        <h4 class="font-black text-slate-950 text-base leading-tight mb-2 uppercase tracking-tight">{{ $mission->title }}</h4>
                                        <p class="text-xs text-slate-700 font-semibold leading-relaxed">{{ $mission->description }}</p>
                                    </div>
                                </div>
                                <div class="mt-6 flex items-center justify-center py-2.5 bg-white text-slate-400 border-2 border-slate-950 rounded-xl text-xs font-black uppercase shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                    <span>Belum Tercapai</span>
                                </div>
                            </div>
                        @endif
                    @empty
                    <div class="col-span-3 text-center py-12 text-slate-400 font-bold uppercase tracking-wider text-xs">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        <p>Belum ada misi tersedia saat ini.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Aktivitas Terbaru (Recent Activities) Table -->
            <div class="bg-white rounded-3xl border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="p-6 border-b-2 border-slate-950 bg-slate-50/50">
                    <h3 class="text-lg font-black text-slate-950 uppercase tracking-tight">Aktivitas Terbaru</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-950 text-white border-b-2 border-slate-950">
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-center w-20">Tipe</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider">Aktivitas</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-center">Poin</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-right">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-slate-950">
                            @forelse($pointHistory as $history)
                            <tr class="hover:bg-slate-100 transition-colors">
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center">
                                        @if($history->type === 'kebaikan')
                                            <div class="w-8 h-8 rounded-xl bg-[#EAFCEF] text-emerald-700 border-2 border-slate-950 flex items-center justify-center shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 rounded-xl bg-[#FFEAEA] text-rose-700 border-2 border-slate-950 flex items-center justify-center shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-black text-slate-950 uppercase tracking-tight leading-none">
                                        {{ $history->points >= 0 ? "Mendapatkan poin dari " : "Penukaran poin untuk " }}{{ strtolower($history->source) }}
                                    </div>
                                    <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-1">Status: Disetujui</div>
                                </td>
                                <td class="px-6 py-4 text-center font-black text-xs {{ $history->type === 'kebaikan' ? 'text-emerald-700' : 'text-rose-700' }}">
                                    {{ $history->points >= 0 ? '+' : '' }}{{ $history->points }} Pts
                                </td>
                                <td class="px-6 py-4 text-right text-xs text-slate-500 font-bold uppercase whitespace-nowrap">
                                    {{ $history->created_at->diffForHumans() }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-12 text-slate-400 text-xs font-bold uppercase tracking-wider">Belum ada riwayat aktivitas.</td>
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
            <div class="bg-[#E4FF1A] text-slate-950 border-4 border-slate-950 rounded-3xl p-6 shadow-[6px_6px_0px_0px_rgba(15,23,42,1)] flex flex-col justify-between min-h-[220px]">
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-[9px] font-black uppercase tracking-wider bg-white text-slate-950 border border-slate-950 px-2.5 py-0.5 rounded shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">AI Student Insight</span>
                        <form method="GET" action="{{ route('student.dashboard') }}">
                            <input type="hidden" name="trigger_ai" value="1">
                            <button type="submit" class="text-xs font-black text-slate-950 hover:underline transition uppercase tracking-wider">Refresh</button>
                        </form>
                    </div>
                    <h3 class="text-base font-black uppercase tracking-tight leading-tight">Bagaimana perkembangan belajarmu hari ini?</h3>
                    <p class="text-xs text-slate-800 leading-relaxed font-semibold line-clamp-4">
                        {{ $aiInsight ?? 'AI Insight belum digenerate. Klik refresh di atas untuk mendapatkan ringkasan profil belajarmu.' }}
                    </p>
                </div>
                <div class="mt-4 pt-4 border-t-2 border-slate-950 flex justify-between items-center text-[10px] font-black text-slate-950 uppercase tracking-wider">
                    <span>Recommends 3 new quests</span>
                    <a href="#quest" class="hover:underline">Lihat Misi →</a>
                </div>
            </div>

            <!-- Statistics Indicators Grid (2x2) -->
            <div class="bg-white rounded-3xl border-4 border-slate-950 p-5 shadow-[6px_6px_0px_0px_rgba(15,23,42,1)]">
                <h3 class="text-sm font-black text-slate-950 uppercase tracking-tight mb-4">Statistik Saya</h3>
                <div class="grid grid-cols-2 gap-4">
                    <!-- Stat 1 -->
                    <div class="bg-slate-50 border-2 border-slate-950 p-4 rounded-xl text-center shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block mb-1">Active days</span>
                        <strong class="text-base font-black text-slate-950">155</strong>
                    </div>
                    <!-- Stat 2 -->
                    <div class="bg-slate-50 border-2 border-slate-950 p-4 rounded-xl text-center shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block mb-1">Total sessions</span>
                        <strong class="text-base font-black text-slate-950">200</strong>
                    </div>
                    <!-- Stat 3 -->
                    <div class="bg-slate-50 border-2 border-slate-950 p-4 rounded-xl text-center shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block mb-1">Total points</span>
                        <strong class="text-base font-black text-slate-950">{{ $user->points }}</strong>
                    </div>
                    <!-- Stat 4 -->
                    <div class="bg-slate-50 border-2 border-slate-950 p-4 rounded-xl text-center shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block mb-1">Longest streak</span>
                        <strong class="text-base font-black text-amber-500">99</strong>
                    </div>
                </div>
            </div>

            <!-- Achievements / Lencana Karakter -->
            <div class="bg-white rounded-3xl border-4 border-slate-950 p-5 shadow-[6px_6px_0px_0px_rgba(15,23,42,1)]">
                <h3 class="text-sm font-black text-slate-950 uppercase tracking-tight mb-4">Lencana Karakter</h3>
                <div class="flex flex-col gap-3">
                    @forelse($badges as $badge)
                    <div class="px-3.5 py-2.5 bg-white border-2 border-slate-950 rounded-xl flex items-center space-x-3 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] cursor-default">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c.195-.39.736-.39.93 0l2.399 4.86 5.342.776c.433.063.606.592.293.898l-3.866 3.769 1.127 5.318c.092.433-.362.762-.75.558L12 17.15l-4.782 2.516c-.388.204-.842-.125-.75-.558l1.127-5.318-3.866-3.769c-.313-.306-.14-.835.293-.898l5.342-.776 2.399-4.86z"></path></svg>
                        <div>
                            <div class="text-xs font-black text-slate-950 uppercase tracking-tight leading-tight">{{ $badge->title }}</div>
                            <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider leading-none mt-1">{{ $badge->description }}</div>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-slate-450 font-bold uppercase tracking-wider text-center w-full py-6 bg-slate-50 border-2 border-slate-950 rounded-xl border-dashed">Belum ada lencana yang diraih. Selesaikan misi untuk meraih lencana!</p>
                    @endforelse
                </div>
            </div>

            <!-- Streak Calendar -->
            <div class="bg-white rounded-3xl border-4 border-slate-950 p-5 shadow-[6px_6px_0px_0px_rgba(15,23,42,1)]">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-black text-slate-950 uppercase tracking-tight">Streak calendar</h3>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">June 2026</span>
                </div>

                @php
                    $days = [28,29,30,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28];
                    $streaks = [1,2,3,4,5,6,8,9,11,12,14,15,16,21,22];
                @endphp
                
                <div class="grid grid-cols-7 gap-y-3 gap-x-1 text-center text-[10px] font-black uppercase">
                    <span class="text-slate-450">M</span>
                    <span class="text-slate-450">T</span>
                    <span class="text-slate-450">W</span>
                    <span class="text-slate-450">T</span>
                    <span class="text-slate-450">F</span>
                    <span class="text-slate-450">S</span>
                    <span class="text-slate-450">S</span>

                    @foreach($days as $idx => $day)
                        @php
                            $isPrevMonth = $idx < 3;
                            $isStreak = in_array($day, $streaks) && !$isPrevMonth;
                        @endphp
                        <div class="flex items-center justify-center h-6 w-full">
                            <span class="flex items-center justify-center h-6 w-6 rounded-full 
                                {{ $isStreak ? 'bg-[#E4FF1A] text-slate-950 border border-slate-950 font-black shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]' : ($isPrevMonth ? 'text-slate-300' : 'text-slate-700') }}">
                                {{ $day }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Points Weekly Chart Widget -->
            <div class="bg-white rounded-3xl border-4 border-slate-950 p-5 shadow-[6px_6px_0px_0px_rgba(15,23,42,1)]">
                <h3 class="text-sm font-black text-slate-950 uppercase tracking-tight mb-4">Points Chart</h3>
                
                <div class="h-28 flex items-end justify-between px-2 pt-2 border-b-2 border-slate-950">
                    <div class="flex flex-col items-center flex-1 space-y-1">
                        <span class="text-[8px] text-slate-400 font-bold">15</span>
                        <div class="w-2.5 bg-[#E4FF1A] border-x border-t border-slate-950 rounded-t-sm" style="height: 60px"></div>
                    </div>
                    <div class="flex flex-col items-center flex-1 space-y-1">
                        <span class="text-[8px] text-slate-400 font-bold">10</span>
                        <div class="w-2.5 bg-[#E4FF1A] border-x border-t border-slate-950 rounded-t-sm" style="height: 40px"></div>
                    </div>
                    <div class="flex flex-col items-center flex-1 space-y-1">
                        <span class="text-[8px] text-slate-400 font-bold">10</span>
                        <div class="w-2.5 bg-[#E4FF1A] border-x border-t border-slate-950 rounded-t-sm" style="height: 40px"></div>
                    </div>
                    <div class="flex flex-col items-center flex-1 space-y-1">
                        <span class="text-[8px] text-slate-400 font-bold">15</span>
                        <div class="w-2.5 bg-[#E4FF1A] border-x border-t border-slate-950 rounded-t-sm" style="height: 60px"></div>
                    </div>
                    <div class="flex flex-col items-center flex-1 space-y-1">
                        <span class="text-[8px] text-slate-400 font-bold">10</span>
                        <div class="w-2.5 bg-[#E4FF1A] border-x border-t border-slate-950 rounded-t-sm" style="height: 40px"></div>
                    </div>
                    <div class="flex flex-col items-center flex-1 space-y-1">
                        <span class="text-[8px] text-slate-400 font-bold">10</span>
                        <div class="w-2.5 bg-[#E4FF1A] border-x border-t border-slate-950 rounded-t-sm" style="height: 40px"></div>
                    </div>
                    <div class="flex flex-col items-center flex-1 space-y-1">
                        <span class="text-[8px] text-slate-400 font-bold">0</span>
                        <div class="w-2.5 bg-slate-100 border-x border-t border-slate-300 rounded-t-sm" style="height: 4px"></div>
                    </div>
                </div>
                <div class="flex justify-between text-[9px] text-slate-400 font-bold px-2 pt-2 text-center uppercase">
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
