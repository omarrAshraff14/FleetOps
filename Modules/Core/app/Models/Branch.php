<?php
// app/Models/Branch.php

namespace Modules\Core\Models;

use Modules\Core\Traits\HasTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Branch extends Model
{
    use HasUlids, HasTenant, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'address',
        'phone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relations
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
}