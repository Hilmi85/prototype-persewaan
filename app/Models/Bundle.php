<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    use HasFactory;

    protected $fillable = [
        'bundle_code',
        'bundle_name',
        'description',
        'jenis_acara',
        'kategori_adat',
        'gender',
        'butuh_rias',
        'budget_category',
        'price',
        'is_custom',
        'is_active',
    ];

    protected $casts = [
        'butuh_rias' => 'boolean',
        'is_custom' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function bundleItems()
    {
        return $this->hasMany(BundleItem::class);
    }

    public function recommendationRules()
    {
        return $this->hasMany(RecommendationRule::class);
    }

    public function orderBundles()
    {
        return $this->hasMany(OrderBundle::class);
    }
}
