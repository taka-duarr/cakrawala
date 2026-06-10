<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class WaliKelasController extends Controller
{
    public function index(Request $request, AIService $aiService)
    {
        $wali = auth()->user();
        $classroom = $wali->classroom;
        $className = $classroom?->name;

        if (!$classroom) {
            return view('walikelas.dashboard', [
                'error' => 'Anda belum ditugaskan sebagai wali kelas di kelas mana pun. Silakan hubungi Admin.'
            ]);
        }

        // Get students in this class
        $students = User::where('role_id', 5)
            ->where('classroom_id', $wali->classroom_id)
            ->orderByDesc('points_kebaikan')
            ->get()
            ->map(function ($student) {
                if ($student->points_pelanggaran > 20) {
                    $student->activity_status = 'Berisiko';
                    $student->activity_color = 'bg-rose-50 text-rose-700 border-rose-100';
                } elseif ($student->points_kebaikan >= 500) {
                    $student->activity_status = 'Sangat Aktif';
                    $student->activity_color = 'bg-emerald-50 text-emerald-700 border-emerald-100';
                } elseif ($student->points_kebaikan >= 150) {
                    $student->activity_status = 'Aktif';
                    $student->activity_color = 'bg-indigo-50 text-indigo-700 border-indigo-100';
                } else {
                    $student->activity_status = 'Kurang Aktif';
                    $student->activity_color = 'bg-slate-50 text-slate-600 border-slate-100';
                }
                return $student;
            });

        // Calculate statistics
        $totalKebaikan = $students->sum('points_kebaikan');
        $totalPelanggaran = $students->sum('points_pelanggaran');
        $avgKebaikan = $students->count() > 0 ? round($totalKebaikan / $students->count(), 1) : 0;
        
        // Students at risk (points_pelanggaran > 30)
        $atRiskStudents = $students->filter(function ($student) {
            return $student->points_pelanggaran > 20;
        });

        // Class Ranking Comparison
        $classRankings = \App\Models\Classroom::leftJoin('users', function($join) {
                $join->on('classrooms.id', '=', 'users.classroom_id')
                     ->where('users.role_id', '=', 5);
            })
            ->selectRaw('classrooms.id, classrooms.name, coalesce(sum(users.points_kebaikan), 0) as total_kebaikan')
            ->groupBy('classrooms.id', 'classrooms.name')
            ->orderByDesc('total_kebaikan')
            ->get();
        
        $myRank = $classRankings->pluck('name')->search($className) + 1;

        // Pending Claims for this class
        $pendingClaims = DB::table('reward_user')
            ->join('users', 'reward_user.user_id', '=', 'users.id')
            ->join('rewards', 'reward_user.reward_id', '=', 'rewards.id')
            ->leftJoin('classrooms', 'users.classroom_id', '=', 'classrooms.id')
            ->select('reward_user.id', 'users.name as student_name', 'classrooms.name as class_name', 'rewards.name as reward_name', 'rewards.points_cost', 'reward_user.status', 'reward_user.created_at')
            ->where('reward_user.status', 'pending_approval')
            ->where('users.classroom_id', $wali->classroom_id)
            ->latest()
            ->get();

        // AI Early Warning Trigger
        $aiWarning = null;
        $cacheKey = 'ai_early_warning_' . $className;
        
        if ($request->has('trigger_ai')) {
            if ($students->count() > 0) {
                $aiWarning = $aiService->getEarlyWarningAnalysis($students);
                Cache::put($cacheKey, $aiWarning, 60 * 24); // Cache for 1 day
            } else {
                $aiWarning = "Belum ada siswa di kelas Anda untuk dianalisis oleh AI.";
            }
        } else {
            $aiWarning = Cache::get($cacheKey);
        }

        return view('walikelas.dashboard', compact(
            'wali',
            'className',
            'students',
            'totalKebaikan',
            'totalPelanggaran',
            'avgKebaikan',
            'atRiskStudents',
            'myRank',
            'pendingClaims',
            'aiWarning'
        ));
    }
}
