<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">
                {{ __('Manajemen Siswa') }}
            </h2>
            <button onclick="UIkit.modal('#modal-add-user').show()" class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white text-xs font-black px-4 py-2.5 rounded-xl border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider flex items-center space-x-2">
                <span uk-icon="icon: plus; ratio: 0.8"></span>
                <span>Tambah Siswa</span>
            </button>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-100/30 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="bg-[#EAFCEF] border-2 border-slate-950 text-emerald-800 px-4 py-3 rounded-xl text-xs font-black flex items-center space-x-2 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                    <span uk-icon="icon: check; ratio: 0.9"></span>
                    <span>{{ session('success') }}</span>
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

            <!-- Filter Card -->
            <div class="bg-white rounded-3xl border-4 border-slate-950 p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <form action="{{ route('admin.students.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Pencarian</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="w-full pl-3 pr-10 py-2.5 text-xs rounded-xl border-2 border-slate-950 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white">
                            <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-950">
                                <span uk-icon="icon: search; ratio: 0.8"></span>
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Filter Kelas</label>
                        <select name="classroom_id" onchange="this.form.submit()" class="w-full py-2.5 text-xs rounded-xl border-2 border-slate-950 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <option value="">Semua Kelas</option>
                            @foreach($classrooms as $classroom)
                                <option value="{{ $classroom->id }}" {{ request('classroom_id') == $classroom->id ? 'selected' : '' }}>{{ $classroom->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end space-x-2">
                        <button type="submit" class="w-full md:w-auto bg-slate-950 hover:bg-[#E4FF1A] text-white hover:text-slate-950 text-xs font-black px-6 py-3 rounded-xl border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider">
                            Terapkan Filter
                        </button>
                        @if(request('search') || request('classroom_id'))
                            <a href="{{ route('admin.students.index') }}" class="text-xs font-black text-rose-600 hover:bg-[#FFEAEA] py-3 px-4 rounded-xl border-2 border-transparent hover:border-slate-950 transition-all uppercase tracking-wider">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Users Table Card -->
            <div class="bg-white rounded-3xl border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b-2 border-slate-950">
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider">Nama & Email</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider">Tingkat/Level</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider text-center">Poin Kebaikan</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-950 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-slate-950">
                            @forelse($users as $user)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-950 text-xs">{{ $user->name }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold mt-0.5 uppercase tracking-wider">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wider border-2 border-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] bg-indigo-50 text-indigo-800">
                                        {{ $user->current_level ?? 'Pemula' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-700">
                                    {{ $user->classroom->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border-2 border-slate-950
                                        {{ $user->is_active ? 'bg-[#EAFCEF] text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-xs font-black text-indigo-600">
                                    {{ $user->role->name === 'siswa' ? number_format($user->points) . ' Pts' : '-' }}
                                </td>
                                <td class="px-6 py-4 text-right space-x-1 whitespace-nowrap">
                                    <!-- Toggle Active -->
                                    <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs font-black p-1.5 rounded-lg border-2 border-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[0px_0px_0px_0px_rgba(15,23,42,1)] transition-all {{ $user->is_active ? 'bg-white text-slate-600 hover:bg-slate-100' : 'bg-[#E4FF1A] text-slate-950 hover:bg-slate-950 hover:text-white' }}" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <span uk-icon="icon: {{ $user->is_active ? 'ban' : 'check' }}; ratio: 0.8"></span>
                                        </button>
                                    </form>

                                    <!-- Reset Pass -->
                                    <button 
                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}"
                                        class="reset-password-btn text-xs font-black p-1.5 rounded-lg border-2 border-slate-950 bg-white text-amber-600 hover:bg-[#FFEAEA] shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[0px_0px_0px_0px_rgba(15,23,42,1)] transition-all" 
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
                                        class="edit-user-btn text-xs font-black p-1.5 rounded-lg border-2 border-slate-950 bg-white text-indigo-600 hover:bg-indigo-50 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[0px_0px_0px_0px_rgba(15,23,42,1)] transition-all" 
                                        title="Edit"
                                    >
                                        <span uk-icon="icon: file-edit; ratio: 0.8"></span>
                                    </button>

                                    <!-- Delete -->
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-black p-1.5 rounded-lg border-2 border-slate-950 bg-rose-500 text-white hover:bg-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[0px_0px_0px_0px_rgba(15,23,42,1)] transition-all" title="Hapus">
                                            <span uk-icon="icon: trash; ratio: 0.8"></span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-slate-400 text-xs font-bold uppercase">Data user tidak ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($users->hasPages())
                    <div class="px-6 py-4 border-t-2 border-slate-950 bg-slate-50">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- MODAL ADD USER -->
    <div id="modal-add-user" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body border-4 border-slate-950 rounded-3xl shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] p-6 bg-white">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-black text-slate-950 mb-4 uppercase tracking-tight border-b-2 border-slate-950 pb-2">Tambah Pengguna Baru</h2>
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Lengkap</label>
                    <input type="text" name="name" required class="w-full text-xs rounded-xl border-2 border-slate-950 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Alamat Email</label>
                    <input type="email" name="email" required class="w-full text-xs rounded-xl border-2 border-slate-950 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Password Awal</label>
                    <input type="password" name="password" required class="w-full text-xs rounded-xl border-2 border-slate-950 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white" placeholder="Minimal 8 karakter">
                </div>

                <div>
                    <input type="hidden" name="role_id" value="5">
                </div>

                <!-- Classroom Assignment -->
                <div id="add-classroom-field">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Kelas Akademik</label>
                    <select name="classroom_id" class="w-full text-xs rounded-xl border-2 border-slate-950 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Child Assignment (Only visible for Orang Tua) -->
                <div id="add-child-field" class="hidden">
                </div>

                <div class="flex justify-end space-x-2 pt-4 border-t-2 border-slate-950">
                    <button class="uk-modal-close bg-white hover:bg-slate-100 text-slate-950 text-xs font-black px-4 py-2 rounded-xl border-2 border-slate-950 transition-all uppercase tracking-wider" type="button">Batal</button>
                    <button class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white text-xs font-black px-5 py-2 rounded-xl border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT USER -->
    <div id="modal-edit-user" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body border-4 border-slate-950 rounded-3xl shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] p-6 bg-white">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-black text-slate-950 mb-4 uppercase tracking-tight border-b-2 border-slate-950 pb-2">Edit Pengguna</h2>
            <form id="edit-user-form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Lengkap</label>
                    <input type="text" name="name" id="edit-name" required class="w-full text-xs rounded-xl border-2 border-slate-950 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Alamat Email</label>
                    <input type="email" name="email" id="edit-email" required class="w-full text-xs rounded-xl border-2 border-slate-950 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white">
                </div>

                <div>
                    <input type="hidden" name="role_id" value="5">
                </div>

                <div id="edit-classroom-field">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Kelas Akademik</label>
                    <select name="classroom_id" id="edit-classroom" class="w-full text-xs rounded-xl border-2 border-slate-950 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="edit-child-field" class="hidden">
                </div>

                <div class="flex justify-end space-x-2 pt-4 border-t-2 border-slate-950">
                    <button class="uk-modal-close bg-white hover:bg-slate-100 text-slate-950 text-xs font-black px-4 py-2 rounded-xl border-2 border-slate-950 transition-all uppercase tracking-wider" type="button">Batal</button>
                    <button class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white text-xs font-black px-5 py-2 rounded-xl border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider" type="submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL RESET PASSWORD -->
    <div id="modal-reset-password" class="uk-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body border-4 border-slate-950 rounded-3xl shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] p-6 bg-white">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="text-lg font-black text-slate-950 mb-2 uppercase tracking-tight border-b-2 border-slate-950 pb-2">Reset Password</h2>
            <p class="text-xs text-slate-400 mb-4 font-bold uppercase tracking-wider">Ubah password untuk pengguna: <strong id="reset-user-name" class="text-slate-950"></strong></p>
            <form id="reset-password-form" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Password Baru</label>
                    <input type="password" name="password" required minlength="8" class="w-full text-xs rounded-xl border-2 border-slate-950 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white" placeholder="Minimal 8 karakter">
                </div>

                <div class="flex justify-end space-x-2 pt-4 border-t-2 border-slate-950">
                    <button class="uk-modal-close bg-white hover:bg-slate-100 text-slate-950 text-xs font-black px-4 py-2 rounded-xl border-2 border-slate-950 transition-all uppercase tracking-wider" type="button">Batal</button>
                    <button class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white text-xs font-black px-5 py-2 rounded-xl border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider" type="submit">Reset Password</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // Edit User trigger
            document.querySelectorAll('.edit-user-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const email = this.getAttribute('data-email');
                    const classroomId = this.getAttribute('data-classroom-id');

                    document.getElementById('edit-name').value = name;
                    document.getElementById('edit-email').value = email;
                    
                    const classroomSelector = document.getElementById('edit-classroom');
                    if(classroomSelector) classroomSelector.value = classroomId || '';

                    document.getElementById('edit-user-form').action = `/admin/users/${id}/update`;
                    
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
