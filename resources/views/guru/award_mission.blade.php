<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('guru.missions.index') }}" class="text-xs font-black text-slate-950 hover:text-slate-700 flex items-center space-x-1 mb-2.5 uppercase tracking-wider">
                    <span uk-icon="icon: arrow-left; ratio: 0.8"></span>
                    <span>Kembali ke Misi</span>
                </a>
                <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">Verifikasi Kelulusan Misi</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-100/40 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-[#EAFCEF] border-2 border-slate-950 text-emerald-800 px-4 py-3 rounded-xl text-xs font-black flex items-center space-x-2 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                    <span uk-icon="icon: check; ratio: 0.9"></span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-3xl border-4 border-slate-950 p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between border-b-4 border-slate-950 pb-4 mb-6 gap-4">
                    <div>
                        <span class="px-2.5 py-1 bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] rounded text-[9px] font-black uppercase tracking-wider">
                            Misi: {{ $mission->type }}
                        </span>
                        <h3 class="text-xl font-black text-slate-950 mt-2.5 uppercase tracking-tight">{{ $mission->title }}</h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">{{ $mission->description }}</p>
                    </div>
                    <div class="sm:text-right self-start sm:self-auto">
                        <span class="text-[9px] text-slate-400 font-black uppercase tracking-wider block mb-1">Hadiah Kelulusan</span>
                        <span class="inline-block text-2xl font-black text-slate-950 bg-[#E4FF1A] border-2 border-slate-950 px-3 py-1 rounded-xl shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider">+{{ $mission->points_reward }} Pts</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('guru.missions.award.process', $mission->id) }}" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-4">
                        <h4 class="text-xs font-black text-slate-950 uppercase tracking-wider">Pilih Siswa yang Menyelesaikan Misi:</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($classrooms as $classroom)
                            <div class="bg-white rounded-2xl border-2 border-slate-950 p-5 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)]">
                                <div class="flex items-center justify-between border-b-2 border-slate-950 pb-3 mb-3">
                                    <h5 class="text-xs font-black text-slate-950 uppercase tracking-wider">Kelas: {{ $classroom->name }}</h5>
                                    <label class="flex items-center space-x-2 text-[10px] font-black text-slate-950 cursor-pointer select-none uppercase tracking-wider">
                                        <input type="checkbox" class="w-4 h-4 text-slate-950 border-2 border-slate-950 rounded focus:ring-0 focus:ring-offset-0 focus:outline-none focus:border-slate-950 cursor-pointer class-select-all" data-class-id="{{ $classroom->id }}">
                                        <span>Pilih Semua</span>
                                    </label>
                                </div>
                                <div class="space-y-2.5 max-h-60 overflow-y-auto pr-1">
                                    @forelse($classroom->students as $student)
                                        @php
                                            $alreadyAwarded = in_array($student->id, $completedStudentIds);
                                        @endphp
                                        <div class="flex items-center justify-between bg-white rounded-xl p-2.5 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                            <label class="flex items-center space-x-3 cursor-pointer select-none flex-1">
                                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" 
                                                    {{ $alreadyAwarded ? 'checked disabled' : '' }}
                                                    class="w-4 h-4 text-slate-950 border-2 border-slate-950 rounded focus:ring-0 focus:ring-offset-0 focus:outline-none focus:border-slate-950 cursor-pointer student-checkbox-{{ $classroom->id }}">
                                                <div class="text-xs">
                                                    <p class="font-black text-slate-950 leading-snug uppercase tracking-tight">{{ $student->name }}</p>
                                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Points: {{ number_format($student->points) }} Pts</p>
                                                </div>
                                            </label>
                                            @if($alreadyAwarded)
                                                <span class="px-2 py-0.5 bg-[#EAFCEF] text-emerald-800 border-2 border-slate-950 rounded-lg text-[9px] font-black uppercase tracking-wider shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                                    Selesai
                                                </span>
                                            @endif
                                        </div>
                                    @empty
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider text-center py-4">Tidak ada siswa terdaftar di kelas ini.</p>
                                    @endforelse
                                </div>
                            </div>
                            @endforeach

                            @if($noClassStudents->count() > 0)
                            <div class="bg-white rounded-2xl border-2 border-slate-950 p-5 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)]">
                                <div class="flex items-center justify-between border-b-2 border-slate-950 pb-3 mb-3">
                                    <h5 class="text-xs font-black text-slate-950 uppercase tracking-wider">Siswa Tanpa Kelas</h5>
                                    <label class="flex items-center space-x-2 text-[10px] font-black text-slate-950 cursor-pointer select-none uppercase tracking-wider">
                                        <input type="checkbox" class="w-4 h-4 text-slate-950 border-2 border-slate-950 rounded focus:ring-0 focus:ring-offset-0 focus:outline-none focus:border-slate-950 cursor-pointer class-select-all" data-class-id="noclass">
                                        <span>Pilih Semua</span>
                                    </label>
                                </div>
                                <div class="space-y-2.5 max-h-60 overflow-y-auto pr-1">
                                    @foreach($noClassStudents as $student)
                                        @php
                                            $alreadyAwarded = in_array($student->id, $completedStudentIds);
                                        @endphp
                                        <div class="flex items-center justify-between bg-white rounded-xl p-2.5 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                            <label class="flex items-center space-x-3 cursor-pointer select-none flex-1">
                                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" 
                                                    {{ $alreadyAwarded ? 'checked disabled' : '' }}
                                                    class="w-4 h-4 text-slate-950 border-2 border-slate-950 rounded focus:ring-0 focus:ring-offset-0 focus:outline-none focus:border-slate-950 cursor-pointer student-checkbox-noclass">
                                                <div class="text-xs">
                                                    <p class="font-black text-slate-950 leading-snug uppercase tracking-tight">{{ $student->name }}</p>
                                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Points: {{ number_format($student->points) }} Pts</p>
                                                </div>
                                            </label>
                                            @if($alreadyAwarded)
                                                <span class="px-2 py-0.5 bg-[#EAFCEF] text-emerald-800 border-2 border-slate-950 rounded-lg text-[9px] font-black uppercase tracking-wider shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                                    Selesai
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="pt-6 border-t-2 border-slate-950 flex justify-end">
                        <button type="submit" class="px-6 py-3.5 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white border-2 border-slate-950 text-xs font-black rounded-xl transition shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider flex items-center space-x-2">
                            <span uk-icon="icon: check; ratio: 0.85"></span>
                            <span>Simpan & Beri Reward</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAlls = document.querySelectorAll('.class-select-all');
            selectAlls.forEach(cb => {
                cb.addEventListener('change', function() {
                    const classId = this.getAttribute('data-class-id');
                    const checkboxes = document.querySelectorAll('.student-checkbox-' + classId);
                    checkboxes.forEach(studentCb => {
                        if (!studentCb.disabled) {
                            studentCb.checked = this.checked;
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>
