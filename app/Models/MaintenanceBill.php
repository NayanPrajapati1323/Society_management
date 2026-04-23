<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceBill extends Model
{
    use HasFactory;
    protected $fillable = ['society_id', 'unit_id', 'total_amount', 'month', 'year', 'status', 'details'];
    protected $casts = ['details' => 'array'];

    public function unit() { return $this->belongsTo(Unit::class); }
    public function society() { return $this->belongsTo(Society::class); }
}
