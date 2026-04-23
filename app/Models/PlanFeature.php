<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanFeature extends Model
{
    protected $fillable = ['plan_id', 'feature_text', 'is_included', 'sort_order'];

    protected $casts = [
        'is_included' => 'boolean',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
