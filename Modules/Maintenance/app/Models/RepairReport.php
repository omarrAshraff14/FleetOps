<?php
namespace App\Modules\Maintenance\Models;

use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class RepairReport extends Model
{
    use HasUlids, HasTenant;

    protected $fillable = [
        'tenant_id',
        'car_id',
        'repair_order_id',
        'reported_by',
        'report_number',
        'result',
        'notes',
        'inspected_at',
    ];

    protected $casts = [
        'inspected_at' => 'datetime',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function repairOrder()
    {
        return $this->belongsTo(RepairOrder::class);
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function krokyPoints()
    {
        return $this->hasMany(KrokyPoint::class);
    }
}