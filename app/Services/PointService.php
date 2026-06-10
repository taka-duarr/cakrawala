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
        $user->points += $points;

        // Recalculate level based on points
        $user->current_level = $this->calculateLevel($user->points);
        
        $user->save();

        // Check for achievements
        $this->checkAndAwardAchievements($user);

        return $user;
    }

    public function checkAndAwardAchievements(User $user)
    {
        $claimedAchievementIds = $user->achievements()->pluck('achievements.id')->toArray();
        $unclaimedAchievements = \App\Models\Achievement::whereNotIn('id', $claimedAchievementIds)->get();

        foreach ($unclaimedAchievements as $achievement) {
            $eligible = false;

            if ($achievement->criteria === 'first_mission') {
                $eligible = $user->missions()->wherePivot('status', 'approved')->exists();
            } elseif ($achievement->criteria === 'points_300') {
                $eligible = $user->points >= 300;
            } elseif ($achievement->criteria === 'teladan_1500') {
                $eligible = ($user->points >= 1500);
            } elseif ($achievement->criteria === 'volunteer_social') {
                $eligible = $user->missions()
                    ->wherePivot('status', 'approved')
                    ->where(function($q) {
                        $q->where('title', 'like', '%Bakti%')
                          ->orWhere('title', 'like', '%Imam%')
                          ->orWhere('title', 'like', '%Bahasa%');
                    })->exists();
            }

            if ($eligible) {
                $user->achievements()->attach($achievement->id);
            }
        }
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
