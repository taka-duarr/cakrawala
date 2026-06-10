<?php

namespace Tests\Feature;

use App\Models\Achievement;
use App\Models\Mission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuruOperationsTest extends TestCase
{
    use RefreshDatabase;

    protected $guru;
    protected $student;
    protected $achievement;

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

        // Create Guru User
        $this->guru = User::create([
            'name' => 'Guru Kakashi',
            'email' => 'kakashi@cakrawala.com',
            'password' => bcrypt('password123'),
            'role_id' => 2, // guru
        ]);

        // Create Student User
        $this->student = User::create([
            'name' => 'Siswa Naruto',
            'email' => 'naruto@cakrawala.com',
            'password' => bcrypt('password123'),
            'role_id' => 5, // siswa
            'points' => 10,
        ]);

        // Create Achievement
        $this->achievement = Achievement::create([
            'title' => 'Murid Teladan',
            'description' => 'Mendapat apresiasi guru.',
            'category' => 'karakter',
            'icon' => 'award',
        ]);
    }

    public function test_guru_can_store_mission()
    {
        $response = $this->actingAs($this->guru)
            ->post(route('guru.missions.store'), [
                'title' => 'Membersihkan Kelas',
                'description' => 'Membantu membersihkan ruangan kelas setelah pulang sekolah.',
                'points_reward' => 25,
                'type' => 'daily',
                'deadline' => now()->addDays(2)->toDateTimeString(),
                'proof_type' => 'file',
            ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('missions', [
            'title' => 'Membersihkan Kelas',
            'proof_type' => 'file',
            'points_reward' => 25
        ]);
    }

    public function test_guru_can_approve_mission_submission()
    {
        // Setup a taken mission for the student
        $mission = Mission::create([
            'title' => 'Ujian Tulis',
            'description' => 'Mengerjakan tes fisika.',
            'points_reward' => 50,
            'type' => 'special',
            'proof_type' => 'link',
        ]);

        $this->student->missions()->attach($mission->id, [
            'status' => 'pending_approval',
            'proof_url' => 'https://drive.google.com/test-proof'
        ]);

        $response = $this->actingAs($this->guru)
            ->post(route('guru.missions.validate'), [
                'user_id' => $this->student->id,
                'mission_id' => $mission->id,
                'status' => 'approved',
                'notes' => 'Kerja bagus, jawaban sangat lengkap!'
            ]);

        $response->assertStatus(302);
        
        // Assert pivot table status and points update
        $this->assertDatabaseHas('mission_user', [
            'user_id' => $this->student->id,
            'mission_id' => $mission->id,
            'status' => 'approved',
            'notes' => 'Kerja bagus, jawaban sangat lengkap!'
        ]);

        $this->student->refresh();
        $this->assertEquals(60, $this->student->points);
    }

    public function test_guru_can_request_revision_on_mission()
    {
        $mission = Mission::create([
            'title' => 'Ujian Tulis',
            'description' => 'Mengerjakan tes fisika.',
            'points_reward' => 50,
            'type' => 'special',
            'proof_type' => 'link',
        ]);

        $this->student->missions()->attach($mission->id, [
            'status' => 'pending_approval',
            'proof_url' => 'https://drive.google.com/test-proof'
        ]);

        $response = $this->actingAs($this->guru)
            ->post(route('guru.missions.validate'), [
                'user_id' => $this->student->id,
                'mission_id' => $mission->id,
                'status' => 'revision',
                'notes' => 'Silakan unggah berkas yang benar.'
            ]);

        $response->assertStatus(302);
        
        $this->assertDatabaseHas('mission_user', [
            'user_id' => $this->student->id,
            'mission_id' => $mission->id,
            'status' => 'revision',
            'notes' => 'Silakan unggah berkas yang benar.'
        ]);

        // Points should remain unchanged
        $this->student->refresh();
        $this->assertEquals(10, $this->student->points);
    }

    public function test_guru_can_adjust_points_manually()
    {
        // Test add points
        $response = $this->actingAs($this->guru)
            ->post(route('guru.points.adjust'), [
                'user_id' => $this->student->id,
                'type' => 'kebaikan',
                'operation' => 'add',
                'amount' => 15,
                'description' => 'Membantu Guru di perpustakaan'
            ]);

        $response->assertStatus(302);
        $this->student->refresh();
        $this->assertEquals(25, $this->student->points);

        // Test subtract points
        $response = $this->actingAs($this->guru)
            ->post(route('guru.points.adjust'), [
                'user_id' => $this->student->id,
                'type' => 'pelanggaran',
                'operation' => 'subtract',
                'amount' => 5,
                'description' => 'Terlambat masuk kelas'
            ]);

        $response->assertStatus(302);
        $this->student->refresh();
        $this->assertEquals(20, $this->student->points);
    }

    public function test_guru_can_toggle_badges_manually()
    {
        // Award Badge
        $response = $this->actingAs($this->guru)
            ->post(route('guru.badges.toggle'), [
                'user_id' => $this->student->id,
                'achievement_id' => $this->achievement->id,
                'action' => 'award'
            ]);

        $response->assertStatus(302);
        $this->assertTrue($this->student->achievements->contains($this->achievement->id));

        // Revoke Badge
        $response = $this->actingAs($this->guru)
            ->post(route('guru.badges.toggle'), [
                'user_id' => $this->student->id,
                'achievement_id' => $this->achievement->id,
                'action' => 'revoke'
            ]);

        $response->assertStatus(302);
        $this->student->refresh();
        $this->assertFalse($this->student->achievements->contains($this->achievement->id));
    }
}
