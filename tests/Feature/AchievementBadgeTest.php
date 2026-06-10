<?php

namespace Tests\Feature;

use App\Models\Achievement;
use App\Models\Mission;
use App\Models\Role;
use App\Models\User;
use App\Services\PointService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AchievementBadgeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles
        $roles = ['admin', 'guru', 'walikelas', 'orangtua', 'siswa'];
        foreach ($roles as $role) {
            Role::create([
                'name' => $role,
                'display_name' => ucfirst($role)
            ]);
        }

        // Seed achievements
        Achievement::create([
            'title' => 'Pemula Aktif',
            'description' => 'Misi kebaikan pertama.',
            'category' => 'karakter',
            'criteria' => 'first_mission',
            'icon' => 'award',
        ]);

        Achievement::create([
            'title' => 'Bintang Akademik',
            'description' => 'Meraih minimal 300 poin.',
            'category' => 'akademik',
            'criteria' => 'points_300',
            'icon' => 'book-open',
        ]);

        Achievement::create([
            'title' => 'Volunteer Tangguh',
            'description' => 'Berpartisipasi aktif dalam kegiatan sosial.',
            'category' => 'sosial',
            'criteria' => 'volunteer_social',
            'icon' => 'users',
        ]);
    }

    public function test_student_gets_point_badge_automatically()
    {
        $student = User::factory()->create([
            'role_id' => 5, // siswa
            'points' => 50,
        ]);

        $pointService = resolve(PointService::class);
        
        // Add points to cross the 300 threshold
        $pointService->addPoints($student, 260, 'kebaikan', 'Juara 1 Lomba Matematika');

        $student->refresh();
        $this->assertEquals(310, $student->points);

        // Assert they got the "Bintang Akademik" badge
        $badge = Achievement::where('criteria', 'points_300')->first();
        $this->assertTrue($student->achievements->contains($badge->id));
    }

    public function test_student_gets_first_mission_badge_automatically()
    {
        $student = User::factory()->create([
            'role_id' => 5, // siswa
            'points' => 0,
        ]);

        $mission = Mission::create([
            'title' => 'Membaca Buku Harian',
            'description' => 'Membaca buku 15 menit',
            'points_reward' => 10,
            'type' => 'daily',
        ]);

        // Attach mission as approved
        $student->missions()->attach($mission->id, ['status' => 'approved']);

        $pointService = resolve(PointService::class);
        
        // Trigger point addition to run checker
        $pointService->addPoints($student, 10, 'kebaikan', 'Misi disetujui');

        $student->refresh();

        // Assert they got the "Pemula Aktif" badge
        $badge = Achievement::where('criteria', 'first_mission')->first();
        $this->assertTrue($student->achievements->contains($badge->id));
    }

    public function test_student_gets_volunteer_badge_automatically()
    {
        $student = User::factory()->create([
            'role_id' => 5, // siswa
            'points' => 0,
        ]);

        $mission = Mission::create([
            'title' => 'Kerja Bakti Bersama',
            'description' => 'Membersihkan taman sekolah',
            'points_reward' => 30,
            'type' => 'weekly',
        ]);

        // Attach mission as approved
        $student->missions()->attach($mission->id, ['status' => 'approved']);

        $pointService = resolve(PointService::class);
        
        // Trigger point addition to run checker
        $pointService->addPoints($student, 30, 'kebaikan', 'Misi disetujui');

        $student->refresh();

        // Assert they got the "Volunteer Tangguh" badge
        $badge = Achievement::where('criteria', 'volunteer_social')->first();
        $this->assertTrue($student->achievements->contains($badge->id));
    }
}
