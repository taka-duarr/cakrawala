<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                {{ __('Manajemen Guru & Wali Kelas') }}
            </h2>
            <button onclick="UIkit.modal('#modal-add-user').show()" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md shadow-indigo-100 transition flex items-center space-x-2">
                <span uk-icon="icon: plus; ratio: 0.8"></span>
                <span>Tambah Guru</span>
            </button>
        </div>
    </x-slot>

    <div class="py-12">
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
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Filter Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <form action="{{ route('admin.teachers.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Pencarian</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="w-full pl-3 pr-10 py-2 text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                                <span uk-icon="icon: search; ratio: 0.8"></span>
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Filter Peran (Role)</label>
                        <select name="role_id" onchange="this.form.submit()" class="w-full py-2 text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Peran</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full md:w-auto bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold px-6 py-2.5 rounded-xl transition">
                            Terapkan Filter
                        </button>
                        @if(request('search') || request('role_id'))
                            <a href="{{ route('admin.teachers.index') }}" class="ml-2 text-xs font-semibold text-rose-600 hover:text-rose-700 py-2.5 px-3">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Users Table Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100/80">
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nama & Email</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Peran (Role)</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kelas (Wali)</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/70">
                            @forelse($users as $user)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800 text-xs">{{ $user->name }}</div>
                                    <div class="text-[10px] text-slate-400 font-semibold mt-0.5">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider
                                        @if($user->role->name === 'admin') bg-rose-50 text-rose-700 border border-rose-100
                                        @elseif($user->role->name === 'guru') bg-indigo-50 text-indigo-700 border border-indigo-100
                                        @elseif($user->role->name === 'walikelas') bg-amber-50 text-amber-700 border border-amber-100
                                        @elseif($user->role->name === 'orangtua') bg-purple-50 text-purple-700 border border-purple-100
                                        @else bg-emerald-50 text-emerald-700 border border-emerald-100 @endif">
                                        {{ $user->role->display_name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold text-slate-600">
                                    {{ $user->classroom->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $user->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-1">
                                    <!-- Toggle Active -->
                                    <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs font-bold px-2 py-1 rounded-lg {{ $user->is_active ? 'text-slate-500 hover:bg-slate-100' : 'text-emerald-600 hover:bg-emerald-50' }}" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <span uk-icon="icon: {{ $user->is_active ? 'ban' : 'check' }}; ratio: 0.8"></span>
                                        </button>
                                    </form>

                                    <!-- Reset Pass -->
                                    <button 
                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}"
                                        class="reset-password-btn text-xs font-bold text-amber-600 hover:bg-amber-50 px-2 py-1 rounded-lg" 
                                        title="Reset Password"
                                    >
                                        <span uk-icon="icon: lock; ratio: 0.8"></span>
                                    </button>

                                    <!-- Edit -->
                                    <button 
                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}"
                                        data-email="{{ $user->email }}"
                                        data-role-id="{{ $user->role_id }}"
                                        data-classroom-id="{{ $user->classroom_id ?? '' }}"
                                        data-child-id="{{ $user->children->first()->id ?? '' }}"
                                        class="edit-user-btn text-xs font-bold text-indigo-600 hover:bg-indigo-50 px-2 py-1 rounded-lg" 
                                        title="Edit"
                                    >
                                        <span uk-icon="icon: file-edit; ratio: 0.8"></span>
                                    </button>

                                    <!-- Delete -->
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-bold text-rose-600 hover:bg-rose-50 px-2 py-1 rounded-lg" title="Hapus">
                                            <span uk-icon="icon: trash; ratio: 0.8"></span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-12 text-slate-400 text-xs font-medium">Data user tidak ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($users->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- MODAL ADD USER -->
    <div id="modal-add-user" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-2xl p-6">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-bold text-slate-800 mb-4">Tambah Pengguna Baru</h2>
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Alamat Email</label>
                    <input type="email" name="email" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Password Awal</label>
                    <input type="password" name="password" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Minimal 8 karakter">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Peran (Role)</label>
                    <select name="role_id" id="add-role-selector" onchange="toggleFormFields('add')" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" data-name="{{ $role->name }}">{{ $role->display_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Classroom Assignment (Only visible for Student & Wali Kelas) -->
                <div id="add-classroom-field" class="hidden">
                    <label class="block text-xs font-bold text-slate-500 mb-1">Kelas Akademik</label>
                    <select name="classroom_id" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Child Assignment (Only visible for Orang Tua) -->
                <div id="add-child-field" class="hidden">
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <button class="uk-modal-close bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-4 py-2 rounded-xl transition" type="button">Batal</button>
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-5 py-2 rounded-xl transition shadow-md shadow-indigo-100" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT USER -->
    <div id="modal-edit-user" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-2xl p-6">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-bold text-slate-800 mb-4">Edit Pengguna</h2>
            <form id="edit-user-form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" id="edit-name" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Alamat Email</label>
                    <input type="email" name="email" id="edit-email" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Peran (Role)</label>
                    <select name="role_id" id="edit-role-selector" onchange="toggleFormFields('edit')" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" data-name="{{ $role->name }}">{{ $role->display_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="edit-classroom-field" class="hidden">
                    <label class="block text-xs font-bold text-slate-500 mb-1">Kelas Akademik</label>
                    <select name="classroom_id" id="edit-classroom" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="edit-child-field" class="hidden">
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <button class="uk-modal-close bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-4 py-2 rounded-xl transition" type="button">Batal</button>
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-5 py-2 rounded-xl transition shadow-md shadow-indigo-100" type="submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL RESET PASSWORD -->
    <div id="modal-reset-password" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body rounded-2xl p-6">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-bold text-slate-800 mb-2">Reset Password</h2>
            <p class="text-xs text-slate-400 mb-4 font-medium">Ubah password untuk pengguna: <strong id="reset-user-name" class="text-slate-700"></strong></p>
            <form id="reset-password-form" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Password Baru</label>
                    <input type="password" name="password" required minlength="8" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Minimal 8 karakter">
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <button class="uk-modal-close bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-4 py-2 rounded-xl transition" type="button">Batal</button>
                    <button class="bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold px-5 py-2 rounded-xl transition shadow-md shadow-amber-100" type="submit">Reset Password</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleFormFields(prefix) {
            const selector = document.getElementById(`${prefix}-role-selector`);
            const selectedOption = selector.options[selector.selectedIndex];
            const roleName = selectedOption.getAttribute('data-name');

            const classroomField = document.getElementById(`${prefix}-classroom-field`);
            const childField = document.getElementById(`${prefix}-child-field`);

            if (roleName === 'siswa' || roleName === 'walikelas') {
                classroomField.classList.remove('hidden');
                childField.classList.add('hidden');
            } else if (roleName === 'orangtua') {
                classroomField.classList.add('hidden');
                childField.classList.remove('hidden');
            } else {
                classroomField.classList.add('hidden');
                childField.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            toggleFormFields('add');

            // Edit User trigger
            document.querySelectorAll('.edit-user-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const email = this.getAttribute('data-email');
                    const roleId = this.getAttribute('data-role-id');
                    const classroomId = this.getAttribute('data-classroom-id');
                    const childId = this.getAttribute('data-child-id');

                    document.getElementById('edit-name').value = name;
                    document.getElementById('edit-email').value = email;
                    
                    const roleSelector = document.getElementById('edit-role-selector');
                    roleSelector.value = roleId;
                    
                    const classroomSelector = document.getElementById('edit-classroom');
                    classroomSelector.value = classroomId || '';

                    const childSelector = document.getElementById('edit-child');
                    childSelector.value = childId || '';

                    document.getElementById('edit-user-form').action = `/admin/users/${id}/update`;
                    
                    toggleFormFields('edit');
                    UIkit.modal('#modal-edit-user').show();
                });
            });

            // Reset Password trigger
            document.querySelectorAll('.reset-password-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');

                    document.getElementById('reset-user-name').innerText = name;
                    document.getElementById('reset-password-form').action = `/admin/users/${id}/reset-password`;
                    UIkit.modal('#modal-reset-password').show();
                });
            });
        });
    </script>
</x-app-layout>
