<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">Dashboard Guru</h2>
            <button onclick="UIkit.modal('#modal-add-mission').show()" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md shadow-indigo-100 transition flex items-center space-x-2">
                <span uk-icon="icon: plus; ratio: 0.8"></span>
                <span>Buat Misi Baru</span>
            </button>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Success/Error Alerts -->
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-xs font-semibold flex items-center space-x-2 shadow-sm">
                    <span uk-icon="icon: check; ratio: 0.9"></span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-xs font-semibold space-y-1 shadow-sm">
                    <div class="flex items-center space-x-2">
                        <span uk-icon="icon: warning; ratio: 0.9"></span>
                        <span class="font-bold">Terjadi kesalahan input:</span>
                    </div>
                    <ul class="list-disc pl-5 space-y-0.5 mt-1 font-medium">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-emerald-600 to-teal-800 rounded-2xl shadow-md p-8 text-white flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-1">Selamat Datang, {{ auth()->user()->name }}!</h1>
                    <p class="text-emerald-100 text-sm">Kelola misi akademik/karakter, tinjau bukti penyelesaian, dan sesuaikan reputasi siswa.</p>
                </div>
                <div class="hidden md:block">
                    <svg class="w-24 h-24 text-emerald-300 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm flex items-center p-6 soft-glow-amber">
                    <div class="p-4 bg-amber-100 text-amber-650 rounded-xl mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Misi Menunggu Persetujuan</p>
                        <p class="text-3xl font-black text-slate-800 mt-1">{{ $pendingMissions->count() }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm flex items-center p-6 soft-glow-indigo">
                    <div class="p-4 bg-indigo-100 text-indigo-600 rounded-xl mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total Siswa Terdaftar</p>
                        <p class="text-3xl font-black text-slate-800 mt-1">{{ $siswas->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Persetujuan Misi -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden soft-glow-indigo">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Misi Menunggu Persetujuan</h3>
                        <p class="text-xs text-slate-400 mt-1 font-medium">Siswa yang telah menyerahkan bukti penyelesaian misi dan menanti verifikasi.</p>
                    </div>
                    @if($pendingMissions->count() > 0)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-100 animate-pulse">
                            {{ $pendingMissions->count() }} Menunggu
                        </span>
                    @endif
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100/80">
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Siswa</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Misi</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Hadiah Poin</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Tipe Bukti</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/70">
                            @forelse($pendingMissions as $mission)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-indigo-50 border border-indigo-100/30 rounded-full flex items-center justify-center font-extrabold text-indigo-700 text-xs shadow-inner">
                                            {{ substr($mission->student->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800 text-xs leading-none mb-1">{{ $mission->student->name }}</div>
                                            <div class="text-[9px] text-slate-400 font-semibold uppercase tracking-wider">Kelas: {{ $mission->student->classroom->name ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-semibold text-slate-800 mb-0.5">{{ $mission->title }}</div>
                                    <div class="text-[10px] text-slate-400 font-medium capitalize">{{ $mission->type ?? 'Harian' }}</div>
                                </td>
                                <td class="px-6 py-4 text-center font-extrabold text-xs text-emerald-600">+{{ $mission->points_reward }} Pts</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-0.5 rounded-lg text-[9px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                                        {{ $mission->proof_type ?? 'none' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button 
                                        data-user-id="{{ $mission->student->id }}"
                                        data-mission-id="{{ $mission->id }}"
                                        data-student-name="{{ $mission->student->name }}"
                                        data-mission-title="{{ $mission->title }}"
                                        data-points-reward="{{ $mission->points_reward }}"
                                        data-proof-type="{{ $mission->proof_type }}"
                                        data-proof-url="{{ $mission->pivot->proof_url }}"
                                        data-proof-content="{{ $mission->pivot->proof_content }}"
                                        class="validate-mission-btn px-3.5 py-1.5 bg-indigo-650 hover:bg-indigo-700 text-white text-[10px] font-bold rounded-xl transition shadow-sm hover:shadow-md inline-flex items-center space-x-1"
                                    >
                                        <span>Periksa Bukti</span>
                                        <span uk-icon="icon: sign-in; ratio: 0.75"></span>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-12 text-slate-400 text-xs font-medium">Tidak ada misi yang menunggu persetujuan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Peringkat & Manajemen Siswa -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden soft-glow-indigo">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">Daftar & Peringkat Siswa</h3>
                    <p class="text-xs text-slate-400 mt-1 font-medium">Daftar seluruh siswa untuk penyesuaian poin manual dan pemberian lencana.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100/80">
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center w-20">Rank</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nama Siswa</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Kelas</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Level</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Total Poin</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center w-48">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/70">
                            @forelse($siswas as $index => $siswa)
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
                                            {{ substr($siswa->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800 text-xs leading-none mb-1">{{ $siswa->name }}</div>
                                            <div class="text-[9px] text-slate-400 font-semibold">{{ $siswa->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center text-xs text-slate-500 font-semibold">{{ $siswa->classroom->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100/80">
                                        {{ $siswa->current_level ?? 'Pemula' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-extrabold text-xs text-emerald-600">{{ $siswa->points }} Pts</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button 
                                            data-user-id="{{ $siswa->id }}"
                                            data-student-name="{{ $siswa->name }}"
                                            class="adjust-points-btn bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 text-[10px] font-bold px-2.5 py-1.5 rounded-xl transition flex items-center space-x-1"
                                        >
                                            <span uk-icon="icon: plus; ratio: 0.65"></span>
                                            <span>Poin</span>
                                        </button>
                                        <button 
                                            data-user-id="{{ $siswa->id }}"
                                            data-student-name="{{ $siswa->name }}"
                                            class="manage-badges-btn bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-150 text-[10px] font-bold px-2.5 py-1.5 rounded-xl transition flex items-center space-x-1"
                                        >
                                            <span uk-icon="icon: star; ratio: 0.65"></span>
                                            <span>Lencana</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-slate-400 text-xs font-medium">Belum ada data siswa.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- MODAL BUAT MISI BARU -->
    <div id="modal-add-mission" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-2xl p-6">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span uk-icon="icon: file-edit; ratio: 1.1"></span>
                <span>Buat Misi Baru</span>
            </h2>
            <form action="{{ route('guru.missions.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Judul Misi</label>
                    <input type="text" name="title" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: Menyelesaikan Modul Fisika Bab 3">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Deskripsi Lengkap</label>
                    <textarea name="description" rows="3" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Jelaskan apa yang harus dikerjakan siswa..."></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Hadiah Poin</label>
                        <input type="number" name="points_reward" value="10" min="1" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Tipe Misi</label>
                        <select name="type" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="daily">Harian (Daily)</option>
                            <option value="weekly">Mingguan (Weekly)</option>
                            <option value="class">Kelas (Class)</option>
                            <option value="school">Sekolah (School)</option>
                            <option value="special">Ujian Khusus (Special)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Jenis Bukti</label>
                        <select name="proof_type" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="none">Tanpa Bukti (None)</option>
                            <option value="file">Unggah Berkas/Foto (File)</option>
                            <option value="link">Tautan Web/Drive (Link)</option>
                            <option value="text">Teks Deskripsi (Text)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Tenggat Waktu (Deadline)</label>
                        <input type="datetime-local" name="deadline" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <button class="uk-modal-close bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-4 py-2 rounded-xl transition" type="button">Batal</button>
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-5 py-2 rounded-xl transition shadow-md shadow-indigo-100" type="submit">Buat Misi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL PERIKSA & VALIDASI MISI -->
    <div id="modal-validate-mission" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-2xl p-6">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span uk-icon="icon: check; ratio: 1.1"></span>
                <span>Validasi Penyelesaian Misi</span>
            </h2>
            <div class="bg-slate-50/80 border border-slate-100 rounded-xl p-4 mb-4 space-y-2 text-xs">
                <div class="flex justify-between">
                    <span class="text-slate-400 font-medium">Siswa:</span>
                    <strong id="val-student-name" class="text-slate-800"></strong>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400 font-medium">Misi:</span>
                    <strong id="val-mission-title" class="text-slate-800"></strong>
                </div>
                <div class="flex justify-between border-t border-slate-100 pt-2">
                    <span class="text-slate-400 font-medium">Hadiah Poin:</span>
                    <strong id="val-points-reward" class="text-emerald-600 font-black"></strong>
                </div>
            </div>

            <!-- Bukti Section -->
            <div class="border border-slate-100 rounded-xl p-4 mb-4">
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Bukti yang Dikirimkan</h4>
                <div id="val-proof-container">
                    <!-- Dinamis terisi dari js -->
                </div>
            </div>

            <form action="{{ route('guru.missions.validate') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="user_id" id="val-user-id">
                <input type="hidden" name="mission_id" id="val-mission-id">

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Keputusan Validasi</label>
                    <select name="status" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="approved">Setujui (Approve) - Berikan Poin</option>
                        <option value="revision">Minta Revisi (Revision) - Tulis Catatan</option>
                        <option value="rejected">Tolak Misi (Reject)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Catatan Evaluasi / Alasan</label>
                    <textarea name="notes" rows="2" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Tulis catatan jika minta revisi atau menolak..."></textarea>
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <button class="uk-modal-close bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-4 py-2 rounded-xl transition" type="button">Batal</button>
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-5 py-2 rounded-xl transition shadow-md shadow-indigo-100" type="submit">Kirim Keputusan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL SESUAIKAN POIN MANUALL -->
    <div id="modal-adjust-points" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-2xl p-6">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span uk-icon="icon: plus-circle; ratio: 1.1"></span>
                <span>Sesuaikan Poin Karakter</span>
            </h2>
            <p class="text-xs text-slate-400 mb-4 font-semibold">Berikan apresiasi poin atau kurangi poin siswa secara manual.</p>

            <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 mb-4 text-xs">
                <span class="text-slate-400 font-medium">Siswa:</span>
                <strong id="adj-student-name" class="text-slate-800 ml-1"></strong>
            </div>

            <form action="{{ route('guru.points.adjust') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="user_id" id="adj-user-id">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Operasi</label>
                        <select name="operation" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="add">Tambah Poin (+)</option>
                            <option value="subtract">Kurang Poin (-)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Jumlah Poin</label>
                    <input type="number" name="amount" value="5" min="1" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Alasan Penyesuaian (Detail)</label>
                    <input type="text" name="description" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: Menolong merapikan ruang OSIS setelah rapat">
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <button class="uk-modal-close bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-4 py-2 rounded-xl transition" type="button">Batal</button>
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-5 py-2 rounded-xl transition shadow-md shadow-indigo-100" type="submit">Terapkan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL KELOLA LENCANA -->
    <div id="modal-manage-badges" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-2xl p-6">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span uk-icon="icon: star; ratio: 1.1"></span>
                <span>Kelola Lencana Siswa</span>
            </h2>
            <p class="text-xs text-slate-400 mb-4 font-semibold">Semua lencana pencapaian khusus siswa dapat diberikan atau dicabut secara manual.</p>

            <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 mb-4 text-xs">
                <span class="text-slate-400 font-medium">Siswa:</span>
                <strong id="badge-student-name" class="text-slate-800 ml-1"></strong>
            </div>

            <form action="{{ route('guru.badges.toggle') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="user_id" id="badge-user-id">

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Aksi</label>
                    <select name="action" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="award">Berikan Lencana (Award)</option>
                        <option value="revoke">Cabut Lencana (Revoke)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Pilih Lencana</label>
                    <select name="achievement_id" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach($achievements as $badge)
                            <option value="{{ $badge->id }}">{{ $badge->title }} ({{ $badge->category }}) - {{ $badge->description }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <button class="uk-modal-close bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-4 py-2 rounded-xl transition" type="button">Batal</button>
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-5 py-2 rounded-xl transition shadow-md shadow-indigo-100" type="submit">Proses Lencana</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Javascript bindings for modals -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Mission Validation Modal Populator
            document.querySelectorAll('.validate-mission-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    const missionId = this.getAttribute('data-mission-id');
                    const studentName = this.getAttribute('data-student-name');
                    const missionTitle = this.getAttribute('data-mission-title');
                    const pointsReward = this.getAttribute('data-points-reward');
                    const proofType = this.getAttribute('data-proof-type');
                    const proofUrl = this.getAttribute('data-proof-url');
                    const proofContent = this.getAttribute('data-proof-content');

                    document.getElementById('val-user-id').value = userId;
                    document.getElementById('val-mission-id').value = missionId;
                    document.getElementById('val-student-name').innerText = studentName;
                    document.getElementById('val-mission-title').innerText = missionTitle;
                    document.getElementById('val-points-reward').innerText = pointsReward + ' Pts';

                    const proofContainer = document.getElementById('val-proof-container');
                    let proofHtml = '';
                    if (proofType === 'none') {
                        proofHtml = '<span class="text-xs text-slate-500 font-medium">Tidak memerlukan bukti berkas/link.</span>';
                    } else {
                        if (proofUrl) {
                            proofHtml += `<div class="mb-2"><span class="text-xs font-bold text-slate-400 block">Tautan Bukti:</span> <a href="${proofUrl}" target="_blank" class="text-xs font-semibold text-indigo-650 hover:underline inline-flex items-center space-x-1 mt-1"><span>Buka Tautan</span> <span uk-icon="icon: link; ratio: 0.8"></span></a></div>`;
                        }
                        if (proofContent) {
                            proofHtml += `<div><span class="text-xs font-bold text-slate-400 block">Konten Bukti:</span> <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-700 whitespace-pre-line font-mono mt-1">${proofContent}</div></div>`;
                        }
                        if (!proofUrl && !proofContent) {
                            proofHtml = '<span class="text-xs text-rose-500 font-semibold">Bukti belum diunggah atau kosong.</span>';
                        }
                    }
                    proofContainer.innerHTML = proofHtml;

                    UIkit.modal('#modal-validate-mission').show();
                });
            });

            // Point Adjustment Modal Populator
            document.querySelectorAll('.adjust-points-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    const studentName = this.getAttribute('data-student-name');

                    document.getElementById('adj-user-id').value = userId;
                    document.getElementById('adj-student-name').innerText = studentName;

                    UIkit.modal('#modal-adjust-points').show();
                });
            });

            // Manage Achievements Modal Populator
            document.querySelectorAll('.manage-badges-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    const studentName = this.getAttribute('data-student-name');

                    document.getElementById('badge-user-id').value = userId;
                    document.getElementById('badge-student-name').innerText = studentName;

                    UIkit.modal('#modal-manage-badges').show();
                });
            });
        });
    </script>
</x-app-layout>

