<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">Kelola Event Sekolah</h2>
            <button onclick="UIkit.modal('#modal-add-event').show()" class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white text-xs font-black px-4 py-2.5 rounded-xl border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider flex items-center space-x-2">
                <span uk-icon="icon: plus; ratio: 0.8"></span>
                <span>Tambah Event Baru</span>
            </button>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-100/30 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
            <div class="bg-[#EAFCEF] border-2 border-slate-950 text-emerald-800 rounded-xl p-4 flex items-center space-x-3 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                <span uk-icon="icon: check; ratio: 0.9"></span>
                <p class="font-black text-xs uppercase">{{ session('success') }}</p>
            </div>
            @endif

            <div class="bg-white rounded-3xl border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="p-6 border-b-4 border-slate-950 bg-slate-50">
                    <h3 class="text-base font-black text-slate-950 uppercase tracking-tight">Daftar Event</h3>
                    <p class="text-xs text-slate-400 font-bold mt-1 uppercase tracking-wider">Buat event sekolah baru, edit informasi, dan pilih siswa untuk verifikasi partisipasi.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b-2 border-slate-950">
                                <th class="px-6 py-3.5 text-xs font-black text-slate-950 uppercase tracking-wider">Judul Event</th>
                                <th class="px-6 py-3.5 text-xs font-black text-slate-950 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-3.5 text-xs font-black text-slate-950 uppercase tracking-wider">Waktu & Tempat</th>
                                <th class="px-6 py-3.5 text-xs font-black text-slate-950 uppercase tracking-wider text-center">Hadiah Poin</th>
                                <th class="px-6 py-3.5 text-xs font-black text-slate-950 uppercase tracking-wider text-center">Kategori</th>
                                <th class="px-6 py-3.5 text-xs font-black text-slate-950 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-slate-950">
                            @forelse($events as $event)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-black text-slate-955 text-xs uppercase tracking-tight">{{ $event->title }}</td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-700 max-w-xs truncate" title="{{ $event->description }}">{{ $event->description }}</td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-550 leading-relaxed uppercase tracking-wider">
                                    <div class="flex items-center space-x-1.5">
                                        <span uk-icon="icon: calendar; ratio: 0.7" class="text-slate-950"></span>
                                        <span>{{ $event->event_date }}</span>
                                    </div>
                                    <div class="flex items-center space-x-1.5 mt-0.5">
                                        <span uk-icon="icon: location; ratio: 0.7" class="text-slate-950"></span>
                                        <span>{{ $event->location }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-black text-xs text-emerald-700">+{{ $event->points_bonus }} Pts</td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $catColors = match($event->category) {
                                            'akademik' => 'bg-indigo-50 text-indigo-805 border-slate-955',
                                            'karakter' => 'bg-[#EAFCEF] text-emerald-808 border-slate-955',
                                            'sosial' => 'bg-amber-50 text-amber-808 border-slate-955',
                                            default => 'bg-slate-50 text-slate-655 border-slate-955',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black border-2 uppercase tracking-wider shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] {{ $catColors }}">
                                        {{ $event->category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.events.award.show', $event->id) }}" class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white border-2 border-slate-950 text-[10px] font-black px-2.5 py-1.5 rounded-xl transition-all shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[0px_0px_0px_0px_rgba(15,23,42,1)] flex items-center space-x-1 uppercase tracking-wider">
                                            <span uk-icon="icon: check; ratio: 0.65"></span>
                                            <span>Beri Reward</span>
                                        </a>
                                        <button onclick="editEvent({{ json_encode($event) }})" class="bg-white hover:bg-slate-950 text-slate-950 hover:text-white border-2 border-slate-950 text-[10px] font-black px-2.5 py-1.5 rounded-xl transition-all shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[0px_0px_0px_0px_rgba(15,23,42,1)] flex items-center space-x-1 uppercase tracking-wider">
                                            <span uk-icon="icon: file-edit; ratio: 0.65"></span>
                                            <span>Edit</span>
                                        </button>
                                        <form method="POST" action="{{ route('admin.events.destroy', $event->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus event ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-500 hover:bg-slate-950 text-white border-2 border-slate-950 text-[10px] font-black px-2.5 py-1.5 rounded-xl transition-all shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[0px_0px_0px_0px_rgba(15,23,42,1)] flex items-center">
                                                <span uk-icon="icon: trash; ratio: 0.65"></span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-slate-400 text-xs font-bold uppercase">Belum ada event sekolah yang terdaftar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($events->hasPages())
                <div class="p-6 border-t-2 border-slate-950 bg-slate-50">
                    {{ $events->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>

    <!-- MODAL ADD EVENT -->
    <div id="modal-add-event" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body border-4 border-slate-950 rounded-3xl shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] p-6 bg-white">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-black text-slate-950 mb-4 flex items-center space-x-2 uppercase tracking-tight border-b-2 border-slate-950 pb-2">
                <span uk-icon="icon: plus; ratio: 1.1"></span>
                <span>Tambah Event Baru</span>
            </h2>
            <form action="{{ route('admin.events.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Judul Event</label>
                    <input type="text" name="title" required class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold" placeholder="Contoh: Pekan Karakter 2026">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Deskripsi</label>
                    <textarea name="description" required rows="3" class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold" placeholder="Uraikan detail event..."></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Pelaksanaan</label>
                        <input type="text" name="event_date" required class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold" placeholder="15 Juni 2026">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Lokasi</label>
                        <input type="text" name="location" required class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold" placeholder="Lapangan Utama">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Bonus Poin</label>
                        <input type="number" name="points_bonus" required min="1" class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold" placeholder="50">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Kategori</label>
                        <select name="category" required class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="karakter">Karakter</option>
                            <option value="akademik">Akademik</option>
                            <option value="sosial">Sosial</option>
                        </select>
                    </div>
                </div>
                <div class="pt-4 flex justify-end space-x-3 border-t-2 border-slate-950">
                    <button type="button" class="uk-modal-close bg-white hover:bg-slate-100 text-slate-955 font-black rounded-xl border-2 border-slate-950 text-xs transition uppercase tracking-wider px-4 py-2">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white font-black rounded-xl border-2 border-slate-950 text-xs shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider">Simpan Event</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT EVENT -->
    <div id="modal-edit-event" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body border-4 border-slate-950 rounded-3xl shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] p-6 bg-white">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-black text-slate-950 mb-4 flex items-center space-x-2 uppercase tracking-tight border-b-2 border-slate-950 pb-2">
                <span uk-icon="icon: file-edit; ratio: 1.1"></span>
                <span>Edit Event</span>
            </h2>
            <form id="edit-event-form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Judul Event</label>
                    <input type="text" id="edit-title" name="title" required class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Deskripsi</label>
                    <textarea id="edit-description" name="description" required rows="3" class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Pelaksanaan</label>
                        <input type="text" id="edit-date" name="event_date" required class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Lokasi</label>
                        <input type="text" id="edit-location" name="location" required class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Bonus Poin</label>
                        <input type="number" id="edit-bonus" name="points_bonus" required min="1" class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Kategori</label>
                        <select id="edit-category" name="category" required class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="karakter">Karakter</option>
                            <option value="akademik">Akademik</option>
                            <option value="sosial">Sosial</option>
                        </select>
                    </div>
                </div>
                <div class="pt-4 flex justify-end space-x-3 border-t-2 border-slate-950">
                    <button type="button" class="uk-modal-close bg-white hover:bg-slate-100 text-slate-955 font-black rounded-xl border-2 border-slate-950 text-xs transition uppercase tracking-wider px-4 py-2">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#E4FF1A] hover:bg-slate-955 text-slate-950 hover:text-white font-black rounded-xl border-2 border-slate-950 text-xs shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editEvent(event) {
            document.getElementById('edit-event-form').action = '/admin/events/' + event.id + '/update';
            document.getElementById('edit-title').value = event.title;
            document.getElementById('edit-description').value = event.description;
            document.getElementById('edit-date').value = event.event_date;
            document.getElementById('edit-location').value = event.location;
            document.getElementById('edit-bonus').value = event.points_bonus;
            document.getElementById('edit-category').value = event.category;
            UIkit.modal('#modal-edit-event').show();
        }
    </script>
</x-app-layout>
