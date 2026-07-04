<?php
// app/Traits/HasTenant.php
// كل Model هيعمل use لده

namespace Modules\Core\Traits;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;

trait HasTenant
{
    protected static function bootHasTenant(): void
    {
        // Global Scope تلقائي على كل query
        static::addGlobalScope('tenant', function (Builder $query) {
            if (app()->has('currentTenant')) {
                $query->where(
                    (new static)->getTable() . '.tenant_id',
                    app('currentTenant')->id
                );
            }
        });

        // tenant_id بيتحط تلقائي عند الإنشاء
        static::creating(function ($model) {
            if (app()->has('currentTenant') && empty($model->tenant_id)) {
                $model->tenant_id = app('currentTenant')->id;
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}