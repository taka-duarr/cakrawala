<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">Kelola Misi Karakter</h2>
            <button onclick="UIkit.modal('#modal-add-mission').show()" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md transition flex items-center space-x-2">
                <span uk-icon="icon: plus; ratio: 0.8"></span>
                <span>Tambah Misi Baru</span>
            </button>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 flex items-center space-x-3">
                <span uk-icon="icon: check; ratio: 0.9"></span>
                <p class="font-medium text-sm">{{ session('success') }}</p>
            </div>
            @endif

            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden soft-glow-indigo">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800 font-sans">Daftar Misi</h3>
                    <p class="text-xs text-slate-400 mt-1 font-medium">Buat misi baru untuk memicu perilaku disiplin dan kepedulian siswa.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100/80">
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Judul Misi</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Hadiah Poin</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Tipe Misi</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Deadline</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/70 font-sans">
                            @forelse($missions as $mission)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-800 text-xs">{{ $mission->title }}</td>
                                <td class="px-6 py-4 text-xs text-slate-500 font-medium max-w-xs truncate" title="{{ $mission->description }}">{{ $mission->description }}</td>
                                <td class="px-6 py-4 text-center font-extrabold text-xs text-emerald-600">+{{ $mission->points_reward }} Pts</td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $typeColors = match($mission->type) {
                                            'daily' => 'bg-emerald-50 text-emerald-700 border-emerald-100/80',
                                            'weekly' => 'bg-indigo-50 text-indigo-700 border-indigo-100/80',
                                            'class' => 'bg-amber-50 text-amber-700 border-amber-100/80',
                                            'school' => 'bg-violet-50 text-violet-700 border-violet-100/80',
                                            'special' => 'bg-rose-50 text-rose-700 border-rose-100/80',
                                            default => 'bg-slate-50 text-slate-600 border-slate-200',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border capitalize {{ $typeColors }}">
                                        {{ $mission->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-xs text-slate-400 font-medium">
                                    {{ $mission->deadline ? \Carbon\Carbon::parse($mission->deadline)->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.missions.award.show', $mission->id) }}" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-100 text-[10px] font-bold px-2.5 py-1.5 rounded-xl transition flex items-center space-x-1">
                                            <span uk-icon="icon: check; ratio: 0.65"></span>
                                            <span>Beri Reward</span>
                                        </a>
                                        <button onclick="editMission({{ json_encode($mission) }})" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-100 text-[10px] font-bold px-2.5 py-1.5 rounded-xl transition flex items-center space-x-1">
                                            <span uk-icon="icon: file-edit; ratio: 0.65"></span>
                                            <span>Edit</span>
                                        </button>
                                        <form method="POST" action="{{ route('admin.missions.destroy', $mission->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus misi ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-100 text-[10px] font-bold px-2.5 py-1.5 rounded-xl transition flex items-center">
                                                <span uk-icon="icon: trash; ratio: 0.65"></span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-slate-400 text-xs font-medium">Belum ada misi yang terdaftar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($missions->hasPages())
                <div class="p-6 border-t border-slate-100">
                    {{ $missions->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>

    <!-- MODAL ADD MISSION -->
    <div id="modal-add-mission" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-2xl p-6">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span uk-icon="icon: plus; ratio: 1.1"></span>
                <span>Tambah Misi Baru</span>
            </h2>
            <form action="{{ route('admin.missions.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Judul Misi</label>
                    <input type="text" name="title" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: Datang Tepat Waktu">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Deskripsi</label>
                    <textarea name="description" required rows="3" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Uraikan kriteria penyelesaian misi..."></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Hadiah Poin</label>
                        <input type="number" name="points_reward" required min="1" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="15">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Tipe Misi</label>
                        <select name="type" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
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
                        <label class="block text-xs font-bold text-slate-500 mb-1">Tenggat Waktu (Opsional)</label>
                        <input type="date" name="deadline" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Metode Verifikasi</label>
                        <select name="proof_type" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="none">Otomatis / Verifikasi Guru</option>
                            <option value="text">Teks Deskripsi</option>
                            <option value="link">URL / Link Website</option>
                            <option value="file">File Dokumen / Foto</option>
                        </select>
                    </div>
                </div>
                <div class="pt-4 flex justify-end space-x-2">
                    <button type="button" class="uk-modal-close px-4 py-2 text-xs font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 rounded-xl transition">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition shadow-md">Simpan Misi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT MISSION -->
    <div id="modal-edit-mission" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-2xl p-6">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span uk-icon="icon: file-edit; ratio: 1.1"></span>
                <span>Edit Misi</span>
            </h2>
            <form id="edit-mission-form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Judul Misi</label>
                    <input type="text" id="edit-title" name="title" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Deskripsi</label>
                    <textarea id="edit-description" name="description" required rows="3" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Hadiah Poin</label>
                        <input type="number" id="edit-points" name="points_reward" required min="1" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Tipe Misi</label>
                        <select id="edit-type" name="type" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
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
                        <label class="block text-xs font-bold text-slate-500 mb-1">Tenggat Waktu (Opsional)</label>
                        <input type="date" id="edit-deadline" name="deadline" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Metode Verifikasi</label>
                        <select id="edit-proof-type" name="proof_type" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="none">Otomatis / Verifikasi Guru</option>
                            <option value="text">Teks Deskripsi</option>
                            <option value="link">URL / Link Website</option>
                            <option value="file">File Dokumen / Foto</option>
                        </select>
                    </div>
                </div>
                <div class="pt-4 flex justify-end space-x-2">
                    <button type="button" class="uk-modal-close px-4 py-2 text-xs font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 rounded-xl transition">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition shadow-md">Simpan Perubahan</button>
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
