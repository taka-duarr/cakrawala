<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mission;
use App\Models\Achievement;
use App\Services\PointService;

class GuruController extends Controller
{
    public function index()
    {
        // Misi yang menunggu persetujuan guru
        $pendingMissions = User::with('classroom')
            ->where('role_id', 5)
            ->whereHas('missions', function ($q) {
                $q->where('status', 'pending_approval');
            })
            ->get()
            ->flatMap(function ($student) {
                return $student->missions()
                    ->wherePivot('status', 'pending_approval')
                    ->get()
                    ->map(function ($mission) use ($student) {
                        $mission->student = $student;
                        return $mission;
                    });
            });

        // Daftar semua siswa di kelas
        $siswas = User::with('classroom')
            ->where('role_id', 5)
            ->orderByDesc('points')
            ->get();

        $achievements = Achievement::all();

        return view('guru.dashboard', compact('pendingMissions', 'siswas', 'achievements'));
    }

    public function storeMission(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'points_reward' => 'required|integer|min:1',
            'type' => 'required|in:daily,weekly,class,school,special',
            'deadline' => 'nullable|date',
            'proof_type' => 'required|in:file,link,text,none',
        ]);

        Mission::create([
            'title' => $request->title,
            'description' => $request->description,
            'points_reward' => $request->points_reward,
            'type' => $request->type,
            'deadline' => $request->deadline,
            'proof_type' => $request->proof_type,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Misi baru berhasil dibuat!');
    }

    public function validateMission(Request $request, PointService $pointService)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'mission_id' => 'required|exists:missions,id',
            'status' => 'required|in:approved,rejected,revision',
            'notes' => 'nullable|string',
        ]);

        $user = User::findOrFail($request->user_id);
        $mission = Mission::findOrFail($request->mission_id);

        $user->missions()->updateExistingPivot($request->mission_id, [
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        if ($request->status === 'approved') {
            $pointService->addPoints($user, $mission->points_reward, 'kebaikan', 'Misi: ' . $mission->title);
            $msg = 'Misi disetujui dan poin telah ditambahkan ke siswa.';
        } elseif ($request->status === 'revision') {
            $msg = 'Permintaan revisi misi berhasil dikirim ke siswa.';
        } else {
            $msg = 'Misi ditolak.';
        }

        return redirect()->back()->with('success', $msg);
    }

    public function adjustPoints(Request $request, PointService $pointService)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:kebaikan,pelanggaran',
            'operation' => 'required|in:add,subtract',
            'amount' => 'required|integer|min:1',
            'description' => 'required|string|max:255',
        ]);

        $user = User::findOrFail($request->user_id);
        
        $points = $request->amount;
        if ($request->operation === 'subtract') {
            $points = -$points;
        }

        $pointService->addPoints($user, $points, $request->type, 'Guru Manual', $request->description);

        return redirect()->back()->with('success', 'Poin siswa berhasil disesuaikan.');
    }

    public function toggleBadge(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'achievement_id' => 'required|exists:achievements,id',
            'action' => 'required|in:award,revoke',
        ]);

        $user = User::findOrFail($request->user_id);
        
        if ($request->action === 'award') {
            $user->achievements()->syncWithoutDetaching([$request->achievement_id]);
            $msg = 'Lencana berhasil diberikan kepada siswa.';
        } else {
            $user->achievements()->detach($request->achievement_id);
            $msg = 'Lencana berhasil dicabut dari siswa.';
        }

        return redirect()->back()->with('success', $msg);
    }
}
