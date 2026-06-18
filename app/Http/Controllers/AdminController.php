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
use App\Models\Subject;
use App\Models\TeachingAssignment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
            'angkatan'         => 'nullable|string|max:10',
            'wali_kelas_id'    => 'nullable|exists:users,id',
        ]);

        $classroom = Classroom::create([
            'name'             => $request->name,
            'points'           => 0,
            'grade_level'      => $request->grade_level,
            'jurusan_id'       => $request->jurusan_id,
            'academic_year_id' => $request->academic_year_id,
            'semester_id'      => $request->semester_id,
            'angkatan'         => $request->angkatan,
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
            'angkatan'         => 'nullable|string|max:10',
            'wali_kelas_id'    => 'nullable|exists:users,id',
        ]);

        $classroom->update([
            'name'             => $request->name,
            'grade_level'      => $request->grade_level,
            'jurusan_id'       => $request->jurusan_id,
            'academic_year_id' => $request->academic_year_id,
            'semester_id'      => $request->semester_id,
            'angkatan'         => $request->angkatan,
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

    // --- Subject Management ---
    public function subjectsIndex()
    {
        $subjects = Subject::withCount('teachingAssignments')
            ->orderBy('name')
            ->get();

        return view('admin.subjects', compact('subjects'));
    }

    public function subjectsStore(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:150|unique:subjects,name',
            'code'        => 'nullable|string|max:30|unique:subjects,code',
            'description' => 'nullable|string|max:255',
            'is_active'   => 'nullable|boolean',
        ]);

        Subject::create([
            'name'        => $request->name,
            'code'        => $request->code,
            'description' => $request->description,
            'is_active'   => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Mata pelajaran berhasil ditambahkan!');
    }

    public function subjectsUpdate(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $request->validate([
            'name'        => ['required', 'string', 'max:150', Rule::unique('subjects', 'name')->ignore($subject->id)],
            'code'        => ['nullable', 'string', 'max:30', Rule::unique('subjects', 'code')->ignore($subject->id)],
            'description' => 'nullable|string|max:255',
            'is_active'   => 'nullable|boolean',
        ]);

        $subject->update([
            'name'        => $request->name,
            'code'        => $request->code,
            'description' => $request->description,
            'is_active'   => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Mata pelajaran berhasil diperbarui!');
    }

    public function subjectsDestroy($id)
    {
        Subject::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Mata pelajaran berhasil dihapus!');
    }

    // --- Teaching Assignment Management ---
    public function teachingAssignmentsIndex(Request $request)
    {
        $teachers = User::whereHas('role', fn($q) => $q->whereIn('name', ['guru', 'walikelas']))
            ->orderBy('name')
            ->get();
        $subjects = Subject::where('is_active', true)->orderBy('name')->get();
        $classrooms = Classroom::with(['jurusan', 'academicYear', 'semester'])->orderBy('grade_level')->orderBy('name')->get();
        $academicYears = AcademicYear::orderByDesc('name')->get();
        $semesters = Semester::with('academicYear')->orderByDesc('id')->get();

        $query = TeachingAssignment::with(['teacher', 'subject', 'classroom', 'academicYear', 'semester'])
            ->latest();

        if ($request->teacher_id) {
            $query->where('teacher_id', $request->teacher_id);
        }
        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->classroom_id) {
            $query->where('classroom_id', $request->classroom_id);
        }
        if ($request->academic_year_id) {
            $query->where('academic_year_id', $request->academic_year_id);
        }
        if ($request->semester_id) {
            $query->where('semester_id', $request->semester_id);
        }

        $assignments = $query->paginate(15)->withQueryString();

        return view('admin.teaching_assignments', compact(
            'assignments',
            'teachers',
            'subjects',
            'classrooms',
            'academicYears',
            'semesters'
        ));
    }

    public function teachingAssignmentsStore(Request $request)
    {
        $validated = $request->validate([
            'teacher_id'       => 'required|exists:users,id',
            'subject_id'       => 'required|exists:subjects,id',
            'classroom_id'     => 'required|exists:classrooms,id',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'semester_id'      => 'nullable|exists:semesters,id',
            'is_active'        => 'nullable|boolean',
            'day_of_week'      => 'nullable|string',
            'start_time'       => 'nullable',
            'end_time'         => 'nullable',
            'total_meetings'   => 'nullable|integer|min:1|max:40',
        ]);

        $teacher = User::with('role')->findOrFail($validated['teacher_id']);
        if (!in_array($teacher->role?->name, ['guru', 'walikelas'])) {
            return redirect()->back()->withErrors(['teacher_id' => 'User yang dipilih harus memiliki role guru atau wali kelas.'])->withInput();
        }

        if ($request->semester_id) {
            $semester = Semester::findOrFail($request->semester_id);
            if ($request->academic_year_id && $semester->academic_year_id != $request->academic_year_id) {
                return redirect()->back()->withErrors(['semester_id' => 'Semester tidak sesuai dengan tahun ajaran yang dipilih.'])->withInput();
            }
        }

        $duplicate = TeachingAssignment::where('teacher_id', $request->teacher_id)
            ->where('subject_id', $request->subject_id)
            ->where('classroom_id', $request->classroom_id)
            ->where('academic_year_id', $request->academic_year_id)
            ->where('semester_id', $request->semester_id)
            ->exists();

        if ($duplicate) {
            return redirect()->back()->withErrors(['assignment' => 'Penugasan guru untuk mapel, kelas, dan periode ini sudah ada.'])->withInput();
        }

        // Check for schedule overlap / clash
        if ($request->day_of_week && $request->start_time && $request->end_time) {
            $clash = TeachingAssignment::with(['classroom', 'subject'])
                ->where('academic_year_id', $request->academic_year_id)
                ->where('semester_id', $request->semester_id)
                ->where('day_of_week', $request->day_of_week)
                ->where('is_active', true)
                ->where(function ($query) use ($request) {
                    $query->where('start_time', '<', $request->end_time)
                          ->where('end_time', '>', $request->start_time);
                })
                ->where(function ($query) use ($request) {
                    $query->where('teacher_id', $request->teacher_id)
                          ->orWhere('classroom_id', $request->classroom_id);
                })
                ->first();

            if ($clash) {
                $timeString = substr($clash->start_time, 0, 5) . ' - ' . substr($clash->end_time, 0, 5);
                if ($clash->teacher_id == $request->teacher_id) {
                    return redirect()->back()->withErrors([
                        'assignment' => "Jadwal bentrok! Guru tersebut sudah memiliki jadwal mengajar pada hari " . $clash->getDayTranslation() . " pukul " . $timeString . " di kelas " . $clash->classroom->name . "."
                    ])->withInput();
                } else {
                    return redirect()->back()->withErrors([
                        'assignment' => "Jadwal bentrok! Kelas " . $clash->classroom->name . " sudah memiliki jadwal pelajaran " . $clash->subject->name . " pada hari " . $clash->getDayTranslation() . " pukul " . $timeString . "."
                    ])->withInput();
                }
            }
        }

        TeachingAssignment::create([
            'teacher_id'       => $request->teacher_id,
            'subject_id'       => $request->subject_id,
            'classroom_id'     => $request->classroom_id,
            'academic_year_id' => $request->academic_year_id,
            'semester_id'      => $request->semester_id,
            'is_active'        => $request->has('is_active'),
            'day_of_week'      => $request->day_of_week,
            'start_time'       => $request->start_time,
            'end_time'         => $request->end_time,
            'total_meetings'   => $request->total_meetings ?? 16,
        ]);

        return redirect()->route('admin.teaching-assignments.index')->with('success', 'Penugasan mengajar berhasil ditambahkan!');
    }

    public function teachingAssignmentsUpdate(Request $request, $id)
    {
        $assignment = TeachingAssignment::findOrFail($id);

        $validated = $request->validate([
            'teacher_id'       => 'required|exists:users,id',
            'subject_id'       => 'required|exists:subjects,id',
            'classroom_id'     => 'required|exists:classrooms,id',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'semester_id'      => 'nullable|exists:semesters,id',
            'is_active'        => 'nullable|boolean',
            'day_of_week'      => 'nullable|string',
            'start_time'       => 'nullable',
            'end_time'         => 'nullable',
            'total_meetings'   => 'nullable|integer|min:1|max:40',
        ]);

        $teacher = User::with('role')->findOrFail($validated['teacher_id']);
        if (!in_array($teacher->role?->name, ['guru', 'walikelas'])) {
            return redirect()->back()->withErrors(['teacher_id' => 'User yang dipilih harus memiliki role guru atau wali kelas.'])->withInput();
        }

        if ($request->semester_id) {
            $semester = Semester::findOrFail($request->semester_id);
            if ($request->academic_year_id && $semester->academic_year_id != $request->academic_year_id) {
                return redirect()->back()->withErrors(['semester_id' => 'Semester tidak sesuai dengan tahun ajaran yang dipilih.'])->withInput();
            }
        }

        $duplicate = TeachingAssignment::where('teacher_id', $request->teacher_id)
            ->where('subject_id', $request->subject_id)
            ->where('classroom_id', $request->classroom_id)
            ->where('academic_year_id', $request->academic_year_id)
            ->where('semester_id', $request->semester_id)
            ->where('id', '!=', $assignment->id)
            ->exists();

        if ($duplicate) {
            return redirect()->back()->withErrors(['assignment' => 'Penugasan guru untuk mapel, kelas, dan periode ini sudah ada.'])->withInput();
        }

        // Check for schedule overlap / clash
        if ($request->day_of_week && $request->start_time && $request->end_time) {
            $clash = TeachingAssignment::with(['classroom', 'subject'])
                ->where('academic_year_id', $request->academic_year_id)
                ->where('semester_id', $request->semester_id)
                ->where('day_of_week', $request->day_of_week)
                ->where('is_active', true)
                ->where('id', '!=', $assignment->id)
                ->where(function ($query) use ($request) {
                    $query->where('start_time', '<', $request->end_time)
                          ->where('end_time', '>', $request->start_time);
                })
                ->where(function ($query) use ($request) {
                    $query->where('teacher_id', $request->teacher_id)
                          ->orWhere('classroom_id', $request->classroom_id);
                })
                ->first();

            if ($clash) {
                $timeString = substr($clash->start_time, 0, 5) . ' - ' . substr($clash->end_time, 0, 5);
                if ($clash->teacher_id == $request->teacher_id) {
                    return redirect()->back()->withErrors([
                        'assignment' => "Jadwal bentrok! Guru tersebut sudah memiliki jadwal mengajar pada hari " . $clash->getDayTranslation() . " pukul " . $timeString . " di kelas " . $clash->classroom->name . "."
                    ])->withInput();
                } else {
                    return redirect()->back()->withErrors([
                        'assignment' => "Jadwal bentrok! Kelas " . $clash->classroom->name . " sudah memiliki jadwal pelajaran " . $clash->subject->name . " pada hari " . $clash->getDayTranslation() . " pukul " . $timeString . "."
                    ])->withInput();
                }
            }
        }

        $assignment->update([
            'teacher_id'       => $request->teacher_id,
            'subject_id'       => $request->subject_id,
            'classroom_id'     => $request->classroom_id,
            'academic_year_id' => $request->academic_year_id,
            'semester_id'      => $request->semester_id,
            'is_active'        => $request->has('is_active'),
            'day_of_week'      => $request->day_of_week,
            'start_time'       => $request->start_time,
            'end_time'         => $request->end_time,
            'total_meetings'   => $request->total_meetings ?? 16,
        ]);

        return redirect()->route('admin.teaching-assignments.index')->with('success', 'Penugasan mengajar berhasil diperbarui!');
    }

    public function teachingAssignmentsDestroy($id)
    {
        TeachingAssignment::findOrFail($id)->delete();
        return redirect()->route('admin.teaching-assignments.index')->with('success', 'Penugasan mengajar berhasil dihapus!');
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
            'type'        => $isAdd ? 'kebaikan' : 'pelanggaran',
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

    // --- Manajemen Toko ---
    public function tokoIndex(Request $request)
    {
        $tokoRole = Role::where('name', 'toko')->first();
        if (!$tokoRole) {
            return back()->with('error', 'Role toko tidak ditemukan.');
        }

        $search = $request->input('search');
        $query = User::with('role')->where('role_id', $tokoRole->id);
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        $tokos = $query->orderBy('name')->paginate(15);
        $tokoRoleId = $tokoRole->id;

        return view('admin.toko', compact('tokos', 'tokoRoleId', 'search'));
    }

    public function tokoStore(Request $request)
    {
        $tokoRole = Role::where('name', 'toko')->firstOrFail();
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role_id'  => $tokoRole->id,
            'points'   => 0,
            'is_active'=> true,
        ]);

        return back()->with('success', 'Akun toko baru berhasil ditambahkan!');
    }

    public function tokoUpdate(Request $request, $id)
    {
        $toko = User::findOrFail($id);
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
        ]);

        $toko->name  = $request->name;
        $toko->email = $request->email;
        if ($request->password) {
            $toko->password = bcrypt($request->password);
        }
        $toko->save();

        return back()->with('success', 'Akun toko berhasil diperbarui!');
    }

    public function tokoDestroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Akun toko berhasil dihapus.');
    }

    public function tokoTransactions(Request $request, $id)
    {
        $toko = User::findOrFail($id);
        $query = \App\Models\ShopTransaction::with('student')
            ->where('shop_user_id', $id);

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->orderByDesc('created_at')->paginate(20);
        $totalPaid    = \App\Models\ShopTransaction::where('shop_user_id', $id)->where('status', 'paid')->sum('points_amount');
        $totalTx      = \App\Models\ShopTransaction::where('shop_user_id', $id)->where('status', 'paid')->count();

        return view('admin.toko_transactions', compact('toko', 'transactions', 'totalPaid', 'totalTx'));
    }

    // --- Dynamic Events CRUD & Awarding ---
    public function eventsIndex(Request $request)
    {
        $events = \App\Models\Event::latest()->paginate(15);
        return view('admin.events', compact('events'));
    }

    public function eventsStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'points_bonus' => 'required|integer|min:1',
            'category' => 'required|in:karakter,akademik,sosial',
        ]);

        \App\Models\Event::create($request->all());

        return back()->with('success', 'Event baru berhasil dibuat!');
    }

    public function eventsUpdate(Request $request, $id)
    {
        $event = \App\Models\Event::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'points_bonus' => 'required|integer|min:1',
            'category' => 'required|in:karakter,akademik,sosial',
        ]);

        $event->update($request->all());

        return back()->with('success', 'Event berhasil diperbarui!');
    }

    public function eventsDestroy($id)
    {
        \App\Models\Event::findOrFail($id)->delete();
        return back()->with('success', 'Event berhasil dihapus.');
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

        return view('admin.award_event', compact('event', 'classrooms', 'noClassStudents', 'completedStudentIds'));
    }

    public function awardEvent(Request $request, $id, \App\Services\PointService $pointService)
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

    // --- Dynamic Missions CRUD & Awarding ---
    public function missionsIndex(Request $request)
    {
        $missions = \App\Models\Mission::latest()->paginate(15);
        return view('admin.missions', compact('missions'));
    }

    public function missionsStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'points_reward' => 'required|integer|min:1',
            'type' => 'required|in:daily,weekly,class,school,special',
            'deadline' => 'nullable|date',
            'proof_type' => 'required|in:file,link,text,none',
        ]);

        \App\Models\Mission::create($request->all());

        return back()->with('success', 'Misi baru berhasil dibuat!');
    }

    public function missionsUpdate(Request $request, $id)
    {
        $mission = \App\Models\Mission::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'points_reward' => 'required|integer|min:1',
            'type' => 'required|in:daily,weekly,class,school,special',
            'deadline' => 'nullable|date',
            'proof_type' => 'required|in:file,link,text,none',
        ]);

        $mission->update($request->all());

        return back()->with('success', 'Misi berhasil diperbarui!');
    }

    public function missionsDestroy($id)
    {
        \App\Models\Mission::findOrFail($id)->delete();
        return back()->with('success', 'Misi berhasil dihapus.');
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

        return view('admin.award_mission', compact('mission', 'classrooms', 'noClassStudents', 'completedStudentIds'));
    }

    public function awardMission(Request $request, $id, \App\Services\PointService $pointService)
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
                        'notes' => 'Reward manual oleh Admin'
                    ]);
                } else {
                    $student->missions()->updateExistingPivot($mission->id, [
                        'status' => 'approved',
                        'notes' => 'Reward manual oleh Admin'
                    ]);
                }

                $pointService->addPoints($student, $mission->points_reward, 'kebaikan', 'Misi: ' . $mission->title, 'Verifikasi manual oleh Admin');
                $awardedCount++;
            }
        }

        return redirect()->back()->with('success', "Berhasil memberikan reward misi kepada {$awardedCount} siswa.");
    }
}

