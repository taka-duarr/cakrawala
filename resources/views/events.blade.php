<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 leading-tight flex items-center gap-2.5">
            <svg class="w-7 h-7 text-indigo-600 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"></path>
            </svg>
            <span>Event Sekolah</span>
        </h2>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto space-y-6">
            
            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 flex items-center space-x-3 shadow-sm">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="font-medium text-xs">{{ session('success') }}</p>
            </div>
            @endif

            <div class="bg-white rounded-2xl border border-slate-100 p-8 shadow-sm soft-glow-indigo">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">Event Karakter & Sosial</h3>
                        <p class="text-xs text-slate-400 mt-1 font-medium font-sans">Ikuti berbagai kegiatan sekolah yang seru untuk melatih kepemimpinan, kepedulian, dan meraih poin karakter tambahan.</p>
                    </div>
                    <!-- Search Input -->
                    <div class="relative w-full md:w-72">
                        <span uk-icon="icon: search; ratio: 0.85" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></span>
                        <input type="text" id="search-events" onkeyup="filterEvents()" placeholder="Cari event..." 
                            class="w-full border border-slate-200 bg-slate-50/50 rounded-xl pl-9 pr-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 placeholder-slate-400">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($events as $event)
                        @php
                            $catColors = match($event['category']) {
                                'akademik' => 'bg-indigo-100 text-indigo-700',
                                'sosial' => 'bg-emerald-100 text-emerald-700',
                                'karakter' => 'bg-amber-100 text-amber-700',
                                default => 'bg-slate-100 text-slate-700',
                            };
                        @endphp
                        <div class="event-card shimmer-card bg-slate-50/50 border border-slate-100 rounded-2xl p-6 flex flex-col justify-between transition hover:-translate-y-1 hover:bg-white hover:shadow-lg hover:shadow-indigo-50"
                             data-title="{{ strtolower($event['title']) }}" data-description="{{ strtolower($event['description']) }}">
                            <div class="space-y-4">
                                <div class="flex justify-between items-start">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $catColors }}">
                                        {{ $event['category'] }}
                                    </span>
                                    <span class="text-xs font-bold text-emerald-600">+{{ $event['points_bonus'] }} Pts Bonus</span>
                                </div>
                                
                                <div>
                                    <h4 class="font-extrabold text-slate-800 text-base leading-tight mb-2">{{ $event['title'] }}</h4>
                                    <p class="text-xs text-slate-500 leading-relaxed">{{ $event['description'] }}</p>
                                </div>

                                <div class="space-y-2 pt-2 border-t border-slate-100 text-[11px] text-slate-400 font-semibold">
                                    <div class="flex items-center space-x-1.5">
                                        <span uk-icon="icon: calendar; ratio: 0.7"></span>
                                        <span>{{ $event['date'] }}</span>
                                    </div>
                                    <div class="flex items-center space-x-1.5">
                                        <span uk-icon="icon: location; ratio: 0.7"></span>
                                        <span>{{ $event['location'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6">
                                <form method="GET" action="{{ route('events') }}" onsubmit="let btn = this.querySelector('button[type=submit]'); if(btn) { btn.disabled = true; btn.innerHTML = '<span class=\'animate-spin inline-block w-3.5 h-3.5 border-2 border-current border-t-transparent rounded-full mr-1.5 align-middle\'></span> Mendaftarkan...'; }">
                                    <input type="hidden" name="register" value="1">
                                    <input type="hidden" name="event_title" value="{{ $event['title'] }}">
                                    <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-sm hover:shadow-md">
                                        Ikuti Event
                                    </button>
                                </form>
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
