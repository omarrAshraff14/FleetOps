<?php
// app/Models/KrokyPoint.php

namespace Modules\Kroky\Models;

use App\Modules\Maintenance\Models\RepairReport;
use Modules\Core\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class KrokyPoint extends Model
{
    use HasUlids, HasTenant;

    protected $fillable = [
        'tenant_id',
        'kroky_version_id',
        'point_number',
        'view',
        'x_percent',
        'y_percent',
        'damage_type',
        'severity',
        'status',
        'description',
        'damage_report_id',
        'repair_order_id',
    ];

    protected $casts = [
        'x_percent' => 'decimal:2',
        'y_percent' => 'decimal:2',
    ];

    public function krokyVersion()
    {
        return $this->belongsTo(KrokyVersion::class);
    }

    public function damageReport()
    {
        return $this->belongsTo(DamageReport::class);
    }

   public function repairReport()
{
    return $this->belongsTo(RepairReport::class);
}
}
