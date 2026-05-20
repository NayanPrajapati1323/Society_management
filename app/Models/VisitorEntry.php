<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_id', 'society_id', 'society_unit_id', 'guard_id', 
        'visitor_type_id', 'resident_id', 'purpose', 'entry_time', 
        'exit_time', 'status', 'otp', 'qr_code', 'notes'
    ];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function society()
    {
        return $this->belongsTo(Society::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'society_unit_id');
    }

    public function securityGuard()
    {
        return $this->belongsTo(Guard::class, 'guard_id');
    }

    public function visitorType()
    {
        return $this->belongsTo(VisitorType::class);
    }

    public function resident()
    {
        return $this->belongsTo(User::class, 'resident_id');
    }
}
