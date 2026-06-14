<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">Penarikan Dana</h2>
                <p class="text-xs text-slate-400 mt-0.5 font-medium">Ubah saldo poin Anda menjadi uang tunai</p>
            </div>
            <div class="flex items-center space-x-2">
                <div class="bg-emerald-50 border border-emerald-100 rounded-xl px-4 py-2 text-center">
                    <span class="text-[10px] text-emerald-500 font-bold block uppercase">Saldo Saat Ini</span>
                    <strong class="text-xl text-emerald-700 font-black">{{ auth()->user()->points }}</strong>
                    <span class="text-[10px] text-emerald-500 font-bold">Poin</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div id="flash-success" class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-xs font-semibold flex items-center space-x-2">
                    <span uk-icon="icon: check; ratio: 0.9"></span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div id="flash-error" class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-xs font-semibold flex items-center space-x-2">
                    <span uk-icon="icon: warning; ratio: 0.9"></span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                
                <!-- KIRI: FORM PENARIKAN -->
                <div class="md:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sticky top-20">
                        <h2 class="text-lg font-bold text-slate-800 mb-1">Ajukan Penarikan</h2>
                        <p class="text-xs text-slate-400 mb-5 font-medium">Masukkan jumlah poin yang ingin ditarik menjadi uang tunai.</p>

                        <form action="{{ route('toko.withdrawals.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">Jumlah Poin</label>
                                <div class="relative">
                                    <input type="number" name="points_amount" id="wd-points" required min="1" max="{{ auth()->user()->points }}" placeholder="Contoh: 500"
                                        class="w-full border border-slate-200 rounded-xl pl-4 pr-12 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 font-bold text-emerald-700"
                                        oninput="document.getElementById('wd-rupiah').innerText = 'Rp ' + (this.value * 1000).toLocaleString('id-ID')">
                                    <span class="absolute right-4 top-3.5 text-xs font-bold text-slate-400">Poin</span>
                                </div>
                                <p class="text-[10px] text-emerald-600 font-bold mt-1.5 flex items-center space-x-1">
                                    <span uk-icon="icon: info; ratio: 0.7"></span>
                                    <span>Akan menerima uang tunai sebesar: <strong id="wd-rupiah" class="text-emerald-700 text-xs">Rp 0</strong></span>
                                </p>
                            </div>
                            <div class="bg-amber-50 border border-amber-200 p-3 rounded-xl flex items-start space-x-2">
                                <svg class="w-4 h-4 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-[10px] text-amber-700 font-medium leading-tight">Pengajuan ini membutuhkan persetujuan Admin. Admin akan menyerahkan uang tunai kepada Anda secara langsung.</p>
                            </div>
                            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold py-3.5 rounded-xl shadow-sm shadow-emerald-200 transition flex items-center justify-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                <span>Ajukan Penarikan</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- KANAN: RIWAYAT PENARIKAN -->
                <div class="md:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                            <h3 class="text-sm font-bold text-slate-800">Riwayat Penarikan Dana</h3>
                            <span class="text-[10px] bg-slate-200 text-slate-600 px-2 py-0.5 rounded-md font-bold">{{ $withdrawals->count() }} Total</span>
                        </div>
                        <div class="divide-y divide-slate-100">
                            @forelse($withdrawals as $wd)
                                <div class="px-5 py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center hover:bg-slate-50 transition">
                                    <div class="mb-2 sm:mb-0">
                                        <p class="text-sm font-bold text-slate-800">Tarik Dana <span class="text-emerald-600">{{ $wd->points_amount }} Poin</span> <span class="text-xs text-slate-500 font-medium ml-1">(Rp {{ number_format($wd->points_amount * 1000, 0, ',', '.') }})</span></p>
                                        <p class="text-[10px] text-slate-400 mt-0.5 font-medium">{{ $wd->created_at->translatedFormat('d F Y - H:i') }} WIB</p>
                                        @if($wd->admin_notes)
                                            <div class="mt-2 text-xs text-slate-600 bg-slate-100 p-2.5 rounded-lg border border-slate-200 max-w-sm flex items-start space-x-2">
                                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <span>{{ $wd->admin_notes }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col items-end shrink-0">
                                        @if($wd->status === 'pending')
                                            <div class="flex items-center space-x-1.5 bg-amber-50 text-amber-700 border border-amber-200 px-3 py-1.5 rounded-xl">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse block"></span>
                                                <span class="text-[10px] font-bold uppercase tracking-wide">Menunggu</span>
                                            </div>
                                        @elseif($wd->status === 'approved')
                                            <div class="flex items-center space-x-1 bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1.5 rounded-xl">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                <span class="text-[10px] font-bold uppercase tracking-wide">Disetujui</span>
                                            </div>
                                        @else
                                            <div class="flex items-center space-x-1 bg-rose-50 text-rose-700 border border-rose-200 px-3 py-1.5 rounded-xl">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                <span class="text-[10px] font-bold uppercase tracking-wide">Ditolak</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="py-12 flex flex-col items-center justify-center text-slate-400">
                                    <svg class="w-12 h-12 text-slate-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p class="text-sm font-semibold">Belum ada riwayat penarikan</p>
                                </div>
                            @endempty
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
