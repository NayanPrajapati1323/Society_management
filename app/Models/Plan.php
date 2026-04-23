<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name', 'description', 'features_summary',
        'max_units', 'max_users', 'is_active', 'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function features()
    {
        return $this->hasMany(PlanFeature::class)->orderBy('sort_order');
    }

    public function societies()
    {
        return $this->hasMany(Society::class);
    }
}
