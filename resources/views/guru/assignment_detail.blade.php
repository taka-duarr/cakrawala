<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('guru.dashboard') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 flex items-center space-x-1 mb-1.5 transition">
                    <span uk-icon="icon: arrow-left; ratio: 0.8"></span>
                    <span>Kembali ke Dashboard</span>
                </a>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">Detail Kelas & Mata Pelajaran</h2>
            </div>
            
            <div class="flex items-center space-x-2">
                <span class="bg-indigo-50 border border-indigo-150 text-indigo-700 text-xs font-bold px-3 py-1.5 rounded-xl uppercase tracking-wider">
                    {{ $assignment->classroom->name }}
                </span>
                <span class="bg-slate-100 border border-slate-200 text-slate-700 text-xs font-bold px-3 py-1.5 rounded-xl">
                    {{ $assignment->academicYear->name ?? '-' }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Detail Mata Pelajaran Card Banner -->
            <div class="bg-gradient-to-r from-indigo-900 to-slate-800 rounded-2xl shadow-md p-8 text-white flex flex-col md:flex-row md:items-center justify-between gap-6" style="background: linear-gradient(135deg, #1e1b4b, #0f172a);">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider bg-white/20 px-2.5 py-0.5 rounded-full inline-block mb-2 text-indigo-200">
                        Mata Pelajaran (Subject)
                    </span>
                    <h1 class="text-3xl font-extrabold mb-1.5">{{ $assignment->subject->name }}</h1>
                    <p class="text-slate-200 text-sm max-w-xl font-medium leading-relaxed">
                        {{ $assignment->subject->description ?? 'Tidak ada deskripsi detail untuk mata pelajaran ini.' }}
                    </p>
                </div>
                <div class="bg-white/10 backdrop-blur border border-white/20 rounded-2xl p-5 min-w-[200px] text-right self-start md:self-auto">
                    <span class="text-[10px] text-indigo-200 block uppercase font-bold tracking-wider mb-1">Kode Mapel</span>
                    <strong class="text-xl text-white font-extrabold tracking-wider block">{{ $assignment->subject->code ?? 'N/A' }}</strong>
                    <span class="text-[10px] text-indigo-200 block uppercase font-bold tracking-wider mt-2.5 mb-1">Semester</span>
                    <strong class="text-xs text-white font-bold block">{{ $assignment->semester->name ?? '-' }} @if($assignment->semester) ({{ $assignment->semester->is_active ? 'Aktif' : 'Non-aktif' }}) @endif</strong>
                </div>
            </div>

            <!-- Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left Column: Daftar Siswa yang Diampu (lg:col-span-2) -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden soft-glow-indigo lg:col-span-2">
                    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Siswa di Kelas ini</h3>
                            <p class="text-xs text-slate-400 mt-1 font-medium">Daftar semua siswa di kelas {{ $assignment->classroom->name }} yang mengampu mata pelajaran {{ $assignment->subject->name }}.</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                            {{ $students->count() }} Terdaftar
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-100/80">
                                    <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center w-16">No</th>
                                    <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nama Siswa</th>
                                    <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tingkat/Level</th>
                                    <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Poin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100/70">
                                @forelse($students as $index => $student)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 text-center text-slate-400 font-bold text-xs">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-indigo-50 border border-indigo-100/30 rounded-full flex items-center justify-center font-extrabold text-indigo-700 text-xs shadow-inner">
                                                {{ substr($student->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-800 text-xs leading-none mb-1">{{ $student->name }}</div>
                                                <div class="text-[9px] text-slate-400 font-semibold">{{ $student->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100/80">
                                            {{ $student->current_level ?? 'Pemula' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-extrabold text-xs text-indigo-600">{{ number_format($student->points) }} Pts</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-12 text-slate-400 text-xs font-medium">Belum ada siswa yang terdaftar di kelas ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Right Column: Fitur & Kontrol Layanan Mapel (lg:col-span-1) -->
                <div class="space-y-6 lg:col-span-1">

                    <!-- Presensi Mapel -->
                    <div class="bg-white rounded-2xl border border-slate-100 p-6 soft-glow-indigo">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="p-2.5 bg-rose-50 text-rose-600 rounded-xl border border-rose-100/50">
                                <span uk-icon="icon: check; ratio: 1.1"></span>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm">Presensi / Absensi Mapel</h4>
                                <p class="text-[10px] text-slate-400 font-semibold">Buka absensi untuk mapel ini di kelas {{ $assignment->classroom->name }}.</p>
                            </div>
                        </div>
                        
                        <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 mb-4 text-center">
                            <span class="text-xs text-slate-500 font-medium block">Absensi Hari Ini</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-rose-50 text-rose-700 border border-rose-100/80 mt-1.5 uppercase tracking-wide">
                                Belum Dibuka
                            </span>
                        </div>

                        <button disabled class="w-full py-2.5 bg-slate-200 text-slate-400 rounded-xl text-xs font-bold transition cursor-not-allowed">
                            Buka Absensi Mapel
                        </button>
                        <span class="block text-[9px] text-slate-400 text-center font-medium mt-1.5">* Fitur absensi mapel akan diimplementasikan di fase berikutnya.</span>
                    </div>

                    <!-- Penugasan Misi Khusus Mapel -->
                    <div class="bg-white rounded-2xl border border-slate-100 p-6 soft-glow-indigo">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl border border-emerald-100/50">
                                <span uk-icon="icon: file-text; ratio: 1.1"></span>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm">Misi Khusus Mapel</h4>
                                <p class="text-[10px] text-slate-400 font-semibold">Tugaskan misi spesifik untuk mapel {{ $assignment->subject->name }}.</p>
                            </div>
                        </div>

                        <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 mb-4 text-center">
                            <p class="text-xs text-slate-500 font-medium">Riwayat Misi Mapel</p>
                            <span class="text-[10px] text-slate-400 font-bold block mt-1">Belum ada misi khusus mata pelajaran ini.</span>
                        </div>

                        <button onclick="UIkit.modal('#modal-add-mission').show()" class="w-full py-2.5 bg-indigo-650 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-sm hover:shadow-md flex items-center justify-center space-x-1">
                            <span uk-icon="icon: plus; ratio: 0.75"></span>
                            <span>Buat Misi Mapel</span>
                        </button>
                    </div>

                    <!-- Statistik Kelas untuk Mapel ini -->
                    <div class="bg-white rounded-2xl border border-slate-100 p-6 soft-glow-indigo">
                        <h4 class="font-bold text-slate-800 text-sm mb-4">Statistik Keaktifan Mapel</h4>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-xs font-bold text-slate-600 mb-1.5">
                                    <span>Tingkat Partisipasi</span>
                                    <span>85%</span>
                                </div>
                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                    <div class="bg-indigo-650 h-full rounded-full" style="width: 85%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-xs font-bold text-slate-600 mb-1.5">
                                    <span>Penyelesaian Misi</span>
                                    <span>60%</span>
                                </div>
                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                    <div class="bg-emerald-600 h-full rounded-full" style="width: 60%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <!-- MODAL BUAT MISI BARU (Sama seperti dashboard untuk kemudahan Guru) -->
    <div id="modal-add-mission" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-2xl p-6">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span uk-icon="icon: file-edit; ratio: 1.1"></span>
                <span>Buat Misi Baru ({{ $assignment->subject->name }})</span>
            </h2>
            <form action="{{ route('guru.missions.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Judul Misi</label>
                    <input type="text" name="title" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" value="[{{ $assignment->subject->code ?? $assignment->subject->name }}] ">
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
                            <option value="class">Kelas (Class) - Direkomendasikan</option>
                            <option value="daily">Harian (Daily)</option>
                            <option value="weekly">Mingguan (Weekly)</option>
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
</x-app-layout>
