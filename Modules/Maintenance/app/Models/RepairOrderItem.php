<?php
// app/Models/RepairOrderItem.php

namespace Modules\Maintenance\Models;

use Modules\Core\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class RepairOrderItem extends Model
{
    use HasUlids, HasTenant;

    protected $fillable = [
        'tenant_id',
        'repair_order_id',
        'description',
        'cost',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
    ];

    public function repairOrder()
    {
        return $this->belongsTo(RepairOrder::class);
    }
}