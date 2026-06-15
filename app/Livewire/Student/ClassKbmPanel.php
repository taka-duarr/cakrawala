<?php

namespace App\Livewire\Student;

use Livewire\Component;
use App\Models\TeachingAssignment;
use App\Models\AttendanceSession;
use App\Models\Attendance;
use App\Models\AssignmentSubmission;
use Illuminate\Support\Facades\Auth;

class ClassKbmPanel extends Component
{
    public $assignmentId;
    public $activeMeeting = null;

    public function mount($assignmentId)
    {
        $this->assignmentId = $assignmentId;
        
        // Find default active meeting (first meeting that is open, or default to 1)
        $sessions = AttendanceSession::where('teaching_assignment_id', $this->assignmentId)->get();
        $openSession = $sessions->firstWhere('status', 'open');
        if ($openSession) {
            $this->activeMeeting = $openSession->meeting_number;
        } else {
            $this->activeMeeting = 1;
        }
    }

    public function selectMeeting($number)
    {
        $this->activeMeeting = $number;
    }

    public function render()
    {
        $user = Auth::user();
        
        $assignment = TeachingAssignment::with(['teacher', 'subject', 'academicYear', 'semester', 'classroom.jurusan'])
            ->findOrFail($this->assignmentId);

        $sessions = AttendanceSession::with(['materials', 'assignments'])
            ->where('teaching_assignment_id', $assignment->id)
            ->orderBy('meeting_number')
            ->get();

        $myAttendances = Attendance::whereIn('attendance_session_id', $sessions->pluck('id'))
            ->where('student_id', $user->id)
            ->get()
            ->keyBy('attendance_session_id');

        $assignmentIds = $sessions->flatMap(fn($s) => $s->assignments->pluck('id'))->unique();
        $assignmentSubmissions = AssignmentSubmission::whereIn('assignment_id', $assignmentIds)
            ->where('student_id', $user->id)
            ->get()
            ->keyBy('assignment_id');

        return view('livewire.student.class-kbm-panel', [
            'user' => $user,
            'assignment' => $assignment,
            'sessions' => $sessions,
            'myAttendances' => $myAttendances,
            'assignmentSubmissions' => $assignmentSubmissions,
        ]);
    }
}
