<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = ['admin', 'guru', 'walikelas', 'orangtua', 'siswa'];
        foreach ($roles as $role) {
            \App\Models\Role::create([
                'name' => $role,
                'display_name' => ucfirst($role)
            ]);
        }

        // Admin User
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@cakrawala.com',
            'role_id' => 1,
        ]);

        // Guru User
        User::factory()->create([
            'name' => 'Bapak Budi',
            'email' => 'guru@cakrawala.com',
            'role_id' => 2,
        ]);

        // Siswa User
        User::factory()->create([
            'name' => 'Andi Siswa',
            'email' => 'siswa@cakrawala.com',
            'role_id' => 5,
            'points_kebaikan' => 150,
            'points_pelanggaran' => 0,
            'current_level' => 'Berkembang',
            'class_name' => 'X IPA 1'
        ]);
    }
}
