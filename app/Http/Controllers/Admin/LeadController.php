<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

/** Task 32: «Заявки» — requests from all site forms. */
class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $ready = Schema::hasTable('leads');
        $type = array_key_exists((string) $request->query('type'), Lead::TYPES) ? (string) $request->query('type') : null;
        $status = array_key_exists((string) $request->query('status'), Lead::STATUSES) ? (string) $request->query('status') : null;
        $leads = $ready
            ? Lead::when($type, fn ($q) => $q->where('type', $type))->when($status, fn ($q) => $q->where('status', $status))
                ->latest()->paginate(40)->withQueryString()
            : collect();
        $newCount = $ready ? Lead::where('status', 'new')->count() : 0;
        $telegramReady = company_fact('telegram_bot_token') !== '' && company_fact('telegram_chat_id') !== '';

        return view('admin.leads', compact('leads', 'type', 'status', 'newCount', 'ready', 'telegramReady'));
    }

    public function update(Request $request, Lead $lead): RedirectResponse
    {
        $lead->update($request->validate(['status' => 'required|in:' . implode(',', array_keys(Lead::STATUSES))]));

        return back()->with(['message' => 'Сохранено', 'alert-type' => 'success']);
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $lead->delete();

        return back()->with(['message' => 'Удалено', 'alert-type' => 'success']);
    }

    /** Sends a test message to the Telegram group (to check the bot settings). */
    public function testTelegram(): RedirectResponse
    {
        $lead = new Lead(['type' => 'contact', 'name' => 'Тест', 'phone' => '+000', 'message' => 'Проверка уведомлений с сайта']);
        $token = trim(company_fact('telegram_bot_token'));
        $chat = trim(company_fact('telegram_chat_id'));
        if ($token === '' || $chat === '') {
            return back()->with(['message' => 'Заполните токен бота и ID группы в «Company facts».', 'alert-type' => 'warning']);
        }
        try {
            $r = \Illuminate\Support\Facades\Http::timeout(8)->asForm()->post('https://api.telegram.org/bot' . $token . '/sendMessage', [
                'chat_id' => $chat, 'text' => \App\Services\LeadNotifier::text($lead),
            ]);
            $ok = $r->successful() && (bool) $r->json('ok');
        } catch (\Throwable) {
            $ok = false;
        }

        return back()->with($ok
            ? ['message' => 'Тестовое сообщение отправлено в Telegram', 'alert-type' => 'success']
            : ['message' => 'Telegram не принял сообщение: проверьте токен, ID группы и что бот добавлен в группу.', 'alert-type' => 'error']);
    }
}
