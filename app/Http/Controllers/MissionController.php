<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MissionController extends Controller
{
    public function takeMission(Request $request, $id)
    {
        $user = auth()->user();
        
        $existing = $user->missions()->where('mission_id', $id)->first();
        if ($existing) {
            $pivot = $existing->pivot;
            if (in_array($pivot->status, ['taken', 'pending_approval'])) {
                return back()->with('error', 'Opps! Anda sudah mengambil misi ini sebelumnya.');
            } elseif ($pivot->status === 'approved') {
                return back()->with('error', 'Opps! Anda sudah menyelesaikan misi ini.');
            }
            
            // Jika ditolak, izinkan mengambil ulang dengan mereset bukti/catatan
            if ($pivot->status === 'rejected') {
                $user->missions()->updateExistingPivot($id, [
                    'status' => 'taken', 
                    'proof_url' => null, 
                    'proof_content' => null, 
                    'notes' => null
                ]);
                return back()->with('success', 'Misi berhasil diambil kembali! Silakan kirimkan bukti baru.');
            }
        }

        $user->missions()->attach($id, ['status' => 'taken']);
        return back()->with('success', 'Misi berhasil diambil! Selamat berjuang!');
    }

    public function submitProof(Request $request, $id)
    {
        $mission = \App\Models\Mission::findOrFail($id);
        $user = auth()->user();
        
        $existing = $user->missions()->where('mission_id', $id)->first();
        if (!$existing) {
            return back()->with('error', 'Silakan ambil misi ini terlebih dahulu sebelum mengirimkan bukti.');
        }

        if ($existing->pivot->status === 'approved') {
            return back()->with('error', 'Misi ini sudah selesai dan telah disetujui.');
        }
        
        $rules = [];
        if ($mission->proof_type === 'file') {
            $rules['proof_file'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120'; // max 5MB
        } elseif ($mission->proof_type === 'link') {
            $rules['proof_url'] = 'required|url';
        } elseif ($mission->proof_type === 'text') {
            $rules['proof_text'] = 'required|string';
        }

        $request->validate($rules);

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
