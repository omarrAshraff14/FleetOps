<?php
// app/Models/CarStatusHistory.php

namespace Modules\Fleet\Models;

use Modules\Core\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class CarStatusHistory extends Model
{
    use HasUlids, HasTenant;

    protected $fillable = [
        'tenant_id',
        'car_id',
        'old_status',
        'new_status',
        'changed_by',
        'is_override',
        'reason',
    ];

    protected $casts = [
        'is_override' => 'boolean',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}