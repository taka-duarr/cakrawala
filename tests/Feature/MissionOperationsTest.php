<?php

namespace Tests\Feature;

use App\Models\Mission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MissionOperationsTest extends TestCase
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
    }

    public function test_student_cannot_take_mission_manually()
    {
        $student = User::factory()->create(['role_id' => 5]);
        $mission = Mission::create([
            'title' => 'Bakti Sosial',
            'description' => 'Membantu warga sekitar',
            'points_reward' => 100,
            'type' => 'school',
            'proof_type' => 'text',
            'is_active' => true,
        ]);

        $response = $this->actingAs($student)
            ->post(route('student.mission.take', $mission->id));

        $response->assertStatus(403);
    }

    public function test_student_cannot_submit_proof_manually()
    {
        $student = User::factory()->create(['role_id' => 5]);
        $mission = Mission::create([
            'title' => 'Bakti Sosial',
            'description' => 'Membantu warga sekitar',
            'points_reward' => 100,
            'type' => 'school',
            'proof_type' => 'text',
            'is_active' => true,
        ]);

        $response = $this->actingAs($student)
            ->post(route('student.mission.submit', $mission->id), [
                'proof_text' => 'Bantu membersihkan mesjid'
            ]);

        $response->assertStatus(403);
    }
}
