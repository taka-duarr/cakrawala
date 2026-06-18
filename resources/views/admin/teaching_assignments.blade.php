<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">Penugasan Guru & Mapel</h2>
            <button onclick="UIkit.modal('#modal-add-assignment').show()"
                class="inline-flex items-center px-4 py-2.5 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white text-xs font-black rounded-xl border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider">
                <span uk-icon="icon: plus; ratio: 0.8" class="mr-1.5"></span>
                Tambah Penugasan
            </button>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-100/30 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-[#EAFCEF] border-2 border-slate-950 text-emerald-800 px-4 py-3 rounded-xl text-xs font-black flex items-center space-x-2 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                    <span uk-icon="icon: check; ratio: 0.9"></span>
                    <span class="uppercase tracking-wider">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-[#FFEAEA] border-2 border-slate-950 text-rose-800 px-4 py-3 rounded-xl text-xs font-black space-y-1 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                    <div class="flex items-center space-x-2">
                        <span uk-icon="icon: warning; ratio: 0.9"></span>
                        <span class="font-black">TERJADI KESALAHAN INPUT:</span>
                    </div>
                    <ul class="list-disc pl-5 space-y-0.5 mt-1 font-bold">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Filter Card -->
            <div class="bg-white rounded-3xl border-4 border-slate-950 p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <form action="{{ route('admin.teaching-assignments.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Guru</label>
                        <select name="teacher_id" class="w-full py-2.5 text-xs rounded-xl border-2 border-slate-950 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="">Semua Guru</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Mapel</label>
                        <select name="subject_id" class="w-full py-2.5 text-xs rounded-xl border-2 border-slate-950 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="">Semua Mapel</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Kelas</label>
                        <select name="classroom_id" class="w-full py-2.5 text-xs rounded-xl border-2 border-slate-950 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="">Semua Kelas</option>
                            @foreach($classrooms as $classroom)
                                <option value="{{ $classroom->id }}" {{ request('classroom_id') == $classroom->id ? 'selected' : '' }}>{{ $classroom->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tahun Ajaran</label>
                        <select name="academic_year_id" class="w-full py-2.5 text-xs rounded-xl border-2 border-slate-950 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="">Semua Tahun</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <div class="flex w-full gap-2 flex-wrap sm:flex-nowrap">
                            <button type="submit" class="flex-1 bg-slate-950 hover:bg-[#E4FF1A] text-white hover:text-slate-950 text-xs font-black px-5 py-3 rounded-xl border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider">Filter</button>
                            @if(request()->hasAny(['teacher_id', 'subject_id', 'classroom_id', 'academic_year_id', 'semester_id']))
                                <a href="{{ route('admin.teaching-assignments.index') }}"
                                   class="px-4 py-3 border-2 border-slate-950 text-slate-950 font-black rounded-xl hover:bg-slate-50 text-xs uppercase tracking-wider bg-white">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-3xl border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="p-6 border-b-4 border-slate-950 bg-slate-50">
                    <h3 class="text-base font-black text-slate-950 uppercase tracking-tight">Daftar Penugasan Mengajar</h3>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-0.5">Data ini menentukan guru boleh membuka absensi untuk mapel dan kelas mana.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b-2 border-slate-950">
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider">Guru</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider">Mapel</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider">Hari & Jam</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider text-center">Pertemuan</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider">Periode</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-slate-950">
                            @forelse($assignments as $assignment)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-950 text-xs">{{ $assignment->teacher->name ?? '-' }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold mt-0.5 uppercase tracking-wider">{{ $assignment->teacher->email ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-700 text-xs">{{ $assignment->subject->name ?? '-' }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold mt-0.5 uppercase tracking-wider">{{ $assignment->subject->code ?? 'Tanpa kode' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-bold text-slate-750">{{ $assignment->classroom->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-xs font-bold text-slate-750">
                                        @if($assignment->day_of_week)
                                            {{ $assignment->getDayTranslation() }} ({{ substr($assignment->start_time, 0, 5) }} - {{ substr($assignment->end_time, 0, 5) }})
                                        @else
                                            <span class="text-slate-400 font-bold">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center text-xs font-black text-slate-950 bg-slate-50/40">{{ $assignment->total_meetings ?? 16 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="text-xs font-bold text-slate-750">{{ $assignment->academicYear->name ?? 'Tanpa tahun ajaran' }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold mt-0.5 uppercase tracking-wider">{{ $assignment->semester->name ?? 'Tanpa semester' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border-2 border-slate-950
                                            {{ $assignment->is_active ? 'bg-[#EAFCEF] text-emerald-800' : 'bg-[#FFEAEA] text-rose-805' }}">
                                            {{ $assignment->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end space-x-2">
                                            <button type="button"
                                                data-id="{{ $assignment->id }}"
                                                data-teacher-id="{{ $assignment->teacher_id }}"
                                                data-subject-id="{{ $assignment->subject_id }}"
                                                data-classroom-id="{{ $assignment->classroom_id }}"
                                                data-academic-year-id="{{ $assignment->academic_year_id }}"
                                                data-semester-id="{{ $assignment->semester_id }}"
                                                data-day-of-week="{{ $assignment->day_of_week }}"
                                                data-start-time="{{ $assignment->start_time }}"
                                                data-end-time="{{ $assignment->end_time }}"
                                                data-active="{{ $assignment->is_active ? '1' : '0' }}"
                                                data-total-meetings="{{ $assignment->total_meetings ?? 16 }}"
                                                class="edit-assignment-btn p-1.5 text-indigo-650 bg-white border-2 border-slate-950 rounded-xl hover:bg-slate-50 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[0px_0px_0px_0px_rgba(15,23,42,1)] transition-all">
                                                <span uk-icon="icon: file-edit; ratio: 0.8"></span>
                                            </button>
                                            <form method="POST" action="{{ route('admin.teaching-assignments.destroy', $assignment->id) }}" class="inline" onsubmit="return confirm('Hapus penugasan mengajar ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-1.5 text-rose-600 bg-white border-2 border-slate-950 rounded-xl hover:bg-[#FFEAEA] shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[0px_0px_0px_0px_rgba(15,23,42,1)] transition-all">
                                                    <span uk-icon="icon: trash; ratio: 0.8"></span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-12 text-slate-400 text-xs font-bold uppercase">
                                        @if(request()->hasAny(['teacher_id', 'subject_id', 'classroom_id', 'academic_year_id', 'semester_id']))
                                            Tidak ada penugasan yang cocok dengan filter saat ini.
                                        @else
                                            Belum ada penugasan mengajar.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($assignments->hasPages())
                    <div class="px-6 py-4 border-t-2 border-slate-950 bg-slate-50">
                        {{ $assignments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @php
        $assignmentFields = [
            ['name' => 'teacher_id', 'label' => 'Guru Pengajar', 'items' => $teachers, 'text' => 'name'],
            ['name' => 'subject_id', 'label' => 'Mata Pelajaran', 'items' => $subjects, 'text' => 'name'],
            ['name' => 'classroom_id', 'label' => 'Kelas', 'items' => $classrooms, 'text' => 'name'],
        ];
    @endphp

    <div id="modal-add-assignment" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body border-4 border-slate-950 rounded-3xl shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] p-0 bg-white" style="width: 720px; max-width: calc(100% - 2rem);">
            <div class="p-6 border-b-2 border-slate-950 flex justify-between items-center bg-white sticky top-0 z-10">
                <h4 class="font-black text-slate-950 uppercase tracking-tight">Tambah Penugasan Mengajar</h4>
                <button class="uk-modal-close-default" type="button" uk-close></button>
            </div>
            <form method="POST" action="{{ route('admin.teaching-assignments.store') }}" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($assignmentFields as $field)
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">{{ $field['label'] }}</label>
                            <select name="{{ $field['name'] }}" required class="w-full border-2 border-slate-950 rounded-xl px-3 py-2 text-xs text-slate-950 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                                <option value="">-- Pilih --</option>
                                @foreach($field['items'] as $item)
                                    <option value="{{ $item->id }}">{{ $item->{$field['text']} }}</option>
                                </option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Tahun Ajaran</label>
                        <select name="academic_year_id" class="w-full border-2 border-slate-950 rounded-xl px-3 py-2 text-xs text-slate-950 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="">-- Opsional --</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}">{{ $year->name }}{{ $year->is_active ? ' (Aktif)' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Semester</label>
                        <select name="semester_id" class="w-full border-2 border-slate-950 rounded-xl px-3 py-2 text-xs text-slate-950 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="">-- Opsional --</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->id }}">{{ $semester->academicYear->name ?? '-' }} - {{ $semester->name }}{{ $semester->is_active ? ' (Aktif)' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Hari</label>
                        <select name="day_of_week" class="w-full border-2 border-slate-950 rounded-xl px-3 py-2 text-xs text-slate-950 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="">-- Pilih Hari --</option>
                            <option value="Monday">Senin</option>
                            <option value="Tuesday">Selasa</option>
                            <option value="Wednesday">Rabu</option>
                            <option value="Thursday">Kamis</option>
                            <option value="Friday">Jumat</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Jam Mulai</label>
                        <input type="time" name="start_time" class="w-full border-2 border-slate-950 rounded-xl px-3 py-2 text-xs text-slate-950 bg-white font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Jam Selesai</label>
                        <input type="time" name="end_time" class="w-full border-2 border-slate-950 rounded-xl px-3 py-2 text-xs text-slate-950 bg-white font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Jumlah Pertemuan</label>
                        <input type="number" name="total_meetings" value="16" min="1" max="40" required class="w-full border-2 border-slate-950 rounded-xl px-3 py-2 text-xs text-slate-950 bg-white font-bold">
                    </div>
                </div>

                <label class="inline-flex items-center space-x-2 text-xs font-bold text-slate-700">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-2 border-slate-950 text-slate-950 focus:ring-slate-950">
                    <span>Aktif</span>
                </label>

                <div class="flex justify-end space-x-3 pt-4 border-t-2 border-slate-950">
                    <button type="button" onclick="UIkit.modal('#modal-add-assignment').hide()"
                        class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-950 font-black rounded-xl border-2 border-slate-950 text-xs transition uppercase tracking-wider">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white font-black rounded-xl border-2 border-slate-950 text-xs shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-edit-assignment" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body border-4 border-slate-950 rounded-3xl shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] p-0 bg-white" style="width: 720px; max-width: calc(100% - 2rem);">
            <div class="p-6 border-b-2 border-slate-950 flex justify-between items-center bg-white sticky top-0 z-10">
                <h4 class="font-black text-slate-950 uppercase tracking-tight">Edit Penugasan Mengajar</h4>
                <button class="uk-modal-close-default" type="button" uk-close></button>
            </div>
            <form id="form-edit-assignment" method="POST" class="p-6 space-y-4">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($assignmentFields as $field)
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">{{ $field['label'] }}</label>
                            <select id="edit-{{ str_replace('_', '-', $field['name']) }}" name="{{ $field['name'] }}" required class="w-full border-2 border-slate-950 rounded-xl px-3 py-2 text-xs text-slate-950 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                                <option value="">-- Pilih --</option>
                                @foreach($field['items'] as $item)
                                    <option value="{{ $item->id }}">{{ $item->{$field['text']} }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Tahun Ajaran</label>
                        <select id="edit-academic-year-id" name="academic_year_id" class="w-full border-2 border-slate-950 rounded-xl px-3 py-2 text-xs text-slate-950 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="">-- Opsional --</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}">{{ $year->name }}{{ $year->is_active ? ' (Aktif)' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Semester</label>
                        <select id="edit-semester-id" name="semester_id" class="w-full border-2 border-slate-950 rounded-xl px-3 py-2 text-xs text-slate-955 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="">-- Opsional --</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->id }}">{{ $semester->academicYear->name ?? '-' }} - {{ $semester->name }}{{ $semester->is_active ? ' (Aktif)' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Hari</label>
                        <select id="edit-day-of-week" name="day_of_week" class="w-full border-2 border-slate-950 rounded-xl px-3 py-2 text-xs text-slate-955 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="">-- Pilih Hari --</option>
                            <option value="Monday">Senin</option>
                            <option value="Tuesday">Selasa</option>
                            <option value="Wednesday">Rabu</option>
                            <option value="Thursday">Kamis</option>
                            <option value="Friday">Jumat</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Jam Mulai</label>
                        <input type="time" id="edit-start-time" name="start_time" class="w-full border-2 border-slate-950 rounded-xl px-3 py-2 text-xs text-slate-955 bg-white font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Jam Selesai</label>
                        <input type="time" id="edit-end-time" name="end_time" class="w-full border-2 border-slate-950 rounded-xl px-3 py-2 text-xs text-slate-955 bg-white font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Jumlah Pertemuan</label>
                        <input type="number" id="edit-total-meetings" name="total_meetings" min="1" max="40" required class="w-full border-2 border-slate-950 rounded-xl px-3 py-2 text-xs text-slate-955 bg-white font-bold">
                    </div>
                </div>

                <label class="inline-flex items-center space-x-2 text-xs font-bold text-slate-700">
                    <input type="checkbox" id="edit-assignment-active" name="is_active" value="1" class="rounded border-2 border-slate-950 text-slate-950 focus:ring-slate-950">
                    <span>Aktif</span>
                </label>

                <div class="flex justify-end space-x-3 pt-4 border-t-2 border-slate-950">
                    <button type="button" onclick="UIkit.modal('#modal-edit-assignment').hide()"
                        class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-950 font-black rounded-xl border-2 border-slate-950 text-xs transition uppercase tracking-wider">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white font-black rounded-xl border-2 border-slate-950 text-xs shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.edit-assignment-btn').forEach(button => {
                button.addEventListener('click', function () {
                    document.getElementById('edit-teacher-id').value = this.dataset.teacherId || '';
                    document.getElementById('edit-subject-id').value = this.dataset.subjectId || '';
                    document.getElementById('edit-classroom-id').value = this.dataset.classroomId || '';
                    document.getElementById('edit-academic-year-id').value = this.dataset.academicYearId || '';
                    document.getElementById('edit-semester-id').value = this.dataset.semesterId || '';
                    document.getElementById('edit-day-of-week').value = this.dataset.dayOfWeek || '';
                    document.getElementById('edit-start-time').value = this.dataset.startTime ? this.dataset.startTime.substring(0, 5) : '';
                    document.getElementById('edit-end-time').value = this.dataset.endTime ? this.dataset.endTime.substring(0, 5) : '';
                    document.getElementById('edit-total-meetings').value = this.dataset.totalMeetings || '16';
                    document.getElementById('edit-assignment-active').checked = this.dataset.active === '1';
                    document.getElementById('form-edit-assignment').action = `/admin/teaching-assignments/${this.dataset.id}/update`;
                    UIkit.modal('#modal-edit-assignment').show();
                });
            });
        });
    </script>
</x-app-layout>
