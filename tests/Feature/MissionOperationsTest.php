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

    public function test_student_can_take_mission()
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

        $response->assertStatus(302);
        $this->assertDatabaseHas('mission_user', [
            'user_id' => $student->id,
            'mission_id' => $mission->id,
            'status' => 'taken',
        ]);
    }

    public function test_student_cannot_take_mission_twice()
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

        // First take
        $student->missions()->attach($mission->id, ['status' => 'taken']);

        // Second take
        $response = $this->actingAs($student)
            ->post(route('student.mission.take', $mission->id));

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Opps! Anda sudah mengambil misi ini sebelumnya.');
    }

    public function test_student_can_retake_rejected_mission()
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

        // Rejected mission
        $student->missions()->attach($mission->id, [
            'status' => 'rejected',
            'proof_content' => 'Old proof content',
            'notes' => 'Not good enough'
        ]);

        // Retake
        $response = $this->actingAs($student)
            ->post(route('student.mission.take', $mission->id));

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Misi berhasil diambil kembali! Silakan kirimkan bukti baru.');

        $this->assertDatabaseHas('mission_user', [
            'user_id' => $student->id,
            'mission_id' => $mission->id,
            'status' => 'taken',
            'proof_url' => null,
            'proof_content' => null,
            'notes' => null,
        ]);
    }

    public function test_student_cannot_submit_proof_without_taking_first()
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

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Silakan ambil misi ini terlebih dahulu sebelum mengirimkan bukti.');
    }

    public function test_student_cannot_submit_proof_for_approved_mission()
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

        $student->missions()->attach($mission->id, [
            'status' => 'approved',
            'proof_content' => 'Bantu membersihkan mesjid'
        ]);

        $response = $this->actingAs($student)
            ->post(route('student.mission.submit', $mission->id), [
                'proof_text' => 'Bantu membersihkan mesjid lagi'
            ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Misi ini sudah selesai dan telah disetujui.');
    }
}
