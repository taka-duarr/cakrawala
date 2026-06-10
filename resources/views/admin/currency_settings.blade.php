<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                {{ __('Pengaturan Poin') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-xs font-semibold flex items-center space-x-2">
                    <span uk-icon="icon: check; ratio: 0.9"></span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-xs font-semibold space-y-1">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Info Banner -->
            <div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-4 flex items-start space-x-3">
                <span uk-icon="icon: info; ratio: 0.9" class="text-indigo-500 shrink-0 mt-0.5"></span>
                <div class="text-xs text-indigo-700">
                    Poin digunakan <span class="font-bold">langsung</span> untuk belanja reward — tidak ada konversi. Siswa membelanjakan poin sebagaimana adanya.
                </div>
            </div>

            <form action="{{ route('admin.currency-settings.update') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Identitas Poin -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-5">
                        <div class="flex items-center space-x-3 pb-3 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-xl bg-indigo-50 flex items-center justify-center">
                                <span uk-icon="icon: tag; ratio: 0.8" class="text-indigo-600"></span>
                            </div>
                            <h3 class="font-bold text-sm text-slate-800">Identitas & Tampilan Poin</h3>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1.5">Nama Poin</label>
                            <input type="text" name="currency_name" id="input-name"
                                   value="{{ old('currency_name', $settings['currency_name']) }}"
                                   placeholder="Contoh: Poin, Bintang, Koin"
                                   oninput="updatePreview()"
                                   class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="text-[10px] text-slate-400 mt-1">Nama sebutan poin yang tampil ke siswa.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1.5">Simbol / Ikon</label>
                            <input type="text" name="currency_symbol" id="input-symbol"
                                   value="{{ old('currency_symbol', $settings['currency_symbol']) }}"
                                   placeholder="Contoh: ⭐, 🪙, P"
                                   oninput="updatePreview()"
                                   class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="text-[10px] text-slate-400 mt-1">Emoji atau karakter pendek (maks 10 karakter).</p>
                        </div>

                    </div>

                    <!-- Live Preview -->
                    <div class="space-y-5">
                        <div class="bg-gradient-to-br from-indigo-600 to-violet-600 rounded-2xl p-6 text-white">
                            <div class="text-xs font-bold text-indigo-200 uppercase tracking-wider mb-4">Preview Tampilan</div>

                            <!-- Saldo Preview -->
                            <div class="bg-white/10 rounded-xl p-4 mb-4">
                                <div class="text-[10px] text-indigo-200 font-semibold uppercase tracking-wider mb-1">Saldo Poin Siswa</div>
                                <div class="flex items-center space-x-2">
                                    <span id="preview-symbol" class="text-2xl">{{ $settings['currency_symbol'] }}</span>
                                    <div>
                                        <span class="text-3xl font-black">250</span>
                                        <span id="preview-name" class="text-sm font-semibold text-indigo-200 ml-1">{{ $settings['currency_name'] }}</span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Reward Preview -->
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-3">Contoh di Halaman Reward</div>
                            <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 bg-slate-50/50">
                                <div>
                                    <div class="text-xs font-bold text-slate-800">Alat Tulis Premium</div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">Hadiah pilihan siswa</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-black text-indigo-600 text-sm flex items-center space-x-1">
                                        <span id="reward-symbol">{{ $settings['currency_symbol'] }}</span>
                                        <span>500</span>
                                    </div>
                                    <div class="text-[9px] text-slate-400" id="reward-name">{{ $settings['currency_name'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-8 py-3 rounded-xl shadow-md shadow-indigo-200 transition flex items-center space-x-2">
                        <span uk-icon="icon: check; ratio: 0.8"></span>
                        <span>Simpan Pengaturan</span>
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        function updatePreview() {
            const name       = document.getElementById('input-name').value || 'Poin';
            const symbol     = document.getElementById('input-symbol').value || '⭐';

            document.getElementById('preview-name').textContent = name;
            document.querySelectorAll('#preview-symbol, #reward-symbol').forEach(el => el.textContent = symbol);
            document.getElementById('reward-name').textContent = name;
        }
    </script>
</x-app-layout>
