<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 leading-tight flex items-center gap-2.5">
            <svg class="w-7 h-7 text-indigo-600 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z"></path>
            </svg>
            <span>Pengumuman Resmi</span>
        </h2>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto space-y-6">
            
            <div class="bg-white rounded-2xl border border-slate-100 p-8 shadow-sm soft-glow-indigo">
                <h3 class="text-xl font-bold text-slate-800 mb-2">Pusat Informasi & Pengumuman</h3>
                <p class="text-xs text-slate-400 mb-8 font-medium">Informasi resmi dari pihak sekolah mengenai sistem pembentukan karakter, peraturan, dan kegiatan penukaran poin.</p>

                <div class="space-y-6">
                    @foreach($announcements as $announcement)
                    <div class="p-6 rounded-2xl border {{ $announcement['is_pinned'] ? 'bg-indigo-50/30 border-indigo-100 shadow-sm' : 'bg-slate-50/50 border-slate-100' }} space-y-3 relative overflow-hidden transition hover:bg-white hover:shadow-md hover:border-slate-200">
                        @if($announcement['is_pinned'])
                        <div class="absolute top-0 right-0 bg-indigo-600 text-white text-[8px] font-bold uppercase tracking-wider px-3 py-1 rounded-bl-xl flex items-center space-x-1 shadow-sm">
                            <span uk-icon="icon: push; ratio: 0.6"></span>
                            <span>Sematkan</span>
                        </div>
                        @endif

                        <div class="flex items-center space-x-2 text-[10px] text-slate-400 font-semibold uppercase tracking-wider">
                            <span class="bg-slate-200/60 text-slate-600 px-2 py-0.5 rounded">{{ $announcement['author'] }}</span>
                            <span>·</span>
                            <span>{{ $announcement['date'] }}</span>
                        </div>

                        <h4 class="font-extrabold text-slate-800 text-base leading-snug">{{ $announcement['title'] }}</h4>
                        <p class="text-xs text-slate-600 leading-relaxed font-normal">{{ $announcement['body'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
