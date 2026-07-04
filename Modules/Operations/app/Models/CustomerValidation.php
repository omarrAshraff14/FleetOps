<?php
// app/Models/CustomerValidation.php

namespace Modules\Operations\Models;

use Modules\Core\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class CustomerValidation extends Model
{
    use HasUlids, HasTenant;

    protected $fillable = [
        'tenant_id',
        'car_log_id',
        'customer_id',
        'kroky_version_id',
        'qr_token',
        'viewed_at',
        'signed_at',
        'signature_data',
        'ip_address',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
        'signed_at' => 'datetime',
    ];

    public function carLog()
    {
        return $this->belongsTo(CarLog::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function krokyVersion()
    {
        return $this->belongsTo(KrokyVersion::class);
    }
}