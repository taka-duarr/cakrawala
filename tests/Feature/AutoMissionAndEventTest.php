<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Mission;
use App\Models\Role;
use App\Models\User;
use App\Models\Classroom;
use App\Services\AutoMissionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AutoMissionAndEventTest extends TestCase
{
    use RefreshDatabase;

    protected $student;
    protected $guru;
    protected $admin;
    protected $classroom;

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

        // Create Classroom
        $this->classroom = Classroom::create([
            'name' => 'X MIPA 1',
            'points' => 0,
        ]);

        // Create Student
        $this->student = User::create([
            'name' => 'Andi Pratama',
            'email' => 'siswa@cakrawala.com',
            'password' => bcrypt('password123'),
            'role_id' => 5, // siswa
            'classroom_id' => $this->classroom->id,
            'points' => 100,
        ]);

        // Create Guru
        $this->guru = User::create([
            'name' => 'Bapak Budi',
            'email' => 'guru@cakrawala.com',
            'password' => bcrypt('password123'),
            'role_id' => 2, // guru
        ]);

        // Create Admin
        $this->admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@cakrawala.com',
            'password' => bcrypt('password123'),
            'role_id' => 1, // admin
        ]);
    }

    public function test_student_attendance_triggers_auto_mission()
    {
        // 1. Create a mission containing the keyword "Presensi"
        $mission = Mission::create([
            'title' => 'Misi Presensi Pagi',
            'description' => 'Misi untuk melatih kedisiplinan hadir pagi.',
            'points_reward' => 25,
            'type' => 'daily',
            'proof_type' => 'none',
            'is_active' => true,
        ]);

        // Verify initial student points and missions count
        $this->assertEquals(100, $this->student->points);
        $this->assertEquals(0, $this->student->missions()->count());

        // 2. Call triggerAttendance
        app(AutoMissionService::class)->triggerAttendance($this->student);

        // Refresh model
        $this->student->refresh();

        // Verify the student got the points and the mission status is approved
        $this->assertEquals(125, $this->student->points);
        $this->assertEquals(1, $this->student->missions()->count());
        $this->assertEquals('approved', $this->student->missions()->first()->pivot->status);
    }

    public function test_guru_can_manually_award_missions()
    {
        $mission = Mission::create([
            'title' => 'Kerja Bakti Kelas',
            'description' => 'Misi membersihkan kelas.',
            'points_reward' => 30,
            'type' => 'weekly',
            'proof_type' => 'none',
            'is_active' => true,
        ]);

        // Authenticate as Guru and post to the award mission endpoint
        $response = $this->actingAs($this->guru)
            ->post(route('guru.missions.award.process', $mission->id), [
                'student_ids' => [$this->student->id]
            ]);

        $response->assertStatus(302); // Redirect back
        $this->student->refresh();

        $this->assertEquals(130, $this->student->points);
        $this->assertEquals('approved', $this->student->missions()->first()->pivot->status);
    }

    public function test_guru_can_manually_award_events()
    {
        $event = Event::create([
            'title' => 'Pekan Karakter Cakrawala',
            'description' => 'Aksi karakter tahunan.',
            'event_date' => '15 - 20 Juni 2026',
            'location' => 'Aula',
            'points_bonus' => 50,
            'category' => 'karakter',
            'is_active' => true,
        ]);

        // Authenticate as Guru and post to the award event endpoint
        $response = $this->actingAs($this->guru)
            ->post(route('guru.events.award.process', $event->id), [
                'student_ids' => [$this->student->id]
            ]);

        $response->assertStatus(302); // Redirect back
        $this->student->refresh();

        $this->assertEquals(150, $this->student->points);
        $this->assertEquals('completed', $this->student->events()->first()->pivot->status);
    }

    public function test_admin_can_manually_award_events()
    {
        $event = Event::create([
            'title' => 'Cakrawala Clean & Green',
            'description' => 'Aksi gotong royong.',
            'event_date' => '23 Juni 2026',
            'location' => 'Sekolah',
            'points_bonus' => 40,
            'category' => 'sosial',
            'is_active' => true,
        ]);

        // Authenticate as Admin and post to the award event endpoint
        $response = $this->actingAs($this->admin)
            ->post(route('admin.events.award.process', $event->id), [
                'student_ids' => [$this->student->id]
            ]);

        $response->assertStatus(302); // Redirect back
        $this->student->refresh();

        $this->assertEquals(140, $this->student->points);
        $this->assertEquals('completed', $this->student->events()->first()->pivot->status);
    }

    public function test_student_cannot_manually_take_or_submit_proof_for_missions()
    {
        $mission = Mission::create([
            'title' => 'Misi Mandiri',
            'description' => 'Misi uji coba.',
            'points_reward' => 10,
            'type' => 'daily',
            'proof_type' => 'text',
            'is_active' => true,
        ]);

        // 1. Try to take mission
        $responseTake = $this->actingAs($this->student)
            ->post(route('student.mission.take', $mission->id));
        
        $responseTake->assertStatus(403);

        // 2. Try to submit proof
        $responseSubmit = $this->actingAs($this->student)
            ->post(route('student.mission.submit', $mission->id), [
                'proof_text' => 'Ini bukti saya'
            ]);

        $responseSubmit->assertStatus(403);
    }
}
