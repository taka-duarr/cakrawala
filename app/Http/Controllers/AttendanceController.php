<?php

namespace App\Http\Controllers;

use App\Models\TeachingAssignment;
use App\Models\AttendanceSession;
use App\Models\Attendance;
use App\Models\Material;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\SchoolLocation;
use App\Services\PointService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // Helper to calculate distance in meters using Haversine formula
    private function getDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // in meters

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c);
    }

    // Guru opens a meeting session
    public function storeSession(Request $request, $assignmentId)
    {
        $assignment = TeachingAssignment::findOrFail($assignmentId);

        $request->validate([
            'session_date' => 'required|date',
            'deadline' => 'required|date_format:Y-m-d\TH:i|after:session_date',
            'mode' => 'required|in:qr_location,button_location',
            'school_location_id' => 'nullable|exists:school_locations,id',
            'materials.*' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,zip|max:10240', // max 10MB
            'material_titles.*' => 'nullable|string|max:255',
            'assignment_titles.*' => 'nullable|string|max:255',
            'assignment_descriptions.*' => 'nullable|string',
            'assignment_files.*' => 'nullable|file|mimes:pdf,zip,jpg,jpeg,png|max:10240',
            'assignment_points.*' => 'nullable|integer|min:1',
            'assignment_deadlines.*' => 'nullable|date_format:Y-m-d\TH:i|after:session_date',
        ]);

        // Pre-validate assignment arrays mapping
        if ($request->has('assignment_titles')) {
            $titles = $request->input('assignment_titles') ?? [];
            $descriptions = $request->input('assignment_descriptions') ?? [];
            foreach ($titles as $index => $title) {
                if (!empty($title) && empty($descriptions[$index])) {
                    return redirect()->back()->withInput()->with('error', "Deskripsi Tugas ke-" . ($index + 1) . " wajib diisi jika judul tugas diisi.");
                }
                if (empty($title) && !empty($descriptions[$index])) {
                    return redirect()->back()->withInput()->with('error', "Judul Tugas ke-" . ($index + 1) . " wajib diisi jika deskripsi tugas diisi.");
                }
            }
        }

        // Auto calculate meeting number
        $maxMeeting = AttendanceSession::where('teaching_assignment_id', $assignment->id)
            ->max('meeting_number');
        $meetingNumber = ($maxMeeting ?? 0) + 1;

        // Create session
        $session = AttendanceSession::create([
            'teaching_assignment_id' => $assignment->id,
            'meeting_number' => $meetingNumber,
            'session_date' => $request->session_date,
            'deadline' => Carbon::parse($request->deadline),
            'mode' => $request->mode,
            'school_location_id' => $request->school_location_id,
            'qr_token' => Str::random(32),
            'status' => 'open',
        ]);

        // Send notifications to students
        $students = $assignment->classroom->students ?? [];
        foreach ($students as $student) {
            \App\Models\Notification::create([
                'user_id' => $student->id,
                'title' => 'Presensi Kelas Dibuka!',
                'body' => "Presensi untuk mata pelajaran {$assignment->subject->name} (Pertemuan Ke-{$meetingNumber}) telah dibuka oleh Guru {$assignment->teacher->name}. Silakan lakukan presensi sebelum " . Carbon::parse($request->deadline)->translatedFormat('d F Y \p\u\k\u\l H:i') . ".",
                'category' => 'attendance',
                'icon' => 'bell',
                'is_unread' => true,
            ]);
        }

        // Upload Materials
        if ($request->hasFile('materials')) {
            $files = $request->file('materials');
            $titles = $request->input('material_titles');
            foreach ($files as $index => $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('materials', 'public');
                    $title = !empty($titles[$index]) ? $titles[$index] : $file->getClientOriginalName();
                    Material::create([
                        'attendance_session_id' => $session->id,
                        'title' => $title,
                        'file_path' => asset('storage/' . $path),
                    ]);
                }
            }
        }

        // Create Assignments
        if ($request->has('assignment_titles')) {
            $titles = $request->input('assignment_titles') ?? [];
            $descriptions = $request->input('assignment_descriptions') ?? [];
            $points = $request->input('assignment_points') ?? [];
            $deadlines = $request->input('assignment_deadlines') ?? [];
            $files = $request->file('assignment_files') ?? [];

            foreach ($titles as $index => $title) {
                if (!empty($title) && !empty($descriptions[$index])) {
                    $assignmentFilePath = null;
                    if (isset($files[$index]) && $files[$index]->isValid()) {
                        $path = $files[$index]->store('assignments', 'public');
                        $assignmentFilePath = asset('storage/' . $path);
                    }

                    Assignment::create([
                        'attendance_session_id' => $session->id,
                        'title' => $title,
                        'description' => $descriptions[$index],
                        'file_path' => $assignmentFilePath,
                        'points_reward' => !empty($points[$index]) ? $points[$index] : 15,
                        'deadline' => !empty($deadlines[$index]) ? Carbon::parse($deadlines[$index]) : $session->deadline,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', "Pertemuan Ke-{$meetingNumber} berhasil dibuka dengan materi/tugas!");
    }

    // Guru closes a meeting session manually
    public function closeSession($id)
    {
        $session = AttendanceSession::findOrFail($id);
        $session->update(['status' => 'closed']);

        return redirect()->back()->with('success', 'Sesi absensi pertemuan ini berhasil ditutup.');
    }

    // Add Material to an existing session
    public function addMaterial(Request $request, $sessionId)
    {
        $session = AttendanceSession::findOrFail($sessionId);

        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,zip|max:10240',
        ]);

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $path = $request->file('file')->store('materials', 'public');
            Material::create([
                'attendance_session_id' => $session->id,
                'title' => $request->title,
                'file_path' => asset('storage/' . $path),
            ]);
            return redirect()->back()->with('success', 'Materi berhasil ditambahkan!');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah materi.');
    }

    // Add Assignment to an existing session
    public function addAssignment(Request $request, $sessionId)
    {
        $session = AttendanceSession::findOrFail($sessionId);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,zip,jpg,jpeg,png|max:10240',
            'points_reward' => 'required|integer|min:1',
            'deadline' => 'required|date_format:Y-m-d\TH:i',
        ]);

        $assignmentFilePath = null;
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $path = $request->file('file')->store('assignments', 'public');
            $assignmentFilePath = asset('storage/' . $path);
        }

        Assignment::create([
            'attendance_session_id' => $session->id,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $assignmentFilePath,
            'points_reward' => $request->points_reward,
            'deadline' => Carbon::parse($request->deadline),
        ]);

        return redirect()->back()->with('success', 'Tugas baru berhasil ditambahkan!');
    }

    // Geolocation Checker Core Logic
    private function verifyStudentLocation(AttendanceSession $session, $studentLat, $studentLng)
    {
        // Get coordinates list to check against
        $locations = [];
        if ($session->school_location_id) {
            $locations[] = SchoolLocation::findOrFail($session->school_location_id);
        } else {
            // Check against all active school locations
            $locations = SchoolLocation::where('is_active', true)->get();
        }

        if (count($locations) === 0) {
            // If admin hasn't set any locations, bypass coordinate check
            return [
                'success' => true,
                'distance' => 0,
                'location' => null
            ];
        }

        $minDistance = null;
        $matchingLocation = null;

        foreach ($locations as $loc) {
            $dist = $this->getDistance($studentLat, $studentLng, $loc->latitude, $loc->longitude);
            if ($dist <= $loc->radius) {
                return [
                    'success' => true,
                    'distance' => $dist,
                    'location' => $loc
                ];
            }
            if ($minDistance === null || $dist < $minDistance) {
                $minDistance = $dist;
                $matchingLocation = $loc;
            }
        }

        return [
            'success' => false,
            'distance' => $minDistance,
            'location' => $matchingLocation
        ];
    }

    // Student Check-In (Mode Button)
    public function checkIn(Request $request, $sessionId, PointService $pointService)
    {
        $session = AttendanceSession::findOrFail($sessionId);
        $student = Auth::user();

        if ($student->classroom_id !== $session->teachingAssignment->classroom_id) {
            return response()->json(['success' => false, 'message' => 'Anda tidak terdaftar di kelas untuk mata pelajaran ini.'], 403);
        }

        if ($session->status !== 'open' || Carbon::now()->gt($session->deadline)) {
            return response()->json(['success' => false, 'message' => 'Sesi absensi sudah ditutup atau telah melewati batas tenggat.'], 422);
        }

        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $locCheck = $this->verifyStudentLocation($session, $request->latitude, $request->longitude);

        if (!$locCheck['success']) {
            $msg = "Absen ditolak. Anda berada di luar radius lokasi sekolah. Jarak terdekat Anda: " . $locCheck['distance'] . " meter dari titik " . ($locCheck['location'] ? $locCheck['location']->name : 'Sekolah');
            return response()->json(['success' => false, 'message' => $msg], 422);
        }

        $student = Auth::user();

        // Check if student already checked in
        $exists = Attendance::where('attendance_session_id', $session->id)
            ->where('student_id', $student->id)
            ->first();

        if ($exists && $exists->status !== 'alpa') {
            return response()->json(['success' => false, 'message' => 'Anda sudah melakukan absensi pada pertemuan ini.'], 422);
        }

        $points = 10; // 10 points for on-time presence

        if ($exists) {
            $exists->update([
                'status' => 'hadir',
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'distance_meters' => $locCheck['distance'],
                'points_awarded' => $points,
            ]);
        } else {
            Attendance::create([
                'attendance_session_id' => $session->id,
                'student_id' => $student->id,
                'status' => 'hadir',
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'distance_meters' => $locCheck['distance'],
                'points_awarded' => $points,
            ]);
        }

        // Award Points
        $pointService->addPoints($student, $points, 'kebaikan', 'Presensi', 'Hadir tepat waktu pertemuan ' . $session->meeting_number);

        // Auto mission trigger
        app(\App\Services\AutoMissionService::class)->triggerAttendance($student);

        return response()->json([
            'success' => true, 
            'message' => "Absen Berhasil! Anda terverifikasi di lokasi {$locCheck['location']->name} (Jarak: {$locCheck['distance']}m) dan mendapatkan +{$points} poin disiplin."
        ]);
    }

    // Show Scan Page for Students (Mode QR)
    public function scanPage($token)
    {
        $session = AttendanceSession::with('teachingAssignment.subject')->where('qr_token', $token)->firstOrFail();
        return view('student.attendance_scan', compact('session'));
    }

    // Student Check-In (Mode QR submit)
    public function checkInScan(Request $request, $token, PointService $pointService)
    {
        $session = AttendanceSession::where('qr_token', $token)->firstOrFail();
        $student = Auth::user();

        if ($student->classroom_id !== $session->teachingAssignment->classroom_id) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar di kelas untuk mata pelajaran ini.');
        }

        if ($session->status !== 'open' || Carbon::now()->gt($session->deadline)) {
            return redirect()->route('dashboard')->with('error', 'Sesi absensi QR sudah ditutup atau telah melewati batas tenggat.');
        }

        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $locCheck = $this->verifyStudentLocation($session, $request->latitude, $request->longitude);

        if (!$locCheck['success']) {
            $msg = "Absen QR Gagal! Anda berada di luar radius lokasi sekolah. Jarak terdekat Anda: " . $locCheck['distance'] . " meter dari " . ($locCheck['location'] ? $locCheck['location']->name : 'Sekolah');
            return redirect()->back()->withInput()->with('error', $msg);
        }

        $student = Auth::user();

        $exists = Attendance::where('attendance_session_id', $session->id)
            ->where('student_id', $student->id)
            ->first();

        if ($exists && $exists->status !== 'alpa') {
            return redirect()->route('dashboard')->with('info', 'Anda sudah melakukan absensi pada pertemuan ini.');
        }

        $points = 10;

        if ($exists) {
            $exists->update([
                'status' => 'hadir',
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'distance_meters' => $locCheck['distance'],
                'points_awarded' => $points,
            ]);
        } else {
            Attendance::create([
                'attendance_session_id' => $session->id,
                'student_id' => $student->id,
                'status' => 'hadir',
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'distance_meters' => $locCheck['distance'],
                'points_awarded' => $points,
            ]);
        }

        $pointService->addPoints($student, $points, 'kebaikan', 'Presensi QR', 'Hadir via QR code pertemuan ' . $session->meeting_number);

        // Auto mission trigger
        app(\App\Services\AutoMissionService::class)->triggerAttendance($student);

        return redirect()->route('student.class-detail', $session->teaching_assignment_id)
            ->with('success', "Absen QR Berhasil! Anda berada di lokasi {$locCheck['location']->name} (Jarak: {$locCheck['distance']}m) dan mendapatkan +{$points} Pts.");
    }

    // Student submits assignment
    public function submitAssignment(Request $request, $assignmentId)
    {
        $assignment = Assignment::findOrFail($assignmentId);

        if (Carbon::now()->gt($assignment->deadline)) {
            return redirect()->back()->with('error', 'Batas tenggat pengumpulan tugas ini sudah terlewati.');
        }

        $request->validate([
            'file' => 'nullable|file|mimes:pdf,zip,jpg,jpeg,png,doc,docx|max:10240',
            'text_content' => 'nullable|string',
        ]);

        if (!$request->hasFile('file') && !$request->filled('text_content')) {
            return redirect()->back()->with('error', 'Harap isi teks jawaban atau unggah file tugas.');
        }

        $filePath = null;
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $path = $request->file('file')->store('submissions', 'public');
            $filePath = asset('storage/' . $path);
        }

        $student = Auth::user();

        $updateData = [
            'text_content' => $request->text_content,
            'status' => 'pending',
            'notes' => null,
        ];

        if ($filePath) {
            $updateData['file_path'] = $filePath;
        }

        AssignmentSubmission::updateOrCreate(
            ['assignment_id' => $assignment->id, 'student_id' => $student->id],
            $updateData
        );

        return redirect()->back()->with('success', 'Tugas berhasil dikumpulkan dan sedang menunggu persetujuan guru.');
    }

    // Teacher grades student assignment submission
    public function gradeSubmission(Request $request, $submissionId, PointService $pointService)
    {
        $submission = AssignmentSubmission::with('assignment')->findOrFail($submissionId);

        $request->validate([
            'status' => 'required|in:approved,rejected,revision',
            'notes' => 'nullable|string',
        ]);

        $submission->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        $student = $submission->student;
        $rewardPoints = $submission->assignment->points_reward;

        if ($request->status === 'approved') {
            $submission->update(['points_awarded' => $rewardPoints]);
            $pointService->addPoints($student, $rewardPoints, 'kebaikan', 'Tugas: ' . $submission->assignment->title, 'Tugas disetujui oleh guru.');
            $msg = 'Tugas disetujui dan poin sebesar +' . $rewardPoints . ' telah diberikan kepada siswa.';
        } elseif ($request->status === 'revision') {
            $msg = 'Umpan balik revisi tugas berhasil dikirimkan ke siswa.';
        } else {
            $msg = 'Tugas ditolak.';
        }

        return redirect()->back()->with('success', $msg);
    }
}
