<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Akcii extends Model
{
    protected $table = 'akcii';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'image',
        'discount_percent',
        'valid_until',
        'sanatorium_name',
        'is_active',
        'sort_order',
        'lang',
    ];

    protected $casts = [
        'valid_until' => 'date',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('created_at', 'desc');
    }
}
