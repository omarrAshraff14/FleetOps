<?php
// app/Models/KrokyVersion.php

namespace Modules\Kroky\Models;

use Modules\Core\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Modules\CarLog\Models\CarLog;
use Modules\Core\Models\User;
use Modules\Fleet\Models\Car;
use Modules\Operations\Models\CustomerValidation;

class KrokyVersion extends Model
{
    use HasUlids, HasTenant;

    protected $fillable = [
        'tenant_id',
        'car_id',
        'car_log_id',
        'version_number',
        'type',
        'created_by',
        'notes',
        'is_locked',
        'locked_at',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
        'locked_at' => 'datetime',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function carLog()
    {
        return $this->belongsTo(CarLog::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function points()
    {
        return $this->hasMany(KrokyPoint::class);
    }

    public function newPoints()
    {
        return $this->hasMany(KrokyPoint::class)
                    ->where('status', 'new');
    }

    public function customerValidations()
    {
        return $this->hasMany(CustomerValidation::class);
    }

    // Lock الـ version بعد الإنشاء
    public function lock(): void
    {
        $this->update([
            'is_locked' => true,
            'locked_at' => now(),
        ]);
    }
}
