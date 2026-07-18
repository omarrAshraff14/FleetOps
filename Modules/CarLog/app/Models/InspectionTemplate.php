<?php
// app/Models/InspectionTemplate.php

namespace Modules\CarLog\Models;

use Modules\Core\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class InspectionTemplate extends Model
{
    use HasUlids, HasTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'type',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active'  => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(InspectionTemplateItem::class)
                    ->orderBy('order_index');
    }
}