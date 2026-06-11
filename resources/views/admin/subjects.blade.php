<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">Manajemen Mata Pelajaran</h2>
            <button onclick="UIkit.modal('#modal-add-subject').show()"
                class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition shadow-md shadow-indigo-100">
                <span uk-icon="icon: plus; ratio: 0.8" class="mr-1.5"></span>
                Tambah Mapel
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
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
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-800">Daftar Mata Pelajaran</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Mapel ini menjadi dasar untuk penugasan mengajar dan absensi per mata pelajaran.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100">
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Mata Pelajaran</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kode</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Assignment</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/70">
                            @forelse($subjects as $subject)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-700 border border-indigo-100 flex items-center justify-center font-black text-xs">
                                                {{ strtoupper(substr($subject->name, 0, 2)) }}
                                            </div>
                                            <div class="font-bold text-slate-800 text-sm">{{ $subject->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-semibold text-slate-500">{{ $subject->code ?? '-' }}</td>
                                    <td class="px-6 py-4 text-xs text-slate-500">{{ $subject->description ?? '-' }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                            {{ $subject->teaching_assignments_count }} Penugasan
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $subject->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                                            {{ $subject->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <button type="button"
                                                data-id="{{ $subject->id }}"
                                                data-name="{{ $subject->name }}"
                                                data-code="{{ $subject->code }}"
                                                data-description="{{ $subject->description }}"
                                                data-active="{{ $subject->is_active ? '1' : '0' }}"
                                                class="edit-subject-btn p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                                <span uk-icon="icon: file-edit; ratio: 0.8"></span>
                                            </button>
                                            <form method="POST" action="{{ route('admin.subjects.destroy', $subject->id) }}" class="inline" onsubmit="return confirm('Hapus mata pelajaran {{ $subject->name }}?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                                    <span uk-icon="icon: trash; ratio: 0.8"></span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-12 text-slate-400 text-xs font-medium">Belum ada mata pelajaran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-add-subject" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-2xl p-0">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h4 class="font-bold text-slate-800">Tambah Mata Pelajaran</h4>
                <button class="uk-modal-close-default" type="button" uk-close></button>
                <button onclick="UIkit.modal('#modal-add-subject').hide()" class="hidden text-slate-400 hover:text-slate-700" type="button">
                    <span uk-icon="icon: close; ratio: 0.9"></span>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.subjects.store') }}" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Nama Mapel</label>
                    <input type="text" name="name" required placeholder="Contoh: Matematika"
                        class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Kode Mapel</label>
                    <input type="text" name="code" placeholder="Contoh: MTK"
                        class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="3" placeholder="Opsional"
                        class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
                <label class="inline-flex items-center space-x-2 text-xs font-semibold text-slate-600">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <span>Aktif</span>
                </label>
                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" onclick="UIkit.modal('#modal-add-subject').hide()"
                        class="px-4 py-2 border border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-50 text-xs transition">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition shadow-md shadow-indigo-100">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-edit-subject" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-2xl p-0">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h4 class="font-bold text-slate-800">Edit Mata Pelajaran</h4>
                <button class="uk-modal-close-default" type="button" uk-close></button>
                <button onclick="UIkit.modal('#modal-edit-subject').hide()" class="hidden text-slate-400 hover:text-slate-700" type="button">
                    <span uk-icon="icon: close; ratio: 0.9"></span>
                </button>
            </div>
            <form id="form-edit-subject" method="POST" class="p-6 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Nama Mapel</label>
                    <input type="text" id="edit-subject-name" name="name" required
                        class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Kode Mapel</label>
                    <input type="text" id="edit-subject-code" name="code"
                        class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Deskripsi</label>
                    <textarea id="edit-subject-description" name="description" rows="3"
                        class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
                <label class="inline-flex items-center space-x-2 text-xs font-semibold text-slate-600">
                    <input type="checkbox" id="edit-subject-active" name="is_active" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <span>Aktif</span>
                </label>
                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" onclick="UIkit.modal('#modal-edit-subject').hide()"
                        class="px-4 py-2 border border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-slate-50 text-xs transition">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition shadow-md shadow-indigo-100">Simpan Perubahan</button>
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
