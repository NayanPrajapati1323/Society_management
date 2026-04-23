<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Society extends Model
{
    protected $fillable = [
        'name', 'type', 'address', 'city', 'state', 'country', 'pincode',
        'contact_email', 'contact_phone', 'plan_id', 'is_active', 'admin_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function buildings()
    {
        return $this->hasMany(Building::class);
    }
}
