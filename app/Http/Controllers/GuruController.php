<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mission;
use App\Models\Achievement;
use App\Services\PointService;

class GuruController extends Controller
{
    public function index()
    {
        $teacher = auth()->user();

        // Ambil penugasan mengajar guru yang aktif beserta kelas dan mata pelajarannya
        $assignments = \App\Models\TeachingAssignment::with(['classroom.jurusan', 'subject', 'academicYear', 'semester'])
            ->where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->get();

        // Dapatkan ID kelas-kelas yang diampu
        $classroomIds = $assignments->pluck('classroom_id')->unique()->toArray();

        // Misi yang menunggu persetujuan guru (hanya untuk siswa di kelas yang diampu)
        $pendingMissions = User::with('classroom')
            ->where('role_id', 5)
            ->whereIn('classroom_id', $classroomIds)
            ->whereHas('missions', function ($q) {
                $q->where('status', 'pending_approval');
            })
            ->get()
            ->flatMap(function ($student) {
                return $student->missions()
                    ->wherePivot('status', 'pending_approval')
                    ->get()
                    ->map(function ($mission) use ($student) {
                        $mission->student = $student;
                        return $mission;
                    });
            });

        // Daftar siswa di kelas yang diampu
        $siswas = User::with('classroom')
            ->where('role_id', 5)
            ->whereIn('classroom_id', $classroomIds)
            ->orderByDesc('points')
            ->get();

        $achievements = Achievement::all();

        return view('guru.dashboard', compact('pendingMissions', 'siswas', 'achievements', 'assignments'));
    }

    public function mySchedule()
    {
        $teacher = auth()->user();

        // Ambil penugasan mengajar guru yang aktif beserta kelas dan mata pelajarannya
        $assignments = \App\Models\TeachingAssignment::with(['classroom.jurusan', 'subject', 'academicYear', 'semester'])
            ->where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->get();

        return view('guru.my_schedule', compact('assignments'));
    }

