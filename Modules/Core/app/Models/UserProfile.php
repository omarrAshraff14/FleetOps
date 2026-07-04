<?php
// app/Models/UserProfile.php

namespace Modules\Core\Models;

use Modules\Core\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasUlids, HasTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'national_id',
        'license_number',
        'license_expiry',
        'license_type',
        'job_title',
        'department',
        'joining_date',
        'emergency_contact_name',
        'emergency_contact_phone',
        'photo',
        'notes',
    ];

    protected $casts = [
        'license_expiry' => 'date',
        'joining_date'   => 'date',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}