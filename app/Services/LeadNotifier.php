<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Lead;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Task 32: notify the team about a new lead — e-mail (company e-mail from Company facts) and
 * Telegram group via bot (token + chat id in Admin → Company facts; skipped while empty).
 */
final class LeadNotifier
{
    public static function text(Lead $lead): string
    {
        $lines = ['🆕 Заявка: ' . (Lead::TYPES[$lead->type] ?? $lead->type), 'Имя: ' . $lead->name, 'Телефон: ' . $lead->phone];
        if ($lead->contact_method) {
            $lines[] = 'Связь: ' . (Lead::METHODS[$lead->contact_method] ?? $lead->contact_method);
        }
        if ($lead->service_title) {
            $lines[] = 'Санаторий: ' . $lead->service_title;
        }
        if ($lead->dates) {
            $lines[] = 'Даты: ' . $lead->dates;
        }
        foreach ((array) $lead->payload as $k => $v) {
            if (is_scalar($v) && (string) $v !== '') {
                $lines[] = $k . ': ' . $v;
            }
        }
        if ($lead->message) {
            $lines[] = 'Сообщение: ' . $lead->message;
        }
        if ($lead->source_url) {
            $lines[] = 'Страница: ' . $lead->source_url;
        }
        $lines[] = 'Админ: ' . url('/admin/leads');

        return implode("\n", $lines);
    }

    public static function send(Lead $lead): void
    {
        $text = self::text($lead);

        // Telegram
        $token = trim(company_fact('telegram_bot_token'));
        $chat = trim(company_fact('telegram_chat_id'));
        if ($token !== '' && $chat !== '') {
            try {
                $r = Http::timeout(8)->asForm()->post('https://api.telegram.org/bot' . $token . '/sendMessage', [
                    'chat_id' => $chat,
                    'text' => mb_substr($text, 0, 4000),
                    'disable_web_page_preview' => 'true',
                ]);
                $lead->telegram_sent = $r->successful() && (bool) $r->json('ok');
                if (! $lead->telegram_sent) {
                    Log::warning('Lead Telegram failed: ' . $r->status());
                }
            } catch (\Throwable $e) {
                Log::warning('Lead Telegram error: ' . $e->getMessage());
            }
        }

        // E-mail
        $to = trim(company_fact('leads_email')) ?: trim(company_fact('email'));
        if ($to !== '' && filter_var($to, FILTER_VALIDATE_EMAIL)) {
            try {
                if (class_exists(\App\Helper\EmailHelper::class)) {
                    \App\Helper\EmailHelper::mail_setup();
                }
                Mail::raw($text, function ($m) use ($to, $lead) {
                    $m->to($to)->subject('Заявка с сайта: ' . (Lead::TYPES[$lead->type] ?? $lead->type) . ' — ' . $lead->name);
                });
                $lead->email_sent = true;
            } catch (\Throwable $e) {
                Log::warning('Lead e-mail error: ' . $e->getMessage());
            }
        }

        $lead->save();
    }
}
