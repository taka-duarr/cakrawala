<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">Kelola Misi Karakter</h2>
            <button onclick="UIkit.modal('#modal-add-mission').show()" class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white text-xs font-black px-4 py-2.5 rounded-xl border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider flex items-center space-x-2">
                <span uk-icon="icon: plus; ratio: 0.8"></span>
                <span>Tambah Misi Baru</span>
            </button>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-100/30 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
            <div class="bg-[#EAFCEF] border-2 border-slate-950 text-emerald-800 rounded-xl p-4 flex items-center space-x-3 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                <span uk-icon="icon: check; ratio: 0.9"></span>
                <p class="font-black text-xs uppercase">{{ session('success') }}</p>
            </div>
            @endif

            <div class="bg-white rounded-3xl border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="p-6 border-b-4 border-slate-950 bg-slate-50">
                    <h3 class="text-base font-black text-slate-950 uppercase tracking-tight">Daftar Misi</h3>
                    <p class="text-xs text-slate-400 font-bold mt-1 uppercase tracking-wider">Buat misi baru untuk memicu perilaku disiplin dan kepedulian siswa.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b-2 border-slate-950">
                                <th class="px-6 py-3.5 text-xs font-black text-slate-950 uppercase tracking-wider">Judul Misi</th>
                                <th class="px-6 py-3.5 text-xs font-black text-slate-950 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-3.5 text-xs font-black text-slate-950 uppercase tracking-wider text-center">Hadiah Poin</th>
                                <th class="px-6 py-3.5 text-xs font-black text-slate-950 uppercase tracking-wider text-center">Tipe Misi</th>
                                <th class="px-6 py-3.5 text-xs font-black text-slate-950 uppercase tracking-wider text-center">Deadline</th>
                                <th class="px-6 py-3.5 text-xs font-black text-slate-950 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-slate-950">
                            @forelse($missions as $mission)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-black text-slate-955 text-xs uppercase tracking-tight">{{ $mission->title }}</td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-700 max-w-xs truncate" title="{{ $mission->description }}">{{ $mission->description }}</td>
                                <td class="px-6 py-4 text-center font-black text-xs text-emerald-700">+{{ $mission->points_reward }} Pts</td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $typeColors = match($mission->type) {
                                            'daily' => 'bg-[#EAFCEF] text-emerald-800 border-slate-950',
                                            'weekly' => 'bg-indigo-50 text-indigo-800 border-slate-950',
                                            'class' => 'bg-amber-50 text-amber-800 border-slate-950',
                                            'school' => 'bg-purple-50 text-purple-800 border-slate-950',
                                            'special' => 'bg-[#FFEAEA] text-rose-800 border-slate-950',
                                            default => 'bg-slate-50 text-slate-650 border-slate-950',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border-2 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] {{ $typeColors }}">
                                        {{ $mission->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">
                                    {{ $mission->deadline ? \Carbon\Carbon::parse($mission->deadline)->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.missions.award.show', $mission->id) }}" class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white border-2 border-slate-950 text-[10px] font-black px-2.5 py-1.5 rounded-xl transition-all shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[0px_0px_0px_0px_rgba(15,23,42,1)] flex items-center space-x-1 uppercase tracking-wider">
                                            <span uk-icon="icon: check; ratio: 0.65"></span>
                                            <span>Beri Reward</span>
                                        </a>
                                        <button onclick="editMission({{ json_encode($mission) }})" class="bg-white hover:bg-slate-950 text-slate-950 hover:text-white border-2 border-slate-950 text-[10px] font-black px-2.5 py-1.5 rounded-xl transition-all shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[0px_0px_0px_0px_rgba(15,23,42,1)] flex items-center space-x-1 uppercase tracking-wider">
                                            <span uk-icon="icon: file-edit; ratio: 0.65"></span>
                                            <span>Edit</span>
                                        </button>
                                        <form method="POST" action="{{ route('admin.missions.destroy', $mission->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus misi ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-500 hover:bg-slate-950 text-white border-2 border-slate-950 text-[10px] font-black px-2.5 py-1.5 rounded-xl transition-all shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[0px_0px_0px_0px_rgba(15,23,42,1)] flex items-center">
                                                <span uk-icon="icon: trash; ratio: 0.65"></span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-slate-400 text-xs font-bold uppercase">Belum ada misi yang terdaftar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($missions->hasPages())
                <div class="p-6 border-t-2 border-slate-950 bg-slate-50">
                    {{ $missions->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>

    <!-- MODAL ADD MISSION -->
    <div id="modal-add-mission" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body border-4 border-slate-950 rounded-3xl shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] p-6 bg-white">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-black text-slate-950 mb-4 flex items-center space-x-2 uppercase tracking-tight border-b-2 border-slate-950 pb-2">
                <span uk-icon="icon: plus; ratio: 1.1"></span>
                <span>Tambah Misi Baru</span>
            </h2>
            <form action="{{ route('admin.missions.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Judul Misi</label>
                    <input type="text" name="title" required class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold" placeholder="Contoh: Datang Tepat Waktu">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Deskripsi</label>
                    <textarea name="description" required rows="3" class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold" placeholder="Uraikan kriteria penyelesaian misi..."></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Hadiah Poin</label>
                        <input type="number" name="points_reward" required min="1" class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold" placeholder="15">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tipe Misi</label>
                        <select name="type" required class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="daily">Daily (Harian)</option>
                            <option value="weekly">Weekly (Mingguan)</option>
                            <option value="class">Class (Kelas)</option>
                            <option value="school">School (Sekolah)</option>
                            <option value="special">Special (Khusus)</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tenggat Waktu (Opsional)</label>
                        <input type="date" name="deadline" class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Metode Verifikasi</label>
                        <select name="proof_type" required class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="none">Otomatis / Verifikasi Guru</option>
                            <option value="text">Teks Deskripsi</option>
                            <option value="link">URL / Link Website</option>
                            <option value="file">File Dokumen / Foto</option>
                        </select>
                    </div>
                </div>
                <div class="pt-4 flex justify-end space-x-3 border-t-2 border-slate-950">
                    <button type="button" class="uk-modal-close bg-white hover:bg-slate-100 text-slate-955 font-black rounded-xl border-2 border-slate-950 text-xs transition uppercase tracking-wider px-4 py-2">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white font-black rounded-xl border-2 border-slate-950 text-xs shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider">Simpan Misi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT MISSION -->
    <div id="modal-edit-mission" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body border-4 border-slate-950 rounded-3xl shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] p-6 bg-white">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-black text-slate-950 mb-4 flex items-center space-x-2 uppercase tracking-tight border-b-2 border-slate-950 pb-2">
                <span uk-icon="icon: file-edit; ratio: 1.1"></span>
                <span>Edit Misi</span>
            </h2>
            <form id="edit-mission-form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Judul Misi</label>
                    <input type="text" id="edit-title" name="title" required class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Deskripsi</label>
                    <textarea id="edit-description" name="description" required rows="3" class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Hadiah Poin</label>
                        <input type="number" id="edit-points" name="points_reward" required min="1" class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tipe Misi</label>
                        <select id="edit-type" name="type" required class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="daily">Daily (Harian)</option>
                            <option value="weekly">Weekly (Mingguan)</option>
                            <option value="class">Class (Kelas)</option>
                            <option value="school">School (Sekolah)</option>
                            <option value="special">Special (Khusus)</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tenggat Waktu (Opsional)</label>
                        <input type="date" id="edit-deadline" name="deadline" class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Metode Verifikasi</label>
                        <select id="edit-proof-type" name="proof_type" required class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="none">Otomatis / Verifikasi Guru</option>
                            <option value="text">Teks Deskripsi</option>
                            <option value="link">URL / Link Website</option>
                            <option value="file">File Dokumen / Foto</option>
                        </select>
                    </div>
                </div>
                <div class="pt-4 flex justify-end space-x-3 border-t-2 border-slate-950">
                    <button type="button" class="uk-modal-close bg-white hover:bg-slate-100 text-slate-955 font-black rounded-xl border-2 border-slate-950 text-xs transition uppercase tracking-wider px-4 py-2">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white font-black rounded-xl border-2 border-slate-950 text-xs shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editMission(mission) {
            document.getElementById('edit-mission-form').action = '/admin/missions/' + mission.id + '/update';
            document.getElementById('edit-title').value = mission.title;
            document.getElementById('edit-description').value = mission.description;
            document.getElementById('edit-points').value = mission.points_reward;
            document.getElementById('edit-type').value = mission.type;
            document.getElementById('edit-deadline').value = mission.deadline ? mission.deadline.split(' ')[0] : '';
            document.getElementById('edit-proof-type').value = mission.proof_type;
            UIkit.modal('#modal-edit-mission').show();
        }
    </script>
</x-app-layout>
