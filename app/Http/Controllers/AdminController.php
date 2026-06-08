<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $totalSiswa = \App\Models\User::where('role_id', 5)->count();
        $totalKebaikan = \App\Models\User::where('role_id', 5)->sum('points_kebaikan');
        $totalPelanggaran = \App\Models\User::where('role_id', 5)->sum('points_pelanggaran');
        
        $kelasRanking = \App\Models\User::where('role_id', 5)
            ->selectRaw('class_name, sum(points_kebaikan) as total_kebaikan, sum(points_pelanggaran) as total_pelanggaran')
            ->groupBy('class_name')
            ->orderByDesc('total_kebaikan')
            ->get();

        return view('admin.dashboard', compact('totalSiswa', 'totalKebaikan', 'totalPelanggaran', 'kelasRanking'));
    }
}
