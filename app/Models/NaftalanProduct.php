<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NaftalanProduct extends Model
{
    protected $table = 'naftalan_products';

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'whatsapp_message',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    // Active products only, in display order
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('created_at');
    }

    // Public image URL or placeholder
    public function getImageUrlAttribute()
    {
        if ($this->image && file_exists(public_path('storage/' . $this->image))) {
            return asset('storage/' . $this->image);
        }
        return asset('images/product-placeholder.jpg');
    }

    // WhatsApp link with pre-filled message
    public function getWhatsappLinkAttribute()
    {
        $message = $this->whatsapp_message
            ?: "Здравствуйте! Хочу заказать:\n\n🛍 {$this->name} ({$this->price} ₼)\n\nИмя:\nАдрес доставки:";
        return 'https://wa.me/994519158894?text=' . urlencode($message);
    }
}
