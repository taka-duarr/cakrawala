<?php

namespace App\Http\Controllers;

use App\Models\ShopTransaction;
use App\Services\PointService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopPaymentController extends Controller
{
    // Halaman konfirmasi pembayaran (siswa scan QR → buka URL ini)
    public function confirm(string $token)
    {
        $tx = ShopTransaction::where('qr_token', $token)
            ->where('status', 'pending')
            ->with('shop')
            ->firstOrFail();

        // Cek apakah QR sudah kadaluarsa
        if ($tx->isExpired()) {
            $tx->update(['status' => 'expired']);
            return view('toko.pay_confirm', [
                'tx'      => null,
                'expired' => true,
                'token'   => $token,
            ]);
        }

        $student = auth()->user();

        return view('toko.pay_confirm', [
            'tx'      => $tx,
            'expired' => false,
            'student' => $student,
        ]);
    }

    // Proses pembayaran
    public function process(string $token, PointService $pointService)
    {
        $tx = ShopTransaction::where('qr_token', $token)
            ->where('status', 'pending')
            ->firstOrFail();

        if ($tx->isExpired()) {
            $tx->update(['status' => 'expired']);
            return back()->with('error', 'QR Code sudah kadaluarsa. Minta toko untuk generate ulang.');
        }

        $student = auth()->user();

        // Cek saldo cukup
        if ($student->points < $tx->points_amount) {
            return back()->with('error', 'Saldo poin Anda tidak cukup. Dibutuhkan ' . $tx->points_amount . ' poin, saldo Anda: ' . $student->points . ' poin.');
        }

        // Potong poin siswa via PointService (catat di PointHistory)
        $pointService->addPoints(
            $student,
            -$tx->points_amount,
            'kebaikan',
            'Belanja Toko',
            'Pembelian: ' . $tx->item_name . ' di Toko ' . $tx->shop->name
        );

        // Tambah poin ke Toko
        $pointService->addPoints(
            $tx->shop,
            $tx->points_amount,
            'kebaikan',
            'Penjualan Toko',
            'Penjualan: ' . $tx->item_name . ' ke ' . $student->name
        );

        // Update transaksi
        $tx->update([
            'status'           => 'paid',
            'student_user_id'  => $student->id,
            'paid_at'          => now(),
        ]);

        return redirect()->route('student.dompet')
            ->with('success', 'Pembayaran berhasil! ' . $tx->points_amount . ' poin telah dibayarkan ke Toko ' . $tx->shop->name . ' untuk "' . $tx->item_name . '".');
    }
}
