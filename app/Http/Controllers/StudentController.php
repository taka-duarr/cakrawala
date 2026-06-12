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
            try {
                $aiInsight = $aiService->getStudentInsight($user);
                $aiQuestRec = $aiService->getQuestRecommendation($user);
                
                \Illuminate\Support\Facades\Cache::put($cacheKeyInsight, $aiInsight, 60 * 24);
                \Illuminate\Support\Facades\Cache::put($cacheKeyQuest, $aiQuestRec, 60 * 24);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('AI Service Error (Student Dashboard): ' . $e->getMessage());
                return redirect()->route('student.dashboard')->with('error', 'Layanan AI sedang sibuk atau limit kuota tercapai. Silakan coba beberapa saat lagi.');
            }
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

    public function myClasses()
    {
        $user = auth()->user();
        
        $classroom = $user->classroom ? \App\Models\Classroom::with('jurusan')->find($user->classroom_id) : null;
        
        $assignments = [];
        if ($classroom) {
            $assignments = \App\Models\TeachingAssignment::with(['teacher', 'subject', 'academicYear', 'semester'])
                ->where('classroom_id', $classroom->id)
                ->where('is_active', true)
                ->get();
        }
        
        return view('student.my_classes', compact('user', 'classroom', 'assignments'));
    }

    public function classDetail($id)
    {
        $user = auth()->user();
        
        // Pastikan penugasan mengajar ini ada di kelas siswa tersebut
        $assignment = \App\Models\TeachingAssignment::with(['teacher', 'subject', 'academicYear', 'semester', 'classroom.jurusan'])
            ->where('classroom_id', $user->classroom_id)
            ->findOrFail($id);

        // Cari teman sekelas (Classmates)
        $classmates = \App\Models\User::where('role_id', 5)
            ->where('classroom_id', $assignment->classroom_id)
            ->orderByDesc('points')
            ->get();

        // Cari misi yang berkaitan dengan subject/mata pelajaran ini
        $subjectCode = $assignment->subject->code;
        $subjectName = $assignment->subject->name;
        $subjectMissions = \App\Models\Mission::where('is_active', true)
            ->where(function ($query) use ($subjectCode, $subjectName) {
                if ($subjectCode) {
                    $query->where('title', 'like', '%' . $subjectCode . '%')
                          ->orWhere('description', 'like', '%' . $subjectCode . '%');
                }
                $query->orWhere('title', 'like', '%' . $subjectName . '%')
                      ->orWhere('description', 'like', '%' . $subjectName . '%');
            })
            ->get();

        // Dapatkan data status misi siswa untuk mencocokkan status (taken, pending_approval, approved, dll)
        $takenMissions = $user->missions()
            ->withPivot('status', 'proof_url', 'proof_content', 'notes')
            ->get()
            ->keyBy('id');

        // Mengambil Sesi Presensi & KBM
        $sessions = \App\Models\AttendanceSession::with(['materials', 'assignments'])
            ->where('teaching_assignment_id', $assignment->id)
            ->orderBy('meeting_number')
            ->get();

        // Presensi Siswa
        $myAttendances = \App\Models\Attendance::whereIn('attendance_session_id', $sessions->pluck('id'))
            ->where('student_id', $user->id)
            ->get()
            ->keyBy('attendance_session_id');

        // Pengumpulan Tugas Siswa
        $assignmentIds = $sessions->flatMap(fn($s) => $s->assignments->pluck('id'))->unique();
        $assignmentSubmissions = \App\Models\AssignmentSubmission::whereIn('assignment_id', $assignmentIds)
            ->where('student_id', $user->id)
            ->get()
            ->keyBy('assignment_id');

        return view('student.class_detail', compact(
            'user', 
            'assignment', 
            'classmates', 
            'subjectMissions', 
            'takenMissions', 
            'sessions', 
            'myAttendances', 
            'assignmentSubmissions'
        ));
    }
}
