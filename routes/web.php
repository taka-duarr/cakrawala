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
    if ($role === 'toko') return redirect()->route('toko.dashboard');
    
    // Default fallback
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Role-based routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
    // User Management Routes
    Route::get('/users', [AdminController::class, 'usersIndex'])->name('users.index');
    Route::get('/parents', [AdminController::class, 'parentsIndex'])->name('parents.index');
    Route::get('/teachers', [AdminController::class, 'teachersIndex'])->name('teachers.index');
    Route::get('/students', [AdminController::class, 'studentsIndex'])->name('students.index');
    Route::post('/users/store', [AdminController::class, 'usersStore'])->name('users.store');
    Route::put('/users/{id}/update', [AdminController::class, 'usersUpdate'])->name('users.update');
    Route::delete('/users/{id}/destroy', [AdminController::class, 'usersDestroy'])->name('users.destroy');
    Route::post('/users/{id}/toggle-status', [AdminController::class, 'usersToggleStatus'])->name('users.toggle-status');
    Route::post('/users/{id}/reset-password', [AdminController::class, 'usersResetPassword'])->name('users.reset-password');

    // Classroom Management Routes
    Route::get('/classrooms', [AdminController::class, 'classroomsIndex'])->name('classrooms.index');
    Route::post('/classrooms/store', [AdminController::class, 'classroomsStore'])->name('classrooms.store');
    Route::put('/classrooms/{id}/update', [AdminController::class, 'classroomsUpdate'])->name('classrooms.update');
    Route::delete('/classrooms/{id}/destroy', [AdminController::class, 'classroomsDestroy'])->name('classrooms.destroy');
    Route::post('/classrooms/{id}/enroll', [AdminController::class, 'classroomEnrollStudent'])->name('classrooms.enroll');
    Route::delete('/classrooms/{classroomId}/unenroll/{userId}', [AdminController::class, 'classroomUnenrollStudent'])->name('classrooms.unenroll');

    // Reward Management Routes
    Route::get('/rewards', [\App\Http\Controllers\RewardController::class, 'manage'])->name('rewards.manage');
    Route::post('/rewards/store', [\App\Http\Controllers\RewardController::class, 'store'])->name('rewards.store');
    Route::put('/rewards/{id}/update', [\App\Http\Controllers\RewardController::class, 'update'])->name('rewards.update');
    Route::delete('/rewards/{id}/destroy', [\App\Http\Controllers\RewardController::class, 'destroy'])->name('rewards.destroy');
    Route::post('/rewards/claims/{id}/approve', [\App\Http\Controllers\RewardController::class, 'approveClaim'])->name('rewards.approve');

    // Academic Year Management Routes
    Route::get('/academic-years', [AdminController::class, 'academicYearsIndex'])->name('academic-years.index');
    Route::post('/academic-years/store', [AdminController::class, 'academicYearsStore'])->name('academic-years.store');
    Route::put('/academic-years/{id}/update', [AdminController::class, 'academicYearsUpdate'])->name('academic-years.update');
    Route::delete('/academic-years/{id}/destroy', [AdminController::class, 'academicYearsDestroy'])->name('academic-years.destroy');
    Route::post('/academic-years/{id}/set-active', [AdminController::class, 'academicYearsSetActive'])->name('academic-years.set-active');

    // Semester Management Routes
    Route::post('/semesters/store', [AdminController::class, 'semestersStore'])->name('semesters.store');
    Route::delete('/semesters/{id}/destroy', [AdminController::class, 'semestersDestroy'])->name('semesters.destroy');
    Route::post('/semesters/{id}/set-active', [AdminController::class, 'semestersSetActive'])->name('semesters.set-active');

    // Jurusan Management Routes
    Route::get('/jurusans', [AdminController::class, 'jurusansIndex'])->name('jurusans.index');
    Route::post('/jurusans/store', [AdminController::class, 'jurusansStore'])->name('jurusans.store');
    Route::put('/jurusans/{id}/update', [AdminController::class, 'jurusansUpdate'])->name('jurusans.update');
    Route::delete('/jurusans/{id}/destroy', [AdminController::class, 'jurusansDestroy'])->name('jurusans.destroy');

    // Subject Management Routes
    Route::get('/subjects', [AdminController::class, 'subjectsIndex'])->name('subjects.index');
    Route::post('/subjects/store', [AdminController::class, 'subjectsStore'])->name('subjects.store');
    Route::put('/subjects/{id}/update', [AdminController::class, 'subjectsUpdate'])->name('subjects.update');
    Route::delete('/subjects/{id}/destroy', [AdminController::class, 'subjectsDestroy'])->name('subjects.destroy');

    // Teaching Assignment Management Routes
    Route::get('/teaching-assignments', [AdminController::class, 'teachingAssignmentsIndex'])->name('teaching-assignments.index');
    Route::post('/teaching-assignments/store', [AdminController::class, 'teachingAssignmentsStore'])->name('teaching-assignments.store');
    Route::put('/teaching-assignments/{id}/update', [AdminController::class, 'teachingAssignmentsUpdate'])->name('teaching-assignments.update');
    Route::delete('/teaching-assignments/{id}/destroy', [AdminController::class, 'teachingAssignmentsDestroy'])->name('teaching-assignments.destroy');

    // Point Management Routes
    Route::get('/currency-settings', [AdminController::class, 'currencySettingsIndex'])->name('currency-settings.index');
    Route::post('/currency-settings/update', [AdminController::class, 'currencySettingsUpdate'])->name('currency-settings.update');
    Route::get('/point-history', [AdminController::class, 'pointHistoryIndex'])->name('point-history.index');
    Route::get('/point-adjust', [AdminController::class, 'pointAdjustIndex'])->name('point-adjust.index');
    Route::post('/point-adjust/store', [AdminController::class, 'pointAdjustStore'])->name('point-adjust.store');
    Route::get('/point-audit', [AdminController::class, 'pointAuditIndex'])->name('point-audit.index');

    // School Locations CRUD
    Route::get('/school-locations', [\App\Http\Controllers\SchoolLocationController::class, 'index'])->name('school-locations.index');
    Route::post('/school-locations/store', [\App\Http\Controllers\SchoolLocationController::class, 'store'])->name('school-locations.store');
    Route::put('/school-locations/{id}/update', [\App\Http\Controllers\SchoolLocationController::class, 'update'])->name('school-locations.update');
    Route::delete('/school-locations/{id}/destroy', [\App\Http\Controllers\SchoolLocationController::class, 'destroy'])->name('school-locations.destroy');

    // Shop Withdrawals
    Route::get('/withdrawals', [\App\Http\Controllers\ShopWithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::post('/withdrawals/{id}/approve', [\App\Http\Controllers\ShopWithdrawalController::class, 'approve'])->name('withdrawals.approve');
    Route::post('/withdrawals/{id}/reject', [\App\Http\Controllers\ShopWithdrawalController::class, 'reject'])->name('withdrawals.reject');

    // Toko Management Routes
    Route::get('/toko', [AdminController::class, 'tokoIndex'])->name('toko.index');
    Route::post('/toko/store', [AdminController::class, 'tokoStore'])->name('toko.store');
    Route::put('/toko/{id}/update', [AdminController::class, 'tokoUpdate'])->name('toko.update');
    Route::delete('/toko/{id}/destroy', [AdminController::class, 'tokoDestroy'])->name('toko.destroy');
    Route::get('/toko/{id}/transactions', [AdminController::class, 'tokoTransactions'])->name('toko.transactions');

    // Event CRUD & Awarding Routes
    Route::get('/events', [AdminController::class, 'eventsIndex'])->name('events.index');
    Route::post('/events/store', [AdminController::class, 'eventsStore'])->name('events.store');
    Route::put('/events/{id}/update', [AdminController::class, 'eventsUpdate'])->name('events.update');
    Route::delete('/events/{id}/destroy', [AdminController::class, 'eventsDestroy'])->name('events.destroy');
    Route::get('/events/{id}/award', [AdminController::class, 'showAwardEvent'])->name('events.award.show');
    Route::post('/events/{id}/award', [AdminController::class, 'awardEvent'])->name('events.award.process');

    // Mission CRUD & Awarding Routes
    Route::get('/missions', [AdminController::class, 'missionsIndex'])->name('missions.index');
    Route::post('/missions/store', [AdminController::class, 'missionsStore'])->name('missions.store');
    Route::put('/missions/{id}/update', [AdminController::class, 'missionsUpdate'])->name('missions.update');
    Route::delete('/missions/{id}/destroy', [AdminController::class, 'missionsDestroy'])->name('missions.destroy');
    Route::get('/missions/{id}/award', [AdminController::class, 'showAwardMission'])->name('missions.award.show');
    Route::post('/missions/{id}/award', [AdminController::class, 'awardMission'])->name('missions.award.process');
});

Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruController::class, 'index'])->name('dashboard');
    Route::get('/my-schedule', [GuruController::class, 'mySchedule'])->name('my-schedule');
    Route::post('/mission/approve/{userId}/{missionId}', [\App\Http\Controllers\MissionController::class, 'approveMission'])->name('mission.approve');
    Route::post('/missions/store', [GuruController::class, 'storeMission'])->name('missions.store');
    Route::post('/missions/validate', [GuruController::class, 'validateMission'])->name('missions.validate');
    Route::post('/points/adjust', [GuruController::class, 'adjustPoints'])->name('points.adjust');
    Route::post('/badges/toggle', [GuruController::class, 'toggleBadge'])->name('badges.toggle');
    Route::get('/assignments/{id}', [GuruController::class, 'assignmentDetail'])->name('assignments.detail');

    // Mission & Event manual awarding for Guru
    Route::get('/missions', [GuruController::class, 'missionsIndex'])->name('missions.index');
    Route::get('/missions/{id}/award', [GuruController::class, 'showAwardMission'])->name('missions.award.show');
    Route::post('/missions/{id}/award', [GuruController::class, 'awardMission'])->name('missions.award.process');
    Route::get('/events/{id}/award', [GuruController::class, 'showAwardEvent'])->name('events.award.show');
    Route::post('/events/{id}/award', [GuruController::class, 'awardEvent'])->name('events.award.process');

    // Attendance, Materials, and Assignments management
    Route::post('/assignments/{id}/sessions/store', [\App\Http\Controllers\AttendanceController::class, 'storeSession'])->name('sessions.store');
    Route::post('/sessions/{id}/close', [\App\Http\Controllers\AttendanceController::class, 'closeSession'])->name('sessions.close');
    Route::post('/sessions/{id}/materials/store', [\App\Http\Controllers\AttendanceController::class, 'addMaterial'])->name('materials.store');
    Route::post('/sessions/{id}/assignments/store', [\App\Http\Controllers\AttendanceController::class, 'addAssignment'])->name('assignments.store');
    Route::post('/submissions/{id}/grade', [\App\Http\Controllers\AttendanceController::class, 'gradeSubmission'])->name('submissions.grade');
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
    Route::get('/my-classes', [StudentController::class, 'myClasses'])->name('my-classes');
    Route::get('/my-classes/{id}', [StudentController::class, 'classDetail'])->name('class-detail');
    Route::get('/dompet', [StudentController::class, 'dompet'])->name('dompet');

    // Peer to Peer Point Transfer
    Route::post('/transfer/generate', [StudentController::class, 'generateTransferQr'])->name('transfer.generate');
    Route::post('/transfer/cancel/{token}', [StudentController::class, 'cancelTransferQr'])->name('transfer.cancel');
    Route::get('/transfer/status/{token}', [StudentController::class, 'checkTransferStatus'])->name('transfer.status');
    Route::get('/transfer/claim/{token}', [StudentController::class, 'claimTransferPage'])->name('transfer.claim.page');
    Route::post('/transfer/claim/{token}', [StudentController::class, 'processTransferClaim'])->name('transfer.claim.process');

    // Geolocated Attendance check-in and assignments
    Route::post('/sessions/{id}/check-in', [\App\Http\Controllers\AttendanceController::class, 'checkIn'])->name('sessions.check-in');
    Route::post('/attendance/scan/{token}/check-in', [\App\Http\Controllers\AttendanceController::class, 'checkInScan'])->name('sessions.scan-check-in');
    Route::post('/assignments/{id}/submit', [\App\Http\Controllers\AttendanceController::class, 'submitAssignment'])->name('assignments.submit');
});

