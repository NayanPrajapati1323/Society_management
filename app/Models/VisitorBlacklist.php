<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorBlacklist extends Model
{
    use HasFactory;

    protected $fillable = ['society_id', 'visitor_id', 'vehicle_number', 'reason', 'blacklisted_by'];

    public function society()
    {
        return $this->belongsTo(Society::class);
    }

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function blacklistedBy()
    {
        return $this->belongsTo(User::class, 'blacklisted_by');
    }
}
