<?php
// app/Models/Tenant.php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Tenant extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'name',
        'domain',
        'logo',
        'settings',
        'is_active',
    ];

    protected $casts = [
        'settings'  => 'array',
        'is_active' => 'boolean',
    ];

    // Relations
    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function notificationTemplates()
    {
        return $this->hasMany(NotificationTemplate::class);
    }
}