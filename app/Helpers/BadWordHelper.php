<?php

namespace App\Helpers;

class BadWordHelper
{
    /**
     * Mengecek apakah teks mengandung kata kasar.
     * Mengembalikan true jika ditemukan kata kasar.
     */
    public static function check(string $text): bool
    {
        $words = self::getWords();
        $text = strtolower($text);
        
        // Pengecekan batas kata sederhana
        foreach ($words as $bad) {
            // Membersihkan simbol untuk pengecekan
            $cleanText = preg_replace('/[^\p{L}\p{N}\s]/u', '', $text);
            $tokens = explode(' ', $cleanText);

            if (in_array($bad, $tokens)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Daftar kata-kata kasar yang dilarang.
     */
    private static function getWords(): array
    {
        return [
            'anjing', 'babi', 'bangsat', 'goblok', 'tolol', 'banci', 'bencong', 
            'bejad', 'bejat', 'bloon', 'bacot', 'bedebah', 'begundal', 'brengsek',
            'buduk', 'bukake', 'celeng', 'cibai', 'cibay', 'cocot', 'cok', 'cuk',
            'dancok', 'dancuk', 'dodol', 'entot', 'eweh', 'gemo', 'gigolo', 'gila',
            'henceut', 'homo', 'idiot', 'itil', 'jamet', 'jancuk', 'jembut', 'jijik',
            'kafir', 'kampang', 'kampret', 'kampungan', 'kimak', 'kontol', 'kunti',
            'lacur', 'lesbi', 'lonte', 'maho', 'matamu', 'memek', 'monyet', 'ndasmu',
            'ngaceng', 'ngewe', 'noob', 'ngentot', 'pantek', 'pantat', 'pecun',
            'peju', 'pepek', 'perek', 'pilat', 'puki', 'pukimak', 'raimu', 'sarap',
            'setan', 'sial', 'sialan', 'sinting', 'siti', 'sodok', 'sontoloyo',
            'tai', 'taik', 'tempek', 'tetek', 'titit', 'toket', 'ublag', 'udik',
            'fuck', 'shit', 'bitch', 'asshole', 'dick', 'pussy', 'whore', 'slut', 'bastard',
            'kasar', 'jelek', 'bodoh'
        ];
    }

    /**
     * Mengecek menggunakan API eksternal untuk kata kasar berbahasa Inggris.
     * Menggunakan API Purgomalum (tanpa autentikasi).
     */
    public static function checkOnline(string $text): bool
    {
        try {
            // Jika pengecekan lokal sudah menemukan, tidak perlu memanggil API
            if (self::check($text)) {
                return true;
            }

            // Panggil API Purgomalum
            $response = \Illuminate\Support\Facades\Http::timeout(3)
                        ->get('https://www.purgomalum.com/service/containsprofanity', [
                            'text' => $text
                        ]);

            if ($response->successful()) {
                // API mengembalikan string "true" atau "false"
                return $response->body() === 'true';
            }
        } catch (\Exception $e) {
            // Biarkan lewat jika API down, atau catat error
            \Illuminate\Support\Facades\Log::warning('BadWord API Error: ' . $e->getMessage());
        }

        return false;
    }
}
