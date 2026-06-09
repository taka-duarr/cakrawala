<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-slate-800 leading-tight flex items-center gap-2.5">
                <svg class="w-7 h-7 text-indigo-600 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"></path></svg>
                <span>Toko Hadiah</span>
            </h2>
            <div class="bg-indigo-50 border border-indigo-100 rounded-full px-4 py-1.5 flex items-center space-x-2 text-indigo-700 font-bold text-sm shadow-sm">
                <svg class="w-4 h-4 text-amber-500 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c.195-.39.736-.39.93 0l2.399 4.86 5.342.776c.433.063.606.592.293.898l-3.866 3.769 1.127 5.318c.092.433-.362.762-.75.558L12 17.15l-4.782 2.516c-.388.204-.842-.125-.75-.558l1.127-5.318-3.866-3.769c-.313-.306-.14-.835.293-.898l5.342-.776 2.399-4.86z"></path></svg>
                <span>Poin Kebaikan Anda:</span>
                <span class="bg-indigo-600 text-white rounded-full px-3 py-0.5 text-xs">{{ $user->points_kebaikan }} Pts</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 flex items-center space-x-3">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="font-medium text-sm">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-4 flex items-center space-x-3">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <p class="font-medium text-sm">{{ session('error') }}</p>
            </div>
            @endif

            <!-- Grid Hadiah -->
            <div class="bg-white rounded-2xl border border-slate-100 p-8 soft-glow-indigo">
                <h3 class="text-xl font-bold text-slate-800 mb-2">Pilih Hadiah Karakter</h3>
                <p class="text-sm text-slate-500 mb-6">Tukarkan poin kebaikan yang telah Anda kumpulkan selama proses belajar dan beraktivitas.</p>
                
                <!-- Search and Filters -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                    <!-- Filters -->
                    <div class="flex flex-wrap gap-2 text-xs font-semibold text-slate-500">
                        <button onclick="filterRewards('all')" class="reward-filter-btn px-4 py-2 rounded-xl bg-indigo-600 text-white shadow-sm transition">Semua</button>
                        <button onclick="filterRewards('akademik')" class="reward-filter-btn px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition">Akademik</button>
                        <button onclick="filterRewards('pengembangan_diri')" class="reward-filter-btn px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition">Pengembangan Diri</button>
                        <button onclick="filterRewards('sekolah')" class="reward-filter-btn px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition">Sekolah</button>
                        <button onclick="filterRewards('penghargaan')" class="reward-filter-btn px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition">Penghargaan</button>
                    </div>
                    <!-- Search -->
                    <div class="relative w-full md:w-72">
                        <span uk-icon="icon: search; ratio: 0.85" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></span>
                        <input type="text" id="search-rewards" onkeyup="filterRewards()" placeholder="Cari hadiah..." 
                            class="w-full border border-slate-200 bg-slate-50/50 rounded-xl pl-9 pr-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 placeholder-slate-400">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @forelse($rewards as $reward)
                    <div class="reward-card shimmer-card bg-slate-50/50 border border-slate-100 rounded-2xl p-6 flex flex-col justify-between transition hover:-translate-y-1 hover:bg-white hover:shadow-lg hover:shadow-indigo-50"
                         data-category="{{ $reward->category }}" data-name="{{ strtolower($reward->name) }}" data-description="{{ strtolower($reward->description) }}">
                        <div>
                            <div class="flex justify-between items-start mb-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 capitalize">
                                    {{ str_replace('_', ' ', $reward->category) }}
                                </span>
                                <span class="font-bold text-sm text-emerald-600">+{{ $reward->points_cost }} Pts</span>
                            </div>
                            <h4 class="font-bold text-slate-800 mb-2">{{ $reward->name }}</h4>
                            <p class="text-xs text-slate-500 mb-4 line-clamp-3 leading-relaxed">{{ $reward->description }}</p>
                        </div>

                        <div class="mt-4">
                            @if($user->points_kebaikan >= $reward->points_cost)
                            <form method="POST" action="{{ route('student.rewards.claim', $reward->id) }}" onsubmit="let btn = this.querySelector('button[type=submit]'); if(btn) { btn.disabled = true; btn.innerHTML = '<span class=\'animate-spin inline-block w-3.5 h-3.5 border-2 border-current border-t-transparent rounded-full mr-1.5 align-middle\'></span> Menukarkan...'; }">
                                @csrf
                                <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold transition">
                                    Tukarkan Hadiah
                                </button>
                            </form>
                            @else
                            <button disabled class="w-full py-2 bg-slate-200 text-slate-400 rounded-xl text-xs font-semibold cursor-not-allowed">
                                Poin Tidak Cukup
                            </button>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="col-span-4 text-center py-12 text-slate-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        <p class="font-medium text-sm">Belum ada hadiah yang tersedia di toko.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Riwayat Klaim -->
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden soft-glow-indigo">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">Riwayat Penukaran Poin</h3>
                    <p class="text-xs text-slate-400 mt-1 font-medium">Pantau status penyerahan hadiah yang telah Anda klaim.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100/80">
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-left">Nama Hadiah</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Biaya Poin</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Tanggal Klaim</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/70">
                            @forelse($claimedRewards as $claim)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-800 text-xs">{{ $claim->name }}</td>
                                <td class="px-6 py-4 text-center font-extrabold text-xs text-rose-500">-{{ $claim->points_cost }} Pts</td>
                                <td class="px-6 py-4 text-center text-xs text-slate-400 font-medium">{{ $claim->pivot->created_at->diffForHumans() }}</td>
                                <td class="px-6 py-4 text-right">
                                    @if($claim->pivot->status === 'pending_approval')
                                        <span class="inline-flex items-center space-x-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-100/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            <span>Menunggu Verifikasi</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center space-x-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Sudah Diserahkan</span>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-12 text-slate-400 text-xs font-medium">Anda belum pernah melakukan klaim hadiah.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        let currentFilter = 'all';

        function filterRewards(category) {
            if (category) {
                currentFilter = category;
                
                // Update active button styles
                const buttons = document.querySelectorAll('.reward-filter-btn');
                buttons.forEach(btn => {
                    let match = false;
                    const text = btn.textContent.trim();
                    if (category === 'all' && text === 'Semua') match = true;
                    if (category === 'akademik' && text === 'Akademik') match = true;
                    if (category === 'pengembangan_diri' && text === 'Pengembangan Diri') match = true;
                    if (category === 'sekolah' && text === 'Sekolah') match = true;
                    if (category === 'penghargaan' && text === 'Penghargaan') match = true;

                    if (match) {
                        btn.className = 'reward-filter-btn px-4 py-2 rounded-xl bg-indigo-600 text-white shadow-sm transition';
                    } else {
                        btn.className = 'reward-filter-btn px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition';
                    }
                });
            }

            const searchVal = document.getElementById('search-rewards').value.toLowerCase();
            const cards = document.querySelectorAll('.reward-card');

            cards.forEach(card => {
                const cardCat = card.getAttribute('data-category');
                const cardName = card.getAttribute('data-name');
                const cardDesc = card.getAttribute('data-description');

                const matchesCategory = currentFilter === 'all' || cardCat === currentFilter;
                const matchesSearch = cardName.includes(searchVal) || cardDesc.includes(searchVal);

                if (matchesCategory && matchesSearch) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</x-app-layout>
