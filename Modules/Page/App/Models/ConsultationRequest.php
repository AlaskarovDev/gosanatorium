<?php

namespace Modules\Page\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConsultationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'custom_page_id',
        'first_name',
        'last_name',
        'email',
        'country',
        'phone',
        'message',
        'marketing_consent',
        'is_read',
    ];

    protected $casts = [
        'marketing_consent' => 'boolean',
        'is_read' => 'boolean',
    ];

    public function customPage()
    {
        return $this->belongsTo(CustomPage::class);
    }
}