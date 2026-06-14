<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">Manajemen Toko</h2>
            <button onclick="UIkit.modal('#modal-add-toko').show()"
                class="bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md shadow-violet-100 transition flex items-center space-x-2">
                <span uk-icon="icon: plus; ratio: 0.8"></span>
                <span>Tambah Toko</span>
            </button>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-xs font-semibold flex items-center space-x-2">
                    <span uk-icon="icon: check; ratio: 0.9"></span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-xs font-semibold space-y-1">
                    <div class="flex items-center space-x-2">
                        <span uk-icon="icon: warning; ratio: 0.9"></span>
                        <span class="font-bold">Terjadi kesalahan input:</span>
                    </div>
                    <ul class="list-disc pl-5 space-y-0.5 mt-1 font-medium">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <!-- Search Filter -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <form action="{{ route('admin.toko.index') }}" method="GET" class="flex items-center gap-3">
                    <div class="relative flex-1">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau email toko..."
                            class="w-full pl-4 pr-10 py-2.5 text-xs rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500">
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400"><span uk-icon="icon: search; ratio: 0.8"></span></span>
                    </div>
                    <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold px-5 py-2.5 rounded-xl transition">Cari</button>
                    @if($search)
                        <a href="{{ route('admin.toko.index') }}" class="text-xs font-semibold text-rose-500 hover:text-rose-700 py-2.5 px-2">Reset</a>
                    @endif
                </form>
            </div>

            <!-- Toko Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-slate-800">Daftar Akun Toko</h3>
                    <span class="text-xs text-slate-400 font-medium">{{ $tokos->total() }} toko terdaftar</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100">
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nama & Email</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Total Transaksi</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Total Poin Terkumpul</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/70">
                            @forelse($tokos as $toko)
                                @php
                                    $txCount   = \App\Models\ShopTransaction::where('shop_user_id', $toko->id)->where('status','paid')->count();
                                    $txPoints  = \App\Models\ShopTransaction::where('shop_user_id', $toko->id)->where('status','paid')->sum('points_amount');
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-9 h-9 bg-violet-100 border border-violet-100 rounded-xl flex items-center justify-center font-extrabold text-violet-700 text-sm shadow-inner">
                                                {{ substr($toko->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-800 text-xs">{{ $toko->name }}</div>
                                                <div class="text-[10px] text-slate-400 font-medium">{{ $toko->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-xs text-slate-700">{{ $txCount }} transaksi</td>
                                    <td class="px-6 py-4 text-center font-extrabold text-xs text-violet-700">{{ number_format($txPoints) }} Poin</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold {{ $toko->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-slate-100 text-slate-500' }}">
                                            {{ $toko->is_active ? 'Aktif' : 'Non-aktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-1">
                                            <a href="{{ route('admin.toko.transactions', $toko->id) }}"
                                                class="px-2.5 py-1.5 bg-violet-50 hover:bg-violet-100 text-violet-700 text-[10px] font-bold rounded-xl border border-violet-100 transition flex items-center space-x-1">
                                                <span uk-icon="icon: list; ratio: 0.7"></span>
                                                <span>Transaksi</span>
                                            </a>
                                            <button onclick="openEditToko({{ $toko->id }}, {{ json_encode($toko->name) }}, {{ json_encode($toko->email) }})"
                                                class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                            <form action="{{ route('admin.toko.destroy', $toko->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin hapus toko {{ $toko->name }}?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-12 text-slate-400 text-xs font-medium">Belum ada akun toko. Klik "Tambah Toko" untuk membuat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($tokos->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">{{ $tokos->links() }}</div>
                @endif
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH TOKO -->
    <div id="modal-add-toko" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-2xl p-6" style="max-width: 480px">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <span uk-icon="icon: plus-circle; ratio: 1.1"></span>
                <span>Tambah Akun Toko Baru</span>
            </h2>
            <form action="{{ route('admin.toko.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Nama Toko <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Kantin Bu Sari"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Email Login <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" required placeholder="contoh@sekolah.com"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Password <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>
                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" class="uk-modal-close bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-4 py-2 rounded-xl">Batal</button>
                    <button type="submit" class="bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-md shadow-violet-100 transition">Tambahkan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT TOKO -->
    <div id="modal-edit-toko" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-2xl p-6" style="max-width: 480px">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-bold text-slate-800 mb-4">Edit Akun Toko</h2>
            <form id="form-edit-toko" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Nama Toko</label>
                    <input type="text" id="edit-toko-name" name="name" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Email Login</label>
                    <input type="email" id="edit-toko-email" name="email" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Password Baru <span class="text-slate-400 font-normal">(kosongkan jika tidak ganti)</span></label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>
                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" class="uk-modal-close bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-4 py-2 rounded-xl">Batal</button>
                    <button type="submit" class="bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-md shadow-violet-100 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditToko(id, name, email) {
            document.getElementById('edit-toko-name').value = name;
            document.getElementById('edit-toko-email').value = email;
            document.getElementById('form-edit-toko').action = '/admin/toko/' + id + '/update';
            UIkit.modal('#modal-edit-toko').show();
        }
    </script>
</x-app-layout>
