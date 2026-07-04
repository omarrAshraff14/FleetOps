<?php
// app/Models/CarDocument.php

namespace Modules\Fleet\Models;

use Modules\Core\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class CarDocument extends Model
{
    use HasUlids, HasTenant;

    protected $fillable = [
        'tenant_id',
        'car_id',
        'type',
        'file_path',
        'expiry_date',
        'notes',
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    // Scope للوثائق المنتهية أو القريبة من الانتهاء
    public function scopeExpiringSoon($query, int $days = 30)
    {
        return $query->whereNotNull('expiry_date')
                     ->whereDate('expiry_date', '<=', now()->addDays($days))
                     ->whereDate('expiry_date', '>=', now());
    }
}