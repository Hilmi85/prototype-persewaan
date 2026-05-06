<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContactSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_user_id',
        'contact_name',
        'whatsapp_number',
        'whatsapp_url',
        'message_template',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }
}
