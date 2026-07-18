<?php
// app/Models/CarLogInspection.php

namespace Modules\CarLog\Models;

use Modules\Core\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\User;

class CarLogInspection extends Model
{
    use HasUlids, HasTenant;

    protected $fillable = [
        'tenant_id',
        'car_log_id',
        'inspection_template_id',
        'type',
        'inspected_by',
        'overall_result',
        'notes',
        'inspected_at',
    ];

    protected $casts = [
        'inspected_at' => 'datetime',
    ];

    public function carLog()
    {
        return $this->belongsTo(CarLog::class);
    }

    public function template()
    {
        return $this->belongsTo(InspectionTemplate::class, 'inspection_template_id');
    }

    public function inspectedBy()
    {
        return $this->belongsTo(User::class, 'inspected_by');
    }

    public function items()
    {
        return $this->hasMany(CarLogInspectionItem::class);
    }

    public function failedItems()
    {
        return $this->hasMany(CarLogInspectionItem::class)
                    ->where('result', 'fail');
    }
}
