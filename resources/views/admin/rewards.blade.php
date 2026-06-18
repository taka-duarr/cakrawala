<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-slate-950 leading-tight tracking-tight">
            {{ __('Kelola Toko Hadiah') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
            <div class="bg-[#EAFCEF] border-2 border-slate-950 text-slate-950 p-4 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] flex items-center space-x-3 mb-6">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Tambah Reward Baru -->
                <div class="bg-white border-2 border-slate-950 p-6 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)]">
                    <h3 class="text-lg font-black text-slate-950 mb-4 tracking-tight">Tambah Hadiah Baru</h3>
                    
                    <form method="POST" action="{{ route('admin.rewards.store') }}" class="space-y-4" onsubmit="let btn = this.querySelector('button[type=submit]'); if(btn) { btn.disabled = true; btn.innerHTML = '<span class=\'animate-spin inline-block w-3.5 h-3.5 border-2 border-current border-t-transparent rounded-full mr-1.5 align-middle\'></span> Menyimpan...'; }">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Hadiah</label>
                            <input type="text" name="name" required placeholder="Contoh: Voucher Buku"
                                class="w-full border-2 border-slate-950 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-950 focus:border-slate-950">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi</label>
                            <textarea name="description" required placeholder="Jelaskan detail hadiah..." rows="3"
                                class="w-full border-2 border-slate-950 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-950 focus:border-slate-950"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Biaya Poin</label>
                                <input type="number" name="points_cost" required min="1" placeholder="100"
                                    class="w-full border-2 border-slate-950 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-950 focus:border-slate-950">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kategori</label>
                                <select name="category" required
                                    class="w-full border-2 border-slate-950 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white">
                                    <option value="akademik">Akademik</option>
                                    <option value="pengembangan_diri">Pengembangan Diri</option>
                                    <option value="sekolah">Sekolah</option>
                                    <option value="penghargaan">Penghargaan</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-2.5 bg-[#E4FF1A] hover:bg-[#d8f014] text-slate-950 border-2 border-slate-950 text-sm font-black transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] hover:shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                            Simpan Hadiah
                        </button>
                    </form>
                </div>

                <!-- Pengajuan Klaim Pending -->
                <div class="bg-white border-2 border-slate-950 p-6 lg:col-span-2 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] overflow-hidden">
                    <h3 class="text-lg font-black text-slate-950 mb-4 tracking-tight">Pengajuan Penukaran Poin (Siswa)</h3>
                    
                    <div class="overflow-x-auto border-2 border-slate-950">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-[#E4FF1A]/10 border-b-2 border-slate-950">
                                    <th class="px-6 py-3.5 text-xs font-extrabold text-slate-950 uppercase tracking-wider">Siswa</th>
                                    <th class="px-6 py-3.5 text-xs font-extrabold text-slate-950 uppercase tracking-wider">Hadiah</th>
                                    <th class="px-6 py-3.5 text-xs font-extrabold text-slate-950 uppercase tracking-wider text-center">Biaya</th>
                                    <th class="px-6 py-3.5 text-xs font-extrabold text-slate-950 uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-2 divide-slate-950">
                                @forelse($pendingClaims as $claim)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-[#E4FF1A] border-2 border-slate-950 rounded-none flex items-center justify-center font-black text-slate-950 text-xs shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                                {{ substr($claim->student_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-black text-slate-950 text-xs leading-none mb-1">{{ $claim->student_name }}</div>
                                                <div class="text-[9px] text-slate-600 font-bold uppercase tracking-wider">Kelas: {{ $claim->class_name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-xs font-bold text-slate-950 mb-0.5">{{ $claim->reward_name }}</div>
                                        <div class="text-[10px] text-slate-500 font-medium">{{ \Carbon\Carbon::parse($claim->created_at)->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center font-black text-xs text-slate-950">
                                        <span class="px-2 py-0.5 bg-[#E4FF1A] border-2 border-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] text-[10px]">
                                            {{ $claim->points_cost }} Pts
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <form method="POST" action="{{ route('admin.rewards.approve', $claim->id) }}" onsubmit="let btn = this.querySelector('button[type=submit]'); if(btn) { btn.disabled = true; btn.innerHTML = '<span class=\'animate-spin inline-block w-3.5 h-3.5 border-2 border-current border-t-transparent rounded-full align-middle\'></span>'; }">
                                            @csrf
                                            <button type="submit" class="inline-flex px-3.5 py-1.5 bg-[#EAFCEF] hover:bg-[#d5fad5] text-slate-950 text-[10px] font-black border-2 border-slate-950 transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] hover:shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] min-w-[110px] items-center justify-center">
                                                Setujui & Serahkan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-12 text-slate-500 text-xs font-bold">Tidak ada klaim hadiah yang pending.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Daftar Semua Hadiah -->
            <div class="bg-white border-2 border-slate-950 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] overflow-hidden">
                <div class="p-6 border-b-2 border-slate-950">
                    <h3 class="text-lg font-black text-slate-950 tracking-tight">Daftar Semua Hadiah</h3>
                    <p class="text-xs text-slate-500 mt-1 font-bold">Edit atau hapus hadiah yang tersedia di toko.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#E4FF1A]/10 border-b-2 border-slate-950">
                                <th class="px-6 py-3.5 text-xs font-extrabold text-slate-950 uppercase tracking-wider">Nama Hadiah</th>
                                <th class="px-6 py-3.5 text-xs font-extrabold text-slate-950 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-3.5 text-xs font-extrabold text-slate-950 uppercase tracking-wider text-center">Biaya Poin</th>
                                <th class="px-6 py-3.5 text-xs font-extrabold text-slate-950 uppercase tracking-wider text-center">Kategori</th>
                                <th class="px-6 py-3.5 text-xs font-extrabold text-slate-950 uppercase tracking-wider text-center">Tersedia</th>
                                <th class="px-6 py-3.5 text-xs font-extrabold text-slate-950 uppercase tracking-wider text-center">Total Klaim</th>
                                <th class="px-6 py-3.5 text-xs font-extrabold text-slate-950 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-slate-950">
                            @forelse($rewards as $reward)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-black text-slate-950 text-xs">{{ $reward->name }}</td>
                                <td class="px-6 py-4 text-xs text-slate-500 font-bold max-w-xs truncate" title="{{ $reward->description }}">{{ $reward->description }}</td>
                                <td class="px-6 py-4 text-center font-extrabold text-xs text-slate-950">
                                    <span class="px-2 py-0.5 bg-[#E4FF1A] border-2 border-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] text-[10px]">
                                        {{ $reward->points_cost }} Pts
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $catColors = match($reward->category) {
                                            'akademik' => 'bg-[#FFEAEA] text-slate-950 border-slate-950',
                                            'pengembangan_diri' => 'bg-[#EAFCEF] text-slate-950 border-slate-950',
                                            'sekolah' => 'bg-[#FFF6EA] text-slate-950 border-slate-950',
                                            'penghargaan' => 'bg-[#EAEFFF] text-slate-950 border-slate-950',
                                            default => 'bg-white text-slate-950 border-slate-950',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-none text-[10px] font-black border-2 capitalize {{ $catColors }} shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                        {{ str_replace('_', ' ', $reward->category) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($reward->is_available)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-none text-[10px] font-black bg-[#EAFCEF] text-slate-950 border-2 border-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                            <span>Ya</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-none text-[10px] font-black bg-[#FFEAEA] text-slate-950 border-2 border-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                            <span>Tidak</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center text-xs text-slate-950 font-black">
                                    {{ $reward->users()->count() }} kali
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end items-center space-x-2">
                                        <!-- Tombol Edit Modal Trigger -->
                                        <button uk-toggle="target: #edit-reward-{{ $reward->id }}" class="p-1.5 text-slate-950 bg-white hover:bg-[#E4FF1A] border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] hover:shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] transition-all hover:-translate-y-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]" title="Edit Hadiah">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                        
                                        <!-- Form Hapus -->
                                        <form method="POST" action="{{ route('admin.rewards.destroy', $reward->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus hadiah ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-950 bg-white hover:bg-rose-500 hover:text-white border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] hover:shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] transition-all hover:-translate-y-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]" title="Hapus Hadiah">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal for this Reward -->
                            <div id="edit-reward-{{ $reward->id }}" uk-modal>
                                <div class="uk-modal-dialog border-4 border-slate-950 p-0 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] bg-white max-w-lg rounded-none overflow-hidden">
                                    <div class="uk-modal-header bg-[#E4FF1A] px-6 py-4 border-b-2 border-slate-950 flex items-center justify-between">
                                        <h2 class="text-base font-black text-slate-950">Edit Hadiah</h2>
                                        <button class="uk-modal-close text-slate-950 hover:text-rose-600 font-bold" type="button" uk-close></button>
                                    </div>
                                    <div class="uk-modal-body p-6">
                                        <form method="POST" action="{{ route('admin.rewards.update', $reward->id) }}" class="space-y-4" onsubmit="let btn = this.querySelector('button[type=submit]'); if(btn) { btn.disabled = true; btn.innerHTML = '<span class=\'animate-spin inline-block w-3.5 h-3.5 border-2 border-current border-t-transparent rounded-full mr-1.5 align-middle\'></span> Menyimpan...'; }">
                                            @csrf
                                            @method('PUT')
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Hadiah</label>
                                                <input type="text" name="name" value="{{ $reward->name }}" required
                                                    class="w-full border-2 border-slate-950 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-950 focus:border-slate-950">
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi</label>
                                                <textarea name="description" required rows="3"
                                                    class="w-full border-2 border-slate-950 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-950 focus:border-slate-950">{{ $reward->description }}</textarea>
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Biaya Poin</label>
                                                    <input type="number" name="points_cost" value="{{ $reward->points_cost }}" required min="1"
                                                        class="w-full border-2 border-slate-950 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-950 focus:border-slate-950">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kategori</label>
                                                    <select name="category" required
                                                        class="w-full border-2 border-slate-950 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white">
                                                        <option value="akademik" {{ $reward->category === 'akademik' ? 'selected' : '' }}>Akademik</option>
                                                        <option value="pengembangan_diri" {{ $reward->category === 'pengembangan_diri' ? 'selected' : '' }}>Pengembangan Diri</option>
                                                        <option value="sekolah" {{ $reward->category === 'sekolah' ? 'selected' : '' }}>Sekolah</option>
                                                        <option value="penghargaan" {{ $reward->category === 'penghargaan' ? 'selected' : '' }}>Penghargaan</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="flex items-center space-x-2 pt-2">
                                                <input type="checkbox" name="is_available" id="is_available_{{ $reward->id }}" value="1" {{ $reward->is_available ? 'checked' : '' }}
                                                    class="w-4 h-4 text-slate-950 border-2 border-slate-950 rounded focus:ring-0 focus:ring-offset-0">
                                                <label for="is_available_{{ $reward->id }}" class="text-xs font-bold text-slate-700 select-none">Tersedia untuk ditukarkan</label>
                                            </div>

                                            <div class="uk-modal-footer bg-slate-50 -mx-6 -mb-6 p-4 border-t-2 border-slate-950 flex justify-end space-x-2">
                                                <button class="uk-modal-close px-4 py-2 border-2 border-slate-950 hover:bg-slate-100 text-slate-950 text-xs font-bold transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]" type="button">Batal</button>
                                                <button type="submit" class="px-4 py-2 bg-[#E4FF1A] hover:bg-[#d8f014] text-slate-950 border-2 border-slate-950 text-xs font-black transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] hover:shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                                    Simpan Hadiah
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-slate-500 text-xs font-bold">Belum ada hadiah yang ditambahkan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
