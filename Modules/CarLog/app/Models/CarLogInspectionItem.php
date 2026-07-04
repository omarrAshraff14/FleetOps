<?php
// app/Models/CarLogInspectionItem.php

namespace Modules\CarLog\Models;

use Modules\Core\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class CarLogInspectionItem extends Model
{
    use HasUlids, HasTenant;

    protected $fillable = [
        'tenant_id',
        'car_log_inspection_id',
        'inspection_template_item_id',
        'result',
        'notes',
    ];

    public function inspection()
    {
        return $this->belongsTo(CarLogInspection::class, 'car_log_inspection_id');
    }

    public function templateItem()
    {
        return $this->belongsTo(
            InspectionTemplateItem::class,
            'inspection_template_item_id'
        );
    }
}