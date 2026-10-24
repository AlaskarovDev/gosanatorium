<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TransferInquiryController extends Controller
{
    private const ROUTES = [
        'airport_naftalan' => 'Аэропорт Гейдар Алиев → Нафталан (49 ₼)',
        'airport_lankaran' => 'Аэропорт Гейдар Алиев → Ленкорань (49 ₼)',
        'airport_baku' => 'Аэропорт Гейдар Алиев → Баку (15 ₼)',
        'ganja_naftalan' => 'Аэропорт Гянджа → Нафталан (по запросу)',
    ];

    public function send(Request $request)
    {
        $data = $request->validate([
            'route' => 'required|in:' . implode(',', array_keys(self::ROUTES)),
            'date' => 'required|date|after_or_equal:today',
            'transfer_time' => 'nullable|date_format:H:i',
            'flight_number' => 'nullable|string|max:50',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        $body = "НОВАЯ ЗАЯВКА НА ТРАНСФЕР\n\n";
        $body .= 'Маршрут: ' . self::ROUTES[$data['route']] . "\n";
        $body .= 'Дата: ' . $data['date'] . "\n";
        $body .= 'Время прилёта: ' . ($data['transfer_time'] ?? '-') . "\n";
        $body .= 'Номер рейса: ' . ($data['flight_number'] ?? '-') . "\n";
        $body .= 'Имя: ' . $data['full_name'] . "\n";
        $body .= 'Телефон: ' . $data['phone'] . "\n";
        $body .= 'Email: ' . ($data['email'] ?? '-') . "\n";
        $body .= 'Пожелания: ' . ($data['special_requests'] ?? '-') . "\n";

        try {
            $toEmail = config('mail.from.address');
            Mail::raw($body, function ($mail) use ($toEmail, $data) {
                $mail->to($toEmail)
                    ->subject('Заявка на трансфер — ' . $data['full_name'])
                    ->replyTo($data['email'] ?? $toEmail, $data['full_name']);
            });
        } catch (\Exception $e) {
            Log::error('Transfer inquiry error: ' . $e->getMessage());
            return back()->withInput()->with('transfer_error', 'Не удалось отправить заявку. Попробуйте ещё раз или позвоните нам.');
        }

        return redirect(url()->previous() . '#transferForm')
            ->with('transfer_success', 'Спасибо! Ваша заявка принята, мы свяжемся с вами в течение 15 минут.');
    }
}
