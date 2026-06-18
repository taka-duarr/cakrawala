<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">Manajemen Kelas</h2>
    </x-slot>

    <div class="py-12 bg-slate-100/30 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
            <div class="bg-[#EAFCEF] border-2 border-slate-950 text-emerald-800 rounded-xl p-4 flex items-center space-x-3 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                <span uk-icon="icon: check; ratio: 0.9"></span>
                <span class="font-black text-xs uppercase">{{ session('success') }}</span>
            </div>
            @endif

            {{-- Header --}}
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total <strong class="text-slate-950 font-black">{{ $classrooms->count() }}</strong> kelas terdaftar</p>
                </div>
                <button onclick="document.getElementById('modal-add-kelas').classList.remove('hidden')"
                    class="inline-flex items-center px-4 py-2.5 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white text-xs font-black rounded-xl border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider">
                    <span uk-icon="icon: plus; ratio: 0.8" class="mr-1.5"></span>
                    Tambah Kelas
                </button>
            </div>

            {{-- Daftar Kelas --}}
            @forelse($classrooms as $classroom)
            <div class="bg-white rounded-3xl border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="p-5 border-b-4 border-slate-950 flex items-center justify-between bg-slate-50 flex-wrap gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-xl bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 flex items-center justify-center font-black text-sm shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                            {{ $classroom->grade_level ?? '?' }}
                        </div>
                        <div>
                            <h3 class="font-black text-slate-950 text-base uppercase tracking-tight">{{ $classroom->name }}</h3>
                            <div class="flex items-center space-x-2 mt-1 flex-wrap gap-y-1">
                                @if($classroom->jurusan)
                                    <span class="px-2 py-0.5 bg-purple-50 text-purple-800 text-[9px] font-black uppercase tracking-wider border-2 border-slate-950 rounded-full">{{ $classroom->jurusan->name }}</span>
                                @endif
                                @if($classroom->academicYear)
                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-800 text-[9px] font-black uppercase tracking-wider border-2 border-slate-950 rounded-full">TA {{ $classroom->academicYear->name }}</span>
                                @endif
                                @if($classroom->semester)
                                    <span class="px-2 py-0.5 bg-[#FFEAEA] text-rose-800 text-[9px] font-black uppercase tracking-wider border-2 border-slate-950 rounded-full">Sem. {{ $classroom->semester->name }}</span>
                                @endif
                                @if($classroom->angkatan)
                                    <span class="px-2 py-0.5 bg-[#EAFCEF] text-emerald-800 text-[9px] font-black uppercase tracking-wider border-2 border-slate-950 rounded-full">Angkatan {{ $classroom->angkatan }}</span>
                                @endif
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider ml-1">{{ $classroom->students_count }} Siswa</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="text-right mr-3">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Wali Kelas</p>
                            <p class="text-xs font-black text-slate-950 uppercase tracking-wider">{{ $classroom->users->first()->name ?? 'Belum ditugaskan' }}</p>
                        </div>
                        <button onclick="openEditKelas({{ $classroom->id }}, {{ json_encode($classroom->name) }}, {{ $classroom->grade_level ?? 'null' }}, {{ $classroom->jurusan_id ?? 'null' }}, {{ $classroom->academic_year_id ?? 'null' }}, {{ $classroom->semester_id ?? 'null' }}, {{ $classroom->users->first()->id ?? 'null' }}, {{ json_encode($classroom->angkatan) }})"
                            class="p-2 text-indigo-650 bg-white border-2 border-slate-950 rounded-xl hover:bg-slate-50 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[0px_0px_0px_0px_rgba(15,23,42,1)] transition-all">
                            <span uk-icon="icon: file-edit; ratio: 0.8"></span>
                        </button>
                        <form method="POST" action="{{ route('admin.classrooms.destroy', $classroom->id) }}" class="inline" onsubmit="return confirm('Hapus kelas {{ $classroom->name }}? Siswa akan kehilangan penempatan kelasnya.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-rose-600 bg-white border-2 border-slate-950 rounded-xl hover:bg-[#FFEAEA] shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[0px_0px_0px_0px_rgba(15,23,42,1)] transition-all">
                                <span uk-icon="icon: trash; ratio: 0.8"></span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Penempatan Siswa --}}
                <div class="p-5">
                    <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                        <h4 class="text-xs font-black text-slate-950 uppercase tracking-wider">Siswa di Kelas Ini</h4>
                        <div class="flex items-center space-x-2">
                            <button onclick="toggleEnrollForm('enroll-{{ $classroom->id }}')"
                                class="inline-flex items-center px-3 py-1.5 bg-slate-950 hover:bg-[#E4FF1A] text-white hover:text-slate-950 text-xs font-black rounded-xl border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider">
                                <span uk-icon="icon: plus; ratio: 0.8" class="mr-1"></span>
                                Tambah / Pindahkan Siswa
                            </button>
                        </div>
                    </div>

                    {{-- Enrollment Form (hidden by default) --}}
                    <div id="enroll-{{ $classroom->id }}" class="hidden mb-4 p-4 bg-[#EAFCEF] border-2 border-slate-950 rounded-2xl shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                        <p class="text-[10px] text-emerald-800 font-black uppercase tracking-wider mb-2">Pilih siswa untuk ditambahkan / dipindahkan ke kelas ini:</p>
                        <form method="POST" action="{{ route('admin.classrooms.enroll', $classroom->id) }}" class="flex items-center space-x-2 flex-wrap sm:flex-nowrap gap-2">
                            @csrf
                            <select name="user_id" required class="flex-1 border-2 border-slate-950 rounded-xl px-3 py-2 text-xs text-slate-950 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
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
                            <button type="submit" class="px-4 py-2 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white text-xs font-black rounded-xl border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider whitespace-nowrap">
                                Tempatkan
                            </button>
                            <button type="button" onclick="toggleEnrollForm('enroll-{{ $classroom->id }}')"
                                class="px-3 py-2 border-2 border-slate-950 text-slate-950 font-black rounded-xl hover:bg-slate-50 text-xs uppercase tracking-wider bg-white">
                                Batal
                            </button>
                        </form>
                    </div>

                    @php
                        $studentsInClass = $classroom->users()->where('role_id', 5)->orderBy('name')->get();
                    @endphp

                    @if($studentsInClass->count())
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach($studentsInClass as $student)
                        <div class="flex items-center justify-between bg-slate-50 border-2 border-slate-950 rounded-xl px-3 py-2 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-0.5 transition-all duration-150">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 rounded-lg bg-[#EAFCEF] border-2 border-slate-950 text-slate-950 flex items-center justify-center font-black text-xs flex-shrink-0">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-950 truncate max-w-[100px]">{{ $student->name }}</p>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">{{ number_format($student->points) }} pts</p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('admin.classrooms.unenroll', [$classroom->id, $student->id]) }}" class="inline" onsubmit="return confirm('Keluarkan {{ $student->name }} dari kelas ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" title="Keluarkan dari kelas" class="text-rose-500 hover:text-rose-700 transition-colors p-1 hover:bg-[#FFEAEA] rounded-lg">
                                    <span uk-icon="icon: close; ratio: 0.8"></span>
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8 text-slate-400 border-2 border-dashed border-slate-200 rounded-xl">
                        <span uk-icon="icon: info; ratio: 1.2" class="mb-2"></span>
                        <p class="text-xs font-bold uppercase tracking-wider">Belum ada siswa di kelas ini</p>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="bg-white rounded-3xl border-4 border-slate-950 p-16 text-center text-slate-400 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <span uk-icon="icon: info; ratio: 2" class="mb-4 text-slate-300"></span>
                <p class="text-sm font-bold uppercase tracking-wider text-slate-950">Belum ada kelas terdaftar.</p>
                <p class="text-xs mt-1 font-semibold">Klik "Tambah Kelas" untuk membuat kelas pertama.</p>
            </div>
            @endforelse

        </div>
    </div>

    {{-- Modal Tambah Kelas --}}
    <div id="modal-add-kelas" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white border-4 border-slate-950 rounded-3xl shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b-2 border-slate-950 flex justify-between items-center sticky top-0 bg-white z-10">
                <h4 class="font-black text-slate-950 uppercase tracking-tight">Tambah Kelas Baru</h4>
                <button onclick="document.getElementById('modal-add-kelas').classList.add('hidden')" class="text-slate-400 hover:text-slate-950">
                    <span uk-icon="icon: close; ratio: 1"></span>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.classrooms.store') }}" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-1">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nama Kelas <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" placeholder="Contoh: X IPA 1" required
                            class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white">
                    </div>
                    <div class="col-span-1">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Angkatan</label>
                        <input type="text" name="angkatan" placeholder="Contoh: 2024"
                            class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Tingkat</label>
                        <select name="grade_level" class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="">-- Pilih --</option>
                            <option value="10">10 (Kelas X)</option>
                            <option value="11">11 (Kelas XI)</option>
                            <option value="12">12 (Kelas XII)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Jurusan</label>
                        <select name="jurusan_id" class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="">-- Pilih Jurusan --</option>
                            @foreach($jurusans as $j)
                                <option value="{{ $j->id }}">{{ $j->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Tahun Ajaran</label>
                        <select name="academic_year_id" class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="">-- Pilih TA --</option>
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}" {{ $ay->is_active ? 'selected' : '' }}>{{ $ay->name }}{{ $ay->is_active ? ' ★' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Semester</label>
                        <select name="semester_id" class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="">-- Pilih Semester --</option>
                            @foreach($semesters as $sem)
                                <option value="{{ $sem->id }}" {{ $sem->is_active ? 'selected' : '' }}>{{ $sem->academicYear->name }} - {{ $sem->name }}{{ $sem->is_active ? ' ★' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Wali Kelas</label>
                        <select name="wali_kelas_id" class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="">-- Pilih Wali Kelas --</option>
                            @foreach($waliKelasCandidates as $wk)
                                <option value="{{ $wk->id }}">{{ $wk->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 pt-4 border-t-2 border-slate-950">
                    <button type="button" onclick="document.getElementById('modal-add-kelas').classList.add('hidden')"
                        class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-950 font-black rounded-xl border-2 border-slate-950 text-xs transition uppercase tracking-wider">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white font-black rounded-xl border-2 border-slate-950 text-xs shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider">Buat Kelas</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Kelas --}}
    <div id="modal-edit-kelas" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white border-4 border-slate-950 rounded-3xl shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b-2 border-slate-950 flex justify-between items-center sticky top-0 bg-white z-10">
                <h4 class="font-black text-slate-950 uppercase tracking-tight">Edit Kelas</h4>
                <button onclick="document.getElementById('modal-edit-kelas').classList.add('hidden')" class="text-slate-400 hover:text-slate-950">
                    <span uk-icon="icon: close; ratio: 1"></span>
                </button>
            </div>
            <form id="form-edit-kelas" method="POST" class="p-6 space-y-4">
                @csrf @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-1">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nama Kelas <span class="text-rose-500">*</span></label>
                        <input type="text" id="edit-kelas-name" name="name" required
                            class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white">
                    </div>
                    <div class="col-span-1">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Angkatan</label>
                        <input type="text" id="edit-kelas-angkatan" name="angkatan" placeholder="Contoh: 2024"
                            class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Tingkat</label>
                        <select id="edit-kelas-grade" name="grade_level" class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="">-- Pilih --</option>
                            <option value="10">10 (Kelas X)</option>
                            <option value="11">11 (Kelas XI)</option>
                            <option value="12">12 (Kelas XII)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Jurusan</label>
                        <select id="edit-kelas-jurusan" name="jurusan_id" class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="">-- Pilih Jurusan --</option>
                            @foreach($jurusans as $j)
                                <option value="{{ $j->id }}">{{ $j->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Tahun Ajaran</label>
                        <select id="edit-kelas-ay" name="academic_year_id" class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="">-- Pilih TA --</option>
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}">{{ $ay->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Semester</label>
                        <select id="edit-kelas-sem" name="semester_id" class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="">-- Pilih Semester --</option>
                            @foreach($semesters as $sem)
                                <option value="{{ $sem->id }}">{{ $sem->academicYear->name }} - {{ $sem->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Wali Kelas</label>
                        <select id="edit-kelas-wali" name="wali_kelas_id" class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="">-- Tidak ada --</option>
                            @foreach($waliKelasCandidates as $wk)
                                <option value="{{ $wk->id }}">{{ $wk->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 pt-4 border-t-2 border-slate-950">
                    <button type="button" onclick="document.getElementById('modal-edit-kelas').classList.add('hidden')"
                        class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-950 font-black rounded-xl border-2 border-slate-950 text-xs transition uppercase tracking-wider">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white font-black rounded-xl border-2 border-slate-950 text-xs shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleEnrollForm(id) {
            const el = document.getElementById(id);
            if (el) el.classList.toggle('hidden');
        }

        function openEditKelas(id, name, grade, jurusanId, ayId, semId, waliId, angkatan) {
            document.getElementById('edit-kelas-name').value = name;
            document.getElementById('edit-kelas-angkatan').value = angkatan || '';
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
