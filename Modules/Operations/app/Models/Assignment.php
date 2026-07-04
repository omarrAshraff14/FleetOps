<?php
namespace Modules\Operations\Models;

use Modules\Core\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasUlids, HasTenant;

    protected $fillable = [
        'tenant_id',
        'request_id',
        'driver_id',
        'rep_id',
        'companion_id',
        'assigned_by',
        'status',
        'cancel_reason',
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function rep()
    {
        return $this->belongsTo(User::class, 'rep_id');
    }

    public function companion()
    {
        return $this->belongsTo(User::class, 'companion_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}