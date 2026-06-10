<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">Manajemen Kelas</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 flex items-center space-x-3">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="font-semibold text-sm">{{ session('success') }}</span>
            </div>
            @endif

            {{-- Header --}}
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Total <strong class="text-slate-700">{{ $classrooms->count() }}</strong> kelas terdaftar</p>
                </div>
                <button onclick="document.getElementById('modal-add-kelas').classList.remove('hidden')"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Kelas
                </button>
            </div>

            {{-- Daftar Kelas --}}
            @forelse($classrooms as $classroom)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-black text-sm">
                            {{ $classroom->grade_level ?? '?' }}
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-base">{{ $classroom->name }}</h3>
                            <div class="flex items-center space-x-2 mt-0.5">
                                @if($classroom->jurusan)
                                    <span class="px-2 py-0.5 bg-violet-100 text-violet-700 text-[10px] font-bold rounded-full">{{ $classroom->jurusan->name }}</span>
                                @endif
                                @if($classroom->academicYear)
                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-[10px] font-bold rounded-full">TA {{ $classroom->academicYear->name }}</span>
                                @endif
                                @if($classroom->semester)
                                    <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 text-[10px] font-bold rounded-full">Sem. {{ $classroom->semester->name }}</span>
                                @endif
                                <span class="text-[10px] text-slate-400 font-semibold">{{ $classroom->students_count }} Siswa</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="text-right mr-3">
                            <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Wali Kelas</p>
                            <p class="text-xs font-bold text-slate-700">{{ $classroom->users->first()->name ?? 'Belum diassign' }}</p>
                        </div>
                        <button onclick="openEditKelas({{ $classroom->id }}, {{ json_encode($classroom->name) }}, {{ $classroom->grade_level ?? 'null' }}, {{ $classroom->jurusan_id ?? 'null' }}, {{ $classroom->academic_year_id ?? 'null' }}, {{ $classroom->semester_id ?? 'null' }}, {{ $classroom->users->first()->id ?? 'null' }})"
                            class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <form method="POST" action="{{ route('admin.classrooms.destroy', $classroom->id) }}" class="inline" onsubmit="return confirm('Hapus kelas {{ $classroom->name }}? Siswa akan kehilangan penempatan kelasnya.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Penempatan Siswa --}}
                <div class="p-5">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-xs font-bold text-slate-600 uppercase tracking-wider">Siswa di Kelas Ini</h4>
                        <div class="flex items-center space-x-2">
                            <button onclick="toggleEnrollForm('enroll-{{ $classroom->id }}')"
                                class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl transition">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Tambah / Pindahkan Siswa
                            </button>
                        </div>
                    </div>

                    {{-- Enrollment Form (hidden by default) --}}
                    <div id="enroll-{{ $classroom->id }}" class="hidden mb-4 p-4 bg-blue-50 border border-blue-100 rounded-xl">
                        <p class="text-[10px] text-blue-600 font-bold uppercase tracking-wider mb-2">Pilih siswa untuk ditambahkan / dipindahkan ke kelas ini:</p>
                        <form method="POST" action="{{ route('admin.classrooms.enroll', $classroom->id) }}" class="flex items-center space-x-2">
                            @csrf
                            <select name="user_id" required class="flex-1 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                                <option value="">-- Pilih Siswa --</option>
                                @foreach($semuaSiswa as $s)
                                    @if($s->classroom_id !== $classroom->id)
                                    <option value="{{ $s->id }}">
                                        {{ $s->name }}
                                        @if($s->classroom) (dari {{ $s->classroom->name }}) @else (tanpa kelas) @endif
                                    </option>
                                    @endif
                                @endforeach
                            </select>
                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl transition whitespace-nowrap">
                                ✓ Tempatkan
                            </button>
                            <button type="button" onclick="toggleEnrollForm('enroll-{{ $classroom->id }}')"
                                class="px-3 py-2 border border-slate-200 text-slate-500 text-xs font-semibold rounded-xl hover:bg-slate-50 transition">
                                Batal
                            </button>
                        </form>
                    </div>

                    @php
                        $studentsInClass = $classroom->users()->where('role_id', 5)->orderBy('name')->get();
                    @endphp

                    @if($studentsInClass->count())
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                        @foreach($studentsInClass as $student)
                        <div class="flex items-center justify-between bg-slate-50 border border-slate-100 rounded-xl px-3 py-2">
                            <div class="flex items-center space-x-2">
                                <div class="w-7 h-7 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-700 truncate max-w-[100px]">{{ $student->name }}</p>
                                    <p class="text-[9px] text-slate-400 font-semibold">{{ number_format($student->points_kebaikan) }} pts</p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('admin.classrooms.unenroll', [$classroom->id, $student->id]) }}" class="inline" onsubmit="return confirm('Keluarkan {{ $student->name }} dari kelas ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" title="Keluarkan dari kelas" class="text-slate-300 hover:text-rose-500 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-6 text-slate-300">
                        <svg class="w-10 h-10 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <p class="text-xs font-medium">Belum ada siswa di kelas ini</p>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-16 text-center text-slate-400">
                <svg class="w-16 h-16 mx-auto mb-4 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                <p class="text-sm font-medium">Belum ada kelas terdaftar.</p>
                <p class="text-xs mt-1">Klik "Tambah Kelas" untuk membuat kelas pertama.</p>
            </div>
            @endforelse

        </div>
    </div>

    {{-- Modal Tambah Kelas --}}
    <div id="modal-add-kelas" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center sticky top-0 bg-white">
                <h4 class="font-bold text-slate-800">Tambah Kelas Baru</h4>
                <button onclick="document.getElementById('modal-add-kelas').classList.add('hidden')" class="text-slate-400 hover:text-slate-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.classrooms.store') }}" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Kelas <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" placeholder="Contoh: X IPA 1" required
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tingkat</label>
                        <select name="grade_level" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">-- Pilih --</option>
                            <option value="10">10 (Kelas X)</option>
                            <option value="11">11 (Kelas XI)</option>
                            <option value="12">12 (Kelas XII)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jurusan</label>
                        <select name="jurusan_id" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">-- Pilih Jurusan --</option>
                            @foreach($jurusans as $j)
                                <option value="{{ $j->id }}">{{ $j->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tahun Ajaran</label>
                        <select name="academic_year_id" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">-- Pilih TA --</option>
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}" {{ $ay->is_active ? 'selected' : '' }}>{{ $ay->name }}{{ $ay->is_active ? ' ★' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Semester</label>
                        <select name="semester_id" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">-- Pilih Semester --</option>
                            @foreach($semesters as $sem)
                                <option value="{{ $sem->id }}" {{ $sem->is_active ? 'selected' : '' }}>{{ $sem->academicYear->name }} - {{ $sem->name }}{{ $sem->is_active ? ' ★' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Wali Kelas</label>
                        <select name="wali_kelas_id" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">-- Pilih Wali Kelas --</option>
                            @foreach($waliKelasCandidates as $wk)
                                <option value="{{ $wk->id }}">{{ $wk->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 pt-2">
                    <button type="button" onclick="document.getElementById('modal-add-kelas').classList.add('hidden')"
                        class="px-4 py-2 border border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-50 text-sm transition">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition shadow-sm">Buat Kelas</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Kelas --}}
    <div id="modal-edit-kelas" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center sticky top-0 bg-white">
                <h4 class="font-bold text-slate-800">Edit Kelas</h4>
                <button onclick="document.getElementById('modal-edit-kelas').classList.add('hidden')" class="text-slate-400 hover:text-slate-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="form-edit-kelas" method="POST" class="p-6 space-y-4">
                @csrf @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Kelas <span class="text-rose-500">*</span></label>
                        <input type="text" id="edit-kelas-name" name="name" required
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tingkat</label>
                        <select id="edit-kelas-grade" name="grade_level" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">-- Pilih --</option>
                            <option value="10">10 (Kelas X)</option>
                            <option value="11">11 (Kelas XI)</option>
                            <option value="12">12 (Kelas XII)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jurusan</label>
                        <select id="edit-kelas-jurusan" name="jurusan_id" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">-- Pilih Jurusan --</option>
                            @foreach($jurusans as $j)
                                <option value="{{ $j->id }}">{{ $j->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tahun Ajaran</label>
                        <select id="edit-kelas-ay" name="academic_year_id" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">-- Pilih TA --</option>
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}">{{ $ay->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Semester</label>
                        <select id="edit-kelas-sem" name="semester_id" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">-- Pilih Semester --</option>
                            @foreach($semesters as $sem)
                                <option value="{{ $sem->id }}">{{ $sem->academicYear->name }} - {{ $sem->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Wali Kelas</label>
                        <select id="edit-kelas-wali" name="wali_kelas_id" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">-- Tidak ada --</option>
                            @foreach($waliKelasCandidates as $wk)
                                <option value="{{ $wk->id }}">{{ $wk->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 pt-2">
                    <button type="button" onclick="document.getElementById('modal-edit-kelas').classList.add('hidden')"
                        class="px-4 py-2 border border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-50 text-sm transition">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition shadow-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleEnrollForm(id) {
            const el = document.getElementById(id);
            el.classList.toggle('hidden');
        }

        function openEditKelas(id, name, grade, jurusanId, ayId, semId, waliId) {
            document.getElementById('edit-kelas-name').value = name;
            setSelectVal('edit-kelas-grade', grade);
            setSelectVal('edit-kelas-jurusan', jurusanId);
            setSelectVal('edit-kelas-ay', ayId);
            setSelectVal('edit-kelas-sem', semId);
            setSelectVal('edit-kelas-wali', waliId);
            document.getElementById('form-edit-kelas').action = '/admin/classrooms/' + id + '/update';
            document.getElementById('modal-edit-kelas').classList.remove('hidden');
        }

        function setSelectVal(id, val) {
            const el = document.getElementById(id);
            if (el && val !== null && val !== undefined) {
                el.value = val;
            }
        }
    </script>
</x-app-layout>
