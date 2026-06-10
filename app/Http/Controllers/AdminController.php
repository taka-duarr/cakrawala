<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Role;
use App\Models\PointHistory;
use App\Models\PointAudit;
use App\Models\Setting;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\Jurusan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $totalSiswa = User::where('role_id', 5)->count();
        $totalGuru = User::where('role_id', 2)->count();
        $totalKelas = Classroom::count();
        $totalPoinSekolah = User::where('role_id', 5)->sum('points');

        // Top 10 Siswa
        $topSiswa = User::where('role_id', 5)
            ->orderByDesc('points')
            ->limit(10)
            ->get();

        // Top 10 Kelas
        $topKelas = Classroom::orderByDesc('points')
            ->limit(10)
            ->get();

        // Aktivitas Terbaru
        $aktivitasTerbaru = PointHistory::with('user')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalSiswa',
            'totalGuru',
            'totalKelas',
            'totalPoinSekolah',
            'topSiswa',
            'topKelas',
            'aktivitasTerbaru'
        ));
    }

    // --- User Management ---
    public function usersIndex(Request $request)
    {
        $roles = Role::whereIn('id', [1])->get();
        $classrooms = Classroom::all();

        $search = $request->input('search');

        $usersQuery = User::with(['role'])->whereIn('role_id', [1]);

        if ($search) {
            $usersQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $usersQuery->orderBy('id', 'desc')->paginate(15);

        return view('admin.users', compact('users', 'roles', 'classrooms'));
    }

    public function parentsIndex(Request $request)
    {
        $roles = Role::whereIn('id', [4])->get();
        $classrooms = Classroom::all();

        $search = $request->input('search');

        $usersQuery = User::with(['role', 'children'])->whereIn('role_id', [4]);

        if ($search) {
            $usersQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $usersQuery->orderBy('id', 'desc')->paginate(15);
        $students = User::where('role_id', 5)->orderBy('name')->get();

        return view('admin.parents', compact('users', 'roles', 'classrooms', 'students'));
    }

    public function teachersIndex(Request $request)
    {
        $roles = Role::whereIn('id', [2, 3])->get();
        $classrooms = Classroom::all();

        $search = $request->input('search');

        $usersQuery = User::with(['role', 'classroom'])->whereIn('role_id', [2, 3]);

        if ($search) {
            $usersQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $usersQuery->orderBy('id', 'desc')->paginate(15);

        return view('admin.teachers', compact('users', 'roles', 'classrooms'));
    }

    public function studentsIndex(Request $request)
    {
        $roles = Role::whereIn('id', [5])->get();
        $classrooms = Classroom::all();

        $search = $request->input('search');
        $classroomId = $request->input('classroom_id');

        $usersQuery = User::with(['role', 'classroom'])->where('role_id', 5);

        if ($classroomId) {
            $usersQuery->where('classroom_id', $classroomId);
        }

        if ($search) {
            $usersQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $usersQuery->orderBy('id', 'desc')->paginate(15);

        return view('admin.students', compact('users', 'roles', 'classrooms'));
    }

    public function usersStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'parent_student_id' => 'nullable|exists:users,id',
        ]);

        if ($request->role_id == 3 && $request->classroom_id) {
            // Remove classroom assignment from any existing wali kelas of this class
            User::where('classroom_id', $request->classroom_id)
                ->where('role_id', 3)
                ->update(['classroom_id' => null]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'classroom_id' => $request->classroom_id,
            'is_active' => true,
        ]);

        if ($request->role_id == 4 && $request->parent_student_id) {
            $user->children()->attach($request->parent_student_id);
        }

        return redirect()->back()->with('success', 'User berhasil ditambahkan!');
    }

    public function usersUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role_id' => 'required|exists:roles,id',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'parent_student_id' => 'nullable|exists:users,id',
        ]);

        if ($request->role_id == 3 && $request->classroom_id) {
            // Remove classroom assignment from other wali kelas of this class
            User::where('classroom_id', $request->classroom_id)
                ->where('role_id', 3)
                ->where('id', '!=', $user->id)
                ->update(['classroom_id' => null]);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->classroom_id = $request->classroom_id;
        $user->save();

        if ($request->role_id == 4) {
            if ($request->parent_student_id) {
                $user->children()->sync([$request->parent_student_id]);
            } else {
                $user->children()->detach();
            }
        }

        return redirect()->back()->with('success', 'User berhasil diperbarui!');
    }

    public function usersDestroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus!');
    }

    public function usersToggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "User {$user->name} berhasil {$status}!");
    }

    public function usersResetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'password' => 'required|string|min:8',
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', "Password untuk user {$user->name} berhasil direset!");
    }

    // --- Classroom Management ---
    public function classroomsIndex()
    {
        $classrooms = Classroom::with([
            'jurusan', 'academicYear', 'semester',
            'users' => fn($q) => $q->where('role_id', 3)
        ])->withCount(['users as students_count' => fn($q) => $q->where('role_id', 5)])
          ->orderBy('grade_level')->orderBy('name')->get();

        $waliKelasCandidates = User::where('role_id', 3)->orderBy('name')->get();
        $jurusans = Jurusan::orderBy('name')->get();
        $academicYears = AcademicYear::orderByDesc('name')->get();
        $semesters = Semester::with('academicYear')->orderByDesc('id')->get();
        // Semua siswa (termasuk yang sudah di kelas lain — untuk pindah kelas)
        $semuaSiswa = User::where('role_id', 5)->with('classroom')->orderBy('name')->get();

        return view('admin.classrooms', compact(
            'classrooms', 'waliKelasCandidates', 'jurusans', 'academicYears', 'semesters', 'semuaSiswa'
        ));
    }

    public function classroomsStore(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255|unique:classrooms,name',
            'grade_level'      => 'nullable|integer|in:10,11,12',
            'jurusan_id'       => 'nullable|exists:jurusans,id',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'semester_id'      => 'nullable|exists:semesters,id',
            'wali_kelas_id'    => 'nullable|exists:users,id',
        ]);

        $classroom = Classroom::create([
            'name'             => $request->name,
            'points'           => 0,
            'grade_level'      => $request->grade_level,
            'jurusan_id'       => $request->jurusan_id,
            'academic_year_id' => $request->academic_year_id,
            'semester_id'      => $request->semester_id,
        ]);

        if ($request->wali_kelas_id) {
            User::where('classroom_id', $classroom->id)->where('role_id', 3)->update(['classroom_id' => null]);
            User::findOrFail($request->wali_kelas_id)->update(['classroom_id' => $classroom->id]);
        }

        return redirect()->back()->with('success', 'Kelas baru berhasil ditambahkan!');
    }

    public function classroomsUpdate(Request $request, $id)
    {
        $classroom = Classroom::findOrFail($id);

        $request->validate([
            'name'             => 'required|string|max:255|unique:classrooms,name,' . $id,
            'grade_level'      => 'nullable|integer|in:10,11,12',
            'jurusan_id'       => 'nullable|exists:jurusans,id',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'semester_id'      => 'nullable|exists:semesters,id',
            'wali_kelas_id'    => 'nullable|exists:users,id',
        ]);

        $classroom->update([
            'name'             => $request->name,
            'grade_level'      => $request->grade_level,
            'jurusan_id'       => $request->jurusan_id,
            'academic_year_id' => $request->academic_year_id,
            'semester_id'      => $request->semester_id,
        ]);

        // Update wali kelas
        User::where('classroom_id', $classroom->id)->where('role_id', 3)->update(['classroom_id' => null]);
        if ($request->wali_kelas_id) {
            User::findOrFail($request->wali_kelas_id)->update(['classroom_id' => $classroom->id]);
        }

        return redirect()->back()->with('success', 'Kelas berhasil diperbarui!');
    }

    public function classroomsDestroy($id)
    {
        $classroom = Classroom::findOrFail($id);
        $classroom->delete();
        return redirect()->back()->with('success', 'Kelas berhasil dihapus!');
    }

    public function classroomEnrollStudent(Request $request, $id)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);
        $classroom = Classroom::findOrFail($id);
        $student = User::findOrFail($request->user_id);
        $oldClass = $student->classroom?->name ?? 'tanpa kelas';
        $student->classroom_id = $classroom->id;
        $student->save();
        $msg = $student->classroom_id !== $classroom->id
            ? "{$student->name} berhasil dipindahkan dari kelas {$oldClass} ke kelas {$classroom->name}!"
            : "{$student->name} berhasil ditempatkan ke kelas {$classroom->name}!";
        return redirect()->back()->with('success', "{$student->name} berhasil ditempatkan ke kelas {$classroom->name}!");
    }

    public function classroomUnenrollStudent($classroomId, $userId)
    {
        $student = User::findOrFail($userId);
        $student->classroom_id = null;
        $student->save();
        return redirect()->back()->with('success', "{$student->name} berhasil dikeluarkan dari kelas!");
    }

    // --- Academic Year Management ---
    public function academicYearsIndex()
    {
        $academicYears = AcademicYear::withCount('semesters')->orderByDesc('name')->get();
        return view('admin.academic-years', compact('academicYears'));
    }

    public function academicYearsStore(Request $request)
    {
        $request->validate(['name' => 'required|string|max:20|unique:academic_years,name']);
        AcademicYear::create(['name' => $request->name, 'is_active' => false]);
        return redirect()->back()->with('success', 'Tahun Ajaran berhasil ditambahkan!');
    }

    public function academicYearsUpdate(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:20|unique:academic_years,name,' . $id]);
        AcademicYear::findOrFail($id)->update(['name' => $request->name]);
        return redirect()->back()->with('success', 'Tahun Ajaran berhasil diperbarui!');
    }

    public function academicYearsDestroy($id)
    {
        AcademicYear::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Tahun Ajaran berhasil dihapus!');
    }

    public function academicYearsSetActive($id)
    {
        AcademicYear::query()->update(['is_active' => false]);
        AcademicYear::findOrFail($id)->update(['is_active' => true]);
        return redirect()->back()->with('success', 'Tahun Ajaran aktif berhasil diubah!');
    }

    // --- Semester Management ---
    public function semestersStore(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name'             => 'required|in:Ganjil,Genap',
        ]);
        Semester::create(['academic_year_id' => $request->academic_year_id, 'name' => $request->name, 'is_active' => false]);
        return redirect()->back()->with('success', 'Semester berhasil ditambahkan!');
    }

    public function semestersDestroy($id)
    {
        Semester::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Semester berhasil dihapus!');
    }

    public function semestersSetActive($id)
    {
        Semester::query()->update(['is_active' => false]);
        Semester::findOrFail($id)->update(['is_active' => true]);
        return redirect()->back()->with('success', 'Semester aktif berhasil diubah!');
    }

    // --- Jurusan Management ---
    public function jurusansIndex()
    {
        $jurusans = Jurusan::withCount('classrooms')->orderBy('name')->get();
        return view('admin.jurusans', compact('jurusans'));
    }

    public function jurusansStore(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:jurusans,name',
            'description' => 'nullable|string|max:255',
        ]);
        Jurusan::create($request->only('name', 'description'));
        return redirect()->back()->with('success', 'Jurusan berhasil ditambahkan!');
    }

    public function jurusansUpdate(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:jurusans,name,' . $id,
            'description' => 'nullable|string|max:255',
        ]);
        Jurusan::findOrFail($id)->update($request->only('name', 'description'));
        return redirect()->back()->with('success', 'Jurusan berhasil diperbarui!');
    }

    public function jurusansDestroy($id)
    {
        Jurusan::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Jurusan berhasil dihapus!');
    }

    // --- Currency & Point Settings ---
    public function currencySettingsIndex()
    {
        $settings = [
            'currency_name'     => Setting::get('currency_name', 'Poin'),
            'currency_symbol'   => Setting::get('currency_symbol', '⭐'),
            'kebaikan_label'    => Setting::get('kebaikan_label', 'Poin Kebaikan'),
            'pelanggaran_label' => Setting::get('pelanggaran_label', 'Poin Pelanggaran'),
        ];
        return view('admin.currency_settings', compact('settings'));
    }

    public function currencySettingsUpdate(Request $request)
    {
        $request->validate([
            'currency_name'     => 'required|string|max:50',
            'currency_symbol'   => 'required|string|max:10',
            'kebaikan_label'    => 'required|string|max:50',
            'pelanggaran_label' => 'required|string|max:50',
        ]);

        Setting::set('currency_name',     $request->currency_name,     'Nama/sebutan poin virtual');
        Setting::set('currency_symbol',   $request->currency_symbol,   'Simbol poin virtual');
        Setting::set('kebaikan_label',    $request->kebaikan_label,    'Label poin positif');
        Setting::set('pelanggaran_label', $request->pelanggaran_label, 'Label poin negatif');

        return redirect()->back()->with('success', 'Pengaturan poin berhasil disimpan!');
    }

    // --- Point History ---
    public function pointHistoryIndex(Request $request)
    {
        $students = User::where('role_id', 5)->orderBy('name')->get();
        $query = PointHistory::with('user')->latest();

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->type) {
            $query->where('type', $request->type);
        }
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $histories = $query->paginate(20);
        return view('admin.point_history', compact('histories', 'students'));
    }

    // --- Point Adjust ---
    public function pointAdjustIndex()
    {
        $students = User::where('role_id', 5)->orderBy('name')->get();
        return view('admin.point_adjust', compact('students'));
    }

    public function pointAdjustStore(Request $request)
    {
        $request->validate([
            'user_id'      => 'required|exists:users,id',
            'action'       => 'required|in:add,deduct',
            'amount'       => 'required|integer|min:1',
            'notes'        => 'nullable|string|max:255',
        ]);

        $student = User::findOrFail($request->user_id);
        $amount = (int) $request->amount;
        $actorId = Auth::id();
        $isAdd = $request->action === 'add';

        // Add or deduct from student
        if ($isAdd) {
            $student->increment('points', $amount);
        } else {
            $student->decrement('points', $amount);
        }

        // Log point history
        PointHistory::create([
            'user_id'     => $student->id,
            'points'      => $isAdd ? $amount : -$amount,
            'type'        => 'adjust',
            'source'      => 'Penyesuaian Admin',
            'description' => ($isAdd ? 'Penambahan poin' : 'Pengurangan poin') . ($request->notes ? ': ' . $request->notes : ''),
        ]);

        // Audit log
        PointAudit::create([
            'actor_id'       => $actorId,
            'target_user_id' => $student->id,
            'action_type'    => 'adjust',
            'amount'         => $isAdd ? $amount : -$amount,
            'point_type'     => 'adjust',
            'notes'          => ($isAdd ? 'Menambahkan poin' : 'Mengurangi poin') . ($request->notes ? ': ' . $request->notes : ''),
        ]);

        $actionText = $isAdd ? 'ditambahkan ke' : 'dikurangi dari';
        return redirect()->back()->with('success', "Berhasil! {$amount} poin telah {$actionText} {$student->name}.");
    }

    // --- Point Audit ---
    public function pointAuditIndex(Request $request)
    {
        $students = User::where('role_id', 5)->orderBy('name')->get();
        $actors   = User::whereIn('role_id', [1, 2, 3])->orderBy('name')->get();

        $query = PointAudit::with(['actor', 'targetUser'])->latest();

        if ($request->actor_id) {
            $query->where('actor_id', $request->actor_id);
        }
        if ($request->target_user_id) {
            $query->where('target_user_id', $request->target_user_id);
        }
        if ($request->action_type) {
            $query->where('action_type', $request->action_type);
        }
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $audits = $query->paginate(20);
        return view('admin.point_audit', compact('audits', 'students', 'actors'));
    }
}
