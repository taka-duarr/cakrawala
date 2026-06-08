<?php

namespace App\Services;

use App\Models\User;
use App\Models\PointHistory;

class PointService
{
    public function addPoints(User $user, int $points, string $type, string $source, ?string $description = null)
    {
        // Save history
        PointHistory::create([
            'user_id' => $user->id,
            'points' => $points,
            'type' => $type,
            'source' => $source,
            'description' => $description,
        ]);

        // Update user points
        if ($type === 'kebaikan') {
            $user->points_kebaikan += $points;
        } else {
            $user->points_pelanggaran += $points;
        }

        // Recalculate level based on kebaikan points
        $user->current_level = $this->calculateLevel($user->points_kebaikan);
        
        $user->save();

        return $user;
    }

    private function calculateLevel(int $points): string
    {
        if ($points <= 100) return 'Pemula';
        if ($points <= 500) return 'Berkembang';
        if ($points <= 1500) return 'Unggul';
        if ($points <= 3000) return 'Teladan';
        
        return 'Inspiratif';
    }
}
