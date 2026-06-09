<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 leading-tight">
            {{ __('Kelola Toko Hadiah') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 flex items-center space-x-3">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="font-medium text-sm">{{ session('success') }}</p>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Tambah Reward Baru -->
                <div class="bg-white rounded-2xl border border-slate-100 p-6 soft-glow-indigo">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Tambah Hadiah Baru</h3>
                    
                    <form method="POST" action="{{ route('admin.rewards.store') }}" class="space-y-4" onsubmit="let btn = this.querySelector('button[type=submit]'); if(btn) { btn.disabled = true; btn.innerHTML = '<span class=\'animate-spin inline-block w-3.5 h-3.5 border-2 border-current border-t-transparent rounded-full mr-1.5 align-middle\'></span> Menyimpan...'; }">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Hadiah</label>
                            <input type="text" name="name" required placeholder="Contoh: Voucher Buku"
                                class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Deskripsi</label>
                            <textarea name="description" required placeholder="Jelaskan detail hadiah..." rows="3"
                                class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Biaya Poin</label>
                                <input type="number" name="points_cost" required min="1" placeholder="100"
                                    class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Kategori</label>
                                <select name="category" required
                                    class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="akademik">Akademik</option>
                                    <option value="pengembangan_diri">Pengembangan Diri</option>
                                    <option value="sekolah">Sekolah</option>
                                    <option value="penghargaan">Penghargaan</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition">
                            Simpan Hadiah
                        </button>
                    </form>
                </div>

                <!-- Pengajuan Klaim Pending -->
                <div class="bg-white rounded-2xl border border-slate-100 p-6 lg:col-span-2 soft-glow-indigo overflow-hidden">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Pengajuan Penukaran Poin (Siswa)</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-100/80">
                                    <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Siswa</th>
                                    <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Hadiah</th>
                                    <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Biaya</th>
                                    <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100/70">
                                @forelse($pendingClaims as $claim)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-indigo-50 border border-indigo-100/30 rounded-full flex items-center justify-center font-extrabold text-indigo-700 text-xs shadow-inner">
                                                {{ substr($claim->student_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-800 text-xs leading-none mb-1">{{ $claim->student_name }}</div>
                                                <div class="text-[9px] text-slate-400 font-semibold uppercase tracking-wider">Kelas: {{ $claim->class_name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-xs font-semibold text-slate-800 mb-0.5">{{ $claim->reward_name }}</div>
                                        <div class="text-[10px] text-slate-400 font-medium">{{ \Carbon\Carbon::parse($claim->created_at)->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center font-extrabold text-xs text-emerald-600">{{ $claim->points_cost }} Pts</td>
                                    <td class="px-6 py-4 text-right">
                                        <form method="POST" action="{{ route('admin.rewards.approve', $claim->id) }}" onsubmit="let btn = this.querySelector('button[type=submit]'); if(btn) { btn.disabled = true; btn.innerHTML = '<span class=\'animate-spin inline-block w-3.5 h-3.5 border-2 border-current border-t-transparent rounded-full align-middle\'></span>'; }">
                                            @csrf
                                            <button type="submit" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-bold rounded-xl transition shadow-sm hover:shadow-md min-w-[110px] flex items-center justify-center">
                                                Setujui & Serahkan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-12 text-slate-400 text-xs font-medium">Tidak ada klaim hadiah yang pending.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Daftar Semua Hadiah -->
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden soft-glow-indigo">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">Daftar Semua Hadiah</h3>
                    <p class="text-xs text-slate-400 mt-1 font-medium">Edit atau hapus hadiah yang tersedia di toko.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100/80">
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nama Hadiah</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Biaya Poin</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Kategori</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Tersedia</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Total Klaim</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/70">
                            @forelse($rewards as $reward)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-800 text-xs">{{ $reward->name }}</td>
                                <td class="px-6 py-4 text-xs text-slate-400 font-medium max-w-xs truncate" title="{{ $reward->description }}">{{ $reward->description }}</td>
                                <td class="px-6 py-4 text-center font-extrabold text-xs text-indigo-600">{{ $reward->points_cost }} Pts</td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $catColors = match($reward->category) {
                                            'akademik' => 'bg-indigo-50 text-indigo-700 border-indigo-100/80',
                                            'pengembangan_diri' => 'bg-emerald-50 text-emerald-700 border-emerald-100/80',
                                            'sekolah' => 'bg-amber-50 text-amber-700 border-amber-100/80',
                                            'penghargaan' => 'bg-violet-50 text-violet-700 border-violet-100/80',
                                            default => 'bg-slate-50 text-slate-600 border-slate-150',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border capitalize {{ $catColors }}">
                                        {{ str_replace('_', ' ', $reward->category) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($reward->is_available)
                                        <span class="inline-flex items-center space-x-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            <span>Ya</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center space-x-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-100/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                            <span>Tidak</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center text-xs text-slate-500 font-bold">
                                    {{ $reward->users()->count() }} kali
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end items-center space-x-1">
                                        <!-- Tombol Edit Modal Trigger -->
                                        <button uk-toggle="target: #edit-reward-{{ $reward->id }}" class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-xl transition" title="Edit Hadiah">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                        
                                        <!-- Form Hapus -->
                                        <form method="POST" action="{{ route('admin.rewards.destroy', $reward->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus hadiah ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-xl transition" title="Hapus Hadiah">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal for this Reward -->
                            <div id="edit-reward-{{ $reward->id }}" uk-modal>
                                <div class="uk-modal-dialog rounded-2xl overflow-hidden shadow-xl border border-slate-100/80">
                                    <div class="uk-modal-header bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                                        <h2 class="text-base font-bold text-slate-800">Edit Hadiah</h2>
                                        <button class="uk-modal-close text-slate-400 hover:text-slate-600" type="button" uk-close></button>
                                    </div>
                                    <div class="uk-modal-body p-6">
                                        <form method="POST" action="{{ route('admin.rewards.update', $reward->id) }}" class="space-y-4" onsubmit="let btn = this.querySelector('button[type=submit]'); if(btn) { btn.disabled = true; btn.innerHTML = '<span class=\'animate-spin inline-block w-3.5 h-3.5 border-2 border-current border-t-transparent rounded-full mr-1.5 align-middle\'></span> Menyimpan...'; }">
                                            @csrf
                                            @method('PUT')
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Hadiah</label>
                                                <input type="text" name="name" value="{{ $reward->name }}" required
                                                    class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            </div>

                                            <div>
                                                <label class="block text-xs font-semibold text-slate-600 mb-1">Deskripsi</label>
                                                <textarea name="description" required rows="3"
                                                    class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ $reward->description }}</textarea>
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Biaya Poin</label>
                                                    <input type="number" name="points_cost" value="{{ $reward->points_cost }}" required min="1"
                                                        class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Kategori</label>
                                                    <select name="category" required
                                                        class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                                        <option value="akademik" {{ $reward->category === 'akademik' ? 'selected' : '' }}>Akademik</option>
                                                        <option value="pengembangan_diri" {{ $reward->category === 'pengembangan_diri' ? 'selected' : '' }}>Pengembangan Diri</option>
                                                        <option value="sekolah" {{ $reward->category === 'sekolah' ? 'selected' : '' }}>Sekolah</option>
                                                        <option value="penghargaan" {{ $reward->category === 'penghargaan' ? 'selected' : '' }}>Penghargaan</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="flex items-center space-x-2 pt-2">
                                                <input type="checkbox" name="is_available" id="is_available_{{ $reward->id }}" value="1" {{ $reward->is_available ? 'checked' : '' }}
                                                    class="w-4 h-4 text-indigo-600 border-slate-250 rounded focus:ring-indigo-500">
                                                <label for="is_available_{{ $reward->id }}" class="text-xs font-semibold text-slate-700 select-none">Tersedia untuk ditukarkan</label>
                                            </div>

                                            <div class="uk-modal-footer bg-slate-50/50 -mx-6 -mb-6 p-4 border-t border-slate-100 flex justify-end space-x-2">
                                                <button class="uk-modal-close px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-semibold transition" type="button">Batal</button>
                                                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold transition">
                                                    Simpan Hadiah
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-slate-400 text-xs font-medium">Belum ada hadiah yang ditambahkan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
