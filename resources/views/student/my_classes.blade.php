<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">Kelas Saya</h2>
                <p class="text-xs text-slate-400 font-medium">Daftar kelas akademik dan mata pelajaran yang sedang Anda ikuti.</p>
            </div>
            
            @if($classroom)
            <div class="flex items-center space-x-2">
                <span class="bg-indigo-50 border border-indigo-150 text-indigo-700 text-xs font-bold px-3 py-1.5 rounded-xl uppercase tracking-wider">
                    Kelas: {{ $classroom->name }}
                </span>
                @if($classroom->jurusan)
                <span class="bg-emerald-50 border border-emerald-150 text-emerald-700 text-xs font-bold px-3 py-1.5 rounded-xl uppercase tracking-wider">
                    {{ $classroom->jurusan->name }}
                </span>
                @endif
            </div>
            @endif
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto space-y-6">
            
            @if(!$classroom)
            <div class="bg-amber-50 border border-amber-200 text-amber-700 p-6 rounded-2xl shadow-sm text-center">
                <span uk-icon="icon: warning; ratio: 1.5" class="text-amber-500 mb-2"></span>
                <h3 class="font-bold text-sm text-slate-800">Anda Belum Terdaftar di Kelas</h3>
                <p class="text-xs text-slate-500 mt-1">Silakan hubungi Admin atau Wali Kelas Anda untuk mendaftarkan Anda ke kelas akademik aktif.</p>
            </div>
            @else
            <!-- Class Banner -->
            <div class="bg-gradient-to-r from-indigo-900 to-indigo-750 rounded-2xl shadow-md p-8 text-white flex flex-col md:flex-row md:items-center justify-between gap-6" style="background: linear-gradient(135deg, #1e1b4b, #312e81);">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider bg-white/20 px-2.5 py-0.5 rounded-full inline-block mb-2 text-indigo-200">
                        Kelas Akademik Aktif
                    </span>
                    <h1 class="text-3xl font-extrabold mb-1.5">{{ $classroom->name }}</h1>
                    <p class="text-indigo-100 text-xs max-w-xl font-medium leading-relaxed">
                        Selamat belajar! Selesaikan tugas dan misi yang diberikan oleh masing-masing guru mata pelajaran untuk mengumpulkan poin reputasi karakter Anda.
                    </p>
                </div>
                <div class="bg-white/10 backdrop-blur border border-white/20 rounded-2xl p-5 min-w-[200px] text-right self-start md:self-auto">
                    <span class="text-[10px] text-indigo-200 block uppercase font-bold tracking-wider mb-1">Skor Keaktifan Kelas</span>
                    <strong class="text-2xl text-white font-extrabold block">{{ number_format($classroom->points) }} Pts</strong>
                    <span class="text-[9px] text-indigo-200 block font-medium mt-1">Total akumulasi poin kebaikan seluruh anggota kelas.</span>
                </div>
            </div>

            <!-- List Mata Pelajaran -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 soft-glow-indigo">
                <h3 class="text-lg font-bold text-slate-800 mb-1">Mata Pelajaran & Guru Pengampu</h3>
                <p class="text-xs text-slate-400 mb-6 font-medium">Klik pada kartu mata pelajaran di bawah untuk melihat detail tugas, absensi, dan teman sekelas.</p>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @forelse($assignments as $assign)
                    <a href="{{ route('student.class-detail', $assign->id) }}" class="bg-slate-50/70 border border-slate-100 hover:border-indigo-200 rounded-2xl p-5 flex flex-col justify-between hover:shadow-md transition-all duration-300 group hover:-translate-y-0.5">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="bg-indigo-50 text-indigo-700 group-hover:bg-indigo-600 group-hover:text-white text-[9px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider transition-colors">
                                    {{ $assign->subject->code ?? 'MAPEL' }}
                                </span>
                                <span class="text-[9px] text-slate-400 font-semibold uppercase tracking-wider">
                                    {{ $assign->semester->name ?? '-' }}
                                </span>
                            </div>
                            
                            <div>
                                <h4 class="text-base font-extrabold text-slate-800 group-hover:text-indigo-650 transition-colors line-clamp-1">{{ $assign->subject->name }}</h4>
                                <div class="flex items-center space-x-2 mt-2">
                                    <div class="w-6 h-6 bg-indigo-550/10 text-indigo-700 rounded-full flex items-center justify-center font-bold text-[10px]">
                                        {{ substr($assign->teacher->name ?? 'G', 0, 1) }}
                                    </div>
                                    <span class="text-xs text-slate-500 font-semibold line-clamp-1">Guru: {{ $assign->teacher->name ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-100/80 flex justify-between items-center text-[9px] text-slate-500 font-bold uppercase tracking-wide">
                            <span>Tahun Ajaran:</span>
                            <span class="text-slate-700 bg-white px-2.5 py-1 rounded-lg border border-slate-100 shadow-sm">{{ $assign->academicYear->name ?? '-' }}</span>
                        </div>
                    </a>
                    @empty
                    <div class="col-span-3 text-center py-12 text-slate-400 text-xs font-semibold bg-slate-50/50 rounded-2xl border border-dashed border-slate-150">
                        ⚠️ Belum ada mata pelajaran aktif yang ditugaskan di kelas ini.
                    </div>
                    @endforelse
                </div>
            </div>
            @endif
            
        </div>
    </div>
</x-app-layout>
