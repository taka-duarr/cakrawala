<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\Jurusan;
use App\Models\Classroom;
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

        // ─── Jurusan ─────────────────────────────────────────────────
        $ipa  = Jurusan::create(['name' => 'IPA',   'description' => 'Ilmu Pengetahuan Alam']);
        $ips  = Jurusan::create(['name' => 'IPS',   'description' => 'Ilmu Pengetahuan Sosial']);
        $bhs  = Jurusan::create(['name' => 'Bahasa','description' => 'Program Bahasa & Sastra']);

        // ─── Tahun Ajaran & Semester ──────────────────────────────────
        $ta2425 = AcademicYear::create(['name' => '2024/2025', 'is_active' => false]);
        $ta2526 = AcademicYear::create(['name' => '2025/2026', 'is_active' => true]);

        Semester::create(['academic_year_id' => $ta2425->id, 'name' => 'Ganjil', 'is_active' => false]);
        Semester::create(['academic_year_id' => $ta2425->id, 'name' => 'Genap',  'is_active' => false]);

        $semGanjil = Semester::create(['academic_year_id' => $ta2526->id, 'name' => 'Ganjil', 'is_active' => true]);
        Semester::create(['academic_year_id' => $ta2526->id, 'name' => 'Genap',  'is_active' => false]);

        // ─── Classrooms ────────────────────────────────────────────────
        $class1 = Classroom::create([
            'name'             => 'X IPA 1',
            'points'           => 1850,
            'grade_level'      => 10,
            'jurusan_id'       => $ipa->id,
            'academic_year_id' => $ta2526->id,
            'semester_id'      => $semGanjil->id,
        ]);

        $class2 = Classroom::create([
            'name'             => 'X IPS 2',
            'points'           => 840,
            'grade_level'      => 10,
            'jurusan_id'       => $ips->id,
            'academic_year_id' => $ta2526->id,
            'semester_id'      => $semGanjil->id,
        ]);

        $class3 = Classroom::create([
            'name'             => 'XI IPA 3',
            'points'           => 790,
            'grade_level'      => 11,
            'jurusan_id'       => $ipa->id,
            'academic_year_id' => $ta2526->id,
            'semester_id'      => $semGanjil->id,
        ]);

        $class4 = Classroom::create([
            'name'             => 'XI IPS 1',
            'points'           => 620,
            'grade_level'      => 11,
            'jurusan_id'       => $ips->id,
            'academic_year_id' => $ta2526->id,
            'semester_id'      => $semGanjil->id,
        ]);

        $class5 = Classroom::create([
            'name'             => 'XII Bahasa',
            'points'           => 1100,
            'grade_level'      => 12,
            'jurusan_id'       => $bhs->id,
            'academic_year_id' => $ta2526->id,
            'semester_id'      => $semGanjil->id,
        ]);

        // ─── Wali Kelas ───────────────────────────────────────────────
        $waliKelas = User::factory()->create([
            'name'         => 'Bapak Rian Wali',
            'email'        => 'walikelas@cakrawala.com',
            'role_id'      => 3,
            'classroom_id' => $class1->id,
        ]);

        // Orang Tua User
        $orangTua = User::factory()->create([
            'name'    => 'Ibu Maria (Orang Tua Andi)',
            'email'   => 'orangtua@cakrawala.com',
            'role_id' => 4,
        ]);

        // ─── Siswa Utama (untuk login demo) ──────────────────────────
        $siswa = User::factory()->create([
            'name'               => 'Andi Pratama',
            'email'              => 'siswa@cakrawala.com',
            'role_id'            => 5,
            'points'    => 150,

            'current_level'      => 'Berkembang',
            'classroom_id'       => $class1->id,
        ]);

        // Hubungkan Orang Tua dan Siswa (Anak)
        $orangTua->children()->attach($siswa->id);

        // ─── Siswa Dummy Kelas X IPA 1 ────────────────────────────────
        $siswasClass1 = [
            ['name' => 'Budi Santoso',       'points' => 320,  'current_level' => 'Berkembang'],
            ['name' => 'Citra Dewi',          'points' => 280, 'current_level' => 'Berkembang'],
            ['name' => 'Dimas Arya',          'points' => 195,  'current_level' => 'Berkembang'],
            ['name' => 'Eka Putri',           'points' => 450,  'current_level' => 'Berkembang'],
            ['name' => 'Fajar Nugroho',       'points' => 60, 'current_level' => 'Pemula'],
        ];
        foreach ($siswasClass1 as $i => $s) {
            User::factory()->create(array_merge($s, [
                'email'      => 'siswa.xipa1.' . ($i+1) . '@cakrawala.com',
                'role_id'    => 5,
                'classroom_id' => $class1->id,
            ]));
        }

        // ─── Siswa Dummy Kelas X IPS 2 ────────────────────────────────
        $siswasClass2 = [
            ['name' => 'Galih Prakoso',       'points' => 210,  'current_level' => 'Berkembang'],
            ['name' => 'Hani Rahayu',         'points' => 175, 'current_level' => 'Berkembang'],
            ['name' => 'Ivan Susanto',        'points' => 90,  'current_level' => 'Pemula'],
            ['name' => 'Julia Anggraini',     'points' => 340,  'current_level' => 'Berkembang'],
        ];
        foreach ($siswasClass2 as $i => $s) {
            User::factory()->create(array_merge($s, [
                'email'        => 'siswa.xips2.' . ($i+1) . '@cakrawala.com',
                'role_id'      => 5,
                'classroom_id' => $class2->id,
            ]));
        }

        // ─── Siswa Dummy Kelas XI IPA 3 ───────────────────────────────
        $siswasClass3 = [
            ['name' => 'Kevin Maulana',       'points' => 780,  'current_level' => 'Unggul'],
            ['name' => 'Laila Sari',          'points' => 520,  'current_level' => 'Berkembang'],
            ['name' => 'Mario Prabowo',       'points' => 410, 'current_level' => 'Berkembang'],
            ['name' => 'Nadia Kusuma',        'points' => 650,  'current_level' => 'Unggul'],
            ['name' => 'Omar Fauzi',          'points' => 290, 'current_level' => 'Berkembang'],
        ];
        foreach ($siswasClass3 as $i => $s) {
            User::factory()->create(array_merge($s, [
                'email'        => 'siswa.xiipa3.' . ($i+1) . '@cakrawala.com',
                'role_id'      => 5,
                'classroom_id' => $class3->id,
            ]));
        }

        // ─── Siswa Dummy Kelas XI IPS 1 ───────────────────────────────
        $siswasClass4 = [
            ['name' => 'Putri Wulandari',     'points' => 130, 'current_level' => 'Berkembang'],
            ['name' => 'Rizky Firmansyah',    'points' => 200,  'current_level' => 'Berkembang'],
            ['name' => 'Sari Indah',          'points' => 75,  'current_level' => 'Pemula'],
        ];
        foreach ($siswasClass4 as $i => $s) {
            User::factory()->create(array_merge($s, [
                'email'        => 'siswa.xiips1.' . ($i+1) . '@cakrawala.com',
                'role_id'      => 5,
                'classroom_id' => $class4->id,
            ]));
        }

        // ─── Siswa Dummy Kelas XII Bahasa ─────────────────────────────
        $siswasClass5 = [
            ['name' => 'Tegar Wahyudi',       'points' => 1600, 'current_level' => 'Teladan'],
            ['name' => 'Umi Kalsum',          'points' => 920, 'current_level' => 'Unggul'],
            ['name' => 'Vino Aditya',         'points' => 740, 'current_level' => 'Unggul'],
            ['name' => 'Wulan Pertiwi',       'points' => 1200, 'current_level' => 'Unggul'],
        ];
        foreach ($siswasClass5 as $i => $s) {
            User::factory()->create(array_merge($s, [
                'email'        => 'siswa.xiibhs.' . ($i+1) . '@cakrawala.com',
                'role_id'      => 5,
                'classroom_id' => $class5->id,
            ]));
        }

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

        // Seeding Mata Pelajaran
        $matematika = \App\Models\Subject::create([
            'name' => 'Matematika',
            'code' => 'MTK',
            'description' => 'Pembelajaran numerasi, logika, dan pemecahan masalah.',
            'is_active' => true,
        ]);

        $bahasaIndonesia = \App\Models\Subject::create([
            'name' => 'Bahasa Indonesia',
            'code' => 'BIN',
            'description' => 'Pembelajaran literasi, komunikasi, dan apresiasi bahasa.',
            'is_active' => true,
        ]);

        $fisika = \App\Models\Subject::create([
            'name' => 'Fisika',
            'code' => 'FIS',
            'description' => 'Pembelajaran sains, pengukuran, dan fenomena alam.',
            'is_active' => true,
        ]);

        // Seeding Penugasan Mengajar awal untuk fondasi absensi per mapel
        \App\Models\TeachingAssignment::create([
            'teacher_id' => 2,
            'subject_id' => $matematika->id,
            'classroom_id' => $class1->id,
            'academic_year_id' => $ta2526->id,
            'semester_id' => $semGanjil->id,
            'is_active' => true,
        ]);

        \App\Models\TeachingAssignment::create([
            'teacher_id' => 2,
            'subject_id' => $bahasaIndonesia->id,
            'classroom_id' => $class2->id,
            'academic_year_id' => $ta2526->id,
            'semester_id' => $semGanjil->id,
            'is_active' => true,
        ]);

        \App\Models\TeachingAssignment::create([
            'teacher_id' => $waliKelas->id,
            'subject_id' => $fisika->id,
            'classroom_id' => $class1->id,
            'academic_year_id' => $ta2526->id,
            'semester_id' => $semGanjil->id,
            'is_active' => true,
        ]);
    }
}
