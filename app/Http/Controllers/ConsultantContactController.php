<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ConsultantContactController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:255',
            'message' => 'nullable|string|max:2000',
        ]);

        try {
            $toEmail = config('mail.from.address');

            Mail::raw($this->buildEmailBody($request), function ($mail) use ($toEmail, $request) {
                $mail->to($toEmail)
                     ->subject('Новая заявка — ' . $request->name)
                     ->replyTo($request->email ?: $toEmail, $request->name);
            });

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Consultant form error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Ошибка отправки'], 500);
        }
    }

    private function buildEmailBody(Request $request): string
    {
        $body = "НОВАЯ ЗАЯВКА НА КОНСУЛЬТАЦИЮ\n\n";
        $body .= "Имя: " . $request->name . "\n";
        $body .= "Телефон: " . $request->phone . "\n";
        if ($request->email) $body .= "Email: " . $request->email . "\n";
        if ($request->service_name) $body .= "Санаторий: " . $request->service_name . "\n";
        if ($request->doctor_name) $body .= "Консультант: " . $request->doctor_name . "\n";
        if ($request->message) $body .= "\nСообщение:\n" . $request->message . "\n";
        $body .= "\nДата: " . now()->format('d.m.Y H:i') . "\n";
        return $body;
    }
}