<?php
// app/Models/DamageReport.php

namespace Modules\Kroky\Models;

use Modules\Core\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class DamageReport extends Model
{
    use HasUlids, HasTenant,HasAttachments;

    protected $fillable = [
        'tenant_id',
        'car_log_id',
        'car_id',
        'reported_by',
        'report_number',
        'type',
        'description',
        'estimated_cost',
        'status',
    ];

    protected $casts = [
        'estimated_cost' => 'decimal:2',
    ];

    public function carLog()
    {
        return $this->belongsTo(CarLog::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function krokyPoints()
    {
        return $this->hasMany(KrokyPoint::class);
    }

    public function repairOrder()
    {
        return $this->hasOne(RepairOrder::class);
    }
}