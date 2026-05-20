<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentVisitor extends Model
{
    use HasFactory;

    protected $table = 'resident_visitors';

    protected $fillable = ['resident_id', 'visitor_id', 'is_frequent', 'scheduled_at'];

    public function resident()
    {
        return $this->belongsTo(User::class, 'resident_id');
    }

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}
