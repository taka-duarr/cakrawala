<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-950 leading-tight flex items-center gap-2.5 uppercase tracking-tight">
            <svg class="w-7 h-7 text-slate-950 inline-block" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 100-6 3 3 0 000 6zM19.5 8.25c0-1.518-1.232-2.75-2.75-2.75h-.75V3H8v2.5h-.75C5.732 5.5 4.5 6.732 4.5 8.25v.75c0 1.518 1.232 2.75 2.75 2.75h.75M19.5 8.25v.75c0 1.518-1.232 2.75-2.75 2.75h-.75M9 21h6M12 15v6"></path>
            </svg>
            <span>Leaderboard Karakter</span>
        </h2>
    </x-slot>

    <div class="py-6 space-y-8 bg-slate-100/30 min-h-screen">
        <div class="max-w-7xl mx-auto space-y-8">
            
            <!-- Student Rankings Top Podium -->
            <div class="bg-white rounded-3xl border-4 border-slate-950 p-8 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <h3 class="text-xl font-black text-slate-950 text-center mb-1 uppercase tracking-tight">Peringkat 3 Besar Karakter Siswa</h3>
                <p class="text-xs text-slate-400 text-center mb-8 font-bold uppercase tracking-wider">Siswa terunggul dalam kebaikan karakter bulan ini.</p>
                
                <div class="flex flex-col md:flex-row justify-center items-end gap-6 md:gap-12 pb-8 border-b-2 border-slate-950">
                    <!-- Rank 2 (Silver) -->
                    @if($leaderboardData->count() > 1)
                    <div class="flex flex-col items-center order-2 md:order-1 mb-2 bg-slate-50 border-4 border-slate-950 rounded-2xl p-6 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] w-full md:w-56">
                        <div class="relative flex items-center justify-center w-20 h-20 bg-white border-2 border-slate-950 rounded-full shadow-[2.5px_2.5px_0px_0px_rgba(15,23,42,1)] font-black text-2xl text-slate-800">
                            {{ substr($leaderboardData[1]->name, 0, 1) }}
                            <span class="absolute -bottom-2 -right-1 w-7 h-7 rounded-full bg-slate-200 border-2 border-slate-950 flex items-center justify-center text-xs text-slate-900 font-black">2</span>
                        </div>
                        <h4 class="font-black text-sm text-slate-950 mt-4 text-center leading-none uppercase tracking-tight truncate max-w-full">{{ $leaderboardData[1]->name }}</h4>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1.5">{{ $leaderboardData[1]->class_name }}</span>
                        <span class="mt-3.5 px-4 py-1.5 bg-white text-slate-950 text-xs font-black rounded-lg border-2 border-slate-950 shadow-[1.5px_1.5px_0px_0px_rgba(15,23,42,1)]">{{ $leaderboardData[1]->points }} Pts</span>
                    </div>
                    @endif

                    <!-- Rank 1 (Gold) -->
                    @if($leaderboardData->count() > 0)
                    <div class="flex flex-col items-center order-1 md:order-2 mb-6 md:mb-8 scale-105 bg-[#E4FF1A] border-4 border-slate-950 rounded-2xl p-6 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] w-full md:w-60">
                        <div class="relative flex items-center justify-center w-24 h-24 bg-white border-2 border-slate-950 rounded-full shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] font-black text-3xl text-slate-900">
                            👑
                            <div class="absolute inset-0 flex items-center justify-center text-slate-900 opacity-10">
                                {{ substr($leaderboardData[0]->name, 0, 1) }}
                            </div>
                            <span class="absolute -bottom-2 right-1 w-8 h-8 rounded-full bg-amber-400 border-2 border-slate-950 flex items-center justify-center text-xs text-slate-950 font-black">1</span>
                        </div>
                        <h4 class="font-black text-base text-slate-950 mt-4 text-center leading-none uppercase tracking-tight truncate max-w-full">{{ $leaderboardData[0]->name }}</h4>
                        <span class="text-xs text-slate-800 font-bold uppercase tracking-wider mt-1.5">{{ $leaderboardData[0]->class_name }}</span>
                        <span class="mt-3.5 px-4 py-1.5 bg-white text-slate-950 text-xs font-black rounded-lg border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">{{ $leaderboardData[0]->points }} Pts</span>
                    </div>
                    @endif

                    <!-- Rank 3 (Bronze) -->
                    @if($leaderboardData->count() > 2)
                    <div class="flex flex-col items-center order-3 mb-2 bg-[#FFF3EA] border-4 border-slate-950 rounded-2xl p-6 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] w-full md:w-56">
                        <div class="relative flex items-center justify-center w-20 h-20 bg-white border-2 border-slate-950 rounded-full shadow-[2.5px_2.5px_0px_0px_rgba(15,23,42,1)] font-black text-2xl text-orange-900">
                            {{ substr($leaderboardData[2]->name, 0, 1) }}
                            <span class="absolute -bottom-2 -right-1 w-7 h-7 rounded-full bg-orange-300 border-2 border-slate-950 flex items-center justify-center text-xs text-slate-900 font-black">3</span>
                        </div>
                        <h4 class="font-black text-sm text-slate-950 mt-4 text-center leading-none uppercase tracking-tight truncate max-w-full">{{ $leaderboardData[2]->name }}</h4>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1.5">{{ $leaderboardData[2]->class_name }}</span>
                        <span class="mt-3.5 px-4 py-1.5 bg-white text-orange-950 text-xs font-black rounded-lg border-2 border-slate-950 shadow-[1.5px_1.5px_0px_0px_rgba(15,23,42,1)]">{{ $leaderboardData[2]->points }} Pts</span>
                    </div>
                    @endif
                </div>

                <!-- Leaderboard Tabbed Tables -->
                <div class="pt-8">
                    <ul class="flex border-b-2 border-slate-950 mb-6 text-sm font-black uppercase tracking-wider" uk-tab="connect: #leaderboard-tables; animation: uk-animation-fade">
                        <li class="uk-active"><a href="#" class="pb-3 px-4 text-slate-500 hover:text-slate-950 border-b-2 border-transparent transition">Peringkat Siswa</a></li>
                        <li><a href="#" class="pb-3 px-4 text-slate-500 hover:text-slate-950 border-b-2 border-transparent transition">Peringkat Kelas</a></li>
                    </ul>

                    <div id="leaderboard-tables" class="uk-switcher">
                        <!-- Siswa Ranking Table -->
                        <div>
                            <div class="overflow-x-auto border-2 border-slate-950 rounded-2xl shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] bg-white">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-950 text-white border-b-2 border-slate-950">
                                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-center w-20">Rank</th>
                                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider">Nama Siswa</th>
                                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-center">Kelas</th>
                                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-center">Level</th>
                                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-right">Total Poin</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y-2 divide-slate-950">
                                        @foreach($leaderboardData as $index => $item)
                                        <tr class="hover:bg-slate-100 transition-colors">
                                            <td class="px-6 py-4 text-center">
                                                @if($index == 0)
                                                    <span class="bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 w-7 h-7 flex items-center justify-center rounded-full text-xs font-black shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] mx-auto">1</span>
                                                @elseif($index == 1)
                                                    <span class="bg-slate-200 text-slate-950 border-2 border-slate-950 w-7 h-7 flex items-center justify-center rounded-full text-xs font-black shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] mx-auto">2</span>
                                                @elseif($index == 2)
                                                    <span class="bg-orange-200 text-orange-950 border-2 border-slate-950 w-7 h-7 flex items-center justify-center rounded-full text-xs font-black shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] mx-auto">3</span>
                                                @else
                                                    <span class="text-slate-500 text-xs font-black">{{ $index + 1 }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-8 h-8 bg-white border-2 border-slate-950 rounded-full flex items-center justify-center font-black text-slate-950 text-xs">
                                                        {{ substr($item->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="font-black text-slate-950 text-xs leading-none mb-1 uppercase">{{ $item->name }}</div>
                                                        <div class="text-[9px] text-slate-400 font-bold">{{ $item->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center text-xs text-slate-700 font-bold uppercase">{{ $item->class_name }}</td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="px-2.5 py-1 rounded-md text-[10px] font-black bg-white text-slate-950 border-2 border-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase">
                                                    {{ $item->current_level }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right font-black text-xs text-slate-950">{{ number_format($item->points) }} Pts</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Class Ranking Table -->
                        <div>
                            <div class="overflow-x-auto border-2 border-slate-950 rounded-2xl shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] bg-white">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-950 text-white border-b-2 border-slate-950">
                                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-center w-24">Rank</th>
                                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider">Nama Kelas</th>
                                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-right">Total Poin</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y-2 divide-slate-950">
                                        @foreach($classRankings as $index => $kelas)
                                        <tr class="hover:bg-slate-100 transition-colors">
                                            <td class="px-6 py-4 text-center">
                                                @if($index == 0)
                                                    <span class="bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 w-7 h-7 flex items-center justify-center rounded-full text-xs font-black shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] mx-auto">1</span>
                                                @elseif($index == 1)
                                                    <span class="bg-slate-200 text-slate-950 border-2 border-slate-950 w-7 h-7 flex items-center justify-center rounded-full text-xs font-black shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] mx-auto">2</span>
                                                @elseif($index == 2)
                                                    <span class="bg-orange-200 text-orange-950 border-2 border-slate-950 w-7 h-7 flex items-center justify-center rounded-full text-xs font-black shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] mx-auto">3</span>
                                                @else
                                                    <span class="text-slate-500 text-xs font-black">{{ $index + 1 }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 font-black text-slate-950 text-xs uppercase">{{ $kelas->class_name }}</td>
                                            <td class="px-6 py-4 text-right font-black text-xs text-slate-950">{{ number_format($kelas->total_kebaikan) }} Pts</td>
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
