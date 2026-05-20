<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorDocument extends Model
{
    use HasFactory;

    protected $fillable = ['visitor_id', 'document_type', 'document_number', 'document_photo_path'];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}
