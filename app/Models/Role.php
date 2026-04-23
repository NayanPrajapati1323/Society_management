<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'display_name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Constants
    const SUPER_ADMIN = 1;
    const SOCIETY_ADMIN = 2;
    const USER = 3;
}
