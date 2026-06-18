<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">Kelola Katalog</h2>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Tambah, edit, atau hapus barang yang dijual di toko Anda.</p>
            </div>
            <div>
                <!-- Add Item Button -->
                <button uk-toggle="target: #modal-add-item" class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white text-xs font-black px-5 py-2.5 rounded-xl border-2 border-slate-950 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition flex items-center space-x-2 uppercase tracking-wider">
                    <span uk-icon="icon: plus; ratio: 0.85"></span>
                    <span>Tambah Barang</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-100/40 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-[#EAFCEF] border-2 border-slate-950 text-emerald-800 px-4 py-3 rounded-xl text-xs font-black flex items-center space-x-2 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                    <span uk-icon="icon: check; ratio: 0.9"></span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-[#FFEAEA] border-2 border-slate-950 text-rose-800 px-4 py-3 rounded-xl text-xs font-black flex items-center space-x-2 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                    <span uk-icon="icon: warning; ratio: 0.9"></span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-3xl border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="p-5 border-b-4 border-slate-950 flex items-center justify-between bg-[#E4FF1A]/10">
                    <h3 class="text-sm font-black text-slate-950 uppercase tracking-tight">Daftar Barang Anda</h3>
                    <span class="bg-white border-2 border-slate-950 text-slate-950 text-[10px] font-black px-2 py-0.5 rounded shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider">{{ $items->count() }} Item</span>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @forelse($items as $item)
                            <div class="flex flex-col bg-white rounded-2xl border-2 border-slate-950 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] p-5 hover:border-slate-950 hover:-translate-y-1 hover:translate-x-0.5 hover:shadow-[6px_6px_0px_0px_rgba(15,23,42,1)] transition-all duration-200 group">
                                <div class="w-12 h-12 bg-[#E4FF1A] border-2 border-slate-950 rounded-xl flex items-center justify-center mb-4 transition shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                    <span uk-icon="icon: bag; ratio: 1.0"></span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-base font-black text-slate-950 leading-tight mb-2 uppercase tracking-tight">{{ $item->name }}</h4>
                                    <div class="text-xs font-black text-slate-950 uppercase tracking-wider">
                                        <span class="bg-[#E4FF1A] border-2 border-slate-950 px-2 py-0.5 rounded shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">{{ $item->points_price }} Pts</span>
                                    </div>
                                </div>
                                <div class="mt-5 pt-4 border-t-2 border-slate-950 flex justify-end space-x-2">
                                    <button onclick="openEditItem({{ $item->id }}, {{ json_encode($item->name) }}, {{ $item->points_price }})"
                                        class="px-3 py-1.5 text-[10px] font-black text-blue-600 bg-white border-2 border-slate-950 rounded-lg transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-none flex items-center space-x-1 uppercase tracking-wider">
                                        <span uk-icon="icon: file-edit; ratio: 0.75"></span>
                                        <span>Edit</span>
                                    </button>
                                    <form action="{{ route('toko.items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus barang ini?')" class="inline-block">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 text-[10px] font-black text-rose-600 bg-white border-2 border-slate-950 rounded-lg transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-none flex items-center space-x-1 uppercase tracking-wider">
                                            <span uk-icon="icon: trash; ratio: 0.75"></span>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-16 flex flex-col items-center justify-center text-slate-400 bg-slate-50 border-2 border-dashed border-slate-950 rounded-2xl space-y-3 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                <span uk-icon="icon: shrink; ratio: 1.5" class="text-slate-400"></span>
                                <p class="text-sm font-black text-slate-950 uppercase tracking-tight">Belum ada barang di katalog.</p>
                                <p class="text-xs mt-1 text-slate-400 font-bold uppercase tracking-wider">Klik tombol "Tambah Barang" untuk mulai berjualan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ═══ MODAL TAMBAH BARANG ═══ -->
    <div id="modal-add-item" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-3xl border-4 border-slate-950 p-6 bg-white shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]" style="max-width: 400px">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-black text-slate-950 mb-4 uppercase tracking-tight">Tambah Barang Baru</h2>
            <form action="{{ route('toko.items.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-black text-slate-950 mb-1.5 uppercase tracking-wider">Nama Barang</label>
                    <input type="text" name="name" required placeholder="Contoh: Es Teh Manis"
                        class="w-full text-xs font-bold rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-0 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-950 mb-1.5 uppercase tracking-wider">Harga (Poin)</label>
                    <input type="number" name="points_price" required min="1" placeholder="10"
                        class="w-full text-xs font-bold rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-0 focus:outline-none">
                </div>
                <div class="flex justify-end space-x-3 pt-4 border-t-2 border-slate-950">
                    <button type="button" class="uk-modal-close border-2 border-slate-950 text-slate-950 hover:bg-slate-100 text-xs font-black px-4 py-2 rounded-xl transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider">Batal</button>
                    <button type="submit" class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white border-2 border-slate-950 text-xs font-black px-5 py-2.5 rounded-xl shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition uppercase tracking-wider">Tambah</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ═══ MODAL EDIT BARANG ═══ -->
    <div id="modal-edit-item" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-3xl border-4 border-slate-950 p-6 bg-white shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]" style="max-width: 400px">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-black text-slate-950 mb-4 uppercase tracking-tight">Edit Barang</h2>
            <form id="form-edit-item" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-xs font-black text-slate-950 mb-1.5 uppercase tracking-wider">Nama Barang</label>
                    <input type="text" name="name" id="edit_name" required
                        class="w-full text-xs font-bold rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-0 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-950 mb-1.5 uppercase tracking-wider">Harga (Poin)</label>
                    <input type="number" name="points_price" id="edit_points_price" required min="1"
                        class="w-full text-xs font-bold rounded-xl border-2 border-slate-950 focus:border-slate-950 focus:ring-0 focus:outline-none">
                </div>
                <div class="flex justify-end space-x-3 pt-4 border-t-2 border-slate-950">
                    <button class="uk-modal-close border-2 border-slate-950 text-slate-950 hover:bg-slate-100 text-xs font-black px-4 py-2.5 rounded-xl transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider" type="button">Batal</button>
                    <button class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white border-2 border-slate-950 text-xs font-black px-5 py-2.5 rounded-xl transition shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider" type="submit">Simpan Perubahan</button>
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
