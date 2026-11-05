<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CurrencyController extends Controller
{
    public const CODES = ['AZN', 'RUB', 'USD', 'EUR', 'KZT', 'GEL', 'UZS', 'BYN', 'KGS', 'TJS', 'TRY', 'AED', 'ILS', 'CNY'];

    public function rates()
    {
        $rates = Cache::get('exchange_rates');

        if (empty($rates)) {
            $rates = $this->fetch();
            if (!empty($rates)) {
                Cache::put('exchange_rates', $rates, 3600);
                Cache::forever('exchange_rates_last_good', $rates);
            } else {
                // API unavailable: fall back to last known rates, retry in 5 minutes
                $rates = Cache::get('exchange_rates_last_good', []);
                if (!empty($rates)) {
                    Cache::put('exchange_rates', $rates, 300);
                }
            }
        }

        return response()->json($rates ?: ['USD' => 1]);
    }

    private function fetch(): array
    {
        foreach (['https://open.er-api.com/v6/latest/USD', 'https://api.exchangerate-api.com/v4/latest/USD'] as $url) {
            try {
                $res = Http::timeout(6)->get($url);
                $all = $res->json()['rates'] ?? [];
                $rates = [];
                foreach (self::CODES as $code) {
                    if (isset($all[$code]) && is_numeric($all[$code])) {
                        $rates[$code] = (float) $all[$code];
                    }
                }
                if (count($rates) > 1) {
                    return $rates;
                }
            } catch (\Throwable $e) {
                Log::warning('Exchange rates fetch failed: ' . $e->getMessage());
            }
        }
        return [];
    }
}
