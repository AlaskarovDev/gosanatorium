<?php

namespace Modules\EmailSetting\App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Modules\EmailSetting\App\Models\EmailTemplate;

class TransferBookingConfirmationMail extends Mailable
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
        
        // Template ID 12 = Transfer Booking Confirmation
        $template = EmailTemplate::find(12);
        
        if (!$template) {
            $this->mailSubject = 'Transfer təsdiqləndi - ' . $booking->booking_code;
            $this->mailBody = '<p>Hörmətli ' . $booking->guest_name . ', transfer rezervasiyanız qəbul edildi. Kod: ' . $booking->booking_code . '</p>';
            return;
        }

        // Dynamic keyword əvəzləmələri
        $replacements = [
            '{{guest_name}}'      => $booking->guest_name ?? '',
            '{{booking_code}}'    => $booking->booking_code ?? '',
            '{{route_name}}'      => $booking->route 
                                        ? $booking->route->from_location . ' → ' . $booking->route->to_location 
                                        : '',
            '{{vehicle_name}}'    => $booking->vehicle?->name ?? '',
            '{{transfer_date}}'   => $booking->transfer_date ? $booking->transfer_date->format('d.m.Y') : '',
            '{{transfer_time}}'   => $booking->transfer_time ?? 'Təyin edilməyib',
            '{{passengers}}'      => $booking->passengers ?? '',
            '{{pickup_address}}'  => $booking->pickup_address ?? 'Təyin edilməyib',
            '{{dropoff_address}}' => $booking->dropoff_address ?? 'Təyin edilməyib',
            '{{flight_number}}'   => $booking->flight_number ?? 'Təyin edilməyib',
            '{{total_price}}'     => number_format((float)($booking->total_price ?? 0), 2) . ' ₼',
            '{{guest_email}}'     => $booking->guest_email ?? '',
            '{{guest_phone}}'     => $booking->guest_phone ?? '',
            '{{site_name}}'       => config('app.name', 'Transfer Service'),
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