<?php
// app/Models/CarBrand.php

namespace Modules\Fleet\Models;

use Modules\Core\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class CarBrand extends Model
{
    use HasUlids, HasTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'logo',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function carModels()
    {
        return $this->hasMany(CarModel::class);
    }
}