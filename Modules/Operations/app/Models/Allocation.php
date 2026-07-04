<?php
namespace Modules\Operations\Models;

use Modules\Core\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Allocation extends ModelAssignment
{
    use HasUlids, HasTenant;

    protected $fillable = [
        'tenant_id',
        'request_id',
        'car_id',
        'allocated_by',
        'status',
        'cancel_reason',
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function allocatedBy()
    {
        return $this->belongsTo(User::class, 'allocated_by');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}