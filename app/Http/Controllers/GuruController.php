<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index()
    {
        // Misi yang menunggu persetujuan guru
        $pendingMissions = \App\Models\User::where('role_id', 5)
            ->with(['missions' => function ($q) {
                $q->wherePivot('status', 'pending_approval');
            }, 'missions.users'])
            ->get()
            ->flatMap(function ($student) {
                return $student->missions->map(function ($mission) use ($student) {
                    $mission->student = $student;
                    return $mission;
                });
            });

        // Daftar semua siswa di kelas
        $siswas = \App\Models\User::where('role_id', 5)
            ->orderByDesc('points_kebaikan')
            ->get();

        return view('guru.dashboard', compact('pendingMissions', 'siswas'));
    }
}
