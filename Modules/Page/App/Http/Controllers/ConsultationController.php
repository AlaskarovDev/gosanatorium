<?php

namespace Modules\Page\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Modules\Page\App\Models\ConsultationRequest;

class ConsultationController extends Controller
{
    /**
     * Admin: Konsultasiya sorğularının siyahısı
     */
    public function index()
    {
        $consultation_requests = ConsultationRequest::with('customPage')
            ->latest()
            ->paginate(20);

        return view('page::consultation.index', [
            'consultation_requests' => $consultation_requests,
        ]);
    }

    /**
     * Admin: Konsultasiya sorğusunun detalları
     */
    public function show($id)
    {
        $consultation_request = ConsultationRequest::with('customPage')->findOrFail($id);

        // Oxunmuş kimi işarələ
        if (!$consultation_request->is_read) {
            $consultation_request->is_read = true;
            $consultation_request->save();
        }

        return view('page::consultation.show', [
            'consultation_request' => $consultation_request,
        ]);
    }

    /**
     * Frontend: Konsultasiya formu göndərilməsi
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'message' => 'required|string|max:5000',
            'custom_page_id' => 'nullable|exists:custom_pages,id',
            'country' => 'nullable|string|max:10',
        ]);

        $consultation = new ConsultationRequest();
        $consultation->custom_page_id = $request->custom_page_id;
        $consultation->first_name = $request->first_name;
        $consultation->last_name = $request->last_name;
        $consultation->email = $request->email;
        $consultation->country = $request->country;
        $consultation->phone = $request->phone;
        $consultation->message = $request->message;
        $consultation->marketing_consent = $request->marketing_consent ? true : false;
        $consultation->save();

        // Send email notification
        try {
            $toEmail = config('app.consultation_email', env('CONSULTATION_EMAIL', env('MAIL_FROM_ADDRESS', 'info@gosanatorium.com')));

            $pageName = '';
            if ($consultation->custom_page_id) {
                $page = \Modules\Page\App\Models\CustomPage::find($consultation->custom_page_id);
                $pageName = $page ? $page->page_name : '';
            }

            $countryNames = [
                'AZ' => 'Азербайджан', 'RU' => 'Россия', 'TR' => 'Турция',
                'UA' => 'Украина', 'KZ' => 'Казахстан', 'GE' => 'Грузия',
                'BY' => 'Беларусь', 'UZ' => 'Узбекистан', 'DE' => 'Германия', 'IL' => 'Израиль',
            ];
            $countryDisplay = $countryNames[$consultation->country] ?? $consultation->country;

            Mail::send([], [], function ($mail) use ($consultation, $toEmail, $pageName, $countryDisplay) {
                $mail->to($toEmail)
                    ->subject('Новая заявка на консультацию — ' . $consultation->first_name . ' ' . $consultation->last_name)
                    ->html(
                        '<div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;">'
                        . '<div style="background:#4338ca;padding:24px 32px;border-radius:12px 12px 0 0;">'
                        . '<h2 style="color:#fff;margin:0;font-size:20px;">📋 Новая заявка на консультацию</h2>'
                        . '</div>'
                        . '<div style="background:#fff;padding:28px 32px;border:1px solid #e2e8f0;border-top:none;border-radius:0 0 12px 12px;">'
                        . ($pageName ? '<p style="color:#6e7590;font-size:13px;margin:0 0 20px;">Страница: <strong>' . $pageName . '</strong></p>' : '')
                        . '<table style="width:100%;border-collapse:collapse;">'
                        . '<tr><td style="padding:10px 0;border-bottom:1px solid #f1f5f9;color:#6e7590;width:140px;">Имя</td><td style="padding:10px 0;border-bottom:1px solid #f1f5f9;font-weight:600;color:#0b0f1a;">' . e($consultation->first_name) . ' ' . e($consultation->last_name) . '</td></tr>'
                        . '<tr><td style="padding:10px 0;border-bottom:1px solid #f1f5f9;color:#6e7590;">Email</td><td style="padding:10px 0;border-bottom:1px solid #f1f5f9;"><a href="mailto:' . e($consultation->email) . '" style="color:#4338ca;">' . e($consultation->email) . '</a></td></tr>'
                        . '<tr><td style="padding:10px 0;border-bottom:1px solid #f1f5f9;color:#6e7590;">Страна</td><td style="padding:10px 0;border-bottom:1px solid #f1f5f9;font-weight:600;">' . e($countryDisplay) . '</td></tr>'
                        . '<tr><td style="padding:10px 0;border-bottom:1px solid #f1f5f9;color:#6e7590;">Телефон</td><td style="padding:10px 0;border-bottom:1px solid #f1f5f9;font-weight:600;">' . e($consultation->phone) . '</td></tr>'
                        . '<tr><td style="padding:10px 0;color:#6e7590;vertical-align:top;">Сообщение</td><td style="padding:10px 0;color:#0b0f1a;line-height:1.6;">' . nl2br(e($consultation->message)) . '</td></tr>'
                        . '</table>'
                        . '<div style="margin-top:24px;padding:16px;background:#f8fafc;border-radius:8px;font-size:12px;color:#a0a6b8;">'
                        . '🕐 Отправлено: ' . now()->format('d.m.Y H:i') . ' | Маркетинг: ' . ($consultation->marketing_consent ? '✅ Да' : '❌ Нет')
                        . '</div>'
                        . '</div></div>'
                    );
            });
        } catch (\Exception $e) {
            \Log::error('Consultation email error: ' . $e->getMessage());
        }

        $notify_message = trans('translate.Your question has been sent successfully');
        $notify_message = array('message' => $notify_message, 'alert-type' => 'success');

        return redirect()->back()->with($notify_message);
    }

    /**
     * Admin: Konsultasiya sorğusunu sil
     */
    public function destroy($id)
    {
        $consultation_request = ConsultationRequest::findOrFail($id);
        $consultation_request->delete();

        $notify_message = trans('translate.Deleted successfully');
        $notify_message = array('message' => $notify_message, 'alert-type' => 'success');

        return redirect()->back()->with($notify_message);
    }
}