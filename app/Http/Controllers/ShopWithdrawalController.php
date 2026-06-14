<?php

namespace App\Http\Controllers;

use App\Models\ShopWithdrawal;
use App\Services\PointService;
use Illuminate\Http\Request;

class ShopWithdrawalController extends Controller
{
    // TOKO: Halaman Penarikan Dana
    public function tokoIndex()
    {
        $withdrawals = ShopWithdrawal::where('shop_user_id', auth()->id())->latest()->get();
        return view('toko.withdrawals', compact('withdrawals'));
    }

    // TOKO: Ajukan pencairan dana
    public function store(Request $request, PointService $pointService)
    {
        $request->validate([
            'points_amount' => 'required|integer|min:1',
        ]);

        $toko = auth()->user();

        if ($toko->points < $request->points_amount) {
            return back()->with('error', 'Saldo poin tidak mencukupi untuk ditarik.');
        }

        // Potong poin langsung agar tidak bisa ditarik ganda (masih status pending)
        $pointService->addPoints(
            $toko,
            -$request->points_amount,
            'kebaikan',
            'Penarikan Dana',
            'Pencairan Poin (Menunggu Persetujuan Admin)'
        );

        ShopWithdrawal::create([
            'shop_user_id'  => $toko->id,
            'points_amount' => $request->points_amount,
            'status'        => 'pending',
        ]);

        return back()->with('success', 'Pengajuan penarikan poin berhasil dikirim. Menunggu persetujuan Admin.');
    }

    // ADMIN: Lihat daftar pencairan
    public function index()
    {
        $withdrawals = ShopWithdrawal::with('shop')->latest()->get();
        return view('admin.withdrawals', compact('withdrawals'));
    }

    // ADMIN: Setujui pencairan
    public function approve(Request $request, $id)
    {
        $wd = ShopWithdrawal::findOrFail($id);

        if ($wd->status !== 'pending') {
            return back()->with('error', 'Penarikan ini sudah diproses.');
        }

        $wd->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes
        ]);

        return back()->with('success', 'Penarikan dana disetujui. Jangan lupa berikan uang tunai kepada pemilik toko.');
    }

    // ADMIN: Tolak pencairan
    public function reject(Request $request, $id, PointService $pointService)
    {
        $wd = ShopWithdrawal::findOrFail($id);

        if ($wd->status !== 'pending') {
            return back()->with('error', 'Penarikan ini sudah diproses.');
        }

        // Kembalikan poin ke toko
        $pointService->addPoints(
            $wd->shop,
            $wd->points_amount,
            'kebaikan',
            'Penarikan Ditolak',
            'Pengembalian Poin: ' . ($request->admin_notes ?? 'Ditolak Admin')
        );

        $wd->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes
        ]);

        return back()->with('success', 'Penarikan dana ditolak. Poin telah dikembalikan ke toko.');
    }
}
