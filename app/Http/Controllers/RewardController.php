<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use App\Models\User;
use App\Services\PointService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RewardController extends Controller
{
    // Toko Hadiah Siswa
    public function index()
    {
        $user = auth()->user();
        $rewards = Reward::where('is_available', true)->get();
        
        $claimedRewards = $user->claimedRewards()->latest()->take(10)->get();

        return view('student.rewards', compact('user', 'rewards', 'claimedRewards'));
    }

    // Siswa menukar poin dengan hadiah
    public function claim($id, PointService $pointService)
    {
        $user = auth()->user();
        $reward = Reward::findOrFail($id);

        if (!$reward->is_available) {
            return back()->with('error', 'Hadiah tidak tersedia saat ini.');
        }

        if ($user->points_kebaikan < $reward->points_cost) {
            return back()->with('error', 'Poin Kebaikan Anda tidak cukup untuk menukarkan hadiah ini.');
        }

        // Jalankan transaksi
        DB::transaction(function () use ($user, $reward, $pointService) {
            // Potong poin kebaikan (menggunakan nilai negatif pada addPoints)
            $pointService->addPoints(
                $user, 
                -$reward->points_cost, 
                'kebaikan', 
                'Penukaran Hadiah', 
                'Menukarkan poin dengan: ' . $reward->name
            );

            // Hubungkan dengan status pending_approval
            $user->claimedRewards()->attach($reward->id, ['status' => 'pending_approval']);
        });

        return back()->with('success', 'Klaim hadiah berhasil diajukan! Silakan hubungi Wali Kelas untuk penyerahan.');
    }

    // Persetujuan Klaim Hadiah (Wali Kelas / Guru)
    public function approveClaim($id)
    {
        // Cari baris di tabel pivot berdasarkan ID-nya
        $claim = DB::table('reward_user')->where('id', $id)->first();
        if (!$claim) {
            return back()->with('error', 'Data klaim tidak ditemukan.');
        }

        DB::table('reward_user')->where('id', $id)->update([
            'status' => 'claimed',
            'updated_at' => now()
        ]);

        return back()->with('success', 'Klaim hadiah berhasil disetujui dan diserahkan.');
    }

    // Halaman Manajemen CRUD Reward (Admin)
    public function manage()
    {
        $rewards = Reward::latest()->get();
        // Klaim yang pending dari seluruh siswa untuk admin/guru
        $pendingClaims = DB::table('reward_user')
            ->join('users', 'reward_user.user_id', '=', 'users.id')
            ->join('rewards', 'reward_user.reward_id', '=', 'rewards.id')
            ->leftJoin('classrooms', 'users.classroom_id', '=', 'classrooms.id')
            ->select('reward_user.id', 'users.name as student_name', 'classrooms.name as class_name', 'rewards.name as reward_name', 'rewards.points_cost', 'reward_user.status', 'reward_user.created_at')
            ->where('reward_user.status', 'pending_approval')
            ->latest()
            ->get();

        return view('admin.rewards', compact('rewards', 'pendingClaims'));
    }

    // Admin membuat reward baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'points_cost' => 'required|integer|min:1',
            'category' => 'required|in:akademik,pengembangan_diri,sekolah,penghargaan',
        ]);

        Reward::create([
            'name' => $request->name,
            'description' => $request->description,
            'points_cost' => $request->points_cost,
            'category' => $request->category,
            'is_available' => $request->has('is_available') ? true : true
        ]);

        return back()->with('success', 'Hadiah baru berhasil ditambahkan!');
    }

    // Admin mengubah reward
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'points_cost' => 'required|integer|min:1',
            'category' => 'required|in:akademik,pengembangan_diri,sekolah,penghargaan',
        ]);

        $reward = Reward::findOrFail($id);
        $reward->update([
            'name' => $request->name,
            'description' => $request->description,
            'points_cost' => $request->points_cost,
            'category' => $request->category,
            'is_available' => $request->has('is_available')
        ]);

        return back()->with('success', 'Hadiah berhasil diperbarui!');
    }

    // Admin menghapus reward
    public function destroy($id)
    {
        $reward = Reward::findOrFail($id);
        $reward->delete();

        return back()->with('success', 'Hadiah berhasil dihapus!');
    }
}
