<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">
                {{ __('Pengaturan Poin') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-100/30 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-[#EAFCEF] border-2 border-slate-950 text-emerald-800 px-4 py-3 rounded-xl text-xs font-black flex items-center space-x-2 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                    <span uk-icon="icon: check; ratio: 0.9"></span>
                    <span class="uppercase tracking-wider">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-[#FFEAEA] border-2 border-slate-950 text-rose-800 px-4 py-3 rounded-xl text-xs font-black space-y-1 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                    <ul class="list-disc pl-5 font-bold">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Info Banner -->
            <div class="bg-blue-50 border-2 border-slate-950 rounded-xl p-4 flex items-start space-x-3 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] text-blue-800">
                <span uk-icon="icon: info; ratio: 0.9" class="shrink-0 mt-0.5 text-blue-900"></span>
                <div class="text-xs font-bold uppercase tracking-wider">
                    Poin digunakan <span class="font-black text-slate-950">langsung</span> untuk belanja reward — tidak ada konversi. Siswa membelanjakan poin sebagaimana adanya.
                </div>
            </div>

            <form action="{{ route('admin.currency-settings.update') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Identitas Poin -->
                    <div class="bg-white rounded-3xl border-4 border-slate-950 p-6 space-y-5 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                        <div class="flex items-center space-x-3 pb-3 border-b-2 border-slate-950">
                            <div class="w-8 h-8 rounded-xl bg-indigo-50 border-2 border-slate-950 flex items-center justify-center shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                <span uk-icon="icon: tag; ratio: 0.8" class="text-slate-950"></span>
                            </div>
                            <h3 class="font-black text-sm text-slate-950 uppercase tracking-tight">Identitas & Tampilan Poin</h3>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nama Poin</label>
                            <input type="text" name="currency_name" id="input-name"
                                   value="{{ old('currency_name', $settings['currency_name']) }}"
                                   placeholder="Contoh: Poin, Bintang, Koin"
                                   oninput="updatePreview()"
                                   class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">Nama sebutan poin yang tampil ke siswa.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Simbol / Ikon</label>
                            <input type="text" name="currency_symbol" id="input-symbol"
                                   value="{{ old('currency_symbol', $settings['currency_symbol']) }}"
                                   placeholder="Contoh: ⭐, 🪙, P"
                                   oninput="updatePreview()"
                                   class="w-full border-2 border-slate-950 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-slate-950 focus:border-slate-950 bg-white font-bold">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">Emoji atau karakter pendek (maks 10 karakter).</p>
                        </div>

                    </div>

                    <!-- Live Preview -->
                    <div class="space-y-5">
                        <div class="bg-[#E4FF1A] border-4 border-slate-950 rounded-3xl p-6 text-slate-950 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                            <div class="text-xs font-black text-slate-900 uppercase tracking-wider mb-4">Preview Tampilan</div>

                            <!-- Saldo Preview -->
                            <div class="bg-white border-2 border-slate-950 rounded-xl p-4 mb-4 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] text-slate-950">
                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Saldo Poin Siswa</div>
                                <div class="flex items-center space-x-2">
                                    <span id="preview-symbol" class="text-2xl">{{ $settings['currency_symbol'] }}</span>
                                    <div>
                                        <span class="text-3xl font-black text-slate-950">250</span>
                                        <span id="preview-name" class="text-xs font-bold text-slate-650 uppercase tracking-wider ml-1">{{ $settings['currency_name'] }}</span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Reward Preview -->
                        <div class="bg-white rounded-3xl border-4 border-slate-950 p-5 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-3">Contoh di Halaman Reward</div>
                            <div class="flex items-center justify-between p-3 rounded-xl border-2 border-slate-950 bg-slate-50">
                                <div>
                                    <div class="text-xs font-black text-slate-950 uppercase tracking-tight">Alat Tulis Premium</div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Hadiah pilihan siswa</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-black text-indigo-650 text-xs flex items-center space-x-1 justify-end">
                                        <span id="reward-symbol">{{ $settings['currency_symbol'] }}</span>
                                        <span>500</span>
                                    </div>
                                    <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider" id="reward-name">{{ $settings['currency_name'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white text-xs font-black px-8 py-3 rounded-xl border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all uppercase tracking-wider flex items-center space-x-2">
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
