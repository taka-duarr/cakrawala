<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 leading-tight flex items-center gap-2.5">
            <svg class="w-7 h-7 text-amber-500 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 100-6 3 3 0 000 6zM19.5 8.25c0-1.518-1.232-2.75-2.75-2.75h-.75V3H8v2.5h-.75C5.732 5.5 4.5 6.732 4.5 8.25v.75c0 1.518 1.232 2.75 2.75 2.75h.75M19.5 8.25v.75c0 1.518-1.232 2.75-2.75 2.75h-.75M9 21h6M12 15v6"></path>
            </svg>
            <span>Leaderboard Karakter</span>
        </h2>
    </x-slot>

    <div class="py-6 space-y-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto space-y-8">
            
            <!-- Student Rankings Top Podium -->
            <div class="bg-white rounded-2xl border border-slate-100 p-8 shadow-sm soft-glow-indigo">
                <h3 class="text-xl font-extrabold text-slate-800 text-center mb-1">Peringkat 3 Besar Karakter Siswa</h3>
                <p class="text-xs text-slate-400 text-center mb-8 font-medium">Siswa terunggul dalam kebaikan karakter bulan ini.</p>
                
                <div class="flex flex-col md:flex-row justify-center items-end gap-6 md:gap-12 pb-6 border-b border-slate-100/80">
                    <!-- Rank 2 (Silver) -->
                    @if($leaderboardData->count() > 1)
                    <div class="flex flex-col items-center order-2 md:order-1 mb-2">
                        <div class="relative flex items-center justify-center w-20 h-20 bg-slate-100 border-2 border-slate-200 rounded-full shadow-inner font-black text-2xl text-slate-600">
                            {{ substr($leaderboardData[1]->name, 0, 1) }}
                            <span class="absolute -bottom-2 -right-1 w-6 h-6 rounded-full bg-slate-400 border-2 border-white flex items-center justify-center text-[10px] text-white font-extrabold">2</span>
                        </div>
                        <h4 class="font-bold text-sm text-slate-700 mt-4 text-center leading-none">{{ $leaderboardData[1]->name }}</h4>
                        <span class="text-[10px] text-slate-400 font-semibold mt-1">{{ $leaderboardData[1]->class_name }}</span>
                        <span class="mt-2.5 px-3 py-1 bg-slate-50 text-slate-700 text-xs font-extrabold rounded-full border border-slate-200">{{ $leaderboardData[1]->points_kebaikan }} Pts</span>
                    </div>
                    @endif

                    <!-- Rank 1 (Gold) -->
                    @if($leaderboardData->count() > 0)
                    <div class="flex flex-col items-center order-1 md:order-2 mb-6 md:mb-10 scale-105">
                        <div class="relative flex items-center justify-center w-24 h-24 bg-amber-100 border-4 border-amber-300 rounded-full shadow-inner font-black text-3xl text-amber-700">
                            👑
                            <div class="absolute inset-0 flex items-center justify-center text-amber-700 opacity-20">
                                {{ substr($leaderboardData[0]->name, 0, 1) }}
                            </div>
                            <span class="absolute -bottom-2 right-1 w-7 h-7 rounded-full bg-amber-400 border-2 border-white flex items-center justify-center text-xs text-white font-black">1</span>
                        </div>
                        <h4 class="font-extrabold text-base text-slate-800 mt-4 text-center leading-none">{{ $leaderboardData[0]->name }}</h4>
                        <span class="text-xs text-slate-400 font-semibold mt-1">{{ $leaderboardData[0]->class_name }}</span>
                        <span class="mt-2.5 px-4 py-1.5 bg-amber-50 text-amber-700 text-xs font-black rounded-full border border-amber-200 shadow-sm shadow-amber-100">{{ $leaderboardData[0]->points_kebaikan }} Pts</span>
                    </div>
                    @endif

                    <!-- Rank 3 (Bronze) -->
                    @if($leaderboardData->count() > 2)
                    <div class="flex flex-col items-center order-3 mb-2">
                        <div class="relative flex items-center justify-center w-20 h-20 bg-orange-50 border-2 border-orange-200 rounded-full shadow-inner font-black text-2xl text-orange-800">
                            {{ substr($leaderboardData[2]->name, 0, 1) }}
                            <span class="absolute -bottom-2 -right-1 w-6 h-6 rounded-full bg-orange-400 border-2 border-white flex items-center justify-center text-[10px] text-white font-extrabold">3</span>
                        </div>
                        <h4 class="font-bold text-sm text-slate-700 mt-4 text-center leading-none">{{ $leaderboardData[2]->name }}</h4>
                        <span class="text-[10px] text-slate-400 font-semibold mt-1">{{ $leaderboardData[2]->class_name }}</span>
                        <span class="mt-2.5 px-3 py-1 bg-orange-50/50 text-orange-800 text-xs font-extrabold rounded-full border border-orange-200">{{ $leaderboardData[2]->points_kebaikan }} Pts</span>
                    </div>
                    @endif
                </div>

                <!-- Leaderboard Tabbed Tables -->
                <div class="pt-8">
                    <ul class="flex border-b border-slate-100 mb-6 text-sm font-semibold" uk-tab="connect: #leaderboard-tables; animation: uk-animation-fade">
                        <li><a href="#" class="pb-3 px-4 text-slate-500 hover:text-indigo-600 transition">Peringkat Siswa</a></li>
                        <li><a href="#" class="pb-3 px-4 text-slate-500 hover:text-indigo-600 transition">Peringkat Kelas</a></li>
                    </ul>

                    <div id="leaderboard-tables" class="uk-switcher">
                        <!-- Siswa Ranking Table -->
                        <div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-50/70 border-b border-slate-100/80">
                                            <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center w-20">Rank</th>
                                            <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nama Siswa</th>
                                            <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Kelas</th>
                                            <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Level</th>
                                            <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Poin Kebaikan</th>
                                            <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Pelanggaran</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100/70">
                                        @foreach($leaderboardData as $index => $item)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="px-6 py-4 text-center">
                                                @if($index == 0)
                                                    <span class="bg-amber-100 text-amber-800 border border-amber-200/50 w-6 h-6 flex items-center justify-center rounded-full text-xs font-black shadow-sm mx-auto">1</span>
                                                @elseif($index == 1)
                                                    <span class="bg-slate-100 text-slate-700 border border-slate-200/50 w-6 h-6 flex items-center justify-center rounded-full text-xs font-black shadow-sm mx-auto">2</span>
                                                @elseif($index == 2)
                                                    <span class="bg-orange-100 text-orange-800 border border-orange-200/50 w-6 h-6 flex items-center justify-center rounded-full text-xs font-black shadow-sm mx-auto">3</span>
                                                @else
                                                    <span class="text-slate-400 text-xs font-bold">{{ $index + 1 }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-8 h-8 bg-indigo-50 border border-indigo-100/30 rounded-full flex items-center justify-center font-extrabold text-indigo-700 text-xs shadow-inner">
                                                        {{ substr($item->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="font-bold text-slate-800 text-xs leading-none mb-1">{{ $item->name }}</div>
                                                        <div class="text-[9px] text-slate-400 font-semibold">{{ $item->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center text-xs text-slate-500 font-semibold">{{ $item->class_name }}</td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100/80">
                                                    {{ $item->current_level }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right font-extrabold text-xs text-emerald-600">{{ $item->points_kebaikan }} Pts</td>
                                            <td class="px-6 py-4 text-right font-extrabold text-xs text-rose-500">{{ $item->points_pelanggaran }} Pts</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Class Ranking Table -->
                        <div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-50/70 border-b border-slate-100/80">
                                            <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center w-24">Rank</th>
                                            <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nama Kelas</th>
                                            <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Total Kebaikan</th>
                                            <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Total Pelanggaran</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100/70">
                                        @foreach($classRankings as $index => $kelas)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="px-6 py-4 text-center">
                                                @if($index == 0)
                                                    <span class="bg-amber-100 text-amber-800 border border-amber-200/50 w-6 h-6 flex items-center justify-center rounded-full text-xs font-black shadow-sm mx-auto">1</span>
                                                @elseif($index == 1)
                                                    <span class="bg-slate-100 text-slate-700 border border-slate-200/50 w-6 h-6 flex items-center justify-center rounded-full text-xs font-black shadow-sm mx-auto">2</span>
                                                @elseif($index == 2)
                                                    <span class="bg-orange-100 text-orange-800 border border-orange-200/50 w-6 h-6 flex items-center justify-center rounded-full text-xs font-black shadow-sm mx-auto">3</span>
                                                @else
                                                    <span class="text-slate-400 text-xs font-bold">{{ $index + 1 }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 font-bold text-slate-800 text-xs">{{ $kelas->class_name }}</td>
                                            <td class="px-6 py-4 text-right font-extrabold text-xs text-emerald-600">{{ $kelas->total_kebaikan }} Pts</td>
                                            <td class="px-6 py-4 text-right font-extrabold text-xs text-rose-500">{{ $kelas->total_pelanggaran }} Pts</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
