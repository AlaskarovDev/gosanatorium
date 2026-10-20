<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultant extends Model
{
    protected $fillable = ['name', 'role', 'phone', 'email', 'photo', 'whatsapp', 'button_text', 'button_url', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];
}
