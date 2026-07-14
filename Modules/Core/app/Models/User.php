<?php
// app/Models/User.php

namespace Modules\Core\Models;

use Modules\Core\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\CarLog\Models\CarLog;
use Modules\HR\Models\Timesheet;
use Modules\Kroky\Models\DamageReport;
use Modules\Operations\Models\Request;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasUlids, HasTenant, SoftDeletes, Notifiable, HasRoles;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'name',
        'email',
        'phone',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active'         => 'boolean',
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // Relations
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function carLogsAsDriver()
    {
        return $this->hasMany(CarLog::class, 'driver_id');
    }

    public function carLogsAsRep()
    {
        return $this->hasMany(CarLog::class, 'rep_id');
    }

    public function requestsCreated()
    {
        return $this->hasMany(Request::class, 'created_by');
    }

    public function requestsAsDriver()
    {
        return $this->hasMany(Request::class, 'driver_id');
    }

    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }

    public function damageReports()
    {
        return $this->hasMany(DamageReport::class, 'reported_by');
    }
}
