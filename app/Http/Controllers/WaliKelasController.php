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
        $className = $wali->class_name;

        if (!$className) {
            return view('walikelas.dashboard', [
                'error' => 'Anda belum ditugaskan sebagai wali kelas di kelas mana pun. Silakan hubungi Admin.'
            ]);
        }

        // Get students in this class
        $students = User::where('role_id', 5)
            ->where('class_name', $className)
            ->orderByDesc('points_kebaikan')
            ->get();

        // Calculate statistics
        $totalKebaikan = $students->sum('points_kebaikan');
        $totalPelanggaran = $students->sum('points_pelanggaran');
        $avgKebaikan = $students->count() > 0 ? round($totalKebaikan / $students->count(), 1) : 0;
        
        // Students at risk (points_pelanggaran > 30)
        $atRiskStudents = $students->filter(function ($student) {
            return $student->points_pelanggaran > 20;
        });

        // Class Ranking Comparison
        $classRankings = User::where('role_id', 5)
            ->selectRaw('class_name, sum(points_kebaikan) as total_kebaikan')
            ->groupBy('class_name')
            ->orderByDesc('total_kebaikan')
            ->get();
        
        $myRank = $classRankings->pluck('class_name')->search($className) + 1;

        // Pending Claims for this class
        $pendingClaims = DB::table('reward_user')
            ->join('users', 'reward_user.user_id', '=', 'users.id')
            ->join('rewards', 'reward_user.reward_id', '=', 'rewards.id')
            ->select('reward_user.id', 'users.name as student_name', 'users.class_name', 'rewards.name as reward_name', 'rewards.points_cost', 'reward_user.status', 'reward_user.created_at')
            ->where('reward_user.status', 'pending_approval')
            ->where('users.class_name', $className)
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
