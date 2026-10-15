<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Claude (Anthropic) API ilə kontentin tərcüməsi.
 * Yalnız admin paneldəki manual "başqa dilə tərcümə et" düyməsi ilə çağırılır
 * (avtomatik deyil — xərc nəzarətdədir).
 *
 * Heç vaxt exception atmır — xəta olsa boş massiv qaytarır və log yazır.
 */
class ClaudeTranslator
{
    private const ENDPOINT = 'https://api.anthropic.com/v1/messages';

    private static array $langNames = [
        'en' => 'English',
        'ru' => 'Russian',
        'az' => 'Azerbaijani',
        'ka' => 'Georgian',
        'tr' => 'Turkish',
        'ar' => 'Arabic',
        'de' => 'German',
        'fr' => 'French',
        'es' => 'Spanish',
        'fa' => 'Persian',
    ];

    /** API açarı varmı? */
    public static function enabled(): bool
    {
        return filled(config('services.anthropic.api_key'));
    }

    /**
     * @param array<string,string> $fields      açar => mənbə mətn
     * @param string               $sourceLang  mənbə dil kodu (məs. 'ru')
     * @param array<int,string>    $targetLangs hədəf dil kodları (məs. ['az'])
     * @return array<string,array<string,string>>  ['az' => ['title'=>'...'], ...]
     */
    public static function translate(array $fields, string $sourceLang, array $targetLangs): array
    {
        $fields = array_filter($fields, fn ($v) => is_string($v) && trim($v) !== '');
        $targetLangs = array_values(array_filter(
            array_unique($targetLangs),
            fn ($l) => $l && $l !== $sourceLang
        ));

        if (empty($fields) || empty($targetLangs) || ! self::enabled()) {
            return [];
        }

        $sourceName = self::$langNames[$sourceLang] ?? $sourceLang;
        $targetSpec = implode(', ', array_map(
            fn ($l) => '"' . $l . '" (' . (self::$langNames[$l] ?? $l) . ')',
            $targetLangs
        ));

        $system = "You are a professional translator for a sanatorium and hotel booking website.\n"
            . "Rules:\n"
            . "- Preserve ALL HTML tags, attributes and structure exactly as in the source.\n"
            . "- Do NOT translate: brand or proper names, URLs, email addresses, phone numbers, and placeholders such as {name}, :count, %s.\n"
            . "- Keep the original meaning with a natural, fluent tone for native speakers.\n"
            . "- Output ONLY a single minified JSON object. No markdown, no code fences, no commentary.";

        $payload = [
            'source_language'  => $sourceName,
            'target_languages' => $targetLangs,
            'fields'           => $fields,
        ];

        $userText = "Translate the values in \"fields\" from {$sourceName} into each of these languages: {$targetSpec}.\n"
            . "Return a JSON object whose keys are the target language codes and whose values are objects with the SAME field keys as the input.\n"
            . "Example shape: {\"az\":{\"title\":\"...\"}}\n\n"
            . "Input:\n" . json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        try {
            $response = Http::withHeaders([
                'x-api-key'         => config('services.anthropic.api_key'),
                'anthropic-version' => '2023-06-01',
                'content-type'      => 'application/json',
            ])->timeout(90)->post(self::ENDPOINT, [
                'model'      => config('services.anthropic.model', 'claude-haiku-4-5'),
                'max_tokens' => 8000,
                'system'     => $system,
                'messages'   => [[
                    'role'    => 'user',
                    'content' => $userText,
                ]],
            ]);

            if (! $response->successful()) {
                Log::warning('ClaudeTranslator API error', [
                    'status' => $response->status(),
                    'body'   => mb_substr($response->body(), 0, 500),
                ]);
                return [];
            }

            $text    = (string) data_get($response->json(), 'content.0.text', '');
            $decoded = json_decode(self::extractJson($text), true);

            if (! is_array($decoded)) {
                Log::warning('ClaudeTranslator: cavab JSON deyil', ['text' => mb_substr($text, 0, 500)]);
                return [];
            }

            $result = [];
            foreach ($decoded as $lang => $vals) {
                if (! is_array($vals)) {
                    continue;
                }
                foreach ($vals as $k => $v) {
                    if (is_string($v)) {
                        $result[$lang][$k] = $v;
                    }
                }
            }

            return $result;
        } catch (\Throwable $e) {
            Log::warning('ClaudeTranslator exception: ' . $e->getMessage());
            return [];
        }
    }

    private static function extractJson(string $text): string
    {
        $text = trim($text);
        $text = preg_replace('/^```(?:json)?\s*|\s*```$/m', '', $text) ?? $text;

        $start = strpos($text, '{');
        $end   = strrpos($text, '}');
        if ($start !== false && $end !== false && $end >= $start) {
            return substr($text, $start, $end - $start + 1);
        }

        return $text;
    }
}
