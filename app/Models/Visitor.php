<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'mobile', 'vehicle_number', 'photo_path', 'status'];

    public function entries()
    {
        return $this->hasMany(VisitorEntry::class);
    }

    public function documents()
    {
        return $this->hasMany(VisitorDocument::class);
    }

    public function blacklists()
    {
        return $this->hasMany(VisitorBlacklist::class);
    }

    public function residents()
    {
        return $this->belongsToMany(User::class, 'resident_visitors', 'visitor_id', 'resident_id')
                    ->withPivot('is_frequent', 'scheduled_at')
                    ->withTimestamps();
    }
}
