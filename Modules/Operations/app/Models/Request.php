<?php
// app/Models/Request.php

namespace Modules\Operations\Models;

use Modules\Core\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Request extends Model
{
    use HasUlids, HasTenant, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'request_type_id',
        'request_number',
        'operation_order_number',
        'customer_id',
        // 'car_id',
        // 'driver_id',
        // 'rep_id',
        // 'companion_id',
        'created_by',
        // 'assigned_by',
        'scheduled_at',
        'expected_return_at',
        'status',
        'cancel_reason',
        'gps_tracking',
        'custom_data',
        'notes',
        'has_photo_proof',
    ];

    protected $casts = [
        'scheduled_at'      => 'datetime',
        'expected_return_at'=> 'datetime',
        'gps_tracking'      => 'boolean',
        'has_photo_proof'   => 'boolean',
        'custom_data'       => 'array',
    ];

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            'assigned',
            'quality_check',
            'dispatched',
            'in_progress',
            'returning',
        ]);
    }

    // Relations
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function type()
    {
        return $this->belongsTo(RequestType::class, 'request_type_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function rep()
    {
        return $this->belongsTo(User::class, 'rep_id');
    }

    public function companion()
    {
        return $this->belongsTo(User::class, 'companion_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function statusHistory()
    {
        return $this->hasMany(RequestStatusHistory::class);
    }

    public function carLog()
    {
        return $this->hasOne(CarLog::class);
    }

    public function timesheet()
    {
        return $this->hasOne(Timesheet::class);
    }

public function allocations()
{
    return $this->hasMany(Allocation::class);
}

public function activeAllocation()
{
    return $this->hasOne(Allocation::class)
                ->where('status', 'active')
                ->latestOfMany();
}

public function assignments()
{
    return $this->hasMany(Assignment::class);
}

public function activeAssignment()
{
    return $this->hasOne(Assignment::class)
                ->where('status', 'active')
                ->latestOfMany();
}

public function carLogs()
{
    // غيرت من hasOne لـ hasMany
    return $this->hasMany(CarLog::class);
}

public function activeCarLog()
{
    return $this->hasOne(CarLog::class)
                ->where('status', 'active')
                ->latestOfMany();
}

// Helpers مهمين
public function currentCar()
{
    return $this->activeAllocation?->car;
}

public function currentDriver()
{
    return $this->activeAssignment?->driver;
}
}