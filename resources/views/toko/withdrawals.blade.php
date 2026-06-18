<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">Penarikan Dana</h2>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Ubah saldo poin Anda menjadi uang tunai</p>
            </div>
            <div class="flex items-center space-x-2">
                <div class="bg-white border-2 border-slate-950 rounded-xl px-4 py-2.5 text-center shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                    <span class="text-[9px] text-slate-400 font-black block uppercase tracking-wider">Saldo Saat Ini</span>
                    <strong class="text-xl text-slate-950 font-black block mt-0.5">{{ number_format(auth()->user()->points) }} Pts</strong>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-100/40 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div id="flash-success" class="bg-[#EAFCEF] border-2 border-slate-950 text-emerald-800 px-4 py-3 rounded-xl text-xs font-black flex items-center space-x-2 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                    <span uk-icon="icon: check; ratio: 0.9"></span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div id="flash-error" class="bg-[#FFEAEA] border-2 border-slate-950 text-rose-800 px-4 py-3 rounded-xl text-xs font-black flex items-center space-x-2 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)]">
                    <span uk-icon="icon: warning; ratio: 0.9"></span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                
                <!-- KIRI: FORM PENARIKAN -->
                <div class="md:col-span-1">
                    <div class="bg-white rounded-3xl border-4 border-slate-950 p-6 sticky top-20 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                        <h2 class="text-lg font-black text-slate-950 uppercase tracking-tight mb-1">Ajukan Penarikan</h2>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-5">Masukkan jumlah poin yang ingin ditarik menjadi uang tunai.</p>

                        <form action="{{ route('toko.withdrawals.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-black text-slate-950 mb-1.5 uppercase tracking-wider">Jumlah Poin</label>
                                <div class="relative">
                                    <input type="number" name="points_amount" id="wd-points" required min="1" max="{{ auth()->user()->points }}" placeholder="Contoh: 500"
                                        class="w-full border-2 border-slate-950 rounded-xl pl-4 pr-16 py-3 text-xs font-black focus:outline-none focus:ring-0 focus:border-slate-950 text-slate-950"
                                        oninput="document.getElementById('wd-rupiah').innerText = 'Rp ' + (this.value * 1000).toLocaleString('id-ID')">
                                    <span class="absolute right-4 top-3.5 text-xs font-black text-slate-950 uppercase tracking-wider">Poin</span>
                                </div>
                                <p class="text-[9px] text-slate-500 font-bold mt-1.5 flex items-center space-x-1 uppercase tracking-wider">
                                    <span>Uang tunai diterima: <strong id="wd-rupiah" class="text-emerald-700 font-black bg-[#EAFCEF] border border-slate-950 px-1.5 py-0.5 rounded shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] ml-1">Rp 0</strong></span>
                                </p>
                            </div>
                            <div class="bg-[#E4FF1A]/10 border-2 border-slate-950 p-3.5 rounded-xl flex items-start space-x-2 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                <span uk-icon="icon: info; ratio: 0.8" class="text-slate-950 shrink-0 mt-0.5"></span>
                                <p class="text-[9px] text-slate-950 font-bold uppercase tracking-wider leading-relaxed">Pengajuan ini membutuhkan persetujuan Admin. Admin akan menyerahkan uang tunai kepada Anda secara langsung.</p>
                            </div>
                            <button type="submit" class="w-full bg-[#E4FF1A] hover:bg-slate-950 text-slate-950 hover:text-white border-2 border-slate-950 text-xs font-black py-4 rounded-xl shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition flex items-center justify-center space-x-2 uppercase tracking-wider">
                                <span uk-icon="icon: check; ratio: 0.85"></span>
                                <span>Ajukan Penarikan</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- KANAN: RIWAYAT PENARIKAN -->
                <div class="md:col-span-2">
                    <div class="bg-white rounded-3xl border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                        <div class="p-5 border-b-4 border-slate-950 flex items-center justify-between bg-[#E4FF1A]/10">
                            <h3 class="text-sm font-black text-slate-950 uppercase tracking-tight">Riwayat Penarikan Dana</h3>
                            <span class="bg-white border-2 border-slate-950 text-slate-950 text-[10px] font-black px-2 py-0.5 rounded shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider">{{ $withdrawals->count() }} Total</span>
                        </div>
                        <div class="divide-y divide-slate-950 bg-white">
                            @forelse($withdrawals as $wd)
                                <div class="px-5 py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center hover:bg-slate-50 transition border-b border-slate-950/20">
                                    <div class="mb-2 sm:mb-0">
                                        <p class="text-sm font-black text-slate-950 uppercase tracking-tight">Tarik Dana <span class="bg-[#EAFCEF] border border-slate-950 text-emerald-800 text-[10px] font-black px-2 py-0.5 rounded shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider ml-1">{{ $wd->points_amount }} Pts</span> <span class="text-xs text-slate-500 font-bold uppercase tracking-wider ml-1.5">(Rp {{ number_format($wd->points_amount * 1000, 0, ',', '.') }})</span></p>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-1">{{ $wd->created_at->translatedFormat('d F Y - H:i') }} WIB</p>
                                        @if($wd->admin_notes)
                                            <div class="mt-2.5 text-[10px] text-slate-950 font-bold bg-slate-50 p-2.5 rounded-lg border-2 border-slate-950 max-w-sm flex items-start space-x-2 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] uppercase tracking-wider">
                                                <span uk-icon="icon: comments; ratio: 0.75" class="text-slate-400 shrink-0 mt-0.5"></span>
                                                <span>{{ $wd->admin_notes }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col items-end shrink-0">
                                        @if($wd->status === 'pending')
                                            <div class="flex items-center space-x-1.5 bg-amber-50 text-slate-950 border-2 border-slate-950 px-3 py-1.5 rounded-xl shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse block"></span>
                                                <span class="text-[9px] font-black uppercase tracking-wider">Menunggu</span>
                                            </div>
                                        @elseif($wd->status === 'approved')
                                            <div class="flex items-center space-x-1.5 bg-[#EAFCEF] text-emerald-800 border-2 border-slate-950 px-3 py-1.5 rounded-xl shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                                <span uk-icon="icon: check; ratio: 0.75"></span>
                                                <span class="text-[9px] font-black uppercase tracking-wider">Disetujui</span>
                                            </div>
                                        @else
                                            <div class="flex items-center space-x-1.5 bg-[#FFEAEA] text-rose-800 border-2 border-slate-950 px-3 py-1.5 rounded-xl shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                                <span uk-icon="icon: close; ratio: 0.75"></span>
                                                <span class="text-[9px] font-black uppercase tracking-wider">Ditolak</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="py-16 flex flex-col items-center justify-center text-slate-400 space-y-2.5">
                                    <span uk-icon="icon: history; ratio: 1.5"></span>
                                    <p class="text-xs font-bold uppercase tracking-wider">Belum ada riwayat penarikan</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
