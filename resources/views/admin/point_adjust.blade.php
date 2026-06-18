<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">
            {{ __('Penyesuaian Poin') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-100/30 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

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
                        <span class="font-black">TERJADI KESALAHAN:</span>
                    </div>
                    <ul class="list-disc pl-5 space-y-0.5 mt-1 font-bold">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Info Banner -->
            <div class="bg-blue-50 border-2 border-slate-950 rounded-xl p-4 flex items-start space-x-3 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] text-blue-800">
                <span uk-icon="icon: info; ratio: 0.9" class="shrink-0 mt-0.5 text-blue-900"></span>
                <div class="text-xs font-bold uppercase tracking-wide">
                    <span class="font-black">Catatan:</span> Gunakan fitur ini untuk memberikan reward tambahan (misal: juara kelas, prestasi ekskul) atau pengurangan poin secara manual kepada siswa.
                </div>
            </div>

            <div class="bg-white rounded-3xl border-4 border-slate-950 p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <form action="{{ route('admin.point-adjust.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Pilih Siswa</label>
                        <select name="user_id" id="searchable-student-select" required placeholder="Ketik nama siswa..." class="w-full text-xs">
                            <option value="">-- Ketik dan Pilih Siswa --</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('user_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }} ({{ $student->classroom->name ?? 'Tanpa Kelas' }}) — Saldo Poin: {{ $student->points }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 border-t-2 border-b-2 border-slate-950 py-5">
                        <!-- Aksi -->
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Aksi</label>
                            <div class="space-y-2">
                                <label class="flex items-center space-x-2 cursor-pointer font-bold text-slate-700">
                                    <input type="radio" name="action" value="add" {{ old('action', 'add') === 'add' ? 'checked' : '' }} class="text-slate-950 border-2 border-slate-950 focus:ring-slate-950">
                                    <span>Tambah Poin (+)</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer font-bold text-slate-700">
                                    <input type="radio" name="action" value="deduct" {{ old('action') === 'deduct' ? 'checked' : '' }} class="text-slate-950 border-2 border-slate-950 focus:ring-slate-950">
                                    <span>Kurangi Poin (-)</span>
                                </label>
                            </div>
                        </div>

                        <!-- Jumlah -->
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Jumlah</label>
                            <input type="number" name="amount" min="1" value="{{ old('amount') }}" required
                                   placeholder="Contoh: 50"
                                   class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Catatan / Alasan <span class="text-rose-500">*</span></label>
                        <input type="text" name="notes" value="{{ old('notes') }}" required
                               placeholder="Contoh: Juara 1 Lomba Pidato Tingkat Provinsi"
                               class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">Wajib diisi sebagai bukti audit dan riwayat bagi siswa.</p>
                    </div>

                    <div class="pt-3 flex justify-end">
                        <button type="submit"
                                class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white text-xs font-black px-8 py-3 rounded-xl border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider flex items-center space-x-2"
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
            border-radius: 0.75rem !important;
            border: 2px solid #0f172a !important;
            padding: 0.65rem 0.75rem !important;
            font-size: 0.75rem !important;
            box-shadow: none !important;
            background-color: white !important;
            font-weight: 700 !important;
            color: #0f172a !important;
        }
        .ts-control.focus {
            border-color: #0f172a !important;
            box-shadow: 0 0 0 2px #0f172a !important;
        }
        .ts-dropdown {
            border-radius: 0.75rem !important;
            border: 2px solid #0f172a !important;
            font-size: 0.75rem !important;
            box-shadow: 4px 4px 0px 0px rgba(15,23,42,1) !important;
            background-color: white !important;
            font-weight: 700 !important;
            color: #0f172a !important;
            margin-top: 5px !important;
        }
        .ts-dropdown .option {
            padding: 0.5rem 0.75rem !important;
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
