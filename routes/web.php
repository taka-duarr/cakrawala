<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\StudentController;

Route::get('/dashboard', function () {
    $role = auth()->user()->role->name ?? '';
    if ($role === 'admin') return redirect()->route('admin.dashboard');
    if ($role === 'guru') return redirect()->route('guru.dashboard');
    if ($role === 'walikelas') return redirect()->route('walikelas.dashboard');
    if ($role === 'orangtua') return redirect()->route('parent.dashboard');
    if ($role === 'siswa') return redirect()->route('student.dashboard');
    
    // Default fallback
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Role-based routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/rewards', [\App\Http\Controllers\RewardController::class, 'manage'])->name('rewards.manage');
    Route::post('/rewards/store', [\App\Http\Controllers\RewardController::class, 'store'])->name('rewards.store');
    Route::put('/rewards/{id}/update', [\App\Http\Controllers\RewardController::class, 'update'])->name('rewards.update');
    Route::delete('/rewards/{id}/destroy', [\App\Http\Controllers\RewardController::class, 'destroy'])->name('rewards.destroy');
    Route::post('/rewards/claims/{id}/approve', [\App\Http\Controllers\RewardController::class, 'approveClaim'])->name('rewards.approve');
});

Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruController::class, 'index'])->name('dashboard');
    Route::post('/mission/approve/{userId}/{missionId}', [\App\Http\Controllers\MissionController::class, 'approveMission'])->name('mission.approve');
});

Route::middleware(['auth', 'role:walikelas'])->prefix('walikelas')->name('walikelas.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\WaliKelasController::class, 'index'])->name('dashboard');
    Route::post('/rewards/claims/{id}/approve', [\App\Http\Controllers\RewardController::class, 'approveClaim'])->name('rewards.approve');
});

Route::middleware(['auth', 'role:orangtua'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\OrangTuaController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'role:siswa'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'index'])->name('dashboard');
    Route::post('/mission/{id}/take', [\App\Http\Controllers\MissionController::class, 'takeMission'])->name('mission.take');
    Route::post('/mission/{id}/submit', [\App\Http\Controllers\MissionController::class, 'submitProof'])->name('mission.submit');
    Route::get('/rewards', [\App\Http\Controllers\RewardController::class, 'index'])->name('rewards');
    Route::post('/rewards/{id}/claim', [\App\Http\Controllers\RewardController::class, 'claim'])->name('rewards.claim');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/leaderboard', [\App\Http\Controllers\GeneralInfoController::class, 'leaderboard'])->name('leaderboard');
    Route::get('/events', [\App\Http\Controllers\GeneralInfoController::class, 'events'])->name('events');
    Route::get('/announcements', [\App\Http\Controllers\GeneralInfoController::class, 'announcements'])->name('announcements');
    Route::get('/help', [\App\Http\Controllers\GeneralInfoController::class, 'help'])->name('help');
    Route::get('/notifications', [\App\Http\Controllers\GeneralInfoController::class, 'notifications'])->name('notifications');
});

require __DIR__.'/auth.php';
