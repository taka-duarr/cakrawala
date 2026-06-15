<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MissionController extends Controller
{
    public function takeMission(Request $request, $id)
    {
        return response()->json(['message' => 'Mengambil misi secara mandiri tidak diizinkan.'], 403);
    }

    public function submitProof(Request $request, $id)
    {
        return response()->json(['message' => 'Mengirimkan bukti misi secara mandiri tidak diizinkan.'], 403);
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
