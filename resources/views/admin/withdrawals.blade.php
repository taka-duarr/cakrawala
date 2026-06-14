<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">Pencairan Dana Toko</h2>
        <p class="text-xs text-slate-400 mt-0.5 font-medium">Manajemen persetujuan dan riwayat penarikan poin oleh toko</p>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-xs font-semibold flex items-center space-x-2">
                    <span uk-icon="icon: check; ratio: 0.9"></span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-xs font-semibold flex items-center space-x-2">
                    <span uk-icon="icon: warning; ratio: 0.9"></span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Daftar Pengajuan Pending -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800">Menunggu Persetujuan</h3>
                    <span class="bg-amber-100 text-amber-700 px-2.5 py-1 rounded-lg text-xs font-bold">{{ $withdrawals->where('status', 'pending')->count() }} Pengajuan</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-500 font-bold border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">Toko</th>
                                <th class="px-6 py-4">Tanggal Pengajuan</th>
                                <th class="px-6 py-4">Jumlah Penarikan</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($withdrawals->where('status', 'pending') as $wd)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-800">{{ $wd->shop->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-medium">{{ $wd->shop->email }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold">{{ $wd->created_at->format('d M Y') }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $wd->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <span class="font-black text-emerald-600 text-lg">{{ $wd->points_amount }}</span>
                                            <span class="text-xs text-slate-400 font-bold">Poin</span>
                                            <span class="text-xs text-slate-500 font-bold bg-slate-100 px-2 py-1 rounded-lg ml-2">(Rp {{ number_format($wd->points_amount * 1000, 0, ',', '.') }})</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center items-center space-x-2">
                                            <!-- Approve -->
                                            <button uk-toggle="target: #modal-approve-{{ $wd->id }}" class="bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center space-x-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                <span>Setujui</span>
                                            </button>
                                            <!-- Reject -->
                                            <button uk-toggle="target: #modal-reject-{{ $wd->id }}" class="bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center space-x-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                <span>Tolak</span>
                                            </button>
                                        </div>

                                        <!-- Modal Approve -->
                                        <div id="modal-approve-{{ $wd->id }}" uk-modal>
                                            <div class="uk-modal-dialog uk-modal-body rounded-2xl p-6" style="max-width: 450px;">
                                                <button class="uk-modal-close-default" type="button" uk-close></button>
                                                <div class="flex flex-col items-center text-center mb-5 mt-2">
                                                    <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mb-3">
                                                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    </div>
                                                    <h2 class="text-lg font-bold text-slate-800">Setujui Penarikan</h2>
                                                    <p class="text-xs text-slate-500 mt-1">Pastikan Anda telah menyerahkan uang tunai sebesar <strong class="text-emerald-600 text-sm">Rp {{ number_format($wd->points_amount * 1000, 0, ',', '.') }}</strong> kepada <strong>{{ $wd->shop->name }}</strong>.</p>
                                                </div>

                                                <form action="{{ route('admin.withdrawals.approve', $wd->id) }}" method="POST">
                                                    @csrf
                                                    <div class="mb-4">
                                                        <label class="block text-xs font-bold text-slate-500 mb-1">Catatan (Opsional)</label>
                                                        <textarea name="admin_notes" rows="2" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="Misal: Sudah ditransfer ke rekening X"></textarea>
                                                    </div>
                                                    <div class="flex justify-end space-x-2">
                                                        <button class="uk-modal-close bg-slate-100 text-slate-700 px-4 py-2 rounded-xl text-xs font-bold" type="button">Batal</button>
                                                        <button class="bg-emerald-600 text-white px-5 py-2 rounded-xl text-xs font-bold" type="submit">Ya, Setujui</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Modal Reject -->
                                        <div id="modal-reject-{{ $wd->id }}" uk-modal>
                                            <div class="uk-modal-dialog uk-modal-body rounded-2xl p-6" style="max-width: 450px;">
                                                <button class="uk-modal-close-default" type="button" uk-close></button>
                                                <div class="flex flex-col items-center text-center mb-5 mt-2">
                                                    <div class="w-12 h-12 bg-rose-100 rounded-full flex items-center justify-center mb-3">
                                                        <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                    </div>
                                                    <h2 class="text-lg font-bold text-slate-800">Tolak Penarikan</h2>
                                                    <p class="text-xs text-slate-500 mt-1">Poin sebesar <strong>{{ $wd->points_amount }}</strong> akan dikembalikan ke saldo <strong>{{ $wd->shop->name }}</strong>.</p>
                                                </div>

                                                <form action="{{ route('admin.withdrawals.reject', $wd->id) }}" method="POST">
                                                    @csrf
                                                    <div class="mb-4">
                                                        <label class="block text-xs font-bold text-slate-500 mb-1">Alasan Penolakan <span class="text-rose-500">*</span></label>
                                                        <textarea name="admin_notes" required rows="2" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-rose-500 outline-none" placeholder="Misal: Nomor rekening tidak valid"></textarea>
                                                    </div>
                                                    <div class="flex justify-end space-x-2">
                                                        <button class="uk-modal-close bg-slate-100 text-slate-700 px-4 py-2 rounded-xl text-xs font-bold" type="button">Batal</button>
                                                        <button class="bg-rose-600 text-white px-5 py-2 rounded-xl text-xs font-bold" type="submit">Tolak Penarikan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                            <p class="text-slate-500 font-semibold text-sm">Tidak ada pengajuan baru</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Riwayat Penarikan Selesai/Ditolak -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800">Riwayat Penarikan Selesai & Ditolak</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-500 font-bold border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">Toko</th>
                                <th class="px-6 py-4">Tanggal Proses</th>
                                <th class="px-6 py-4">Jumlah Poin</th>
                                <th class="px-6 py-4">Status & Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($withdrawals->whereIn('status', ['approved', 'rejected'])->take(20) as $wd)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-800">{{ $wd->shop->name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold">{{ $wd->updated_at->format('d M Y') }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $wd->updated_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-slate-700">{{ $wd->points_amount }} Poin</span>
                                        <div class="text-[10px] text-slate-500 font-semibold mt-0.5">Rp {{ number_format($wd->points_amount * 1000, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($wd->status === 'approved')
                                            <div class="inline-flex items-center space-x-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                <span class="text-[10px] font-bold uppercase">Disetujui</span>
                                            </div>
                                        @else
                                            <div class="inline-flex items-center space-x-1.5 px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-lg">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                <span class="text-[10px] font-bold uppercase">Ditolak</span>
                                            </div>
                                        @endif
                                        @if($wd->admin_notes)
                                            <div class="text-xs text-slate-500 mt-2 bg-slate-100 p-2 rounded-lg inline-block break-words max-w-xs">
                                                {{ $wd->admin_notes }}
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-xs text-slate-400 font-medium">
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
