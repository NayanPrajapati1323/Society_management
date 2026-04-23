<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;
    protected $table = 'society_buildings';
    protected $fillable = ['society_id', 'name', 'floors'];

    public function society() { return $this->belongsTo(Society::class); }
    public function units() { return $this->hasMany(Unit::class, 'building_id'); }
}
