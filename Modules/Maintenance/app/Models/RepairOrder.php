<?php
// app/Models/RepairOrder.php

namespace Modules\Maintenance\Models;

use App\Modules\Maintenance\Models\RepairReport;
use Modules\Core\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\User;
use Modules\Fleet\Models\Car;
use Modules\Kroky\Models\DamageReport;
use Modules\Kroky\Models\KrokyPoint;

class RepairOrder extends Model
{
    use HasUlids, HasTenant;

    protected $fillable = [
        'tenant_id',
        'car_id',
        'damage_report_id',
        'order_number',
        'supplier_name',
        'supplier_contact',
        'status',
        'total_cost',
        'started_at',
        'completed_at',
        'created_by',
        'approved_by',
        'notes',
    ];

    protected $casts = [
        'total_cost'   => 'decimal:2',
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function damageReport()
    {
        return $this->belongsTo(DamageReport::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items()
    {
        return $this->hasMany(RepairOrderItem::class);
    }

    public function krokyPoints()
    {
        return $this->hasMany(KrokyPoint::class);
    }
    public function repairReport()
{
    return $this->hasOne(RepairReport::class);
}
}
