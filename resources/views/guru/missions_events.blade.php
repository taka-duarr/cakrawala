<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('guru.dashboard') }}" class="text-xs font-black text-slate-950 hover:text-slate-700 flex items-center space-x-1 mb-2.5 uppercase tracking-wider">
                    <span uk-icon="icon: arrow-left; ratio: 0.8"></span>
                    <span>Kembali ke Dashboard</span>
                </a>
                <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">Kelola Misi & Event Karakter</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-100/40 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="bg-[#EAFCEF] border-2 border-slate-950 text-emerald-800 px-4 py-3 rounded-xl text-xs font-black flex items-center space-x-2 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                    <span uk-icon="icon: check; ratio: 0.9"></span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Section Misi -->
            <div class="bg-white rounded-3xl border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="p-6 border-b-4 border-slate-950 bg-[#E4FF1A]/10">
                    <h3 class="text-lg font-black text-slate-950 uppercase tracking-tight">Daftar Misi Karakter</h3>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Pilih misi di bawah ini untuk memverifikasi kelulusan siswa dan memberikan reward secara manual.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-950 text-white border-b-2 border-slate-950">
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider">Judul Misi</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-center">Hadiah Poin</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-center">Tipe Misi</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-950">
                            @forelse($missions as $mission)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-black text-slate-950 text-xs uppercase tracking-tight">{{ $mission->title }}</td>
                                <td class="px-6 py-4 text-xs text-slate-500 font-bold max-w-xs truncate uppercase tracking-wider" title="{{ $mission->description }}">{{ $mission->description }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-[#E4FF1A] border-2 border-slate-950 text-slate-950 text-[10px] font-black px-2.5 py-1 rounded shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider">
                                        +{{ $mission->points_reward }} Pts
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $typeColors = match($mission->type) {
                                            'daily' => 'bg-emerald-100',
                                            'weekly' => 'bg-indigo-100',
                                            'class' => 'bg-amber-100',
                                            'school' => 'bg-purple-100',
                                            'special' => 'bg-rose-100',
                                            default => 'bg-slate-100',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded text-[9px] font-black border-2 border-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider {{ $typeColors }}">
                                        {{ $mission->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('guru.missions.award.show', $mission->id) }}" class="bg-white hover:bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 text-[10px] font-black px-3.5 py-1.5 rounded-lg transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] inline-flex items-center space-x-1 uppercase tracking-wider">
                                        <span uk-icon="icon: check; ratio: 0.65"></span>
                                        <span>Verifikasi & Award</span>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-12 text-slate-400 text-xs font-bold uppercase tracking-wider bg-slate-50/20">Belum ada misi yang terdaftar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Section Event -->
            <div class="bg-white rounded-3xl border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="p-6 border-b-4 border-slate-950 bg-[#E4FF1A]/10">
                    <h3 class="text-lg font-black text-slate-950 uppercase tracking-tight">Daftar Event Sekolah</h3>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Pilih event sekolah di bawah ini untuk memverifikasi keikutsertaan siswa.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-950 text-white border-b-2 border-slate-950">
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider">Judul Event</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider">Waktu & Tempat</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-center">Hadiah Poin</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-center">Kategori</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-950">
                            @forelse($events as $event)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-black text-slate-950 text-xs uppercase tracking-tight">{{ $event->title }}</td>
                                <td class="px-6 py-4 text-xs text-slate-500 font-bold max-w-xs truncate uppercase tracking-wider" title="{{ $event->description }}">{{ $event->description }}</td>
                                <td class="px-6 py-4 text-xs text-slate-400 font-bold leading-normal uppercase tracking-wider">
                                    <div class="flex items-center space-x-1.5 text-slate-950">
                                        <span uk-icon="icon: calendar; ratio: 0.7"></span>
                                        <span>{{ $event->event_date }}</span>
                                    </div>
                                    <div class="flex items-center space-x-1.5 mt-1 text-slate-400">
                                        <span uk-icon="icon: location; ratio: 0.7"></span>
                                        <span>{{ $event->location }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-[#E4FF1A] border-2 border-slate-950 text-slate-950 text-[10px] font-black px-2.5 py-1 rounded shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider">
                                        +{{ $event->points_bonus }} Pts
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $catColors = match($event->category) {
                                            'akademik' => 'bg-indigo-100',
                                            'karakter' => 'bg-emerald-100',
                                            'sosial' => 'bg-amber-100',
                                            default => 'bg-slate-100',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded text-[9px] font-black border-2 border-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider {{ $catColors }}">
                                        {{ $event->category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('guru.events.award.show', $event->id) }}" class="bg-white hover:bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 text-[10px] font-black px-3.5 py-1.5 rounded-lg transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] inline-flex items-center space-x-1 uppercase tracking-wider">
                                        <span uk-icon="icon: check; ratio: 0.65"></span>
                                        <span>Verifikasi & Award</span>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-slate-400 text-xs font-bold uppercase tracking-wider bg-slate-50/20">Belum ada event sekolah yang terdaftar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
