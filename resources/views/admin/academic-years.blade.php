<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">Manajemen Tahun Ajaran & Semester</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 flex items-center space-x-3">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="font-semibold text-sm">{{ session('success') }}</span>
            </div>
            @endif

            {{-- ============================================================ --}}
            {{-- TAHUN AJARAN --}}
            {{-- ============================================================ --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Tahun Ajaran</h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">Kelola periode tahun ajaran sekolah. Contoh: 2025/2026</p>
                    </div>
                    <button onclick="document.getElementById('modal-add-tahun').classList.remove('hidden')"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Tahun Ajaran
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100">
                                <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tahun Ajaran</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Semester</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/70">
                            @forelse($academicYears as $ay)
                            <tr class="hover:bg-slate-50/50 transition-colors {{ $ay->is_active ? 'bg-blue-50/30' : '' }}">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-slate-800 text-sm">{{ $ay->name }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($ay->is_active)
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-100">● Aktif</span>
                                    @else
                                        <form method="POST" action="{{ route('admin.academic-years.set-active', $ay->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500 hover:bg-blue-100 hover:text-blue-700 border border-slate-200 transition cursor-pointer">
                                                Set Aktif
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    {{-- Semester sub-list --}}
                                    <div class="flex flex-wrap gap-2 justify-center">
                                        @foreach($ay->semesters as $sem)
                                        <div class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full border text-[10px] font-bold
                                            {{ $sem->is_active ? 'bg-indigo-50 border-indigo-200 text-indigo-700' : 'bg-slate-50 border-slate-200 text-slate-500' }}">
                                            <span>{{ $sem->name }}</span>
                                            @if(!$sem->is_active)
                                            <form method="POST" action="{{ route('admin.semesters.set-active', $sem->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" title="Set Aktif" class="text-indigo-400 hover:text-indigo-700 transition">●</button>
                                            </form>
                                            @else
                                            <span class="text-indigo-400">★</span>
                                            @endif
                                            <form method="POST" action="{{ route('admin.semesters.destroy', $sem->id) }}" class="inline" onsubmit="return confirm('Hapus semester {{ $sem->name }}?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-rose-400 hover:text-rose-600 transition">×</button>
                                            </form>
                                        </div>
                                        @endforeach

                                        {{-- Add semester dropdown --}}
                                        @if($ay->semesters->count() < 2)
                                        <form method="POST" action="{{ route('admin.semesters.store') }}" class="inline-flex items-center">
                                            @csrf
                                            <input type="hidden" name="academic_year_id" value="{{ $ay->id }}">
                                            <select name="name" class="text-[10px] border border-dashed border-slate-300 rounded-full px-2 py-1 bg-white text-slate-600 focus:outline-none focus:ring-1 focus:ring-blue-400">
                                                @if(!$ay->semesters->where('name','Ganjil')->count())
                                                    <option value="Ganjil">+ Ganjil</option>
                                                @endif
                                                @if(!$ay->semesters->where('name','Genap')->count())
                                                    <option value="Genap">+ Genap</option>
                                                @endif
                                            </select>
                                            <button type="submit" class="ml-1 text-[10px] px-2 py-1 bg-blue-100 text-blue-700 rounded-full font-bold hover:bg-blue-200 transition">Tambah</button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button onclick="openEditTahun({{ $ay->id }}, '{{ $ay->name }}')"
                                            class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <form method="POST" action="{{ route('admin.academic-years.destroy', $ay->id) }}" class="inline" onsubmit="return confirm('Yakin hapus tahun ajaran {{ $ay->name }}? Semua semester terkait akan ikut terhapus.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-12 text-slate-400 text-sm">
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
    <div id="modal-add-tahun" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h4 class="font-bold text-slate-800">Tambah Tahun Ajaran</h4>
                <button onclick="document.getElementById('modal-add-tahun').classList.add('hidden')" class="text-slate-400 hover:text-slate-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.academic-years.store') }}" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Tahun Ajaran <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" placeholder="Contoh: 2025/2026" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-[10px] text-slate-400 mt-1">Format: YYYY/YYYY (contoh: 2025/2026)</p>
                </div>
                <div class="flex justify-end space-x-3 pt-2">
                    <button type="button" onclick="document.getElementById('modal-add-tahun').classList.add('hidden')"
                        class="px-4 py-2 border border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-50 text-sm transition">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition shadow-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Tahun Ajaran --}}
    <div id="modal-edit-tahun" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h4 class="font-bold text-slate-800">Edit Tahun Ajaran</h4>
                <button onclick="document.getElementById('modal-edit-tahun').classList.add('hidden')" class="text-slate-400 hover:text-slate-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="form-edit-tahun" method="POST" class="p-6 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Tahun Ajaran <span class="text-rose-500">*</span></label>
                    <input type="text" id="edit-tahun-name" name="name" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex justify-end space-x-3 pt-2">
                    <button type="button" onclick="document.getElementById('modal-edit-tahun').classList.add('hidden')"
                        class="px-4 py-2 border border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-50 text-sm transition">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition shadow-sm">Simpan Perubahan</button>
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
