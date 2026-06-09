<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request, \App\Services\AIService $aiService)
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

        // AI Student Insight and Quest Recommendation
        $cacheKeyInsight = 'ai_insight_student_' . $user->id;
        $cacheKeyQuest = 'ai_quest_rec_student_' . $user->id;

        if ($request->has('trigger_ai')) {
            $aiInsight = $aiService->getStudentInsight($user);
            $aiQuestRec = $aiService->getQuestRecommendation($user);
            
            \Illuminate\Support\Facades\Cache::put($cacheKeyInsight, $aiInsight, 60 * 24);
            \Illuminate\Support\Facades\Cache::put($cacheKeyQuest, $aiQuestRec, 60 * 24);
        } else {
            $aiInsight = \Illuminate\Support\Facades\Cache::get($cacheKeyInsight);
            $aiQuestRec = \Illuminate\Support\Facades\Cache::get($cacheKeyQuest);
        }

        // Dapatkan badge siswa
        $badges = $user->achievements()->get();
        
        return view('student.dashboard', compact(
            'user', 
            'availableMissions', 
            'takenMissionIds', 
            'activeMissions', 
            'pointHistory',
            'aiInsight',
            'aiQuestRec',
            'badges'
        ));
    }
}
