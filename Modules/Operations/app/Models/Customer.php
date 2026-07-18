<?php
// app/Models/Customer.php

namespace Modules\Operations\Models;

use Modules\Core\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Models\Branch;

class Customer extends Model
{
    use HasUlids, HasTenant, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'type',
        'name',
        'phone',
        'email',
        'national_id',
        'company_name',
        'tax_number',
        'address',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    public function validations()
    {
        return $this->hasMany(CustomerValidation::class);
    }
}
