<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceBill extends Model
{
    use HasFactory;
    protected $fillable = [
        'society_id', 'unit_id', 'user_id', 'title', 'total_amount', 
        'penalty_amount', 'month', 'year', 'due_date', 'status', 
        'paid_amount', 'paid_at', 'payment_method', 'transaction_id', 'details'
    ];

    protected $casts = [
        'details' => 'array',
        'total_amount' => 'decimal:2',
        'penalty_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function unit() { return $this->belongsTo(Unit::class); }
    public function society() { return $this->belongsTo(Society::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function transaction() { return $this->belongsTo(Transaction::class); }

    public function getIsOverdueAttribute()
    {
        return $this->status !== 'paid' && $this->due_date && $this->due_date->isPast();
    }
}
