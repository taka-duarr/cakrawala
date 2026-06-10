<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralInfoController extends Controller
{
    public function leaderboard()
    {
        // Fetch actual students
        $actualStudents = User::with('classroom')
            ->where('role_id', 5)
            ->orderByDesc('points_kebaikan')
            ->get();

        // Standard mock competitors to populate the podium and list nicely
        $mockCompetitors = collect([
            (object)['name' => 'Budi Santoso', 'class_name' => 'X IPA 1', 'current_level' => 'Teladan', 'points_kebaikan' => 1250, 'points_pelanggaran' => 0, 'email' => 'budi@sekolah.sch.id'],
            (object)['name' => 'Siti Aminah', 'class_name' => 'X IPS 2', 'current_level' => 'Unggul', 'points_kebaikan' => 840, 'points_pelanggaran' => 5, 'email' => 'siti@sekolah.sch.id'],
            (object)['name' => 'Dewi Lestari', 'class_name' => 'XI IPA 3', 'current_level' => 'Unggul', 'points_kebaikan' => 790, 'points_pelanggaran' => 10, 'email' => 'dewi@sekolah.sch.id'],
            (object)['name' => 'Rian Hidayat', 'class_name' => 'X IPA 1', 'current_level' => 'Berkembang', 'points_kebaikan' => 450, 'points_pelanggaran' => 0, 'email' => 'rian@sekolah.sch.id'],
        ]);

        // Merge real student data with competitors and sort
        $leaderboardData = collect();
        foreach ($actualStudents as $student) {
            $leaderboardData->push((object)[
                'name' => $student->name,
                'class_name' => $student->classroom->name ?? 'Belum ada kelas',
                'current_level' => $student->current_level ?? 'Pemula',
                'points_kebaikan' => $student->points_kebaikan,
                'points_pelanggaran' => $student->points_pelanggaran,
                'email' => $student->email
            ]);
        }

        // Add mock if needed to make it look full
        foreach ($mockCompetitors as $mock) {
            if (!$leaderboardData->contains('name', $mock->name)) {
                $leaderboardData->push($mock);
            }
        }

        $leaderboardData = $leaderboardData->sortByDesc('points_kebaikan')->values();

        // Class rankings
        $classRankings = \App\Models\Classroom::leftJoin('users', function($join) {
                $join->on('classrooms.id', '=', 'users.classroom_id')
                     ->where('users.role_id', '=', 5);
            })
            ->selectRaw('classrooms.name as class_name, coalesce(sum(users.points_kebaikan), 0) as total_kebaikan, coalesce(sum(users.points_pelanggaran), 0) as total_pelanggaran')
            ->groupBy('classrooms.id', 'classrooms.name')
            ->orderByDesc('total_kebaikan')
            ->get();

        if ($classRankings->isEmpty()) {
            $classRankings = collect([
                (object)['class_name' => 'X IPA 1', 'total_kebaikan' => 1850, 'total_pelanggaran' => 0],
                (object)['class_name' => 'X IPS 2', 'total_kebaikan' => 840, 'total_pelanggaran' => 5],
                (object)['class_name' => 'XI IPA 3', 'total_kebaikan' => 790, 'total_pelanggaran' => 10],
            ]);
        }

        return view('leaderboard', compact('leaderboardData', 'classRankings'));
    }

    public function events(Request $request)
    {
        if ($request->has('register')) {
            $eventTitle = $request->input('event_title');
            return back()->with('success', 'Berhasil mendaftar ke Event: "' . $eventTitle . '"! Detail tiket pendaftaran telah dikirim ke email Anda.');
        }

        // Seeded Event list
        $events = [
            [
                'title' => 'Pekan Karakter Cakrawala 2026',
                'description' => 'Ajang tahunan unjuk aksi kebaikan dan kreativitas karakter antarkelas. Dapatkan quest khusus poin besar!',
                'date' => '15 - 20 Juni 2026',
                'location' => 'Aula & Lapangan Utama Sekolah',
                'points_bonus' => 150,
                'category' => 'karakter'
            ],
            [
                'title' => 'Cakrawala Clean & Green',
                'description' => 'Aksi gotong royong akbar membersihkan lingkungan sekitar sekolah dan penanaman pohon bersama.',
                'date' => '23 Juni 2026',
                'location' => 'Area Sekitar Sekolah',
                'points_bonus' => 80,
                'category' => 'sosial'
            ],
            [
                'title' => 'Seminar Literasi Digital',
                'description' => 'Membangun karakter bijak ber media sosial dan menangkal hoaks demi masa depan digital yang sehat.',
                'date' => '30 Juni 2026',
                'location' => 'Laboratorium Multimedia',
                'points_bonus' => 50,
                'category' => 'akademik'
            ],
        ];

        return view('events', compact('events'));
    }

    public function announcements()
    {
        $announcements = [
            [
                'title' => 'Sistem Gamifikasi Poin Karakter Resmi Dimulai',
                'body' => 'Selamat datang di platform CAKRAWALA. Mulai hari ini, setiap kebaikan dan keaktifan Anda di sekolah akan dihargai dengan Poin Karakter yang dapat ditukarkan dengan berbagai hadiah menarik di Toko Hadiah.',
                'author' => 'Super Admin',
                'date' => '1 hari yang lalu',
                'is_pinned' => true
            ],
            [
                'title' => 'Pemberian Penghargaan Siswa Teladan Bulan Mei',
                'body' => 'Selamat kepada para siswa yang telah meraih peringkat teratas kebaikan pada bulan lalu. Lencana dan sertifikat resmi telah ditambahkan ke profil Anda masing-masing.',
                'author' => 'Bapak Budi (Wakasek Kesiswaan)',
                'date' => '3 hari yang lalu',
                'is_pinned' => false
            ],
            [
                'title' => 'Peringatan Penurunan Poin Pelanggaran',
                'body' => 'Diberitahukan kepada seluruh siswa bahwa pelanggaran ketertiban (seperti terlambat atau seragam tidak lengkap) akan memotong poin perkembangan karakter Anda. Mari bersama-sama menjaga disiplin sekolah.',
                'author' => 'Tim Ketertiban Sekolah',
                'date' => '1 minggu yang lalu',
                'is_pinned' => false
            ],
        ];

        return view('announcements', compact('announcements'));
    }

    public function help()
    {
        return view('help');
    }

    public function notifications()
    {
        $notifications = [
            [
                'title' => 'Misi Harian Tersedia!',
                'body' => 'Misi "Hadir Tepat Waktu" siap diambil hari ini.',
                'time' => '1 jam yang lalu',
                'icon' => 'sparkles',
                'category' => 'quest',
                'is_unread' => true
            ],
            [
                'title' => 'Poin Karakter Disetujui',
                'body' => 'Poin misi "Membaca Buku" (+10 Pts) telah ditambahkan.',
                'time' => '3 jam yang lalu',
                'icon' => 'star',
                'category' => 'point',
                'is_unread' => true
            ],
            [
                'title' => 'Lencana Baru Diraih!',
                'body' => 'Selamat! Anda memperoleh lencana "Pemula Aktif".',
                'time' => 'Kemarin',
                'icon' => 'trophy',
                'category' => 'achievement',
                'is_unread' => true
            ],
            [
                'title' => 'Klaim Hadiah Disetujui',
                'body' => 'Klaim hadiah "Voucher Kantin Sehat" Anda telah disetujui oleh Wali Kelas.',
                'time' => '2 hari yang lalu',
                'icon' => 'gift',
                'category' => 'reward',
                'is_unread' => false
            ],
            [
                'title' => 'Peringatan AI Early Warning',
                'body' => 'Ada analisis baru perkembangan keaktifan kelas oleh sistem AI.',
                'time' => '3 hari yang lalu',
                'icon' => 'warning',
                'category' => 'system',
                'is_unread' => false
            ]
        ];

        return view('notifications', compact('notifications'));
    }
}