    public function storeMission(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'points_reward' => 'required|integer|min:1',
            'type' => 'required|in:daily,weekly,class,school,special',
            'deadline' => 'nullable|date',
            'proof_type' => 'required|in:file,link,text,none',
        ]);

        Mission::create([
            'title' => $request->title,
            'description' => $request->description,
            'points_reward' => $request->points_reward,
            'type' => $request->type,
            'deadline' => $request->deadline,
            'proof_type' => $request->proof_type,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Misi baru berhasil dibuat!');
    }

    public function validateMission(Request $request, PointService $pointService)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'mission_id' => 'required|exists:missions,id',
            'status' => 'required|in:approved,rejected,revision',
            'notes' => 'nullable|string',
        ]);

        $user = User::findOrFail($request->user_id);
        $mission = Mission::findOrFail($request->mission_id);

        $user->missions()->updateExistingPivot($request->mission_id, [
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        if ($request->status === 'approved') {
            $pointService->addPoints($user, $mission->points_reward, 'kebaikan', 'Misi: ' . $mission->title);
            $msg = 'Misi disetujui dan poin telah ditambahkan ke siswa.';
        } elseif ($request->status === 'revision') {
            $msg = 'Permintaan revisi misi berhasil dikirim ke siswa.';
        } else {
            $msg = 'Misi ditolak.';
        }

        return redirect()->back()->with('success', $msg);
    }

    public function adjustPoints(Request $request, PointService $pointService)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:kebaikan,pelanggaran',
            'operation' => 'required|in:add,subtract',
            'amount' => 'required|integer|min:1',
            'description' => 'required|string|max:255',
        ]);

        $user = User::findOrFail($request->user_id);
        
        $points = $request->amount;
        $type = $request->type;

        if ($request->operation === 'subtract') {
            $points = -$points;
            $type = 'pelanggaran';
        } else {
            $type = 'kebaikan';
        }

        $source = 'Guru: ' . auth()->user()->name;
        $pointService->addPoints($user, $points, $type, $source, $request->description);

        // Catat di PointAudit agar terpantau oleh admin
        \App\Models\PointAudit::create([
            'actor_id'       => auth()->id(),
            'target_user_id' => $user->id,
            'action_type'    => 'adjust',
            'amount'         => $points,
            'point_type'     => $type,
            'notes'          => 'Guru menyesuaikan poin: ' . $request->description,
        ]);

        return redirect()->back()->with('success', 'Poin siswa berhasil disesuaikan.');
    }

    public function toggleBadge(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'achievement_id' => 'required|exists:achievements,id',
            'action' => 'required|in:award,revoke',
        ]);

        $user = User::findOrFail($request->user_id);
        
        if ($request->action === 'award') {
            $user->achievements()->syncWithoutDetaching([$request->achievement_id]);
            $msg = 'Lencana berhasil diberikan kepada siswa.';
        } else {
            $user->achievements()->detach($request->achievement_id);
            $msg = 'Lencana berhasil dicabut dari siswa.';
        }

        return redirect()->back()->with('success', $msg);
    }

    public function assignmentDetail($id)
    {
        $teacher = auth()->user();

        $assignment = \App\Models\TeachingAssignment::with(['classroom.jurusan', 'subject', 'academicYear', 'semester'])
            ->where('teacher_id', $teacher->id)
            ->findOrFail($id);

        $students = User::where('role_id', 5)
            ->where('classroom_id', $assignment->classroom_id)
            ->orderBy('name')
            ->get();

        $sessions = \App\Models\AttendanceSession::with([
            'materials',
            'assignments',
            'attendances.student'
        ])
        ->withCount(['attendances as present_count' => fn($q) => $q->where('status', 'hadir')])
        ->where('teaching_assignment_id', $assignment->id)
        ->orderBy('meeting_number')
        ->get();

        $locations = \App\Models\SchoolLocation::where('is_active', true)->get();

        $sessionIds = $sessions->pluck('id');
        $assignmentSubmissions = \App\Models\AssignmentSubmission::with(['assignment', 'student'])
            ->whereHas('assignment', fn($q) => $q->whereIn('attendance_session_id', $sessionIds))
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('guru.assignment_detail', compact(
            'assignment', 
            'students', 
            'sessions', 
            'locations', 
            'assignmentSubmissions'
        ));
    }

    public function missionsIndex()
    {
        $missions = \App\Models\Mission::latest()->get();
        $events = \App\Models\Event::latest()->get();
        return view('guru.missions_events', compact('missions', 'events'));
    }

    public function showAwardMission($id)
    {
        $mission = \App\Models\Mission::findOrFail($id);
        $classrooms = \App\Models\Classroom::with(['students' => function ($q) {
            $q->where('role_id', 5);
        }])->get();

        $noClassStudents = \App\Models\User::where('role_id', 5)
            ->whereNull('classroom_id')
            ->get();

        $query = \Illuminate\Support\Facades\DB::table('mission_user')
            ->where('mission_id', $mission->id)
            ->where('status', 'approved');
        if ($mission->type === 'daily') {
            $query->whereDate('updated_at', now()->toDateString());
        }
        $completedStudentIds = $query->pluck('user_id')->toArray();

        return view('guru.award_mission', compact('mission', 'classrooms', 'noClassStudents', 'completedStudentIds'));
    }

    public function awardMission(Request $request, $id, PointService $pointService)
    {
        $mission = \App\Models\Mission::findOrFail($id);
        $studentIds = $request->input('student_ids', []);

        $query = \Illuminate\Support\Facades\DB::table('mission_user')
            ->where('mission_id', $mission->id)
            ->where('status', 'approved');
        if ($mission->type === 'daily') {
            $query->whereDate('updated_at', now()->toDateString());
        }
        $completedStudentIds = $query->pluck('user_id')->toArray();

        $awardedCount = 0;
        foreach ($studentIds as $studentId) {
            if (!in_array($studentId, $completedStudentIds)) {
                $student = \App\Models\User::findOrFail($studentId);

                $existing = $student->missions()->where('mission_id', $mission->id)->first();
                if (!$existing) {
                    $student->missions()->attach($mission->id, [
                        'status' => 'approved',
                        'notes' => 'Reward manual oleh Guru'
                    ]);
                } else {
                    $student->missions()->updateExistingPivot($mission->id, [
                        'status' => 'approved',
                        'notes' => 'Reward manual oleh Guru'
                    ]);
                }

                $pointService->addPoints($student, $mission->points_reward, 'kebaikan', 'Misi: ' . $mission->title, 'Verifikasi manual oleh Guru');
                $awardedCount++;
            }
        }

        return redirect()->back()->with('success', "Berhasil memberikan reward misi kepada {$awardedCount} siswa.");
    }

    public function showAwardEvent($id)
    {
        $event = \App\Models\Event::findOrFail($id);
        $classrooms = \App\Models\Classroom::with(['students' => function ($q) {
            $q->where('role_id', 5);
        }])->get();

        $noClassStudents = \App\Models\User::where('role_id', 5)
            ->whereNull('classroom_id')
            ->get();

        $completedStudentIds = \Illuminate\Support\Facades\DB::table('event_user')
            ->where('event_id', $event->id)
            ->where('status', 'completed')
            ->pluck('user_id')
            ->toArray();

        return view('guru.award_event', compact('event', 'classrooms', 'noClassStudents', 'completedStudentIds'));
    }

    public function awardEvent(Request $request, $id, PointService $pointService)
    {
        $event = \App\Models\Event::findOrFail($id);
        $studentIds = $request->input('student_ids', []);

        $completedStudentIds = \Illuminate\Support\Facades\DB::table('event_user')
            ->where('event_id', $event->id)
            ->where('status', 'completed')
            ->pluck('user_id')
            ->toArray();

        $awardedCount = 0;
        foreach ($studentIds as $studentId) {
            if (!in_array($studentId, $completedStudentIds)) {
                $student = \App\Models\User::findOrFail($studentId);
                
                $student->events()->attach($event->id, [
                    'status' => 'completed'
                ]);

                $pointService->addPoints($student, $event->points_bonus, 'kebaikan', 'Event: ' . $event->title, 'Reward partisipasi event manual');
                $awardedCount++;
            }
        }

        return redirect()->back()->with('success', "Berhasil memberikan reward event kepada {$awardedCount} siswa.");
    }
}
