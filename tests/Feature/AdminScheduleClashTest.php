<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Role;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\TeachingAssignment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminScheduleClashTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $guru1;
    protected $guru2;
    protected $classroom1;
    protected $classroom2;
    protected $subject1;
    protected $subject2;
    protected $academicYear;
    protected $semester;

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

        // Create Admin
        $this->admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
            'role_id' => 1,
        ]);

        // Create Teachers
        $this->guru1 = User::create([
            'name' => 'Guru Iruka',
            'email' => 'iruka@test.com',
            'password' => bcrypt('password123'),
            'role_id' => 2,
        ]);

        $this->guru2 = User::create([
            'name' => 'Guru Kakashi',
            'email' => 'kakashi@test.com',
            'password' => bcrypt('password123'),
            'role_id' => 2,
        ]);

        // Create Classrooms
        $this->classroom1 = Classroom::create(['name' => 'X IPA 1']);
        $this->classroom2 = Classroom::create(['name' => 'X IPA 2']);

        // Create Subjects
        $this->subject1 = Subject::create(['name' => 'Matematika', 'code' => 'MTK']);
        $this->subject2 = Subject::create(['name' => 'Fisika', 'code' => 'FIS']);

        // Create Academic Year & Semester
        $this->academicYear = AcademicYear::create(['name' => '2025/2026', 'is_active' => true]);
        $this->semester = Semester::create([
            'academic_year_id' => $this->academicYear->id,
            'name' => 'Ganjil',
            'is_active' => true,
        ]);
    }

    public function test_admin_cannot_store_schedule_with_teacher_overlap()
    {
        // First assignment: Guru 1 teaches Subject 1 to Classroom 1 on Monday at 08:00 - 09:30
        TeachingAssignment::create([
            'teacher_id' => $this->guru1->id,
            'subject_id' => $this->subject1->id,
            'classroom_id' => $this->classroom1->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
            'is_active' => true,
            'day_of_week' => 'Monday',
            'start_time' => '08:00:00',
            'end_time' => '09:30:00',
        ]);

        // Second assignment (clash): Try to assign Guru 1 to teach Subject 2 to Classroom 2 on Monday at 09:00 - 10:30 (overlaps!)
        $response = $this->actingAs($this->admin)
            ->post(route('admin.teaching-assignments.store'), [
                'teacher_id' => $this->guru1->id,
                'subject_id' => $this->subject2->id,
                'classroom_id' => $this->classroom2->id,
                'academic_year_id' => $this->academicYear->id,
                'semester_id' => $this->semester->id,
                'is_active' => '1',
                'day_of_week' => 'Monday',
                'start_time' => '09:00:00',
                'end_time' => '10:30:00',
            ]);

        $response->assertSessionHasErrors(['assignment']);
        $this->assertEquals(1, TeachingAssignment::count());
    }

    public function test_admin_cannot_store_schedule_with_classroom_overlap()
    {
        // First assignment: Guru 1 teaches Subject 1 to Classroom 1 on Monday at 08:00 - 09:30
        TeachingAssignment::create([
            'teacher_id' => $this->guru1->id,
            'subject_id' => $this->subject1->id,
            'classroom_id' => $this->classroom1->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
            'is_active' => true,
            'day_of_week' => 'Monday',
            'start_time' => '08:00:00',
            'end_time' => '09:30:00',
        ]);

        // Second assignment (clash): Try to assign Guru 2 to teach Subject 2 to Classroom 1 on Monday at 09:00 - 10:30 (overlaps!)
        $response = $this->actingAs($this->admin)
            ->post(route('admin.teaching-assignments.store'), [
                'teacher_id' => $this->guru2->id,
                'subject_id' => $this->subject2->id,
                'classroom_id' => $this->classroom1->id,
                'academic_year_id' => $this->academicYear->id,
                'semester_id' => $this->semester->id,
                'is_active' => '1',
                'day_of_week' => 'Monday',
                'start_time' => '09:00:00',
                'end_time' => '10:30:00',
            ]);

        $response->assertSessionHasErrors(['assignment']);
        $this->assertEquals(1, TeachingAssignment::count());
    }

    public function test_admin_can_store_non_overlapping_schedule()
    {
        // First assignment: Monday 08:00 - 09:30
        TeachingAssignment::create([
            'teacher_id' => $this->guru1->id,
            'subject_id' => $this->subject1->id,
            'classroom_id' => $this->classroom1->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
            'is_active' => true,
            'day_of_week' => 'Monday',
            'start_time' => '08:00:00',
            'end_time' => '09:30:00',
        ]);

        // Second assignment (valid): Monday 09:40 - 11:10 (no overlap)
        $response = $this->actingAs($this->admin)
            ->post(route('admin.teaching-assignments.store'), [
                'teacher_id' => $this->guru1->id,
                'subject_id' => $this->subject2->id,
                'classroom_id' => $this->classroom2->id,
                'academic_year_id' => $this->academicYear->id,
                'semester_id' => $this->semester->id,
                'is_active' => '1',
                'day_of_week' => 'Monday',
                'start_time' => '09:40:00',
                'end_time' => '11:10:00',
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals(2, TeachingAssignment::count());
    }
}
