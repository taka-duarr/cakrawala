<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">Kelola Event Sekolah</h2>
            <button onclick="UIkit.modal('#modal-add-event').show()" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md transition flex items-center space-x-2">
                <span uk-icon="icon: plus; ratio: 0.8"></span>
                <span>Tambah Event Baru</span>
            </button>
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

            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden soft-glow-indigo">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800 font-sans">Daftar Event</h3>
                    <p class="text-xs text-slate-400 mt-1 font-medium">Buat event sekolah baru, edit informasi, dan pilih siswa untuk verifikasi partisipasi.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100/80">
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Judul Event</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Waktu & Tempat</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Hadiah Poin</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Kategori</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/70 font-sans">
                            @forelse($events as $event)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-800 text-xs">{{ $event->title }}</td>
                                <td class="px-6 py-4 text-xs text-slate-500 font-medium max-w-xs truncate" title="{{ $event->description }}">{{ $event->description }}</td>
                                <td class="px-6 py-4 text-xs text-slate-400 font-semibold leading-relaxed">
                                    <div class="flex items-center space-x-1.5">
                                        <span uk-icon="icon: calendar; ratio: 0.7"></span>
                                        <span>{{ $event->event_date }}</span>
                                    </div>
                                    <div class="flex items-center space-x-1.5 mt-0.5">
                                        <span uk-icon="icon: location; ratio: 0.7"></span>
                                        <span>{{ $event->location }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-extrabold text-xs text-emerald-600">+{{ $event->points_bonus }} Pts</td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $catColors = match($event->category) {
                                            'akademik' => 'bg-indigo-50 text-indigo-700 border-indigo-100/80',
                                            'karakter' => 'bg-emerald-50 text-emerald-700 border-emerald-100/80',
                                            'sosial' => 'bg-amber-50 text-amber-700 border-amber-100/80',
                                            default => 'bg-slate-50 text-slate-600 border-slate-200',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border capitalize {{ $catColors }}">
                                        {{ $event->category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.events.award.show', $event->id) }}" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-100 text-[10px] font-bold px-2.5 py-1.5 rounded-xl transition flex items-center space-x-1">
                                            <span uk-icon="icon: check; ratio: 0.65"></span>
                                            <span>Beri Reward</span>
                                        </a>
                                        <button onclick="editEvent({{ json_encode($event) }})" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-100 text-[10px] font-bold px-2.5 py-1.5 rounded-xl transition flex items-center space-x-1">
                                            <span uk-icon="icon: file-edit; ratio: 0.65"></span>
                                            <span>Edit</span>
                                        </button>
                                        <form method="POST" action="{{ route('admin.events.destroy', $event->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus event ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-100 text-[10px] font-bold px-2.5 py-1.5 rounded-xl transition flex items-center">
                                                <span uk-icon="icon: trash; ratio: 0.65"></span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-slate-400 text-xs font-medium">Belum ada event sekolah yang terdaftar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($events->hasPages())
                <div class="p-6 border-t border-slate-100">
                    {{ $events->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>

    <!-- MODAL ADD EVENT -->
    <div id="modal-add-event" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-2xl p-6">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span uk-icon="icon: plus; ratio: 1.1"></span>
                <span>Tambah Event Baru</span>
            </h2>
            <form action="{{ route('admin.events.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Judul Event</label>
                    <input type="text" name="title" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: Pekan Karakter 2026">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Deskripsi</label>
                    <textarea name="description" required rows="3" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Uraikan detail event..."></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Tanggal Pelaksanaan</label>
                        <input type="text" name="event_date" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="15 Juni 2026">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Lokasi</label>
                        <input type="text" name="location" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Lapangan Utama">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Bonus Poin</label>
                        <input type="number" name="points_bonus" required min="1" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="50">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Kategori</label>
                        <select name="category" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="karakter">Karakter</option>
                            <option value="akademik">Akademik</option>
                            <option value="sosial">Sosial</option>
                        </select>
                    </div>
                </div>
                <div class="pt-4 flex justify-end space-x-2">
                    <button type="button" class="uk-modal-close px-4 py-2 text-xs font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 rounded-xl transition">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition shadow-md">Simpan Event</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT EVENT -->
    <div id="modal-edit-event" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-2xl p-6">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span uk-icon="icon: file-edit; ratio: 1.1"></span>
                <span>Edit Event</span>
            </h2>
            <form id="edit-event-form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Judul Event</label>
                    <input type="text" id="edit-title" name="title" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Deskripsi</label>
                    <textarea id="edit-description" name="description" required rows="3" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Tanggal Pelaksanaan</label>
                        <input type="text" id="edit-date" name="event_date" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Lokasi</label>
                        <input type="text" id="edit-location" name="location" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Bonus Poin</label>
                        <input type="number" id="edit-bonus" name="points_bonus" required min="1" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Kategori</label>
                        <select id="edit-category" name="category" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="karakter">Karakter</option>
                            <option value="akademik">Akademik</option>
                            <option value="sosial">Sosial</option>
                        </select>
                    </div>
                </div>
                <div class="pt-4 flex justify-end space-x-2">
                    <button type="button" class="uk-modal-close px-4 py-2 text-xs font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 rounded-xl transition">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition shadow-md">Simpan Perubahan</button>
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
