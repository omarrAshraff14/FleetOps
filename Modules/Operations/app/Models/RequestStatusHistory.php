<?php
// app/Models/RequestStatusHistory.php

namespace Modules\Operations\Models;

use Modules\Core\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\User;

class RequestStatusHistory extends Model
{
    use HasUlids, HasTenant;

    protected $fillable = [
        'tenant_id',
        'request_id',
        'old_status',
        'new_status',
        'changed_by',
        'notes',
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
