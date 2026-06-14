<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">Kelola Katalog</h2>
                <p class="text-xs text-slate-400 mt-0.5 font-medium">Tambah, edit, atau hapus barang yang dijual di toko Anda.</p>
            </div>
            <div>
                <!-- Add Item Button -->
                <button uk-toggle="target: #modal-add-item" class="bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl shadow-sm transition flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Tambah Barang</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-xs font-semibold flex items-center space-x-2">
                    <span uk-icon="icon: check; ratio: 0.9"></span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-xs font-semibold flex items-center space-x-2">
                    <span uk-icon="icon: warning; ratio: 0.9"></span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                    <h3 class="text-sm font-bold text-slate-800">Daftar Barang Anda</h3>
                    <span class="text-[10px] bg-slate-200 text-slate-600 px-2 py-0.5 rounded-md font-bold">{{ $items->count() }} Item</span>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @forelse($items as $item)
                            <div class="flex flex-col bg-white rounded-2xl border border-slate-200 shadow-sm p-5 hover:border-violet-400 hover:shadow-md transition-all duration-200 group">
                                <div class="w-12 h-12 bg-violet-50 group-hover:bg-violet-100 rounded-xl flex items-center justify-center mb-4 transition">
                                    <svg class="w-6 h-6 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 11H4L5 9z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-base font-bold text-slate-800 leading-tight mb-1">{{ $item->name }}</h4>
                                    <div class="text-violet-600 font-black text-sm">{{ $item->points_price }} <span class="text-xs font-bold text-violet-400">Poin</span></div>
                                </div>
                                <div class="mt-4 pt-4 border-t border-slate-100 flex justify-end space-x-2">
                                    <button onclick="openEditItem({{ $item->id }}, {{ json_encode($item->name) }}, {{ $item->points_price }})"
                                        class="px-3 py-1.5 text-[10px] font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition flex items-center space-x-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span>Edit</span>
                                    </button>
                                    <form action="{{ route('toko.items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus barang ini?')" class="inline-block">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 text-[10px] font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg transition flex items-center space-x-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-16 flex flex-col items-center justify-center text-slate-400 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                                <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                <p class="text-sm font-semibold text-slate-500">Belum ada barang di katalog.</p>
                                <p class="text-xs mt-1 text-slate-400">Klik tombol "Tambah Barang" untuk mulai berjualan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ═══ MODAL TAMBAH BARANG ═══ -->
    <div id="modal-add-item" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-2xl p-6" style="max-width: 400px">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-base font-bold text-slate-800 mb-4">Tambah Barang Baru</h2>
            <form action="{{ route('toko.items.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Nama Barang</label>
                    <input type="text" name="name" required placeholder="Contoh: Es Teh Manis"
                        class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Harga (Poin)</label>
                    <input type="number" name="points_price" required min="1" placeholder="10"
                        class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>
                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" class="uk-modal-close bg-slate-100 text-slate-700 text-xs font-bold px-4 py-2.5 rounded-xl transition hover:bg-slate-200">Batal</button>
                    <button type="submit" class="bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-sm transition">Tambah</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ═══ MODAL EDIT BARANG ═══ -->
    <div id="modal-edit-item" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-2xl p-6" style="max-width: 400px">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-base font-bold text-slate-800 mb-4">Edit Barang</h2>
            <form id="form-edit-item" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Nama Barang</label>
                    <input type="text" name="name" id="edit_name" required
                        class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-violet-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Harga (Poin)</label>
                    <input type="number" name="points_price" id="edit_points_price" required min="1"
                        class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-violet-500 outline-none">
                </div>
                <div class="flex justify-end space-x-2 pt-2">
                    <button class="uk-modal-close bg-slate-100 text-slate-700 px-4 py-2.5 rounded-xl text-xs font-bold transition hover:bg-slate-200" type="button">Batal</button>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-xs font-bold transition shadow-sm" type="submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openEditItem(id, name, price) {
            document.getElementById('form-edit-item').action = `/toko/items/${id}/update`;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_points_price').value = price;
            UIkit.modal('#modal-edit-item').show();
        }
    </script>
    @endpush
</x-app-layout>
