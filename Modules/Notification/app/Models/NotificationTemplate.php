<?php
// Modules/Notification/app/Models/NotificationTemplate.php

namespace Modules\Notification\Models;

use Modules\Core\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    use HasUlids, HasTenant;

    protected $fillable = [
        'tenant_id',
        'event',
        'title',
        'body',
        'channels',
        'roles',
        'is_active',
    ];

    protected $casts = [
        'channels'  => 'array',
        'roles'     => 'array',
        'is_active' => 'boolean',
    ];
}
