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
        $mission = \App\Models\Mission::findOrFail($id);
        
        $rules = [];
        if ($mission->proof_type === 'file') {
            $rules['proof_file'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120'; // max 5MB
        } elseif ($mission->proof_type === 'link') {
            $rules['proof_url'] = 'required|url';
        } elseif ($mission->proof_type === 'text') {
            $rules['proof_text'] = 'required|string';
        }

        $request->validate($rules);

        $user = auth()->user();
        $updateData = ['status' => 'pending_approval'];

        if ($mission->proof_type === 'file' && $request->hasFile('proof_file')) {
            $path = $request->file('proof_file')->store('proofs', 'public');
            $updateData['proof_url'] = asset('storage/' . $path);
        } elseif ($mission->proof_type === 'link') {
            $updateData['proof_url'] = $request->proof_url;
        } elseif ($mission->proof_type === 'text') {
            $updateData['proof_content'] = $request->proof_text;
        }

        $user->missions()->updateExistingPivot($id, $updateData);

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
