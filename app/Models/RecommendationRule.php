<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecommendationRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'rule_code',
        'rule_name',
        'bundle_id',
        'jenis_acara',
        'kategori_adat',
        'gender',
        'butuh_rias',
        'budget',
        'size',
        'priority',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'butuh_rias' => 'boolean',
        'priority' => 'integer',
        'is_active' => 'boolean',
    ];

    public function bundle()
    {
        return $this->belongsTo(Bundle::class);
    }
}
