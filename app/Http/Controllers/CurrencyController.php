<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Exchange rates for the currency selector (Task 33).
 *
 * Source: Central Bank of Azerbaijan daily XML (https://www.cbar.az/currencies/DD.MM.YYYY.xml),
 * refreshed once a day and stored with its date. Fallback: open.er-api.com / exchangerate-api.com.
 * Response: rates per 1 USD ({"USD":1,"AZN":1.7,...}) plus "_date" (Y-m-d of the rates).
 */
class CurrencyController extends Controller
{
    public const CODES = ['USD', 'AZN', 'RUB', 'KZT', 'UZS', 'EUR', 'GEL', 'TRY', 'BYN', 'KGS', 'TJS', 'AED', 'ILS', 'CNY'];

    private const CACHE_KEY = 'exchange_rates_v2';
    private const LAST_GOOD_KEY = 'exchange_rates_v2_last_good';

    public function rates()
    {
        return response()->json(self::current())->header('Cache-Control', 'public, max-age=1800');
    }

    /** Rates per 1 USD + "_date". Cached until the next refresh; last good copy kept forever. */
    public static function current(): array
    {
        $rates = Cache::get(self::CACHE_KEY);
        if (!empty($rates)) {
            return $rates;
        }

        $rates = self::fetchCbar() ?: self::fetchFallback();
        if (!empty($rates)) {
            Cache::put(self::CACHE_KEY, $rates, 6 * 3600);
            Cache::forever(self::LAST_GOOD_KEY, $rates);
            return $rates;
        }

        // Sources unavailable: last known rates, retry in 10 minutes.
        $rates = Cache::get(self::LAST_GOOD_KEY, []);
        if (!empty($rates)) {
            Cache::put(self::CACHE_KEY, $rates, 600);
        }

        return $rates ?: ['USD' => 1, '_date' => null];
    }

    private static function fetchCbar(): array
    {
        $day = Carbon::now('Asia/Baku');
        for ($i = 0; $i < 5; $i++, $day = $day->copy()->subDay()) {
            try {
                $res = Http::timeout(8)->get('https://www.cbar.az/currencies/' . $day->format('d.m.Y') . '.xml');
                if (!$res->ok() || !str_contains($res->body(), '<ValCurs')) {
                    continue;
                }
                $xml = @simplexml_load_string($res->body());
                if (!$xml) {
                    continue;
                }
                $azn = ['AZN' => 1.0]; // AZN for 1 unit of currency
                foreach ($xml->xpath('//Valute') as $v) {
                    $code = (string) $v['Code'];
                    $nominal = (float) preg_replace('/[^0-9.]/', '', (string) $v->Nominal) ?: 1.0;
                    $value = (float) $v->Value;
                    if ($value > 0) {
                        $azn[$code] = $value / $nominal;
                    }
                }
                if (empty($azn['USD'])) {
                    continue;
                }
                $rates = [];
                foreach (self::CODES as $code) {
                    if (!empty($azn[$code])) {
                        $rates[$code] = round($azn['USD'] / $azn[$code], 6);
                    }
                }
                $rates['USD'] = 1;
                $date = (string) ($xml['Date'] ?? '');
                $rates['_date'] = preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $date)
                    ? Carbon::createFromFormat('d.m.Y', $date)->format('Y-m-d')
                    : $day->format('Y-m-d');
                $rates['_source'] = 'cbar';

                return $rates;
            } catch (\Throwable $e) {
                Log::warning('CBAR rates fetch failed: ' . $e->getMessage());
            }
        }

        return [];
    }

    private static function fetchFallback(): array
    {
        foreach (['https://open.er-api.com/v6/latest/USD', 'https://api.exchangerate-api.com/v4/latest/USD'] as $url) {
            try {
                $all = Http::timeout(6)->get($url)->json()['rates'] ?? [];
                $rates = [];
                foreach (self::CODES as $code) {
                    if (isset($all[$code]) && is_numeric($all[$code])) {
                        $rates[$code] = (float) $all[$code];
                    }
                }
                if (count($rates) > 1) {
                    $rates['_date'] = Carbon::now('Asia/Baku')->format('Y-m-d');
                    $rates['_source'] = 'market';
                    return $rates;
                }
            } catch (\Throwable $e) {
                Log::warning('Exchange rates fetch failed: ' . $e->getMessage());
            }
        }

        return [];
    }
}
