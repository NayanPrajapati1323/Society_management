<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    protected $fillable = [
        'society_id', 'user_id', 'transaction_no', 'type', 
        'amount', 'source', 'reference_id', 'payment_mode', 
        'description', 'status'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($transaction) {
            if (!$transaction->transaction_no) {
                $transaction->transaction_no = 'TRX-' . strtoupper(Str::random(10));
            }
        });
    }

    public function society()
    {
        return $this->belongsTo(Society::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
