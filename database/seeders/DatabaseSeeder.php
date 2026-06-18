<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\Jurusan;
use App\Models\Classroom;
use App\Models\Role;
use App\Models\Mission;

use App\Models\Achievement;
use App\Models\Subject;
use App\Models\TeachingAssignment;
use App\Models\SchoolLocation;
use App\Models\AttendanceSession;
use App\Models\Material;
use App\Models\Assignment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ─── Roles ───────────────────────────────────────────────────
        $roles = ['admin', 'guru', 'walikelas', 'orangtua', 'siswa', 'toko'];
        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role],
                ['display_name' => ucfirst($role)]
            );
        }

        // ─── Admin User ──────────────────────────────────────────────
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@cakrawala.com',
            'role_id' => 1,
        ]);

        // ─── Guru User (Bapak Budi) ──────────────────────────────────
        $guruBudi = User::factory()->create([
            'name' => 'Bapak Budi',
            'email' => 'guru@cakrawala.com',
            'role_id' => 2,
        ]);

        // ─── Toko User (Kantin Sekolah) ──────────────────────────────
        $tokoRole = \App\Models\Role::where('name', 'toko')->first();
        User::factory()->create([
            'name'    => 'Kantin Sekolah',
            'email'   => 'toko@cakrawala.com',
            'role_id' => $tokoRole->id,
            'points'  => 0,
        ]);

        User::factory()->create([
            'name'    => 'Koperasi Serba Ada',
            'email'   => 'koperasi@cakrawala.com',
            'role_id' => $tokoRole->id,
            'points'  => 0,
        ]);

        User::factory()->create([
            'name'    => 'Toko Buku Pintar',
            'email'   => 'tokobuku@cakrawala.com',
            'role_id' => $tokoRole->id,
            'points'  => 0,
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
            'angkatan'         => '2024',
        ]);

        $class2 = Classroom::create([
            'name'             => 'X IPS 2',
            'points'           => 840,
            'grade_level'      => 10,
            'jurusan_id'       => $ips->id,
            'academic_year_id' => $ta2526->id,
            'semester_id'      => $semGanjil->id,
            'angkatan'         => '2024',
        ]);

        $class3 = Classroom::create([
            'name'             => 'XI IPA 3',
            'points'           => 790,
            'grade_level'      => 11,
            'jurusan_id'       => $ipa->id,
            'academic_year_id' => $ta2526->id,
            'semester_id'      => $semGanjil->id,
            'angkatan'         => '2023',
        ]);

        $class4 = Classroom::create([
            'name'             => 'XI IPS 1',
            'points'           => 620,
            'grade_level'      => 11,
            'jurusan_id'       => $ips->id,
            'academic_year_id' => $ta2526->id,
            'semester_id'      => $semGanjil->id,
            'angkatan'         => '2023',
        ]);

        $class5 = Classroom::create([
            'name'             => 'XII Bahasa',
            'points'           => 1100,
            'grade_level'      => 12,
            'jurusan_id'       => $bhs->id,
            'academic_year_id' => $ta2526->id,
            'semester_id'      => $semGanjil->id,
            'angkatan'         => '2022',
        ]);

        // ─── Wali Kelas ───────────────────────────────────────────────
        $waliKelas = User::factory()->create([
            'name'         => 'Bapak Rian Wali',
            'email'        => 'walikelas@cakrawala.com',
            'role_id'      => 3,
            'classroom_id' => $class1->id,
        ]);

        // ─── Orang Tua User ───────────────────────────────────────────
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
            'points'             => 150,
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
                'email'        => 'siswa.xipa1.' . ($i+1) . '@cakrawala.com',
                'role_id'      => 5,
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

        // ─── Missions ────────────────────────────────────────────────
        Mission::create([
            'title' => 'Hadir Tepat Waktu',
            'description' => 'Datang ke sekolah sebelum pukul 07.00 WIB dengan seragam rapi.',
            'points_reward' => 15,
            'type' => 'daily',
            'is_active' => true,
        ]);

        Mission::create([
            'title' => 'Membaca Buku 15 Menit',
            'description' => 'Membaca buku non-pelajaran di perpustakaan sekolah pada jam istirahat.',
            'points_reward' => 10,
            'type' => 'daily',
            'is_active' => true,
        ]);

        Mission::create([
            'title' => 'Menjadi Imam Shalat / Pemimpin Doa',
            'description' => 'Memimpin doa bersama di kelas atau menjadi imam shalat berjamaah di musholla.',
            'points_reward' => 40,
            'type' => 'weekly',
            'is_active' => true,
        ]);

        Mission::create([
            'title' => 'Kerja Bakti Lingkungan Kelas',
            'description' => 'Berpartisipasi aktif membersihkan dan merapikan lingkungan kelas setelah pulang sekolah.',
            'points_reward' => 30,
            'type' => 'weekly',
            'is_active' => true,
        ]);

        Mission::create([
            'title' => 'Panitia Event Bulan Bahasa',
            'description' => 'Menjadi panitia aktif atau koordinator acara lomba Bulan Bahasa sekolah.',
            'points_reward' => 100,
            'type' => 'special',
            'is_active' => true,
        ]);



        // ─── Events ──────────────────────────────────────────────────
        \App\Models\Event::create([
            'title' => 'Pekan Karakter Cakrawala 2026',
            'description' => 'Ajang tahunan unjuk aksi kebaikan dan kreativitas karakter antarkelas. Dapatkan quest khusus poin besar!',
            'event_date' => '15 - 20 Juni 2026',
            'location' => 'Aula & Lapangan Utama Sekolah',
            'points_bonus' => 150,
            'category' => 'karakter',
            'is_active' => true,
        ]);

        \App\Models\Event::create([
            'title' => 'Cakrawala Clean & Green',
            'description' => 'Aksi gotong royong akbar membersihkan lingkungan sekitar sekolah dan penanaman pohon bersama.',
            'event_date' => '23 Juni 2026',
            'location' => 'Area Sekitar Sekolah',
            'points_bonus' => 80,
            'category' => 'sosial',
            'is_active' => true,
        ]);

        \App\Models\Event::create([
            'title' => 'Seminar Literasi Digital',
            'description' => 'Membangun karakter bijak ber media sosial dan menangkal hoaks demi masa depan digital yang sehat.',
            'event_date' => '30 Juni 2026',
            'location' => 'Laboratorium Multimedia',
            'points_bonus' => 50,
            'category' => 'akademik',
            'is_active' => true,
        ]);

        // ─── Achievements ────────────────────────────────────────────
        Achievement::create([
            'title' => 'Pemula Aktif',
            'description' => 'Diberikan saat siswa berhasil menyelesaikan misi pertamanya.',
            'category' => 'karakter',
            'criteria' => 'first_mission',
            'icon' => 'award',
        ]);

        Achievement::create([
            'title' => 'Bintang Akademik',
            'description' => 'Meraih minimal 300 poin kebaikan di sekolah.',
            'category' => 'akademik',
            'criteria' => 'points_300',
            'icon' => 'book-open',
        ]);

        Achievement::create([
            'title' => 'Siswa Teladan',
            'description' => 'Meraih minimal 1500 poin kebaikan dan tidak memiliki poin pelanggaran.',
            'category' => 'karakter',
            'criteria' => 'teladan_1500',
            'icon' => 'shield-check',
        ]);

        Achievement::create([
            'title' => 'Volunteer Tangguh',
            'description' => 'Berpartisipasi aktif dalam kegiatan gotong royong dan sosial kelas.',
            'category' => 'sosial',
            'criteria' => 'volunteer_social',
            'icon' => 'users',
        ]);

        // ─── Subjects ────────────────────────────────────────────────
        $matematika = Subject::create([
            'name' => 'Matematika',
            'code' => 'MTK',
            'description' => 'Pembelajaran numerasi, logika, dan pemecahan masalah.',
            'is_active' => true,
        ]);

        $bahasaIndonesia = Subject::create([
            'name' => 'Bahasa Indonesia',
            'code' => 'BIN',
            'description' => 'Pembelajaran literasi, komunikasi, dan apresiasi bahasa.',
            'is_active' => true,
        ]);

        $fisika = Subject::create([
            'name' => 'Fisika',
            'code' => 'FIS',
            'description' => 'Pembelajaran sains, pengukuran, dan fenomena alam.',
            'is_active' => true,
        ]);

        $kimia = Subject::create([
            'name' => 'Kimia',
            'code' => 'KIM',
            'description' => 'Pembelajaran struktur materi, reaksi, dan ikatan kimia.',
            'is_active' => true,
        ]);

        $biologi = Subject::create([
            'name' => 'Biologi',
            'code' => 'BIO',
            'description' => 'Pembelajaran makhluk hidup, ekosistem, dan genetika.',
            'is_active' => true,
        ]);

        $sejarah = Subject::create([
            'name' => 'Sejarah',
            'code' => 'SEJ',
            'description' => 'Pembelajaran peristiwa masa lalu, peradaban, dan nilai kebangsaan.',
            'is_active' => true,
        ]);

        $bahasaInggris = Subject::create([
            'name' => 'Bahasa Inggris',
            'code' => 'ING',
            'description' => 'Pembelajaran komunikasi, tata bahasa, dan literasi global.',
            'is_active' => true,
        ]);

        // ─── Additional Teachers ──────────────────────────────────────
        $guruSusi = User::factory()->create([
            'name' => 'Ibu Susi',
            'email' => 'guru.susi@cakrawala.com',
            'role_id' => 2,
        ]);

        $guruJono = User::factory()->create([
            'name' => 'Bapak Jono',
            'email' => 'guru.jono@cakrawala.com',
            'role_id' => 2,
        ]);

        // ─── School Locations ──────────────────────────────────────────
        $locGedungUtama = SchoolLocation::create([
            'name' => 'Gedung Utama SMA Cakrawala',
            'latitude' => -6.200000,
            'longitude' => 106.816666,
            'radius' => 100,
            'is_active' => true,
        ]);

        $locLaboratorium = SchoolLocation::create([
            'name' => 'Laboratorium Komputer',
            'latitude' => -6.200100,
            'longitude' => 106.816700,
            'radius' => 50,
            'is_active' => true,
        ]);

        // ─── Teaching Assignments Matrix (Overlap-free KBM Schedule) ──
        $t1 = $guruBudi->id;
        $t2 = $waliKelas->id;
        $t3 = $guruSusi->id;
        $t4 = $guruJono->id;

        $taId = $ta2526->id;
        $semId = $semGanjil->id;

        $matrix = [
            // MONDAY
            ['day' => 'Monday', 'slot' => 1, 'class' => $class1->id, 'sub' => $matematika->id, 'teacher' => $t1],
            ['day' => 'Monday', 'slot' => 1, 'class' => $class2->id, 'sub' => $bahasaIndonesia->id, 'teacher' => $t3],
            ['day' => 'Monday', 'slot' => 1, 'class' => $class5->id, 'sub' => $bahasaInggris->id, 'teacher' => $t4],

            ['day' => 'Monday', 'slot' => 2, 'class' => $class1->id, 'sub' => $fisika->id, 'teacher' => $t2],
            ['day' => 'Monday', 'slot' => 2, 'class' => $class3->id, 'sub' => $matematika->id, 'teacher' => $t1],
            ['day' => 'Monday', 'slot' => 2, 'class' => $class4->id, 'sub' => $bahasaIndonesia->id, 'teacher' => $t3],

            ['day' => 'Monday', 'slot' => 3, 'class' => $class2->id, 'sub' => $sejarah->id, 'teacher' => $t1],
            ['day' => 'Monday', 'slot' => 3, 'class' => $class3->id, 'sub' => $bahasaInggris->id, 'teacher' => $t4],
            ['day' => 'Monday', 'slot' => 3, 'class' => $class5->id, 'sub' => $bahasaIndonesia->id, 'teacher' => $t3],

            ['day' => 'Monday', 'slot' => 4, 'class' => $class4->id, 'sub' => $sejarah->id, 'teacher' => $t1],
            ['day' => 'Monday', 'slot' => 4, 'class' => $class5->id, 'sub' => $fisika->id, 'teacher' => $t2],

            // TUESDAY
            ['day' => 'Tuesday', 'slot' => 1, 'class' => $class1->id, 'sub' => $kimia->id, 'teacher' => $t3],
            ['day' => 'Tuesday', 'slot' => 1, 'class' => $class2->id, 'sub' => $fisika->id, 'teacher' => $t2],

            ['day' => 'Tuesday', 'slot' => 2, 'class' => $class1->id, 'sub' => $bahasaIndonesia->id, 'teacher' => $t2],
            ['day' => 'Tuesday', 'slot' => 2, 'class' => $class2->id, 'sub' => $kimia->id, 'teacher' => $t3],
            ['day' => 'Tuesday', 'slot' => 2, 'class' => $class4->id, 'sub' => $matematika->id, 'teacher' => $t1],

            ['day' => 'Tuesday', 'slot' => 3, 'class' => $class3->id, 'sub' => $biologi->id, 'teacher' => $t3],
            ['day' => 'Tuesday', 'slot' => 3, 'class' => $class4->id, 'sub' => $fisika->id, 'teacher' => $t2],

            ['day' => 'Tuesday', 'slot' => 4, 'class' => $class3->id, 'sub' => $fisika->id, 'teacher' => $t2],

            // WEDNESDAY
            ['day' => 'Wednesday', 'slot' => 1, 'class' => $class1->id, 'sub' => $biologi->id, 'teacher' => $t3],
            ['day' => 'Wednesday', 'slot' => 1, 'class' => $class2->id, 'sub' => $bahasaInggris->id, 'teacher' => $t4],
            ['day' => 'Wednesday', 'slot' => 1, 'class' => $class5->id, 'sub' => $sejarah->id, 'teacher' => $t1],

            ['day' => 'Wednesday', 'slot' => 2, 'class' => $class3->id, 'sub' => $kimia->id, 'teacher' => $t3],
            ['day' => 'Wednesday', 'slot' => 2, 'class' => $class4->id, 'sub' => $bahasaInggris->id, 'teacher' => $t4],

            ['day' => 'Wednesday', 'slot' => 4, 'class' => $class5->id, 'sub' => $kimia->id, 'teacher' => $t3],

            // THURSDAY
            ['day' => 'Thursday', 'slot' => 1, 'class' => $class1->id, 'sub' => $bahasaInggris->id, 'teacher' => $t4],
            ['day' => 'Thursday', 'slot' => 1, 'class' => $class2->id, 'sub' => $matematika->id, 'teacher' => $t1],
            ['day' => 'Thursday', 'slot' => 1, 'class' => $class5->id, 'sub' => $bahasaIndonesia->id, 'teacher' => $t2],

            ['day' => 'Thursday', 'slot' => 2, 'class' => $class3->id, 'sub' => $bahasaIndonesia->id, 'teacher' => $t2],
            ['day' => 'Thursday', 'slot' => 2, 'class' => $class4->id, 'sub' => $kimia->id, 'teacher' => $t3],

            ['day' => 'Thursday', 'slot' => 3, 'class' => $class5->id, 'sub' => $matematika->id, 'teacher' => $t1],

            // FRIDAY
            ['day' => 'Friday', 'slot' => 1, 'class' => $class1->id, 'sub' => $sejarah->id, 'teacher' => $t1],

            ['day' => 'Friday', 'slot' => 2, 'class' => $class2->id, 'sub' => $biologi->id, 'teacher' => $t3],
            ['day' => 'Friday', 'slot' => 2, 'class' => $class3->id, 'sub' => $sejarah->id, 'teacher' => $t1],

            ['day' => 'Friday', 'slot' => 3, 'class' => $class4->id, 'sub' => $biologi->id, 'teacher' => $t3],

            ['day' => 'Friday', 'slot' => 4, 'class' => $class5->id, 'sub' => $biologi->id, 'teacher' => $t3],
        ];

        $slotTimes = [
            1 => ['start' => '07:30:00', 'end' => '09:00:00'],
            2 => ['start' => '09:15:00', 'end' => '10:45:00'],
            3 => ['start' => '11:00:00', 'end' => '12:30:00'],
            4 => ['start' => '13:00:00', 'end' => '14:30:00'],
        ];

        foreach ($matrix as $data) {
            TeachingAssignment::create([
                'teacher_id'       => $data['teacher'],
                'subject_id'       => $data['sub'],
                'classroom_id'     => $data['class'],
                'academic_year_id' => $taId,
                'semester_id'      => $semId,
                'is_active'        => true,
                'day_of_week'      => $data['day'],
                'start_time'       => $slotTimes[$data['slot']]['start'],
                'end_time'         => $slotTimes[$data['slot']]['end'],
            ]);
        }

        $this->call(ShopItemSeeder::class);
    }
}
