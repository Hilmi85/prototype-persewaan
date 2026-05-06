<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cat_name',
        'description',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
