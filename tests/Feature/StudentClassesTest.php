<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Mission;
use App\Models\Role;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\TeachingAssignment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentClassesTest extends TestCase
{
    use RefreshDatabase;

    protected $student;
    protected $classroom;
    protected $guru;
    protected $subject;
    protected $academicYear;
    protected $semester;
    protected $assignment;

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
            'name' => 'XI MIPA 1',
            'points' => 300,
        ]);

        // Create Student User in the classroom
        $this->student = User::create([
            'name' => 'Siswa Sasuke',
            'email' => 'sasuke@cakrawala.com',
            'password' => bcrypt('password123'),
            'role_id' => 5, // siswa
            'classroom_id' => $this->classroom->id,
            'points' => 50,
        ]);

        // Create Guru User
        $this->guru = User::create([
            'name' => 'Guru Iruka',
            'email' => 'iruka@cakrawala.com',
            'password' => bcrypt('password123'),
            'role_id' => 2, // guru
        ]);

        // Create Subject
        $this->subject = Subject::create([
            'name' => 'Matematika Peminatan',
            'code' => 'MAT-PEM',
            'description' => 'Mata pelajaran matematika lanjut.',
        ]);

        // Create Academic Year & Semester
        $this->academicYear = AcademicYear::create([
            'name' => '2025/2026',
            'is_active' => true,
        ]);

        $this->semester = Semester::create([
            'academic_year_id' => $this->academicYear->id,
            'name' => 'Ganjil',
            'is_active' => true,
        ]);

        // Create Teaching Assignment
        $this->assignment = TeachingAssignment::create([
            'teacher_id' => $this->guru->id,
            'subject_id' => $this->subject->id,
            'classroom_id' => $this->classroom->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
            'is_active' => true,
        ]);
    }

    public function test_student_can_view_active_classes_list()
    {
        $response = $this->actingAs($this->student)
            ->get(route('student.my-classes'));

        $response->assertStatus(200);
        $response->assertSee('XI MIPA 1');
        $response->assertSee('Matematika Peminatan');
        $response->assertSee('MAT-PEM');
        $response->assertSee('Guru Iruka');
    }

    public function test_student_can_view_class_detail()
    {
        // Create a subject mission with matching subject code in title
        $mission = Mission::create([
            'title' => '[MAT-PEM] Menyelesaikan Soal Trigonometri',
            'description' => 'Kerjakan halaman 45 buku paket.',
            'points_reward' => 20,
            'type' => 'class',
            'proof_type' => 'link',
        ]);

        $response = $this->actingAs($this->student)
            ->get(route('student.class-detail', $this->assignment->id));

        $response->assertStatus(200);
        $response->assertSee('Matematika Peminatan');
        $response->assertSee('MAT-PEM');
        $response->assertSee('Guru Iruka');
        
        // Assert subject mission is displayed
        $response->assertSee('[MAT-PEM] Menyelesaikan Soal Trigonometri');
        
        // Assert classmate is displayed (our student is in this classroom)
        $response->assertSee('Siswa Sasuke');
    }

    public function test_student_cannot_view_class_detail_of_other_classroom()
    {
        $otherClassroom = Classroom::create([
            'name' => 'XII IPS 2',
        ]);

        $otherAssignment = TeachingAssignment::create([
            'teacher_id' => $this->guru->id,
            'subject_id' => $this->subject->id,
            'classroom_id' => $otherClassroom->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->student)
            ->get(route('student.class-detail', $otherAssignment->id));

        // Should abort or throw 404 since it's not their class
        $response->assertStatus(404);
    }
}
