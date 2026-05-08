<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassbookEntry extends Model
{
    use HasFactory;
    protected $fillable = [
        'society_id', 'user_id', 'transaction_id', 'passbook_type', 
        'entry_type', 'amount', 'balance_after', 'category', 
        'description', 'entry_date'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'entry_date' => 'date',
    ];

    public function society() { return $this->belongsTo(Society::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function transaction() { return $this->belongsTo(Transaction::class); }
}
