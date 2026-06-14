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

    public function dompet()
    {
        $user = auth()->user();

        // Riwayat Poin (Kebaikan/Pelanggaran/Penyesuaian)
        $pointHistory = \App\Models\PointHistory::where('user_id', $user->id)
            ->latest()
            ->get();

        // Riwayat Belanja di Toko
        $shopTransactions = \App\Models\ShopTransaction::with('shop')
            ->where('student_user_id', $user->id)
            ->latest()
            ->get();

        return view('student.dompet', compact('user', 'pointHistory', 'shopTransactions'));
    }

    public function generateTransferQr(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:1'
        ]);

        $user = auth()->user();

        if ($user->points < $request->amount) {
            return response()->json(['success' => false, 'message' => 'Saldo poin tidak mencukupi.'], 400);
        }

        $token = \Illuminate\Support\Str::random(32);
        
        \Illuminate\Support\Facades\Cache::put('transfer_qr_' . $token, [
            'sender_id' => $user->id,
            'amount' => $request->amount,
            'status' => 'pending'
        ], now()->addMinutes(10));

        return response()->json([
            'success' => true,
            'token' => $token,
            'qr_url' => route('student.transfer.claim.page', ['token' => $token])
        ]);
    }

    public function cancelTransferQr($token)
    {
        $transfer = \Illuminate\Support\Facades\Cache::get('transfer_qr_' . $token);
        if ($transfer && $transfer['sender_id'] == auth()->id()) {
            \Illuminate\Support\Facades\Cache::forget('transfer_qr_' . $token);
        }
        return response()->json(['success' => true]);
    }

    public function checkTransferStatus($token)
    {
        $transfer = \Illuminate\Support\Facades\Cache::get('transfer_qr_' . $token);
        if (!$transfer) {
            return response()->json(['status' => 'not_found']);
        }
        
        return response()->json(['status' => $transfer['status']]);
    }

    public function claimTransferPage($token)
    {
        $transfer = \Illuminate\Support\Facades\Cache::get('transfer_qr_' . $token);
        
        if (!$transfer) {
            return redirect()->route('student.dompet')->with('error', 'QR Code transfer tidak valid atau sudah kedaluwarsa.');
        }

        $sender = \App\Models\User::find($transfer['sender_id']);
        if (!$sender || $sender->id == auth()->id()) {
            return redirect()->route('student.dompet')->with('error', 'Anda tidak bisa menerima poin dari diri sendiri.');
        }

        return view('student.transfer_claim', compact('transfer', 'sender', 'token'));
    }

    public function processTransferClaim(Request $request, $token)
    {
        // Hindari Cache::pull agar tidak terjadi race condition saat sender melakukan polling
        $receiver = auth()->user();

        $lockKey = 'lock_transfer_qr_' . $token;
        $lock = \Illuminate\Support\Facades\Cache::lock($lockKey, 10);

        if (!$lock->get()) {
            return redirect()->route('student.dompet')->with('error', 'Transaksi sedang diproses, silakan coba lagi.');
        }

        try {
            $transfer = \Illuminate\Support\Facades\Cache::get('transfer_qr_' . $token);

            if (!$transfer || $transfer['status'] === 'claimed') {
                $lock->release();
                return redirect()->route('student.dompet')->with('error', 'QR Code transfer sudah tidak berlaku atau sudah diklaim.');
            }

            $sender = \App\Models\User::find($transfer['sender_id']);
            if (!$sender || $sender->id == $receiver->id) {
                $lock->release();
                return redirect()->route('student.dompet')->with('error', 'Transfer dibatalkan.');
            }

            if ($sender->points < $transfer['amount']) {
                $lock->release();
                return redirect()->route('student.dompet')->with('error', 'Pengirim tidak memiliki poin yang cukup saat ini.');
            }

            \Illuminate\Support\Facades\DB::transaction(function () use ($sender, $receiver, $transfer) {
                // Kurangi pengirim
                $sender->decrement('points', $transfer['amount']);
                \App\Models\PointHistory::create([
                    'user_id' => $sender->id,
                    'points' => -$transfer['amount'],
                    'type' => 'transfer_out',
                    'source' => 'Dompet Poin',
                    'description' => 'Transfer Poin ke ' . $receiver->name
                ]);

                // Tambah penerima
                $receiver->increment('points', $transfer['amount']);
                \App\Models\PointHistory::create([
                    'user_id' => $receiver->id,
                    'points' => $transfer['amount'],
                    'type' => 'transfer_in',
                    'source' => 'Dompet Poin',
                    'description' => 'Terima Poin dari ' . $sender->name
                ]);
            });

            // Set status untuk polling dari device pengirim menjadi 'claimed'
            \Illuminate\Support\Facades\Cache::put('transfer_qr_' . $token, array_merge($transfer, ['status' => 'claimed']), now()->addMinutes(1));

            $lock->release();
            return redirect()->route('student.dompet')->with('success', 'Berhasil menerima ' . $transfer['amount'] . ' poin dari ' . $sender->name . '!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Transfer Claim Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            $lock->release();
            return redirect()->route('student.dompet')->with('error', 'Terjadi kesalahan saat memproses transfer. ' . $e->getMessage());
        }
    }
}
