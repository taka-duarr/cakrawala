<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">Manajemen Tahun Ajaran & Semester</h2>
    </x-slot>

    <div class="py-12 bg-slate-100/30 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
            <div class="bg-[#EAFCEF] border-2 border-slate-950 text-emerald-800 rounded-xl p-4 flex items-center space-x-3 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                <span uk-icon="icon: check; ratio: 0.9"></span>
                <span class="font-black text-xs uppercase">{{ session('success') }}</span>
            </div>
            @endif

            {{-- Tahun Ajaran --}}
            <div class="bg-white rounded-3xl border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="p-6 border-b-4 border-slate-950 flex items-center justify-between flex-wrap gap-4 bg-slate-50">
                    <div>
                        <h3 class="text-base font-black text-slate-950 uppercase tracking-tight">Tahun Ajaran</h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-0.5">Kelola periode tahun ajaran sekolah. Contoh: 2025/2026</p>
                    </div>
                    <button onclick="document.getElementById('modal-add-tahun').classList.remove('hidden')"
                        class="inline-flex items-center px-4 py-2.5 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white text-xs font-black rounded-xl border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider">
                        <span uk-icon="icon: plus; ratio: 0.8" class="mr-1.5"></span>
                        Tambah Tahun Ajaran
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b-2 border-slate-950">
                                <th class="px-6 py-3 text-xs font-black text-slate-950 uppercase tracking-wider">Tahun Ajaran</th>
                                <th class="px-6 py-3 text-xs font-black text-slate-950 uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-3 text-xs font-black text-slate-950 uppercase tracking-wider text-center">Semester</th>
                                <th class="px-6 py-3 text-xs font-black text-slate-950 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-slate-950">
                            @forelse($academicYears as $ay)
                            <tr class="hover:bg-slate-50/50 transition-colors {{ $ay->is_active ? 'bg-indigo-50/20' : '' }}">
                                <td class="px-6 py-4">
                                    <span class="font-black text-slate-950 text-sm uppercase tracking-tight">{{ $ay->name }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($ay->is_active)
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-[#EAFCEF] text-emerald-800 border-2 border-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">● Aktif</span>
                                    @else
                                        <form method="POST" action="{{ route('admin.academic-years.set-active', $ay->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-white text-slate-950 hover:bg-[#E4FF1A] border-2 border-slate-950 transition-all shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[0px_0px_0px_0px_rgba(15,23,42,1)] cursor-pointer">
                                                Set Aktif
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    {{-- Semester sub-list --}}
                                    <div class="flex flex-wrap gap-2 justify-center items-center">
                                        @foreach($ay->semesters as $sem)
                                        <div class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full border-2 border-slate-950 text-[10px] font-black uppercase tracking-wider shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]
                                            {{ $sem->is_active ? 'bg-[#E4FF1A] text-slate-950' : 'bg-white text-slate-500' }}">
                                            <span>{{ $sem->name }}</span>
                                            @if(!$sem->is_active)
                                            <form method="POST" action="{{ route('admin.semesters.set-active', $sem->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" title="Set Aktif" class="text-indigo-600 hover:text-indigo-900 font-bold transition">●</button>
                                            </form>
                                            @else
                                            <span class="text-indigo-700">★</span>
                                            @endif
                                            <form method="POST" action="{{ route('admin.semesters.destroy', $sem->id) }}" class="inline" onsubmit="return confirm('Hapus semester {{ $sem->name }}?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-rose-500 hover:text-rose-700 font-black transition">×</button>
                                            </form>
                                        </div>
                                        @endforeach

                                        {{-- Add semester dropdown --}}
                                        @if($ay->semesters->count() < 2)
                                        <form method="POST" action="{{ route('admin.semesters.store') }}" class="inline-flex items-center">
                                            @csrf
                                            <input type="hidden" name="academic_year_id" value="{{ $ay->id }}">
                                            <select name="name" class="text-[10px] border-2 border-slate-950 rounded-full px-2 py-0.5 bg-white text-slate-950 focus:outline-none font-bold">
                                                @if(!$ay->semesters->where('name','Ganjil')->count())
                                                    <option value="Ganjil">+ Ganjil</option>
                                                @endif
                                                @if(!$ay->semesters->where('name','Genap')->count())
                                                    <option value="Genap">+ Genap</option>
                                                @endif
                                            </select>
                                            <button type="submit" class="ml-1 text-[10px] px-2 py-0.5 bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 rounded-full font-black hover:bg-slate-950 hover:text-white transition shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[0px_0px_0px_0px_rgba(15,23,42,1)]">Tambah</button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button onclick="openEditTahun({{ $ay->id }}, '{{ $ay->name }}')"
                                            class="p-1.5 text-indigo-650 bg-white border-2 border-slate-950 rounded-xl hover:bg-slate-50 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[0px_0px_0px_0px_rgba(15,23,42,1)] transition-all">
                                            <span uk-icon="icon: file-edit; ratio: 0.8"></span>
                                        </button>
                                        <form method="POST" action="{{ route('admin.academic-years.destroy', $ay->id) }}" class="inline" onsubmit="return confirm('Yakin hapus tahun ajaran {{ $ay->name }}? Semua semester terkait akan ikut terhapus.')">
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
                                <td colspan="4" class="text-center py-12 text-slate-400 text-xs font-bold uppercase">
                                    Belum ada tahun ajaran. Silakan tambahkan!
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- Modal Tambah Tahun Ajaran --}}
    <div id="modal-add-tahun" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white border-4 border-slate-950 rounded-3xl shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] w-full max-w-md">
            <div class="p-6 border-b-2 border-slate-950 flex justify-between items-center bg-white sticky top-0 z-10">
                <h4 class="font-black text-slate-950 uppercase tracking-tight">Tambah Tahun Ajaran</h4>
                <button onclick="document.getElementById('modal-add-tahun').classList.add('hidden')" class="text-slate-400 hover:text-slate-950">
                    <span uk-icon="icon: close; ratio: 1"></span>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.academic-years.store') }}" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nama Tahun Ajaran <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" placeholder="Contoh: 2025/2026" required
                        class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">Format: YYYY/YYYY (contoh: 2025/2026)</p>
                </div>
                <div class="flex justify-end space-x-3 pt-4 border-t-2 border-slate-950">
                    <button type="button" onclick="document.getElementById('modal-add-tahun').classList.add('hidden')"
                        class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-950 font-black rounded-xl border-2 border-slate-950 text-xs transition uppercase tracking-wider">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white font-black rounded-xl border-2 border-slate-950 text-xs shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Tahun Ajaran --}}
    <div id="modal-edit-tahun" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white border-4 border-slate-950 rounded-3xl shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] w-full max-w-md">
            <div class="p-6 border-b-2 border-slate-950 flex justify-between items-center bg-white sticky top-0 z-10">
                <h4 class="font-black text-slate-950 uppercase tracking-tight">Edit Tahun Ajaran</h4>
                <button onclick="document.getElementById('modal-edit-tahun').classList.add('hidden')" class="text-slate-400 hover:text-slate-950">
                    <span uk-icon="icon: close; ratio: 1"></span>
                </button>
            </div>
            <form id="form-edit-tahun" method="POST" class="p-6 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nama Tahun Ajaran <span class="text-rose-500">*</span></label>
                    <input type="text" id="edit-tahun-name" name="name" required
                        class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white">
                </div>
                <div class="flex justify-end space-x-3 pt-4 border-t-2 border-slate-950">
                    <button type="button" onclick="document.getElementById('modal-edit-tahun').classList.add('hidden')"
                        class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-950 font-black rounded-xl border-2 border-slate-950 text-xs transition uppercase tracking-wider">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white font-black rounded-xl border-2 border-slate-950 text-xs shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditTahun(id, name) {
            document.getElementById('edit-tahun-name').value = name;
            document.getElementById('form-edit-tahun').action = '/admin/academic-years/' + id + '/update';
            document.getElementById('modal-edit-tahun').classList.remove('hidden');
        }
    </script>
</x-app-layout>
