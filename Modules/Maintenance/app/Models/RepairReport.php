<?php
namespace App\Modules\Maintenance\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\User;
use Modules\Core\Traits\HasTenant ;
use Modules\Fleet\Models\Car;
use Modules\Kroky\Models\KrokyPoint;
use Modules\Maintenance\Models\RepairOrder;

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
