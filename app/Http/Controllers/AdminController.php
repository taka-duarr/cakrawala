<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Role;
use App\Models\PointHistory;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $totalSiswa = User::where('role_id', 5)->count();
        $totalGuru = User::where('role_id', 2)->count();
        $totalKelas = Classroom::count();
        $totalPoinSekolah = User::where('role_id', 5)->sum('points_kebaikan');

        // Top 10 Siswa
        $topSiswa = User::where('role_id', 5)
            ->orderByDesc('points_kebaikan')
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
        $roles = Role::all();
        $classrooms = Classroom::all();

        $roleId = $request->input('role_id');
        $search = $request->input('search');

        $usersQuery = User::with(['role', 'classroom']);

        if ($roleId) {
            $usersQuery->where('role_id', $roleId);
        }

        if ($search) {
            $usersQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $usersQuery->orderBy('id', 'desc')->paginate(15);
        $students = User::where('role_id', 5)->orderBy('name')->get();

        return view('admin.users', compact('users', 'roles', 'classrooms', 'students'));
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
        // Classroom listing with their Wali Kelas and total students count
        $classrooms = Classroom::with(['users' => function($query) {
            $query->where('role_id', 3); // wali kelas
        }])->withCount(['users as students_count' => function($query) {
            $query->where('role_id', 5); // siswa
        }])->get();

        // Get Wali Kelas candidates (users with role_id 3 who don't have a classroom yet or are available)
        $waliKelasCandidates = User::where('role_id', 3)->orderBy('name')->get();

        return view('admin.classrooms', compact('classrooms', 'waliKelasCandidates'));
    }

    public function classroomsStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:classrooms,name',
            'points' => 'required|integer|min:0',
            'wali_kelas_id' => 'nullable|exists:users,id',
        ]);

        $classroom = Classroom::create([
            'name' => $request->name,
            'points' => $request->points,
        ]);

        if ($request->wali_kelas_id) {
            $wali = User::findOrFail($request->wali_kelas_id);
            $wali->classroom_id = $classroom->id;
            $wali->save();
        }

        return redirect()->back()->with('success', 'Kelas baru berhasil ditambahkan!');
    }

    public function classroomsUpdate(Request $request, $id)
    {
        $classroom = Classroom::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:classrooms,name,' . $id,
            'points' => 'required|integer|min:0',
            'wali_kelas_id' => 'nullable|exists:users,id',
        ]);

        $classroom->name = $request->name;
        $classroom->points = $request->points;
        $classroom->save();

        // Remove current wali kelas
        User::where('classroom_id', $classroom->id)
            ->where('role_id', 3)
            ->update(['classroom_id' => null]);

        // Assign new wali kelas
        if ($request->wali_kelas_id) {
            $newWali = User::findOrFail($request->wali_kelas_id);
            $newWali->classroom_id = $classroom->id;
            $newWali->save();
        }

        return redirect()->back()->with('success', 'Kelas berhasil diperbarui!');
    }

    public function classroomsDestroy($id)
    {
        $classroom = Classroom::findOrFail($id);
        $classroom->delete();

        return redirect()->back()->with('success', 'Kelas berhasil dihapus!');
    }
}
