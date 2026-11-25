<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class HolidayService
{
    public function getNationalHolidays(?int $year = null): array
    {
        $year = $year ?? (int) date('Y');

        return Cache::remember("holidays.id.{$year}", now()->addDay(), function () use ($year) {
            $holidays = [];

            try {
                $response = Http::timeout(3)->get('https://api-harilibur.vercel.app/api', [
                    'year' => $year,
                ]);

                if ($response->successful() && is_array($response->json())) {
                    foreach ($response->json() as $entry) {
                        if (!empty($entry['holiday_date']) && !empty($entry['holiday_name'])) {
                            $timestamp = strtotime((string) $entry['holiday_date']);
                            if ($timestamp !== false) {
                                $holidays[date('Y-m-d', $timestamp)] = (string) $entry['holiday_name'];
                            }
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Abaikan gangguan API eksternal agar cetak laporan tetap berjalan
            }

            return $holidays;
        });
    }
}
