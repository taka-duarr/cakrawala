<?php

namespace App\Http\Controllers;

use App\Models\ShopItem;
use App\Models\ShopTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TokoController extends Controller
{
    // ─── Dashboard Kasir ──────────────────────────────────────────────
    public function index()
    {
        $shop = auth()->user();

        $items = ShopItem::where('shop_user_id', $shop->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $activeTx = ShopTransaction::where('shop_user_id', $shop->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if ($activeTx && $activeTx->isExpired()) {
            $activeTx->update(['status' => 'expired']);
            $activeTx = null;
        }

        $todayTransactions = ShopTransaction::where('shop_user_id', $shop->id)
            ->whereDate('created_at', today())
            ->orderByDesc('created_at')
            ->get();

        $todayPoints = $todayTransactions->where('status', 'paid')->sum('points_amount');

        return view('toko.dashboard', compact('shop', 'items', 'activeTx', 'todayTransactions', 'todayPoints'));
    }

    public function katalog()
    {
        $items = ShopItem::where('shop_user_id', auth()->id())->get();
        return view('toko.katalog', compact('items'));
    }

    // ─── Generate QR dari Keranjang ───────────────────────────────────
    public function generateQr(Request $request)
    {
        $request->validate([
            'cart'          => 'required|array|min:1',
            'cart.*.id'     => 'required|integer|exists:shop_items,id',
            'cart.*.qty'    => 'required|integer|min:1',
            'total_points'  => 'required|integer|min:1',
        ]);

        $shop = auth()->user();

        // Bangun cart items dari DB untuk keamanan (harga tidak bisa dimanipulasi)
        $cartItems = [];
        $total = 0;
        foreach ($request->cart as $line) {
            $item = ShopItem::where('id', $line['id'])
                ->where('shop_user_id', $shop->id)
                ->where('is_active', true)
                ->firstOrFail();
            $subtotal = $item->points_price * (int)$line['qty'];
            $total += $subtotal;
            $cartItems[] = [
                'id'           => $item->id,
                'name'         => $item->name,
                'qty'          => (int)$line['qty'],
                'points_price' => $item->points_price,
                'subtotal'     => $subtotal,
            ];
        }

        // Batalkan semua transaksi pending toko ini
        ShopTransaction::where('shop_user_id', $shop->id)
            ->where('status', 'pending')
            ->update(['status' => 'expired']);

        // Label singkat untuk item_name
        $itemSummary = count($cartItems) === 1
            ? $cartItems[0]['name']
            : count($cartItems) . ' item';

        $tx = ShopTransaction::create([
            'shop_user_id'  => $shop->id,
            'item_name'     => $itemSummary,
            'cart_items'    => $cartItems,
            'points_amount' => $total,
            'qr_token'      => Str::random(40),
            'status'        => 'pending',
            'expires_at'    => now()->addMinutes(30),
        ]);

        return response()->json([
            'success'   => true,
            'pay_url'   => url('/pay/' . $tx->qr_token),
            'token'     => $tx->qr_token,
            'total'     => $total,
            'cart'      => $cartItems,
        ]);
    }

    public function cancelQr()
    {
        ShopTransaction::where('shop_user_id', auth()->id())
            ->where('status', 'pending')
            ->update(['status' => 'expired']);

        return response()->json(['success' => true]);
    }

    // ─── Cek Status Transaksi QR ──────────────────────────────────────
    public function checkStatus($token)
    {
        $tx = ShopTransaction::where('qr_token', $token)
            ->where('shop_user_id', auth()->id())
            ->first();

        if (!$tx) {
            return response()->json(['status' => 'not_found']);
        }

        return response()->json(['status' => $tx->status]);
    }

    // ─── CRUD Katalog Barang ──────────────────────────────────────────
    public function itemStore(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'points_price' => 'required|integer|min:1',
        ]);

        ShopItem::create([
            'shop_user_id' => auth()->id(),
            'name'         => $request->name,
            'points_price' => $request->points_price,
            'is_active'    => true,
        ]);

        return back()->with('success', 'Barang berhasil ditambahkan ke katalog!');
    }

    public function itemUpdate(Request $request, $id)
    {
        $item = ShopItem::where('id', $id)->where('shop_user_id', auth()->id())->firstOrFail();
        $request->validate([
            'name'         => 'required|string|max:255',
            'points_price' => 'required|integer|min:1',
        ]);

        $item->update([
            'name'         => $request->name,
            'points_price' => $request->points_price,
        ]);

        return back()->with('success', 'Barang berhasil diperbarui!');
    }

    public function itemDestroy($id)
    {
        ShopItem::where('id', $id)->where('shop_user_id', auth()->id())->firstOrFail()->delete();
        return back()->with('success', 'Barang dihapus dari katalog.');
    }
}
