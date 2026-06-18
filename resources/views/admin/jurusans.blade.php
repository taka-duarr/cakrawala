<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">Manajemen Jurusan</h2>
    </x-slot>

    <div class="py-12 bg-slate-100/30 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
            <div class="bg-[#EAFCEF] border-2 border-slate-950 text-emerald-800 rounded-xl p-4 flex items-center space-x-3 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                <span uk-icon="icon: check; ratio: 0.9"></span>
                <span class="font-black text-xs uppercase">{{ session('success') }}</span>
            </div>
            @endif

            <div class="bg-white rounded-3xl border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="p-6 border-b-4 border-slate-950 flex items-center justify-between flex-wrap gap-4 bg-slate-50">
                    <div>
                        <h3 class="text-base font-black text-slate-950 uppercase tracking-tight">Daftar Jurusan</h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-0.5">Kelola program keahlian / jurusan sekolah. Contoh: IPA, IPS, Bahasa</p>
                    </div>
                    <button onclick="document.getElementById('modal-add-jurusan').classList.remove('hidden')"
                        class="inline-flex items-center px-4 py-2.5 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white text-xs font-black rounded-xl border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider">
                        <span uk-icon="icon: plus; ratio: 0.8" class="mr-1.5"></span>
                        Tambah Jurusan
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b-2 border-slate-950">
                                <th class="px-6 py-3 text-xs font-black text-slate-950 uppercase tracking-wider w-12">#</th>
                                <th class="px-6 py-3 text-xs font-black text-slate-950 uppercase tracking-wider">Nama Jurusan</th>
                                <th class="px-6 py-3 text-xs font-black text-slate-950 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-3 text-xs font-black text-slate-950 uppercase tracking-wider text-center">Total Kelas</th>
                                <th class="px-6 py-3 text-xs font-black text-slate-950 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-slate-950">
                            @forelse($jurusans as $index => $jurusan)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-xs text-slate-950 font-black">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-xl bg-violet-100 text-violet-755 border-2 border-slate-950 flex items-center justify-center font-black text-xs shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                            {{ strtoupper(substr($jurusan->name, 0, 2)) }}
                                        </div>
                                        <span class="font-black text-slate-950 text-sm uppercase tracking-tight">{{ $jurusan->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-700">{{ $jurusan->description ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-650 border-2 border-slate-950">
                                        {{ $jurusan->classrooms_count }} Kelas
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button onclick="openEditJurusan({{ $jurusan->id }}, '{{ addslashes($jurusan->name) }}', '{{ addslashes($jurusan->description ?? '') }}')"
                                            class="p-1.5 text-indigo-650 bg-white border-2 border-slate-950 rounded-xl hover:bg-slate-50 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[0px_0px_0px_0px_rgba(15,23,42,1)] transition-all">
                                            <span uk-icon="icon: file-edit; ratio: 0.8"></span>
                                        </button>
                                        <form method="POST" action="{{ route('admin.jurusans.destroy', $jurusan->id) }}" class="inline" onsubmit="return confirm('Hapus jurusan {{ $jurusan->name }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1.5 text-rose-600 bg-white border-2 border-slate-950 rounded-xl hover:bg-[#FFEAEA] shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[0px_0px_0px_0px_rgba(15,23,42,1)] transition-all">
                                                <span uk-icon="icon: trash; ratio: 0.8"></span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-12 text-slate-400 text-xs font-bold uppercase">
                                    Belum ada jurusan. Silakan tambahkan!
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tambah --}}
    <div id="modal-add-jurusan" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white border-4 border-slate-950 rounded-3xl shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] w-full max-w-md">
            <div class="p-6 border-b-2 border-slate-950 flex justify-between items-center bg-white sticky top-0 z-10">
                <h4 class="font-black text-slate-950 uppercase tracking-tight">Tambah Jurusan</h4>
                <button onclick="document.getElementById('modal-add-jurusan').classList.add('hidden')" class="text-slate-400 hover:text-slate-950">
                    <span uk-icon="icon: close; ratio: 1"></span>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.jurusans.store') }}" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nama Jurusan <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" placeholder="Contoh: IPA" required
                        class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Deskripsi</label>
                    <input type="text" name="description" placeholder="Opsional"
                        class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white">
                </div>
                <div class="flex justify-end space-x-3 pt-4 border-t-2 border-slate-950">
                    <button type="button" onclick="document.getElementById('modal-add-jurusan').classList.add('hidden')"
                        class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-950 font-black rounded-xl border-2 border-slate-950 text-xs transition uppercase tracking-wider">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white font-black rounded-xl border-2 border-slate-950 text-xs shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div id="modal-edit-jurusan" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white border-4 border-slate-950 rounded-3xl shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] w-full max-w-md">
            <div class="p-6 border-b-2 border-slate-950 flex justify-between items-center bg-white sticky top-0 z-10">
                <h4 class="font-black text-slate-950 uppercase tracking-tight">Edit Jurusan</h4>
                <button onclick="document.getElementById('modal-edit-jurusan').classList.add('hidden')" class="text-slate-400 hover:text-slate-950">
                    <span uk-icon="icon: close; ratio: 1"></span>
                </button>
            </div>
            <form id="form-edit-jurusan" method="POST" class="p-6 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nama Jurusan <span class="text-rose-500">*</span></label>
                    <input type="text" id="edit-jurusan-name" name="name" required
                        class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Deskripsi</label>
                    <input type="text" id="edit-jurusan-desc" name="description"
                        class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white">
                </div>
                <div class="flex justify-end space-x-3 pt-4 border-t-2 border-slate-950">
                    <button type="button" onclick="document.getElementById('modal-edit-jurusan').classList.add('hidden')"
                        class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-950 font-black rounded-xl border-2 border-slate-950 text-xs transition uppercase tracking-wider">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white font-black rounded-xl border-2 border-slate-950 text-xs shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditJurusan(id, name, desc) {
            document.getElementById('edit-jurusan-name').value = name;
            document.getElementById('edit-jurusan-desc').value = desc;
            document.getElementById('form-edit-jurusan').action = '/admin/jurusans/' + id + '/update';
            document.getElementById('modal-edit-jurusan').classList.remove('hidden');
        }
    </script>
</x-app-layout>
