<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassbookEntry extends Model
{
    use HasFactory;
    protected $fillable = ['society_id', 'type', 'amount', 'category', 'description', 'entry_date'];

    public function society() { return $this->belongsTo(Society::class); }
}
