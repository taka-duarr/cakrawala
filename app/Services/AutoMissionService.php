<?php

namespace App\Services;

use App\Models\User;
use App\Models\Mission;
use Illuminate\Support\Carbon;

class AutoMissionService
{
    protected $pointService;

    public function __construct(PointService $pointService)
    {
        $this->pointService = $pointService;
    }

    /**
     * Trigger daily/weekly attendance missions.
     * Searches for active missions with title/description containing "Hadir" or "Presensi"
     */
    public function triggerAttendance(User $student)
    {
        $missions = Mission::where('is_active', true)
            ->where(function ($query) {
                $query->where('title', 'like', '%Hadir%')
                      ->orWhere('title', 'like', '%Presensi%')
                      ->orWhere('description', 'like', '%Hadir%')
                      ->orWhere('description', 'like', '%Presensi%');
            })
            ->get();

        foreach ($missions as $mission) {
            $this->completeMissionForStudent($student, $mission);
        }
    }

    /**
     * Trigger store/shopping transaction missions.
     * Searches for active missions with title/description containing "Belanja", "Toko", or "Kantin"
     */
    public function triggerStoreTransaction(User $student, int $amount)
    {
        $missions = Mission::where('is_active', true)
            ->where(function ($query) {
                $query->where('title', 'like', '%Belanja%')
                      ->orWhere('title', 'like', '%Toko%')
                      ->orWhere('title', 'like', '%Kantin%')
                      ->orWhere('description', 'like', '%Belanja%')
                      ->orWhere('description', 'like', '%Toko%')
                      ->orWhere('description', 'like', '%Kantin%');
            })
            ->get();

        foreach ($missions as $mission) {
            $this->completeMissionForStudent($student, $mission);
        }
    }

    /**
     * Trigger transfer missions.
     * Searches for active missions with title/description containing "Transfer" or "Kirim"
     */
    public function triggerTransfer(User $sender)
    {
        $missions = Mission::where('is_active', true)
            ->where(function ($query) {
                $query->where('title', 'like', '%Transfer%')
                      ->orWhere('title', 'like', '%Kirim%')
                      ->orWhere('description', 'like', '%Transfer%')
                      ->orWhere('description', 'like', '%Kirim%');
            })
            ->get();

        foreach ($missions as $mission) {
            $this->completeMissionForStudent($sender, $mission);
        }
    }

    /**
     * Mark a mission as completed (approved) for the student and award points.
     */
    protected function completeMissionForStudent(User $student, Mission $mission)
    {
        $pivot = $student->missions()->where('mission_id', $mission->id)->first();

        if ($pivot && $pivot->pivot->status === 'approved') {
            if ($mission->type === 'daily') {
                $lastApproved = Carbon::parse($pivot->pivot->updated_at);
                if ($lastApproved->isToday()) {
                    return; // Already completed today
                }
            } else {
                return; // Weekly/other missions: already completed and approved
            }
        }

        if (!$pivot) {
            $student->missions()->attach($mission->id, [
                'status' => 'approved',
                'notes' => 'Penyelesaian Otomatis oleh Sistem'
            ]);
        } else {
            $student->missions()->updateExistingPivot($mission->id, [
                'status' => 'approved',
                'notes' => 'Penyelesaian Otomatis oleh Sistem'
            ]);
        }

        // Award points
        $this->pointService->addPoints(
            $student, 
            $mission->points_reward, 
            'kebaikan', 
            'Misi: ' . $mission->title,
            'Hadiah penyelesaian misi otomatis'
        );
    }
}
