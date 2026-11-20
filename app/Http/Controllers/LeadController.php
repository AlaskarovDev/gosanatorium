<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Services\LeadNotifier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;

/** Task 32: one endpoint for the site lead forms. Returns JSON (the form shows the success screen). */
class LeadController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $success = company_text(company_fact('lead_success_text'));

        // honeypot + time trap (form filled faster than 2 s)
        $started = (int) $request->input('_ts', 0);
        if (trim((string) $request->input('website', '')) !== '' || ($started > 0 && time() - $started < 2)) {
            return response()->json(['ok' => true, 'message' => $success]);
        }
        $key = 'lead:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json(['ok' => false, 'errors' => ['phone' => ['Слишком много заявок подряд. Напишите нам в WhatsApp.']]], 429);
        }

        $data = $request->validate([
            'type' => 'nullable|in:' . implode(',', array_keys(Lead::TYPES)),
            'name' => 'required|string|min:2|max:120',
            'phone_code' => 'nullable|string|max:6|regex:/^\+?\d{1,4}$/',
            'phone' => ['required', 'string', 'max:30', 'regex:/^[\d\s\-\(\)\+]{5,30}$/'],
            'contact_method' => 'nullable|in:' . implode(',', array_keys(Lead::METHODS)),
            'service_id' => 'nullable|integer',
            'dates' => 'nullable|string|max:120',
            'message' => 'nullable|string|max:3000',
            'payload' => 'nullable|array',
            'payload.*' => 'nullable|string|max:300',
            'source_url' => 'nullable|string|max:500',
            'consent' => 'accepted',
        ], [
            'name.required' => 'Укажите имя.',
            'phone.required' => 'Укажите телефон.',
            'phone.regex' => 'Проверьте номер телефона.',
            'consent.accepted' => 'Нужно согласие на обработку данных.',
        ]);
        RateLimiter::hit($key, 3600);

        if (! Schema::hasTable('leads')) {
            return response()->json(['ok' => false, 'errors' => ['phone' => ['Сейчас форма недоступна. Напишите нам в WhatsApp.']]], 503);
        }

        $phone = trim((string) $data['phone']);
        $code = trim((string) ($data['phone_code'] ?? ''));
        if ($code !== '' && ! str_starts_with($phone, '+')) {
            $phone = '+' . ltrim($code, '+') . ' ' . $phone;
        }
        $serviceTitle = null;
        if (! empty($data['service_id'])) {
            $s = \Modules\TourBooking\App\Models\Service::with('translation')->find((int) $data['service_id']);
            $serviceTitle = $s ? gs_display_title($s->translation?->title ?: $s->title) : null;
        }

        $lead = Lead::create([
            'type' => $data['type'] ?? 'contact',
            'name' => strip_tags($data['name']),
            'phone' => $phone,
            'contact_method' => $data['contact_method'] ?? null,
            'service_id' => $serviceTitle ? (int) $data['service_id'] : null,
            'service_title' => $serviceTitle,
            'dates' => isset($data['dates']) ? strip_tags($data['dates']) : null,
            'message' => isset($data['message']) ? strip_tags($data['message']) : null,
            'payload' => array_map(fn ($v) => strip_tags((string) $v), array_filter((array) ($data['payload'] ?? []), fn ($v) => $v !== null && $v !== '')),
            'status' => 'new',
            'source_url' => isset($data['source_url']) ? mb_substr(strip_tags($data['source_url']), 0, 500) : mb_substr((string) $request->headers->get('referer'), 0, 500),
            'ip' => $request->ip(),
        ]);

        LeadNotifier::send($lead);

        return response()->json(['ok' => true, 'message' => $success, 'id' => $lead->id]);
    }
}
