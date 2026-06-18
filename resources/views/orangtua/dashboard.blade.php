<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">
            {{ __('Monitoring Orang Tua') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-100/40 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome Banner -->
            <div class="bg-[#E4FF1A] border-4 border-slate-950 rounded-3xl shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] p-8 text-slate-950">
                <h1 class="text-3xl font-black mb-2 uppercase tracking-tight">Selamat Datang, {{ $parent->name }}!</h1>
                <p class="text-slate-800 text-xs font-bold uppercase tracking-wider">Pantau aktivitas, prestasi kebaikan, serta laporan perkembangan karakter anak-anak Anda secara langsung di bawah ini.</p>
            </div>

            <!-- Loop through children -->
            @forelse($children as $child)
            <div class="bg-white rounded-3xl border-4 border-slate-950 p-8 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] space-y-6">
                <!-- Child Info Header -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b-4 border-slate-950 pb-6 gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-14 h-14 bg-white border-2 border-slate-950 rounded-full flex items-center justify-center font-black text-slate-950 text-xl shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                            {{ substr($child->name, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-slate-950 uppercase tracking-tight">{{ $child->name }}</h2>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">
                                Kelas: <strong class="text-slate-950">{{ $child->classroom->name ?? '-' }}</strong> · Level: 
                                <span class="bg-[#E4FF1A] border-2 border-slate-950 text-slate-950 px-2 py-0.5 rounded shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] text-[10px] font-black uppercase tracking-wider ml-1">
                                    {{ $child->current_level }}
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex space-x-6">
                        <div>
                            <span class="text-[9px] text-slate-400 font-black uppercase tracking-wider block mb-1 text-left md:text-right">Total Poin</span>
                            <span class="inline-block text-xl font-black text-slate-950 bg-[#E4FF1A] border-2 border-slate-950 px-3 py-1 rounded-xl shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider">
                                {{ number_format($child->points) }} Pts
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- AI Student Insight -->
                    <div class="lg:col-span-2 space-y-4">
                        <div class="flex flex-wrap justify-between items-center gap-2">
                            <h3 class="font-black text-slate-950 text-sm uppercase tracking-wider">🤖 AI Student Insight</h3>
                            <form method="GET" action="{{ route('parent.dashboard') }}" onsubmit="let btn = this.querySelector('button[type=submit]'); if(btn) { btn.disabled = true; btn.innerHTML = '<span class=\'animate-spin inline-block w-3.5 h-3.5 border-2 border-current border-t-transparent rounded-full mr-1.5 align-middle\'></span> Memproses...'; }">
                                <input type="hidden" name="trigger_ai" value="1">
                                <input type="hidden" name="student_id" value="{{ $child->id }}">
                                <button type="submit" class="px-4 py-2 bg-white hover:bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 text-xs font-black rounded-xl transition shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider flex items-center space-x-1.5">
                                    <span>Minta AI Insight</span>
                                </button>
                            </form>
                        </div>
                        
                        <div class="bg-[#E4FF1A]/10 border-2 border-slate-950 rounded-2xl p-6 text-xs text-slate-950 font-bold uppercase tracking-wide leading-relaxed shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                            {{ $aiInsights[$child->id] }}
                        </div>
                        
                        <!-- Lencana/Badges yang diraih -->
                        <div class="space-y-3 pt-2">
                            <h3 class="font-black text-slate-950 text-sm uppercase tracking-wider flex items-center gap-2">
                                <span uk-icon="icon: award; ratio: 0.9" class="text-amber-500"></span>
                                <span>Lencana Karakter & Prestasi</span>
                            </h3>
                            <div class="flex flex-wrap gap-3">
                                @forelse($child->achievements as $achievement)
                                    <div class="px-4 py-2.5 bg-white border-2 border-slate-950 rounded-xl flex items-center space-x-2.5 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-0.5 transition-all">
                                        <span uk-icon="icon: star; ratio: 0.75" class="text-amber-500"></span>
                                        <div>
                                            <div class="text-xs font-black text-slate-950 uppercase tracking-tight">{{ $achievement->title }}</div>
                                            <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $achievement->description }}</div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider italic">Belum ada lencana yang diraih.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Riwayat Aktivitas Anak -->
                    <div class="bg-white rounded-2xl border-2 border-slate-950 overflow-hidden shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] flex flex-col justify-between">
                        <div>
                            <div class="p-6 border-b-2 border-slate-950 bg-[#E4FF1A]/10">
                                <h3 class="font-black text-slate-950 text-sm uppercase tracking-tight">Log Aktivitas</h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-950 text-white border-b-2 border-slate-950">
                                            <th class="px-4 py-3 text-[9px] font-black uppercase tracking-wider">Aktivitas</th>
                                            <th class="px-4 py-3 text-[9px] font-black uppercase tracking-wider text-center">Poin</th>
                                            <th class="px-4 py-3 text-[9px] font-black uppercase tracking-wider text-right">Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-950 bg-white">
                                        @forelse($pointHistories[$child->id] ?? [] as $history)
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="px-4 py-3">
                                                <div class="text-xs font-black text-slate-950 uppercase tracking-tight leading-snug">{{ $history->source }}</div>
                                                <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $history->type }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-center font-black text-xs {{ $history->points > 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                                                {{ $history->points > 0 ? '+' : '' }}{{ $history->points }} Pts
                                            </td>
                                            <td class="px-4 py-3 text-right text-[9px] text-slate-400 font-black uppercase tracking-wider whitespace-nowrap">
                                                {{ $history->created_at->diffForHumans() }}
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-8 text-slate-400 text-xs font-bold uppercase tracking-wider italic">Belum ada riwayat aktivitas.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-2xl border-2 border-slate-950 p-8 text-center text-slate-400 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] font-bold uppercase tracking-wider">
                <p>Akun Anda belum ditautkan ke siswa mana pun. Silakan hubungi admin sekolah.</p>
            </div>
            @endforelse

        </div>
    </div>
</x-app-layout>
