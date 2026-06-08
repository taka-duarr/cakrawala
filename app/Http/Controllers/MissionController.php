<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MissionController extends Controller
{
    public function takeMission(Request $request, $id)
    {
        $user = auth()->user();
        $user->missions()->attach($id, ['status' => 'taken']);
        return back()->with('success', 'Misi berhasil diambil!');
    }

    public function submitProof(Request $request, $id)
    {
        $request->validate(['proof_url' => 'required|url']);
        $user = auth()->user();
        $user->missions()->updateExistingPivot($id, [
            'status' => 'pending_approval',
            'proof_url' => $request->proof_url
        ]);
        return back()->with('success', 'Bukti misi berhasil dikirim, menunggu persetujuan guru.');
    }

    public function approveMission(Request $request, $userId, $missionId, \App\Services\PointService $pointService)
    {
        $user = \App\Models\User::findOrFail($userId);
        $mission = \App\Models\Mission::findOrFail($missionId);
        
        $user->missions()->updateExistingPivot($missionId, ['status' => 'approved']);
        
        // Add points
        $pointService->addPoints($user, $mission->points_reward, 'kebaikan', 'Misi: ' . $mission->title);
        
        return back()->with('success', 'Misi disetujui dan poin telah ditambahkan.');
    }
}
