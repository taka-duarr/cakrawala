<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">
            {{ __('Penyesuaian Poin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

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
                        <span class="font-bold">Terjadi kesalahan:</span>
                    </div>
                    <ul class="list-disc pl-5 space-y-0.5 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Info Banner -->
            <div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-4 flex items-start space-x-3">
                <span uk-icon="icon: info; ratio: 0.9" class="text-indigo-600 shrink-0 mt-0.5"></span>
                <div class="text-xs text-indigo-700">
                    <span class="font-bold">Catatan:</span> Gunakan fitur ini untuk memberikan reward tambahan (misal: juara kelas, prestasi ekskul) atau pengurangan poin secara manual kepada siswa.
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <form action="{{ route('admin.point-adjust.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Pilih Siswa</label>
                        <select name="user_id" id="searchable-student-select" required placeholder="Ketik nama siswa..." class="w-full text-xs">
                            <option value="">-- Ketik dan Pilih Siswa --</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('user_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }} ({{ $student->classroom->name ?? 'Tanpa Kelas' }}) — Saldo Poin: {{ $student->points }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 border-t border-b border-slate-50 py-5">
                        <!-- Aksi -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1.5">Aksi</label>
                            <div class="space-y-2">
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="action" value="add" {{ old('action', 'add') === 'add' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                                    <span class="text-xs font-semibold text-slate-700">Tambah Poin (+)</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="action" value="deduct" {{ old('action') === 'deduct' ? 'checked' : '' }} class="text-rose-600 focus:ring-rose-500">
                                    <span class="text-xs font-semibold text-slate-700">Kurangi Poin (-)</span>
                                </label>
                            </div>
                        </div>

                        <!-- Jumlah -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1.5">Jumlah</label>
                            <input type="number" name="amount" min="1" value="{{ old('amount') }}" required
                                   placeholder="Contoh: 50"
                                   class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Catatan / Alasan <span class="text-rose-500">*</span></label>
                        <input type="text" name="notes" value="{{ old('notes') }}" required
                               placeholder="Contoh: Juara 1 Lomba Pidato Tingkat Provinsi"
                               class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="text-[10px] text-slate-400 mt-1">Wajib diisi sebagai bukti audit dan riwayat bagi siswa.</p>
                    </div>

                    <div class="pt-3 flex justify-end">
                        <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-8 py-3 rounded-xl shadow-md shadow-indigo-200 transition flex items-center space-x-2"
                                onclick="return confirm('Apakah Anda yakin ingin melakukan penyesuaian poin ini? Tindakan ini akan tercatat di sistem.')">
                            <span uk-icon="icon: check; ratio: 0.8"></span>
                            <span>Simpan Penyesuaian</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- TomSelect Styles & Script -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    <style>
        .ts-control {
            border-radius: 0.75rem;
            border-color: #e2e8f0;
            padding: 0.6rem 0.75rem;
            font-size: 0.75rem;
            box-shadow: none;
        }
        .ts-control.focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 1px #6366f1;
        }
        .ts-dropdown {
            border-radius: 0.75rem;
            font-size: 0.75rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }
        .ts-dropdown .option {
            padding: 0.5rem 0.75rem;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect("#searchable-student-select",{
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
        });
    </script>
</x-app-layout>
