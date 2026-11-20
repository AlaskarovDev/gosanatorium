<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Task 32: a request from any site form («Заявки» in admin). */
class Lead extends Model
{
    public const TYPES = [
        'contact' => 'Контакты',
        'consult' => 'Консультация',
        'transfer' => 'Трансфер',
        'tour' => 'Тур с перелётом',
        'quiz' => 'Подбор (квиз)',
        'calc' => 'Калькулятор',
    ];

    public const STATUSES = ['new' => 'Новая', 'in_work' => 'В работе', 'done' => 'Закрыта', 'spam' => 'Спам'];

    public const METHODS = ['whatsapp' => 'WhatsApp', 'telegram' => 'Telegram', 'call' => 'Звонок'];

    protected $fillable = [
        'type', 'name', 'phone', 'contact_method', 'service_id', 'service_title', 'dates', 'message',
        'payload', 'status', 'source_url', 'country', 'ip', 'telegram_sent', 'email_sent',
    ];

    protected $casts = ['payload' => 'array', 'telegram_sent' => 'boolean', 'email_sent' => 'boolean'];
}
