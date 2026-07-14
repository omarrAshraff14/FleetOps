<?php
namespace Modules\CarLog\Models;

use Modules\Core\Traits\HasTenant;
use Modules\Core\Traits\HasAttachments;
use Modules\Core\Models\Branch;
use Modules\Fleet\Models\Car;
use Modules\Operations\Models\Request;
use Modules\Kroky\Models\KrokyVersion;
use Modules\Kroky\Models\DamageReport;
use  Modules\Core\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Modules\Operations\Models\CustomerValidation;

class CarLog extends Model
{
    use HasUlids, HasTenant, HasAttachments;

    protected $fillable = [
        'tenant_id', 'request_id', 'car_id',
        'driver_id', 'rep_id',
        'departure_branch_id', 'departure_km',
        'departure_fuel_level', 'departure_fuel_amount',
        'departure_at', 'departure_approved_by',
        'destination_address', 'customer_address',
        'return_branch_id', 'return_km',
        'return_fuel_level', 'return_at', 'return_approved_by',
        'total_km', 'total_minutes',
        'travel_allowance', 'other_expenses', 'other_expenses_note',
        'daily_km_limit', 'extra_km_charge',
        'daily_rate', 'rental_days', 'route_details',
        'customer_handback_done', 'customer_handback_at',
        'status', 'notes',
    ];

    protected $casts = [
        'departure_at'          => 'datetime',
        'return_at'             => 'datetime',
        'customer_handback_at'  => 'datetime',
        'travel_allowance'      => 'decimal:2',
        'other_expenses'        => 'decimal:2',
        'departure_fuel_amount' => 'decimal:2',
        'extra_km_charge'       => 'decimal:2',
        'daily_rate'            => 'decimal:2',
        'customer_handback_done'=> 'boolean',
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function rep()
    {
        return $this->belongsTo(User::class, 'rep_id');
    }

    public function departureBranch()
    {
        return $this->belongsTo(Branch::class, 'departure_branch_id');
    }

    public function returnBranch()
    {
        return $this->belongsTo(Branch::class, 'return_branch_id');
    }

    public function departureApprovedBy()
    {
        return $this->belongsTo(User::class, 'departure_approved_by');
    }

    public function returnApprovedBy()
    {
        return $this->belongsTo(User::class, 'return_approved_by');
    }

    public function inspections()
    {
        return $this->hasMany(CarLogInspection::class);
    }

    public function damageReports()
    {
        return $this->hasMany(DamageReport::class);
    }

    public function krokyVersions()
    {
        return $this->hasMany(KrokyVersion::class);
    }

    public function customerValidation()
    {
        return $this->hasOne(CustomerValidation::class);
    }

    public function qualityDepartureInspection()
    {
        return $this->hasOne(CarLogInspection::class)
                    ->where('type', 'quality_departure');
    }

    public function qualityReturnInspection()
    {
        return $this->hasOne(CarLogInspection::class)
                    ->where('type', 'quality_return');
    }
}
