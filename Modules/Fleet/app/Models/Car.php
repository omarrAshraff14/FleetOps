<?php
// app/Models/Car.php

namespace Modules\Fleet\Models;

use Modules\Core\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use HasUlids, HasTenant, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'car_model_id',
        'code',
        'plate_number',
        'year',
        'color',
        'chassis_number',
        'engine_number',
        'fuel_type',
        'current_km',
        'owner_name',
        'supplier_name',
        'account_manager',
        'features',
        'has_camera',
        'has_sensors',
        'status',
        'status_override_by',
        'status_override_note',
        'status_override_at',
        'current_driver_id',
    ];

    protected $casts = [
        'features'           => 'array',
        'has_camera'         => 'boolean',
        'has_sensors'        => 'boolean',
        'status_override_at' => 'datetime',
    ];

    // Scopes
    public function scopeReady($query)
    {
        return $query->where('status', 'ready');
    }

    public function scopeInUse($query)
    {
        return $query->where('status', 'in_use');
    }

    // Relations
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function model()
    {
        return $this->belongsTo(CarModel::class, 'car_model_id');
    }

    public function currentDriver()
    {
        return $this->belongsTo(User::class, 'current_driver_id');
    }

    public function statusOverrideBy()
    {
        return $this->belongsTo(User::class, 'status_override_by');
    }

    public function documents()
    {
        return $this->hasMany(CarDocument::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(CarStatusHistory::class);
    }

    public function carLogs()
    {
        return $this->hasMany(CarLog::class);
    }

    public function krokyVersions()
    {
        return $this->hasMany(KrokyVersion::class);
    }

    public function latestKroky()
    {
        return $this->hasOne(KrokyVersion::class)
                    ->latestOfMany('version_number');
    }

    public function repairOrders()
    {
        return $this->hasMany(RepairOrder::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }
}