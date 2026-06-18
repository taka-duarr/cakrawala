<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShopItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\ShopItem::truncate();

        // 1. Kantin Sekolah
        $kantin = \App\Models\User::where('email', 'toko@cakrawala.com')->first();
        if ($kantin) {
            \App\Models\ShopItem::insert([
                ['shop_user_id' => $kantin->id, 'name' => 'Es Teh Manis', 'points_price' => 20, 'is_active' => true],
                ['shop_user_id' => $kantin->id, 'name' => 'Nasi Goreng Kantin', 'points_price' => 100, 'is_active' => true],
                ['shop_user_id' => $kantin->id, 'name' => 'Ayam Geprek', 'points_price' => 150, 'is_active' => true],
                ['shop_user_id' => $kantin->id, 'name' => 'Kopi Kapal Api', 'points_price' => 25, 'is_active' => true],
                ['shop_user_id' => $kantin->id, 'name' => 'Mie Instan Rebus', 'points_price' => 80, 'is_active' => true],
                ['shop_user_id' => $kantin->id, 'name' => 'Sosis Bakar', 'points_price' => 50, 'is_active' => true],
                ['shop_user_id' => $kantin->id, 'name' => 'Roti Bakar Coklat', 'points_price' => 60, 'is_active' => true],
            ]);
        }

        // 2. Koperasi Serba Ada
        $koperasi = \App\Models\User::where('email', 'koperasi@cakrawala.com')->first();
        if ($koperasi) {
            \App\Models\ShopItem::insert([
                ['shop_user_id' => $koperasi->id, 'name' => 'Seragam Putih Abu-abu', 'points_price' => 500, 'is_active' => true],
                ['shop_user_id' => $koperasi->id, 'name' => 'Topi Sekolah', 'points_price' => 120, 'is_active' => true],
                ['shop_user_id' => $koperasi->id, 'name' => 'Dasi Sekolah', 'points_price' => 80, 'is_active' => true],
                ['shop_user_id' => $koperasi->id, 'name' => 'Bet Nama & Lokasi', 'points_price' => 50, 'is_active' => true],
                ['shop_user_id' => $koperasi->id, 'name' => 'Kaos Kaki Hitam Putih', 'points_price' => 60, 'is_active' => true],
                ['shop_user_id' => $koperasi->id, 'name' => 'Ikat Pinggang Sekolah', 'points_price' => 150, 'is_active' => true],
            ]);
        }

        // 3. Toko Buku Pintar
        $tokoBuku = \App\Models\User::where('email', 'tokobuku@cakrawala.com')->first();
        if ($tokoBuku) {
            \App\Models\ShopItem::insert([
                ['shop_user_id' => $tokoBuku->id, 'name' => 'Buku Tulis Sidu 58', 'points_price' => 200, 'is_active' => true],
                ['shop_user_id' => $tokoBuku->id, 'name' => 'Pena Standard AE7', 'points_price' => 15, 'is_active' => true],
                ['shop_user_id' => $tokoBuku->id, 'name' => 'Pensil 2B Faber Castell', 'points_price' => 25, 'is_active' => true],
                ['shop_user_id' => $tokoBuku->id, 'name' => 'Penghapus Joyko Hitam', 'points_price' => 10, 'is_active' => true],
                ['shop_user_id' => $tokoBuku->id, 'name' => 'Penggaris Besi 30cm', 'points_price' => 45, 'is_active' => true],
                ['shop_user_id' => $tokoBuku->id, 'name' => 'Tipe-x / Correction Pen', 'points_price' => 80, 'is_active' => true],
            ]);
        }
    }
}
