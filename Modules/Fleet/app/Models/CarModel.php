<?php
// app/Models/CarModel.php

namespace Modules\Fleet\Models;

use Modules\Core\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    use HasUlids, HasTenant;

    protected $fillable = [
        'tenant_id',
        'car_brand_id',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function brand()
    {
        return $this->belongsTo(CarBrand::class, 'car_brand_id');
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}