<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $table = 'society_units';
    protected $fillable = ['society_id', 'building_id', 'unit_number', 'floor', 'status', 'user_id'];

    public function building() { return $this->belongsTo(Building::class); }
    public function owner() { return $this->belongsTo(User::class, 'user_id'); }
    public function bills() { return $this->hasMany(MaintenanceBill::class); }
}
