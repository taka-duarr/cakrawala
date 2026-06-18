<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">Manajemen Mata Pelajaran</h2>
            <button onclick="UIkit.modal('#modal-add-subject').show()"
                class="inline-flex items-center px-4 py-2.5 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white text-xs font-black rounded-xl border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider">
                <span uk-icon="icon: plus; ratio: 0.8" class="mr-1.5"></span>
                Tambah Mapel
            </button>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-100/30 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-[#EAFCEF] border-2 border-slate-950 text-emerald-800 px-4 py-3 rounded-xl text-xs font-black flex items-center space-x-2 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                    <span uk-icon="icon: check; ratio: 0.9"></span>
                    <span class="uppercase tracking-wider">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-[#FFEAEA] border-2 border-slate-950 text-rose-800 px-4 py-3 rounded-xl text-xs font-black space-y-1 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                    <div class="flex items-center space-x-2">
                        <span uk-icon="icon: warning; ratio: 0.9"></span>
                        <span class="font-black">TERJADI KESALAHAN INPUT:</span>
                    </div>
                    <ul class="list-disc pl-5 space-y-0.5 mt-1 font-bold">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-3xl border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="p-6 border-b-4 border-slate-950 bg-slate-50">
                    <h3 class="text-base font-black text-slate-950 uppercase tracking-tight">Daftar Mata Pelajaran</h3>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-0.5">Mapel ini menjadi dasar untuk penugasan mengajar dan absensi per mata pelajaran.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b-2 border-slate-950">
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider">Mata Pelajaran</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider">Kode</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider text-center">Assignment</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-slate-950">
                            @forelse($subjects as $subject)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-9 h-9 rounded-xl bg-indigo-50 border-2 border-slate-950 text-indigo-800 flex items-center justify-center font-black text-xs shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                                {{ strtoupper(substr($subject->name, 0, 2)) }}
                                            </div>
                                            <div class="font-black text-slate-950 text-sm uppercase tracking-tight">{{ $subject->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-bold text-slate-700">{{ $subject->code ?? '-' }}</td>
                                    <td class="px-6 py-4 text-xs font-bold text-slate-700">{{ $subject->description ?? '-' }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-slate-100 text-slate-650 border-2 border-slate-950">
                                            {{ $subject->teaching_assignments_count }} Penugasan
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border-2 border-slate-950
                                            {{ $subject->is_active ? 'bg-[#EAFCEF] text-emerald-800' : 'bg-[#FFEAEA] text-rose-805' }}">
                                            {{ $subject->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end space-x-2">
                                            <button type="button"
                                                data-id="{{ $subject->id }}"
                                                data-name="{{ $subject->name }}"
                                                data-code="{{ $subject->code }}"
                                                data-description="{{ $subject->description }}"
                                                data-active="{{ $subject->is_active ? '1' : '0' }}"
                                                class="edit-subject-btn p-1.5 text-indigo-650 bg-white border-2 border-slate-950 rounded-xl hover:bg-slate-50 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[0px_0px_0px_0px_rgba(15,23,42,1)] transition-all">
                                                <span uk-icon="icon: file-edit; ratio: 0.8"></span>
                                            </button>
                                            <form method="POST" action="{{ route('admin.subjects.destroy', $subject->id) }}" class="inline" onsubmit="return confirm('Hapus mata pelajaran {{ $subject->name }}?')">
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
                                    <td colspan="6" class="text-center py-12 text-slate-400 text-xs font-bold uppercase">Belum ada mata pelajaran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add -->
    <div id="modal-add-subject" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body border-4 border-slate-950 rounded-3xl shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] p-0 bg-white">
            <div class="p-6 border-b-2 border-slate-950 flex justify-between items-center bg-white sticky top-0 z-10">
                <h4 class="font-black text-slate-950 uppercase tracking-tight">Tambah Mata Pelajaran</h4>
                <button class="uk-modal-close-default" type="button" uk-close></button>
            </div>
            <form method="POST" action="{{ route('admin.subjects.store') }}" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nama Mapel</label>
                    <input type="text" name="name" required placeholder="Contoh: Matematika"
                        class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Kode Mapel</label>
                    <input type="text" name="code" placeholder="Contoh: MTK"
                        class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="3" placeholder="Opsional"
                        class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white"></textarea>
                </div>
                <label class="inline-flex items-center space-x-2 text-xs font-bold text-slate-700">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-2 border-slate-950 text-slate-950 focus:ring-slate-950">
                    <span>Aktif</span>
                </label>
                <div class="flex justify-end space-x-3 pt-4 border-t-2 border-slate-950">
                    <button type="button" onclick="UIkit.modal('#modal-add-subject').hide()"
                        class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-950 font-black rounded-xl border-2 border-slate-950 text-xs transition uppercase tracking-wider">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white font-black rounded-xl border-2 border-slate-950 text-xs shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="modal-edit-subject" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body border-4 border-slate-950 rounded-3xl shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] p-0 bg-white">
            <div class="p-6 border-b-2 border-slate-950 flex justify-between items-center bg-white sticky top-0 z-10">
                <h4 class="font-black text-slate-950 uppercase tracking-tight">Edit Mata Pelajaran</h4>
                <button class="uk-modal-close-default" type="button" uk-close></button>
            </div>
            <form id="form-edit-subject" method="POST" class="p-6 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nama Mapel</label>
                    <input type="text" id="edit-subject-name" name="name" required
                        class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Kode Mapel</label>
                    <input type="text" id="edit-subject-code" name="code"
                        class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Deskripsi</label>
                    <textarea id="edit-subject-description" name="description" rows="3"
                        class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white"></textarea>
                </div>
                <label class="inline-flex items-center space-x-2 text-xs font-bold text-slate-700">
                    <input type="checkbox" id="edit-subject-active" name="is_active" value="1" class="rounded border-2 border-slate-950 text-slate-950 focus:ring-slate-950">
                    <span>Aktif</span>
                </label>
                <div class="flex justify-end space-x-3 pt-4 border-t-2 border-slate-950">
                    <button type="button" onclick="UIkit.modal('#modal-edit-subject').hide()"
                        class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-950 font-black rounded-xl border-2 border-slate-950 text-xs transition uppercase tracking-wider">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white font-black rounded-xl border-2 border-slate-950 text-xs shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.edit-subject-btn').forEach(button => {
                button.addEventListener('click', function () {
                    document.getElementById('edit-subject-name').value = this.dataset.name || '';
                    document.getElementById('edit-subject-code').value = this.dataset.code || '';
                    document.getElementById('edit-subject-description').value = this.dataset.description || '';
                    document.getElementById('edit-subject-active').checked = this.dataset.active === '1';
                    document.getElementById('form-edit-subject').action = `/admin/subjects/${this.dataset.id}/update`;
                    UIkit.modal('#modal-edit-subject').show();
                });
            });
        });
    </script>
</x-app-layout>
