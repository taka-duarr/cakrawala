<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-950 leading-tight flex items-center gap-2.5 uppercase tracking-tight">
            <svg class="w-7 h-7 text-slate-950 inline-block" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"></path>
            </svg>
            <span>Event Sekolah</span>
        </h2>
    </x-slot>

    <div class="py-6 bg-slate-100/30 min-h-screen">
        <div class="max-w-7xl mx-auto space-y-6">
            
            @if(session('success'))
            <div class="bg-[#EAFCEF] border-2 border-slate-950 text-emerald-800 rounded-xl p-4 flex items-center space-x-3 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                <p class="font-bold text-xs uppercase tracking-wider">{{ session('success') }}</p>
            </div>
            @endif

            <div class="bg-white rounded-3xl border-4 border-slate-950 p-8 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                    <div>
                        <h3 class="text-xl font-black text-slate-950 uppercase tracking-tight">Event Karakter & Sosial</h3>
                        <p class="text-xs text-slate-400 mt-1 font-bold uppercase tracking-wider">Ikuti berbagai kegiatan sekolah yang seru untuk melatih kepemimpinan, kepedulian, dan meraih poin karakter tambahan.</p>
                    </div>
                    <!-- Search Input -->
                    <div class="relative w-full md:w-72">
                        <span uk-icon="icon: search; ratio: 0.85" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-950"></span>
                        <input type="text" id="search-events" onkeyup="filterEvents()" placeholder="Cari event..." 
                            class="w-full border-2 border-slate-950 bg-white rounded-xl pl-9 pr-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] focus-within:shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] transition-all placeholder-slate-400">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($events as $event)
                        @php
                            $catColors = match($event->category) {
                                'akademik' => 'bg-[#FFEAEA] text-rose-700 border-2 border-slate-950',
                                'sosial' => 'bg-[#EAFCEF] text-emerald-700 border-2 border-slate-950',
                                'karakter' => 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950',
                                default => 'bg-white text-slate-950 border-2 border-slate-950',
                            };
                        @endphp
                        <div class="event-card bg-white border-2 border-slate-950 rounded-2xl p-6 flex flex-col justify-between transition hover:-translate-y-1 hover:shadow-[6px_6px_0px_0px_rgba(15,23,42,1)] shadow-[4px_4px_0px_0px_rgba(15,23,42,1)]"
                             data-title="{{ strtolower($event->title) }}" data-description="{{ strtolower($event->description) }}">
                            <div class="space-y-4">
                                <div class="flex justify-between items-start">
                                    <span class="px-2.5 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider {{ $catColors }} shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                        {{ $event->category }}
                                    </span>
                                    <span class="text-xs font-black text-slate-950 uppercase">+{{ $event->points_bonus }} Pts Bonus</span>
                                </div>
                                
                                <div>
                                    <h4 class="font-black text-slate-950 text-base leading-tight uppercase tracking-tight mb-2">{{ $event->title }}</h4>
                                    <p class="text-xs text-slate-700 font-semibold leading-relaxed">{{ $event->description }}</p>
                                </div>

                                <div class="space-y-2 pt-3 border-t-2 border-slate-950 text-[10px] text-slate-400 font-bold uppercase">
                                    <div class="flex items-center space-x-1.5 text-slate-800">
                                        <span uk-icon="icon: calendar; ratio: 0.7" class="text-slate-950"></span>
                                        <span>{{ $event->event_date }}</span>
                                    </div>
                                    <div class="flex items-center space-x-1.5 text-slate-800">
                                        <span uk-icon="icon: location; ratio: 0.7" class="text-slate-950"></span>
                                        <span>{{ $event->location }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6">
                                @if(auth()->check() && auth()->user()->role->name === 'siswa')
                                    @if(in_array($event->id, $completedEventIds))
                                        <div class="w-full py-2.5 bg-[#EAFCEF] border-2 border-slate-950 text-emerald-700 rounded-xl text-xs font-black text-center flex items-center justify-center space-x-1.5 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                            <span uk-icon="icon: check; ratio: 0.8"></span>
                                            <span>Diikuti (+{{ $event->points_bonus }} Pts)</span>
                                        </div>
                                    @else
                                        <div class="w-full py-2.5 bg-slate-100 border-2 border-slate-950 text-slate-650 rounded-xl text-xs font-black text-center shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                            Tersedia
                                        </div>
                                    @endif
                                @else
                                    <div class="w-full py-2.5 bg-white border-2 border-slate-950 text-slate-950 rounded-xl text-xs font-black text-center shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                        Aktif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <script>
        function filterEvents() {
            const searchVal = document.getElementById('search-events').value.toLowerCase();
            const cards = document.querySelectorAll('.event-card');

            cards.forEach(card => {
                const title = card.getAttribute('data-title');
                const desc = card.getAttribute('data-description');

                if (title.includes(searchVal) || desc.includes(searchVal)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</x-app-layout>
