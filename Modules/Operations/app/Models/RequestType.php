<?php
// app/Models/RequestType.php

namespace Modules\Operations\Models;

use Modules\Core\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class RequestType extends Model
{
    use HasUlids, HasTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'requires_customer',
        'requires_return',
        'custom_fields',
        'is_active',
    ];

    protected $casts = [
        'requires_customer' => 'boolean',
        'requires_return'   => 'boolean',
        'custom_fields'     => 'array',
        'is_active'         => 'boolean',
    ];

    public function requests()
    {
        return $this->hasMany(Request::class);
    }
}