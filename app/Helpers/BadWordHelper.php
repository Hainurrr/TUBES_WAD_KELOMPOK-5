<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BadWordHelper
{
    public static function check(string $text): bool
    {
        $normalized = mb_strtolower($text, 'UTF-8');
        $cleanText = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $normalized) ?? '';
        $tokens = preg_split('/\s+/u', trim($cleanText), -1, PREG_SPLIT_NO_EMPTY);

        if (empty($tokens)) {
            return false;
        }

        $badWordSet = array_flip(self::getWords());

        foreach ($tokens as $token) {
            if (isset($badWordSet[$token])) {
                return true;
            }
        }

        return false;
    }

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
            'kasar', 'jelek', 'bodoh',
        ];
    }

    public static function checkOnline(string $text): bool
    {
        if (self::check($text)) {
            return true;
        }

        try {
            $response = Http::timeout(3)
                ->get('https://www.purgomalum.com/service/containsprofanity', [
                    'text' => $text,
                ]);

            if ($response->successful()) {
                return trim($response->body()) === 'true';
            }
        } catch (\Throwable $e) {
            Log::warning('Profanity filter service unavailable: ' . $e->getMessage());
        }

        return false;
    }
}
