<?php
// app/Models/InspectionTemplateItem.php

namespace Modules\CarLog\Models;

use Modules\Core\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class InspectionTemplateItem extends Model
{
    use HasUlids, HasTenant;

    protected $fillable = [
        'tenant_id',
        'inspection_template_id',
        'category',
        'item_name',
        'is_required',
        'order_index',
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    public function template()
    {
        return $this->belongsTo(InspectionTemplate::class, 'inspection_template_id');
    }
}