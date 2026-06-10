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

        // Create Classrooms
        $class1 = \App\Models\Classroom::create([
            'name' => 'X IPA 1',
            'points' => 1850,
        ]);

        $class2 = \App\Models\Classroom::create([
            'name' => 'X IPS 2',
            'points' => 840,
        ]);

        $class3 = \App\Models\Classroom::create([
            'name' => 'XI IPA 3',
            'points' => 790,
        ]);

        // Wali Kelas User
        $waliKelas = User::factory()->create([
            'name' => 'Bapak Rian Wali',
            'email' => 'walikelas@cakrawala.com',
            'role_id' => 3,
            'classroom_id' => $class1->id,
        ]);

        // Orang Tua User
        $orangTua = User::factory()->create([
            'name' => 'Ibu Maria (Orang Tua Andi)',
            'email' => 'orangtua@cakrawala.com',
            'role_id' => 4,
        ]);

        // Siswa User
        $siswa = User::factory()->create([
            'name' => 'Andi Siswa',
            'email' => 'siswa@cakrawala.com',
            'role_id' => 5,
            'points_kebaikan' => 150,
            'points_pelanggaran' => 0,
            'current_level' => 'Berkembang',
            'classroom_id' => $class1->id,
        ]);

        // Hubungkan Orang Tua dan Siswa (Anak)
        $orangTua->children()->attach($siswa->id);

        // Seeding Missions
        \App\Models\Mission::create([
            'title' => 'Hadir Tepat Waktu',
            'description' => 'Datang ke sekolah sebelum pukul 07.00 WIB dengan seragam rapi.',
            'points_reward' => 15,
            'type' => 'daily',
            'is_active' => true,
        ]);

        \App\Models\Mission::create([
            'title' => 'Membaca Buku 15 Menit',
            'description' => 'Membaca buku non-pelajaran di perpustakaan sekolah pada jam istirahat.',
            'points_reward' => 10,
            'type' => 'daily',
            'is_active' => true,
        ]);

        \App\Models\Mission::create([
            'title' => 'Menjadi Imam Shalat / Pemimpin Doa',
            'description' => 'Memimpin doa bersama di kelas atau menjadi imam shalat berjamaah di musholla.',
            'points_reward' => 40,
            'type' => 'weekly',
            'is_active' => true,
        ]);

        \App\Models\Mission::create([
            'title' => 'Kerja Bakti Lingkungan Kelas',
            'description' => 'Berpartisipasi aktif membersihkan dan merapikan lingkungan kelas setelah pulang sekolah.',
            'points_reward' => 30,
            'type' => 'weekly',
            'is_active' => true,
        ]);

        \App\Models\Mission::create([
            'title' => 'Panitia Event Bulan Bahasa',
            'description' => 'Menjadi panitia aktif atau koordinator acara lomba Bulan Bahasa sekolah.',
            'points_reward' => 100,
            'type' => 'special',
            'is_active' => true,
        ]);

        // Seeding Rewards
        \App\Models\Reward::create([
            'name' => 'Voucher Kantin Sehat Rp10.000',
            'description' => 'Voucher makan siang gratis di kantin sehat sekolah.',
            'points_cost' => 50,
            'category' => 'sekolah',
            'is_available' => true,
        ]);

        \App\Models\Reward::create([
            'name' => 'Buku Catatan Eksklusif CAKRAWALA',
            'description' => 'Buku catatan berkualitas tinggi dengan sampul keras bermotif CAKRAWALA.',
            'points_cost' => 120,
            'category' => 'akademik',
            'is_available' => true,
        ]);

        \App\Models\Reward::create([
            'name' => 'Merchandise Kaos Cakrawala',
            'description' => 'Kaos katun premium CAKRAWALA - Melampaui Nilai, Membentuk Masa Depan.',
            'points_cost' => 300,
            'category' => 'sekolah',
            'is_available' => true,
        ]);

        \App\Models\Reward::create([
            'name' => 'Sertifikat Karakter Unggul Sekolah',
            'description' => 'Sertifikat penghargaan resmi karakter siswa teladan yang disahkan oleh Kepala Sekolah.',
            'points_cost' => 500,
            'category' => 'penghargaan',
            'is_available' => true,
        ]);

        // Seeding Achievements
        \App\Models\Achievement::create([
            'title' => 'Pemula Aktif',
            'description' => 'Diberikan saat siswa berhasil menyelesaikan misi pertamanya.',
            'category' => 'karakter',
            'criteria' => 'first_mission',
            'icon' => 'award',
        ]);

        \App\Models\Achievement::create([
            'title' => 'Bintang Akademik',
            'description' => 'Meraih minimal 300 poin kebaikan di sekolah.',
            'category' => 'akademik',
            'criteria' => 'points_300',
            'icon' => 'book-open',
        ]);

        \App\Models\Achievement::create([
            'title' => 'Siswa Teladan',
            'description' => 'Meraih minimal 1500 poin kebaikan dan tidak memiliki poin pelanggaran.',
            'category' => 'karakter',
            'criteria' => 'teladan_1500',
            'icon' => 'shield-check',
        ]);

        \App\Models\Achievement::create([
            'title' => 'Volunteer Tangguh',
            'description' => 'Berpartisipasi aktif dalam kegiatan gotong royong dan sosial kelas.',
            'category' => 'sosial',
            'criteria' => 'volunteer_social',
            'icon' => 'users',
        ]);
    }
}
