<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class OrangTuaController extends Controller
{
    public function index(Request $request, AIService $aiService)
    {
        $parent = auth()->user();
        
        // Dapatkan semua anak dari orang tua ini
        $children = $parent->children()->with(['missions', 'achievements', 'classroom'])->get();

        // Cari riwayat poin dari semua anak
        $childrenIds = $children->pluck('id')->toArray();
        $pointHistories = \App\Models\PointHistory::whereIn('user_id', $childrenIds)
            ->latest()
            ->take(15)
            ->get()
            ->groupBy('user_id');

        // AI Student Insight trigger
        $aiInsights = [];
        foreach ($children as $child) {
            $cacheKey = 'ai_insight_student_' . $child->id;
            
            if ($request->has('trigger_ai') && $request->student_id == $child->id) {
                try {
                    $insight = $aiService->getStudentInsight($child);
                    Cache::put($cacheKey, $insight, 60 * 24); // Cache selama 1 hari
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('AI Service Error (Parent Dashboard): ' . $e->getMessage());
                    return redirect()->route('parent.dashboard')->with('error', 'Layanan AI untuk analisis anak sedang tidak tersedia saat ini.');
                }
            }
            
            $aiInsights[$child->id] = Cache::get($cacheKey) ?? 'AI Insight belum digenerate. Klik tombol "Minta AI Insight" di bawah.';
        }

        return view('orangtua.dashboard', compact('parent', 'children', 'pointHistories', 'aiInsights'));
    }
}
