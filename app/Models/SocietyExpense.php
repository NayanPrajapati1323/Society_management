<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocietyExpense extends Model
{
    protected $fillable = [
        'society_id', 'title', 'amount', 'category', 
        'description', 'expense_date', 'attachment_path', 'created_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
    ];

    public function society()
    {
        return $this->belongsTo(Society::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
