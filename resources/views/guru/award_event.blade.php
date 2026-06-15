<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('guru.missions.index') }}" class="text-slate-500 hover:text-slate-700 transition">
                <span uk-icon="icon: arrow-left; ratio: 1.2"></span>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">Verifikasi Partisipasi Event</h2>
                <p class="text-xs text-slate-400 mt-1 font-medium">Beri penghargaan poin kepada siswa yang mengikuti event.</p>
            </div>
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

            <div class="bg-white rounded-2xl border border-slate-100 p-6 soft-glow-indigo">
                <div class="flex items-start justify-between border-b border-slate-100 pb-4 mb-6">
                    <div>
                        <span class="px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-100 rounded-md text-[10px] font-bold uppercase tracking-wider">
                            Event: {{ $event->category }}
                        </span>
                        <h3 class="text-lg font-black text-slate-800 mt-2">{{ $event->title }}</h3>
                        <p class="text-xs text-slate-400 font-medium mt-1">{{ $event->description }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block">Hadiah</span>
                        <span class="text-2xl font-black text-emerald-600">+{{ $event->points_bonus }} Pts</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('guru.events.award.process', $event->id) }}" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-4">
                        <h4 class="text-sm font-bold text-slate-700">Pilih Siswa yang Berpartisipasi:</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($classrooms as $classroom)
                            <div class="bg-slate-50/50 rounded-2xl border border-slate-100 p-5">
                                <div class="flex items-center justify-between border-b border-slate-200/60 pb-3 mb-3">
                                    <h5 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Kelas: {{ $classroom->name }}</h5>
                                    <label class="flex items-center space-x-2 text-xs font-semibold text-indigo-600 cursor-pointer select-none">
                                        <input type="checkbox" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 class-select-all" data-class-id="{{ $classroom->id }}">
                                        <span>Pilih Semua</span>
                                    </label>
                                </div>
                                <div class="space-y-2.5 max-h-60 overflow-y-auto pr-1">
                                    @forelse($classroom->students as $student)
                                        @php
                                            $alreadyAwarded = in_array($student->id, $completedStudentIds);
                                        @endphp
                                        <div class="flex items-center justify-between bg-white rounded-xl p-2.5 border border-slate-100/80 shadow-sm">
                                            <label class="flex items-center space-x-3 cursor-pointer select-none flex-1">
                                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" 
                                                    {{ $alreadyAwarded ? 'checked disabled' : '' }}
                                                    class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 student-checkbox-{{ $classroom->id }}">
                                                <div class="text-xs">
                                                    <p class="font-bold text-slate-800 leading-snug">{{ $student->name }}</p>
                                                    <p class="text-[9px] text-slate-400 font-medium">Points: {{ $student->points }} Pts</p>
                                                </div>
                                            </label>
                                            @if($alreadyAwarded)
                                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 border border-emerald-100/60 rounded-lg text-[9px] font-bold">
                                                    Diikuti
                                                </span>
                                            @endif
                                        </div>
                                    @empty
                                        <p class="text-[10px] text-slate-400 font-semibold text-center py-4">Tidak ada siswa terdaftar di kelas ini.</p>
                                    @endforelse
                                </div>
                            </div>
                            @endforeach

                            @if($noClassStudents->count() > 0)
                            <div class="bg-slate-50/50 rounded-2xl border border-slate-100 p-5">
                                <div class="flex items-center justify-between border-b border-slate-200/60 pb-3 mb-3">
                                    <h5 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Siswa Tanpa Kelas</h5>
                                    <label class="flex items-center space-x-2 text-xs font-semibold text-indigo-600 cursor-pointer select-none">
                                        <input type="checkbox" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 class-select-all" data-class-id="noclass">
                                        <span>Pilih Semua</span>
                                    </label>
                                </div>
                                <div class="space-y-2.5 max-h-60 overflow-y-auto pr-1">
                                    @foreach($noClassStudents as $student)
                                        @php
                                            $alreadyAwarded = in_array($student->id, $completedStudentIds);
                                        @endphp
                                        <div class="flex items-center justify-between bg-white rounded-xl p-2.5 border border-slate-100/80 shadow-sm">
                                            <label class="flex items-center space-x-3 cursor-pointer select-none flex-1">
                                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" 
                                                    {{ $alreadyAwarded ? 'checked disabled' : '' }}
                                                    class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 student-checkbox-noclass">
                                                <div class="text-xs">
                                                    <p class="font-bold text-slate-800 leading-snug">{{ $student->name }}</p>
                                                    <p class="text-[9px] text-slate-400 font-medium">Points: {{ $student->points }} Pts</p>
                                                </div>
                                            </label>
                                            @if($alreadyAwarded)
                                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 border border-emerald-100/60 rounded-lg text-[9px] font-bold">
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

                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-md">
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
