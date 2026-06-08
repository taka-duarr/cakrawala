<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class AIService
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
    }

    public function getStudentInsight(User $student)
    {
        if (!$this->apiKey) return "AI Insight belum tersedia (API Key kosong).";

        $prompt = "Buatkan ringkasan 2 paragraf untuk siswa bernama {$student->name} kelas {$student->class_name} yang saat ini berada di level {$student->current_level}. Ia memiliki Poin Kebaikan: {$student->points_kebaikan} dan Poin Pelanggaran: {$student->points_pelanggaran}. Berikan pujian untuk poin kebaikannya, dan teguran/saran yang membangun jika ada poin pelanggaran.";

        return $this->callOpenAI($prompt);
    }

    public function getQuestRecommendation(User $student)
    {
        if (!$this->apiKey) return "AI Rekomendasi Misi belum tersedia (API Key kosong).";

        $prompt = "Siswa {$student->name} berada di level {$student->current_level} dengan Poin Kebaikan {$student->points_kebaikan}. Sarankan 3 misi kebaikan harian/mingguan yang cocok untuk level dan usianya. Format sebagai list berbutir.";

        return $this->callOpenAI($prompt);
    }

    private function callOpenAI($prompt)
    {
        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(15)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'system', 'content' => 'Anda adalah asisten AI untuk platform pendidikan gamifikasi bernama CAKRAWALA. Anda membantu memberikan saran perkembangan siswa yang ramah, memotivasi, dan edukatif.'],
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'max_tokens' => 250,
                ]);

            if ($response->successful()) {
                return $response->json()['choices'][0]['message']['content'];
            }

            return "Error menghubungi layanan AI.";
        } catch (\Exception $e) {
            return "Koneksi AI gagal: " . $e->getMessage();
        }
    }
}
