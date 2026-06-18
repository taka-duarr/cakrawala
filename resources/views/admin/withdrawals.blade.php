<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-slate-950 leading-tight tracking-tight">Pencairan Dana Toko</h2>
        <p class="text-xs text-slate-500 mt-0.5 font-bold">Manajemen persetujuan dan riwayat penarikan poin oleh toko</p>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-[#EAFCEF] border-2 border-slate-950 text-slate-950 px-4 py-3 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] text-xs font-bold flex items-center space-x-2 mb-6">
                    <span uk-icon="icon: check; ratio: 0.9"></span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-[#FFEAEA] border-2 border-slate-950 text-slate-950 px-4 py-3 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] text-xs font-bold flex items-center space-x-2 mb-6">
                    <span uk-icon="icon: warning; ratio: 0.9"></span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Daftar Pengajuan Pending -->
            <div class="bg-white border-2 border-slate-950 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] overflow-hidden mb-8">
                <div class="bg-[#E4FF1A]/10 px-6 py-4 border-b-2 border-slate-950 flex items-center justify-between flex-wrap gap-2">
                    <h3 class="font-black text-slate-950 tracking-tight text-base">Menunggu Persetujuan</h3>
                    <span class="bg-[#FFF6EA] text-slate-950 border-2 border-slate-950 px-2.5 py-1 text-xs font-black shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">{{ $withdrawals->where('status', 'pending')->count() }} Pengajuan</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-900 border-collapse">
                        <thead class="bg-[#E4FF1A]/10 text-xs uppercase text-slate-950 font-extrabold border-b-2 border-slate-950">
                            <tr>
                                <th class="px-6 py-4">Toko</th>
                                <th class="px-6 py-4">Tanggal Pengajuan</th>
                                <th class="px-6 py-4">Jumlah Penarikan</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-slate-950">
                            @forelse($withdrawals->where('status', 'pending') as $wd)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-black text-slate-950 text-xs">{{ $wd->shop->name }}</div>
                                        <div class="text-[10px] text-slate-500 font-bold">{{ $wd->shop->email }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-xs font-bold text-slate-950">{{ $wd->created_at->format('d M Y') }}</div>
                                        <div class="text-[10px] text-slate-500 font-bold">{{ $wd->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <span class="px-2 py-0.5 bg-[#E4FF1A] border-2 border-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] text-xs font-black text-slate-950">
                                                {{ $wd->points_amount }} Pts
                                            </span>
                                            <span class="text-xs text-slate-950 font-bold bg-[#EAFCEF] border border-slate-950 px-2 py-0.5 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]">
                                                Rp {{ number_format($wd->points_amount * 1000, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center items-center space-x-3">
                                            <!-- Approve -->
                                            <button uk-toggle="target: #modal-approve-{{ $wd->id }}" class="bg-[#EAFCEF] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] hover:shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] px-3 py-1.5 text-xs font-black transition-all flex items-center space-x-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                <span>Setujui</span>
                                            </button>
                                            <!-- Reject -->
                                            <button uk-toggle="target: #modal-reject-{{ $wd->id }}" class="bg-[#FFEAEA] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] hover:shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] px-3 py-1.5 text-xs font-black transition-all flex items-center space-x-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                <span>Tolak</span>
                                            </button>
                                        </div>

                                        <!-- Modal Approve -->
                                        <div id="modal-approve-{{ $wd->id }}" uk-modal>
                                            <div class="uk-modal-dialog uk-modal-body border-4 border-slate-950 p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] bg-white max-w-md rounded-none relative">
                                                <button class="uk-modal-close-default text-slate-950 hover:text-rose-600 font-bold border-2 border-slate-950 p-1 bg-white shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-0.5 active:translate-y-0.5 active:shadow-none" type="button" uk-close></button>
                                                <div class="flex flex-col items-center text-center mb-5 mt-2">
                                                    <div class="w-12 h-12 bg-[#EAFCEF] border-2 border-slate-950 rounded-none flex items-center justify-center mb-3 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                                        <svg class="w-6 h-6 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    </div>
                                                    <h2 class="text-lg font-black text-slate-950 tracking-tight">Setujui Penarikan</h2>
                                                    <div class="mt-2 border-2 border-slate-950 bg-[#EAFCEF]/40 p-3 text-xs font-bold text-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                                        Pastikan Anda telah menyerahkan uang tunai sebesar <strong class="text-slate-950 text-sm block mt-1 bg-[#EAFCEF] border border-slate-950 p-1 font-black">Rp {{ number_format($wd->points_amount * 1000, 0, ',', '.') }}</strong> kepada <strong class="text-slate-950 block mt-0.5">{{ $wd->shop->name }}</strong>.
                                                    </div>
                                                </div>

                                                <form action="{{ route('admin.withdrawals.approve', $wd->id) }}" method="POST">
                                                    @csrf
                                                    <div class="mb-4">
                                                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Catatan (Opsional)</label>
                                                        <textarea name="admin_notes" rows="2" class="w-full border-2 border-slate-950 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-950 focus:border-slate-950" placeholder="Misal: Sudah ditransfer ke rekening X"></textarea>
                                                    </div>
                                                    <div class="flex justify-end space-x-2 pt-2">
                                                        <button class="uk-modal-close px-4 py-2 border-2 border-slate-950 hover:bg-slate-100 text-slate-950 text-xs font-bold transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]" type="button">Batal</button>
                                                        <button class="bg-[#EAFCEF] hover:bg-[#d5fad5] text-slate-950 border-2 border-slate-950 px-5 py-2 text-xs font-black shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] hover:shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all" type="submit">Ya, Setujui</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Modal Reject -->
                                        <div id="modal-reject-{{ $wd->id }}" uk-modal>
                                            <div class="uk-modal-dialog uk-modal-body border-4 border-slate-950 p-6 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] bg-white max-w-md rounded-none relative">
                                                <button class="uk-modal-close-default text-slate-950 hover:text-rose-600 font-bold border-2 border-slate-950 p-1 bg-white shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-0.5 active:translate-y-0.5 active:shadow-none" type="button" uk-close></button>
                                                <div class="flex flex-col items-center text-center mb-5 mt-2">
                                                    <div class="w-12 h-12 bg-[#FFEAEA] border-2 border-slate-950 rounded-none flex items-center justify-center mb-3 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                                        <svg class="w-6 h-6 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                    </div>
                                                    <h2 class="text-lg font-black text-slate-950 tracking-tight">Tolak Penarikan</h2>
                                                    <div class="mt-2 border-2 border-slate-950 bg-[#FFEAEA]/40 p-3 text-xs font-bold text-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                                        Poin sebesar <strong class="text-slate-950 font-black my-1 bg-[#FFEAEA] border border-slate-950 p-1 inline-block">{{ $wd->points_amount }} Pts</strong> akan dikembalikan ke saldo <strong class="text-slate-950 block mt-0.5">{{ $wd->shop->name }}</strong>.
                                                    </div>
                                                </div>

                                                <form action="{{ route('admin.withdrawals.reject', $wd->id) }}" method="POST">
                                                    @csrf
                                                    <div class="mb-4">
                                                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Alasan Penolakan <span class="text-rose-500">*</span></label>
                                                        <textarea name="admin_notes" required rows="2" class="w-full border-2 border-slate-950 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-950 focus:border-slate-950" placeholder="Misal: Nomor rekening tidak valid"></textarea>
                                                    </div>
                                                    <div class="flex justify-end space-x-2 pt-2">
                                                        <button class="uk-modal-close px-4 py-2 border-2 border-slate-950 hover:bg-slate-100 text-slate-950 text-xs font-bold transition shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)]" type="button">Batal</button>
                                                        <button class="bg-[#FFEAEA] hover:bg-[#ffdadc] text-rose-700 border-2 border-slate-950 px-5 py-2 text-xs font-black shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] hover:shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all" type="submit">Tolak Penarikan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-500 font-bold">
                                        Tidak ada pengajuan baru
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Riwayat Penarikan Selesai/Ditolak -->
            <div class="bg-white border-2 border-slate-950 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b-2 border-slate-950">
                    <h3 class="font-black text-slate-950 tracking-tight">Riwayat Penarikan Selesai & Ditolak</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-900 border-collapse">
                        <thead class="bg-[#E4FF1A]/10 text-xs uppercase text-slate-950 font-extrabold border-b-2 border-slate-950">
                            <tr>
                                <th class="px-6 py-4">Toko</th>
                                <th class="px-6 py-4">Tanggal Proses</th>
                                <th class="px-6 py-4">Jumlah Poin</th>
                                <th class="px-6 py-4">Status & Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-slate-950">
                            @forelse($withdrawals->whereIn('status', ['approved', 'rejected'])->take(20) as $wd)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-black text-slate-950 text-xs">{{ $wd->shop->name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-xs font-bold text-slate-950">{{ $wd->updated_at->format('d M Y') }}</div>
                                        <div class="text-[10px] text-slate-500 font-bold">{{ $wd->updated_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-0.5 bg-[#E4FF1A] border-2 border-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] text-xs font-black text-slate-950">
                                            {{ $wd->points_amount }} Pts
                                        </span>
                                        <div class="text-[10px] text-slate-950 font-bold bg-[#EAFCEF] border border-slate-950 px-2 py-0.5 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] mt-1.5 inline-block">
                                            Rp {{ number_format($wd->points_amount * 1000, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($wd->status === 'approved')
                                            <div class="inline-flex items-center space-x-1.5 px-2.5 py-1 bg-[#EAFCEF] text-slate-950 border-2 border-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] text-[10px] font-black">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                <span class="text-[10px] font-black uppercase">Disetujui</span>
                                            </div>
                                        @else
                                            <div class="inline-flex items-center space-x-1.5 px-2.5 py-1 bg-[#FFEAEA] text-slate-950 border-2 border-slate-950 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] text-[10px] font-black">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                <span class="text-[10px] font-black uppercase">Ditolak</span>
                                            </div>
                                        @endif
                                        @if($wd->admin_notes)
                                            <div class="text-xs text-slate-950 mt-2 bg-slate-50 border-2 border-slate-950 p-2 shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] inline-block break-words max-w-xs font-bold">
                                                {{ $wd->admin_notes }}
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-xs text-slate-500 font-bold">
                                        Belum ada riwayat penarikan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