Route::middleware('auth')->group(function () {
    Route::get('/student/attendance/scan/{token}', [\App\Http\Controllers\AttendanceController::class, 'scanPage'])->name('student.sessions.scan-page');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/leaderboard', [\App\Http\Controllers\GeneralInfoController::class, 'leaderboard'])->name('leaderboard');
    Route::get('/events', [\App\Http\Controllers\GeneralInfoController::class, 'events'])->name('events');
    Route::get('/announcements', [\App\Http\Controllers\GeneralInfoController::class, 'announcements'])->name('announcements');
    Route::get('/help', [\App\Http\Controllers\GeneralInfoController::class, 'help'])->name('help');
    Route::get('/notifications', [\App\Http\Controllers\GeneralInfoController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\GeneralInfoController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
});

Route::middleware(['auth', 'role:toko'])->prefix('toko')->name('toko.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\TokoController::class, 'index'])->name('dashboard');
    Route::get('/katalog', [\App\Http\Controllers\TokoController::class, 'katalog'])->name('katalog');
    Route::post('/qr/generate', [\App\Http\Controllers\TokoController::class, 'generateQr'])->name('qr.generate');
    Route::post('/qr/cancel', [\App\Http\Controllers\TokoController::class, 'cancelQr'])->name('qr.cancel');
    Route::get('/qr/{token}/status', [\App\Http\Controllers\TokoController::class, 'checkStatus'])->name('qr.status');
    // Item catalog CRUD
    Route::post('/items/store', [\App\Http\Controllers\TokoController::class, 'itemStore'])->name('items.store');
    Route::put('/items/{id}/update', [\App\Http\Controllers\TokoController::class, 'itemUpdate'])->name('items.update');
    Route::delete('/items/{id}/destroy', [\App\Http\Controllers\TokoController::class, 'itemDestroy'])->name('items.destroy');

    // Withdrawals
    Route::get('/withdrawals', [\App\Http\Controllers\ShopWithdrawalController::class, 'tokoIndex'])->name('withdrawals.index');
    Route::post('/withdrawals', [\App\Http\Controllers\ShopWithdrawalController::class, 'store'])->name('withdrawals.store');
});

// Shop payment routes (accessible by logged-in siswa)
Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/pay/{token}', [\App\Http\Controllers\ShopPaymentController::class, 'confirm'])->name('student.pay.confirm');
    Route::post('/pay/{token}/process', [\App\Http\Controllers\ShopPaymentController::class, 'process'])->name('student.pay.process');
});

require __DIR__.'/auth.php';
