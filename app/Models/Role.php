<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'role_name',
        'description',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
