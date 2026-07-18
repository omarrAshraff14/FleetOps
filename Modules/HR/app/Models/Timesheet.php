<?php
// app/Models/Timesheet.php

namespace Modules\HR\Models;

use Modules\Core\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Branch;
use Modules\Core\Models\User;

class Timesheet extends Model
{
    use HasUlids, HasTenant;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'user_id',
        'date',
        'check_in',
        'check_out',
        'total_minutes',
        'status',
        'approved_by',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
