<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.events.index') }}" class="inline-flex items-center justify-center w-10 h-10 border-2 border-slate-950 bg-white text-slate-950 hover:bg-[#E4FF1A] shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all">
                <span uk-icon="icon: arrow-left; ratio: 1.2"></span>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-950 leading-tight tracking-tight">Verifikasi Partisipasi Event</h2>
                <p class="text-xs text-slate-500 mt-1 font-bold">Beri penghargaan poin kepada siswa yang mengikuti event.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
            <div class="bg-[#EAFCEF] border-2 border-slate-950 text-slate-950 p-4 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] flex items-center space-x-3 mb-6">
                <span uk-icon="icon: check; ratio: 0.9"></span>
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
            @endif

            <div class="bg-white border-2 border-slate-950 p-6 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)]">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between border-b-2 border-slate-950 pb-4 mb-6 gap-4">
                    <div>
                        <span class="inline-block px-2.5 py-0.5 bg-[#FFF6EA] text-slate-950 border-2 border-slate-950 text-[10px] font-black uppercase tracking-wider shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                            Event: {{ $event->category }}
                        </span>
                        <h3 class="text-lg font-black text-slate-950 mt-2 tracking-tight">{{ $event->title }}</h3>
                        <p class="text-xs text-slate-500 font-bold mt-1">{{ $event->description }}</p>
                    </div>
                    <div class="sm:text-right">
                        <span class="text-xs text-slate-500 font-bold uppercase tracking-wider block mb-1">Hadiah</span>
                        <span class="inline-block px-3 py-1 bg-[#E4FF1A] border-2 border-slate-950 text-slate-950 font-black text-xl shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                            +{{ $event->points_bonus }} Pts
                        </span>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.events.award.process', $event->id) }}" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-4">
                        <h4 class="text-sm font-extrabold text-slate-950 uppercase tracking-wider">Pilih Siswa yang Berpartisipasi:</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            @foreach($classrooms as $classroom)
                            <div class="bg-white border-2 border-slate-950 p-5 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)]">
                                <div class="flex items-center justify-between border-b-2 border-slate-950 pb-3 mb-3">
                                    <h5 class="text-xs font-black text-slate-950 uppercase tracking-wider">Kelas: {{ $classroom->name }}</h5>
                                    <label class="flex items-center space-x-2 text-xs font-bold text-slate-950 cursor-pointer select-none">
                                        <input type="checkbox" class="w-4 h-4 rounded-none border-2 border-slate-950 text-slate-950 focus:ring-0 focus:ring-offset-0 class-select-all" data-class-id="{{ $classroom->id }}">
                                        <span>Pilih Semua</span>
                                    </label>
                                </div>
                                <div class="space-y-3 max-h-60 overflow-y-auto pr-1">
                                    @forelse($classroom->students as $student)
                                        @php
                                            $alreadyAwarded = in_array($student->id, $completedStudentIds);
                                        @endphp
                                        <div class="flex items-center justify-between bg-white p-2.5 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                            <label class="flex items-center space-x-3 cursor-pointer select-none flex-1">
                                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" 
                                                    {{ $alreadyAwarded ? 'checked disabled' : '' }}
                                                    class="w-4 h-4 rounded-none border-2 border-slate-950 text-slate-950 focus:ring-0 focus:ring-offset-0 student-checkbox-{{ $classroom->id }}">
                                                <div class="text-xs">
                                                    <p class="font-bold text-slate-950 leading-snug">{{ $student->name }}</p>
                                                    <p class="text-[9px] text-slate-500 font-bold">Points: {{ $student->points }} Pts</p>
                                                </div>
                                            </label>
                                            @if($alreadyAwarded)
                                                <span class="px-2 py-0.5 bg-[#EAFCEF] text-slate-950 border-2 border-slate-950 text-[9px] font-black shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                                    Diikuti
                                                </span>
                                            @endif
                                        </div>
                                    @empty
                                        <p class="text-[10px] text-slate-500 font-bold text-center py-4">Tidak ada siswa terdaftar di kelas ini.</p>
                                    @endforelse
                                </div>
                            </div>
                            @endforeach

                            @if($noClassStudents->count() > 0)
                            <div class="bg-white border-2 border-slate-950 p-5 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)]">
                                <div class="flex items-center justify-between border-b-2 border-slate-950 pb-3 mb-3">
                                    <h5 class="text-xs font-black text-slate-950 uppercase tracking-wider">Siswa Tanpa Kelas</h5>
                                    <label class="flex items-center space-x-2 text-xs font-bold text-slate-950 cursor-pointer select-none">
                                        <input type="checkbox" class="w-4 h-4 rounded-none border-2 border-slate-950 text-slate-950 focus:ring-0 focus:ring-offset-0 class-select-all" data-class-id="noclass">
                                        <span>Pilih Semua</span>
                                    </label>
                                </div>
                                <div class="space-y-3 max-h-60 overflow-y-auto pr-1">
                                    @foreach($noClassStudents as $student)
                                        @php
                                            $alreadyAwarded = in_array($student->id, $completedStudentIds);
                                        @endphp
                                        <div class="flex items-center justify-between bg-white p-2.5 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                            <label class="flex items-center space-x-3 cursor-pointer select-none flex-1">
                                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" 
                                                    {{ $alreadyAwarded ? 'checked disabled' : '' }}
                                                    class="w-4 h-4 rounded-none border-2 border-slate-950 text-slate-950 focus:ring-0 focus:ring-offset-0 student-checkbox-noclass">
                                                <div class="text-xs">
                                                    <p class="font-bold text-slate-950 leading-snug">{{ $student->name }}</p>
                                                    <p class="text-[9px] text-slate-500 font-bold">Points: {{ $student->points }} Pts</p>
                                                </div>
                                            </label>
                                            @if($alreadyAwarded)
                                                <span class="px-2 py-0.5 bg-[#EAFCEF] text-slate-950 border-2 border-slate-950 text-[9px] font-black shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                                    Diikuti
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="pt-4 border-t-2 border-slate-950 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-[#E4FF1A] hover:bg-[#d8f014] text-slate-950 border-2 border-slate-950 text-xs font-black transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] hover:shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                            Simpan & Beri Reward
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
