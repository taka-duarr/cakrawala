<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                {{ __('Kelola Kelas') }}
            </h2>
            <button onclick="UIkit.modal('#modal-add-classroom').show()" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md shadow-indigo-100 transition flex items-center space-x-2">
                <span uk-icon="icon: plus; ratio: 0.8"></span>
                <span>Tambah Kelas Baru</span>
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-xs font-semibold flex items-center space-x-2">
                    <span uk-icon="icon: check; ratio: 0.9"></span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-xs font-semibold space-y-1">
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

            <!-- Classrooms Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($classrooms as $classroom)
                    @php
                        // Get assigned Wali Kelas user
                        $assignedWali = $classroom->users->first();
                    @endphp
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between hover:shadow-md transition">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <span class="bg-indigo-50 text-indigo-700 text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wider">
                                    {{ $classroom->name }}
                                </span>
                                <div class="flex space-x-1">
                                    <!-- Edit -->
                                    <button 
                                        data-id="{{ $classroom->id }}"
                                        data-name="{{ $classroom->name }}"
                                        data-points="{{ $classroom->points }}"
                                        data-wali-id="{{ $assignedWali->id ?? '' }}"
                                        class="edit-classroom-btn text-slate-400 hover:text-indigo-600 p-1.5 hover:bg-slate-50 rounded-lg transition"
                                    >
                                        <span uk-icon="icon: file-edit; ratio: 0.8"></span>
                                    </button>
                                    <!-- Delete -->
                                    <form action="{{ route('admin.classrooms.destroy', $classroom->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-rose-600 p-1.5 hover:bg-slate-50 rounded-lg transition">
                                            <span uk-icon="icon: trash; ratio: 0.8"></span>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-slate-400 font-medium">Total Poin Kelas:</span>
                                    <span class="font-extrabold text-indigo-600">{{ number_format($classroom->points) }} Pts</span>
                                </div>
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-slate-400 font-medium">Jumlah Siswa:</span>
                                    <span class="font-bold text-slate-700">{{ $classroom->students_count }} Siswa</span>
                                </div>
                                <div class="flex justify-between items-start text-xs border-t border-slate-50 pt-2.5">
                                    <span class="text-slate-400 font-medium mt-0.5">Wali Kelas:</span>
                                    <span class="font-semibold text-slate-800 text-right">
                                        {{ $assignedWali->name ?? 'Belum Ditugaskan' }}
                                        @if($assignedWali)
                                            <span class="block text-[9px] text-slate-400 font-semibold mt-0.5">{{ $assignedWali->email }}</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50/70 border border-slate-100 rounded-xl p-3 text-[10px] text-slate-500 font-medium flex items-center justify-between">
                            <span>Sumbangsih CoTE Point</span>
                            <span class="font-bold text-indigo-700 bg-white px-2 py-0.5 rounded-lg border border-slate-100">Aktif</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 bg-white rounded-2xl shadow-sm border border-slate-100 py-16 text-center text-slate-400 text-xs font-semibold">
                        Belum ada kelas terdaftar. Silakan tambahkan kelas baru.
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    <!-- MODAL ADD CLASSROOM -->
    <div id="modal-add-classroom" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-2xl p-6">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-bold text-slate-800 mb-4">Tambah Kelas Baru</h2>
            <form action="{{ route('admin.classrooms.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Nama Kelas</label>
                    <input type="text" name="name" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: X IPA 1">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Poin Awal Kelas</label>
                    <input type="number" name="points" value="0" min="0" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Tugaskan Wali Kelas</label>
                    <select name="wali_kelas_id" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Pilih Wali Kelas --</option>
                        @foreach($waliKelasCandidates as $candidate)
                            <option value="{{ $candidate->id }}">{{ $candidate->name }} ({{ $candidate->classroom->name ?? 'Belum ada kelas' }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <button class="uk-modal-close bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-4 py-2 rounded-xl transition" type="button">Batal</button>
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-5 py-2 rounded-xl transition shadow-md shadow-indigo-100" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT CLASSROOM -->
    <div id="modal-edit-classroom" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-2xl p-6">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-bold text-slate-800 mb-4">Edit Kelas</h2>
            <form id="edit-classroom-form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Nama Kelas</label>
                    <input type="text" name="name" id="edit-name" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Poin Kelas</label>
                    <input type="number" name="points" id="edit-points" min="0" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Tugaskan Wali Kelas</label>
                    <select name="wali_kelas_id" id="edit-wali-kelas" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Pilih Wali Kelas --</option>
                        @foreach($waliKelasCandidates as $candidate)
                            <option value="{{ $candidate->id }}">{{ $candidate->name }} ({{ $candidate->classroom->name ?? 'Belum ada kelas' }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <button class="uk-modal-close bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-4 py-2 rounded-xl transition" type="button">Batal</button>
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-5 py-2 rounded-xl transition shadow-md shadow-indigo-100" type="submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.edit-classroom-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const points = this.getAttribute('data-points');
                    const waliId = this.getAttribute('data-wali-id');

                    document.getElementById('edit-name').value = name;
                    document.getElementById('edit-points').value = points;
                    
                    const waliSelector = document.getElementById('edit-wali-kelas');
                    waliSelector.value = waliId || '';

                    document.getElementById('edit-classroom-form').action = `/admin/classrooms/${id}/update`;
                    
                    UIkit.modal('#modal-edit-classroom').show();
                });
            });
        });
    </script>
</x-app-layout>
