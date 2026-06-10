<?php

namespace Tests\Feature;

use App\Models\Reward;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RewardTransactionTest extends TestCase
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

    public function test_student_can_claim_reward_with_sufficient_points()
    {
        $student = User::factory()->create([
            'role_id' => 5, // siswa
            'points' => 200,
        ]);

        $reward = Reward::create([
            'name' => 'Kaos Eksklusif',
            'description' => 'Kaos Cakrawala',
            'points_cost' => 120,
            'category' => 'sekolah',
            'is_available' => true,
        ]);

        $response = $this->actingAs($student)
            ->post(route('student.rewards.claim', $reward->id));

        $response->assertStatus(302);
        
        // Assert points were deducted
        $student->refresh();
        $this->assertEquals(80, $student->points);

        // Assert record exists in pivot
        $this->assertDatabaseHas('reward_user', [
            'user_id' => $student->id,
            'reward_id' => $reward->id,
            'status' => 'pending_approval',
        ]);
    }

    public function test_student_cannot_claim_reward_with_insufficient_points()
    {
        $student = User::factory()->create([
            'role_id' => 5, // siswa
            'points' => 50,
        ]);

        $reward = Reward::create([
            'name' => 'Kaos Eksklusif',
            'description' => 'Kaos Cakrawala',
            'points_cost' => 120,
            'category' => 'sekolah',
            'is_available' => true,
        ]);

        $response = $this->actingAs($student)
            ->post(route('student.rewards.claim', $reward->id));

        $response->assertStatus(302);
        
        // Assert points were NOT deducted
        $student->refresh();
        $this->assertEquals(50, $student->points);

        // Assert no record in pivot
        $this->assertDatabaseMissing('reward_user', [
            'user_id' => $student->id,
            'reward_id' => $reward->id,
        ]);
    }
}
