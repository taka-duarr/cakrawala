<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 leading-tight">
            {{ __('Monitoring Orang Tua') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-slate-700 to-indigo-950 rounded-2xl shadow-md p-8 text-white">
                <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ $parent->name }}!</h1>
                <p class="text-slate-200 text-sm">Pantau aktivitas, prestasi kebaikan, serta laporan perkembangan karakter anak-anak Anda secara langsung di bawah ini.</p>
            </div>

            <!-- Loop through children -->
            @forelse($children as $child)
            <div class="bg-white rounded-2xl border border-slate-100 p-8 soft-glow-indigo space-y-6">
                <!-- Child Info Header -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-100 pb-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-14 h-14 bg-indigo-100 rounded-full flex items-center justify-center font-bold text-indigo-700 text-xl shadow-inner">
                            {{ substr($child->name, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-slate-800">{{ $child->name }}</h2>
                            <p class="text-sm text-slate-500">Kelas: <strong class="text-slate-700">{{ $child->class_name ?? '-' }}</strong> · Level: <span class="bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-full text-xs font-semibold">{{ $child->current_level }}</span></p>
                        </div>
                    </div>
                    
                    <div class="mt-4 md:mt-0 flex space-x-6">
                        <div class="text-center md:text-right">
                            <span class="text-xs text-slate-400 block font-medium">Poin Kebaikan</span>
                            <strong class="text-2xl text-emerald-600 font-bold">{{ $child->points_kebaikan }} Pts</strong>
                        </div>
                        <div class="text-center md:text-right border-l border-slate-100 pl-6">
                            <span class="text-xs text-slate-400 block font-medium">Poin Pelanggaran</span>
                            <strong class="text-2xl text-rose-500 font-bold">{{ $child->points_pelanggaran }} Pts</strong>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- AI Student Insight -->
                    <div class="lg:col-span-2 space-y-4">
                        <div class="flex justify-between items-center">
                            <h3 class="font-bold text-slate-800 text-base">🤖 AI Student Insight</h3>
                            <form method="GET" action="{{ route('parent.dashboard') }}" onsubmit="let btn = this.querySelector('button[type=submit]'); if(btn) { btn.disabled = true; btn.innerHTML = '<span class=\'animate-spin inline-block w-3.5 h-3.5 border-2 border-current border-t-transparent rounded-full mr-1.5 align-middle\'></span> Memproses...'; }">
                                <input type="hidden" name="trigger_ai" value="1">
                                <input type="hidden" name="student_id" value="{{ $child->id }}">
                                <button type="submit" class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold transition">
                                    Minta AI Insight
                                </button>
                            </form>
                        </div>
                        
                        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 text-sm text-slate-700 leading-relaxed font-sans shadow-inner">
                            {{ $aiInsights[$child->id] }}
                        </div>
                        
                        <!-- Lencana/Badges yang diraih -->
                        <div class="space-y-3 pt-2">
                            <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                                <svg class="w-5 h-5 text-amber-500 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 100-6 3 3 0 000 6zM19.5 8.25c0-1.518-1.232-2.75-2.75-2.75h-.75V3H8v2.5h-.75C5.732 5.5 4.5 6.732 4.5 8.25v.75c0 1.518 1.232 2.75 2.75 2.75h.75M19.5 8.25v.75c0 1.518-1.232 2.75-2.75 2.75h-.75M9 21h6M12 15v6"></path></svg>
                                <span>Lencana Karakter & Prestasi</span>
                            </h3>
                            <div class="flex flex-wrap gap-3">
                                @forelse($child->achievements as $achievement)
                                <div class="badge-glow px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl flex items-center space-x-2.5 shadow-sm">
                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c.195-.39.736-.39.93 0l2.399 4.86 5.342.776c.433.063.606.592.293.898l-3.866 3.769 1.127 5.318c.092.433-.362.762-.75.558L12 17.15l-4.782 2.516c-.388.204-.842-.125-.75-.558l1.127-5.318-3.866-3.769c-.313-.306-.14-.835.293-.898l5.342-.776 2.399-4.86z"></path></svg>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800">{{ $achievement->title }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $achievement->description }}</div>
                                    </div>
                                </div>
                                @empty
                                <p class="text-xs text-slate-400">Belum ada lencana yang diraih.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Riwayat Aktivitas Anak -->
                    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden soft-glow-indigo flex flex-col justify-between">
                        <div>
                            <div class="p-6 border-b border-slate-100">
                                <h3 class="font-bold text-slate-800 text-base">Activity Log</h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-50/70 border-b border-slate-100/80">
                                            <th class="px-4 py-2.5 text-[9px] font-bold text-slate-400 uppercase tracking-wider">Aktivitas</th>
                                            <th class="px-4 py-2.5 text-[9px] font-bold text-slate-400 uppercase tracking-wider text-center">Poin</th>
                                            <th class="px-4 py-2.5 text-[9px] font-bold text-slate-400 uppercase tracking-wider text-right">Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100/70">
                                        @forelse($pointHistories[$child->id] ?? [] as $history)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="px-4 py-3">
                                                <div class="text-xs font-semibold text-slate-700 leading-tight">{{ $history->source }}</div>
                                                <div class="text-[9px] text-slate-400 mt-0.5 capitalize">{{ $history->type }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-center font-extrabold text-xs {{ $history->type === 'kebaikan' ? 'text-emerald-600' : 'text-rose-500' }}">
                                                {{ $history->type === 'kebaikan' ? '+' : '-' }}{{ $history->points }} Pts
                                            </td>
                                            <td class="px-4 py-3 text-right text-[10px] text-slate-400 font-medium whitespace-nowrap">
                                                {{ $history->created_at->diffForHumans() }}
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-8 text-slate-400 text-xs font-medium">Belum ada riwayat aktivitas.</td>
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
            <div class="bg-white rounded-2xl border border-slate-100 p-8 text-center text-slate-400 shadow-sm">
                <p class="font-medium">Akun Anda belum ditautkan ke siswa mana pun. Silakan hubungi admin sekolah.</p>
            </div>
            @endforelse

        </div>
    </div>
</x-app-layout>
