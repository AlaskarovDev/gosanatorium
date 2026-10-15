<?php

namespace Modules\EmailSetting\App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Modules\EmailSetting\App\Models\EmailTemplate;

class HotelBookingConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $mailBody;
    public $mailSubject;

    /**
     * Create a new message instance.
     */
    public function __construct($booking)
    {
        $this->booking = $booking;
        
        // Template ID 11 = Hotel Booking Confirmation
        $template = EmailTemplate::find(11);
        
        if (!$template) {
            $this->mailSubject = 'Rezervasiyanız qəbul edildi - ' . $booking->booking_code;
            $this->mailBody = '<p>Hörmətli ' . $booking->customer_name . ', rezervasiyanız qəbul edildi. Kod: ' . $booking->booking_code . '</p>';
            return;
        }

        // Dynamic keyword əvəzləmələri
        $replacements = [
            '{{customer_name}}'  => $booking->customer_name ?? '',
            '{{booking_code}}'   => $booking->booking_code ?? '',
            '{{hotel_name}}'     => $booking->service?->title ?? '',
            '{{room_name}}'      => $booking->room?->name ?? 'Standart',
            '{{check_in_date}}'  => $booking->check_in_date ? $booking->check_in_date->format('d.m.Y') : '',
            '{{check_out_date}}' => $booking->check_out_date ? $booking->check_out_date->format('d.m.Y') : '',
            '{{nights}}'         => $booking->nights ?? '1',
            '{{guests}}'         => $booking->adults ?? '1',
            '{{total_price}}'    => number_format((float)($booking->total ?? 0), 2) . ' ₼',
            '{{customer_email}}' => $booking->customer_email ?? '',
            '{{customer_phone}}' => $booking->customer_phone ?? '',
            '{{site_name}}'      => config('app.name', 'Hotel'),
        ];

        $this->mailSubject = str_replace(
            array_keys($replacements),
            array_values($replacements),
            $template->subject
        );

        $this->mailBody = str_replace(
            array_keys($replacements),
            array_values($replacements),
            $template->description
        );
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->mailSubject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emailsetting::mail.booking_template',
            with: [
                'mailBody' => $this->mailBody,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}