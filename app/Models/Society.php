<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Society extends Model
{
    protected $fillable = [
        'name', 'type', 'address', 'city', 'state', 'country', 'pincode',
        'contact_email', 'contact_phone', 'plan_id', 'has_website', 'plan_duration', 'plan_price', 'plan_expiry_date', 'is_active', 'admin_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'has_website' => 'boolean',
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

    public function maintenanceSettings()
    {
        return $this->hasMany(MaintenanceSetting::class);
    }

    public function expenses()
    {
        return $this->hasMany(SocietyExpense::class);
    }

    public function getIsPlanExpiredAttribute()
    {
        if (!$this->plan_expiry_date) return false;
        return \Carbon\Carbon::parse($this->plan_expiry_date)->isPast();
    }
}
