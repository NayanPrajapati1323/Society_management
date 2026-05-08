<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceSetting extends Model
{
    protected $fillable = [
        'society_id', 'title', 'amount', 'description', 
        'due_date_day', 'grace_days', 'penalty_type', 
        'penalty_value', 'calculation_type', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'amount' => 'decimal:2',
        'penalty_value' => 'decimal:2',
    ];

    public function society()
    {
        return $this->belongsTo(Society::class);
    }
}
