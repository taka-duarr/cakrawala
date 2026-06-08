<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Semua misi tersedia
        $availableMissions = \App\Models\Mission::where('is_active', true)->get();
        
        // ID misi yang sudah diambil
        $takenMissionIds = $user->missions()->pluck('mission_id')->toArray();
        
        // Misi yang sedang berjalan (sudah diambil tapi belum approved)
        $activeMissions = $user->missions()
            ->whereIn('mission_user.status', ['taken', 'pending_approval'])
            ->get();
        
        // Riwayat poin
        $pointHistory = \App\Models\PointHistory::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();
        
        return view('student.dashboard', compact('user', 'availableMissions', 'takenMissionIds', 'activeMissions', 'pointHistory'));
    }
}
