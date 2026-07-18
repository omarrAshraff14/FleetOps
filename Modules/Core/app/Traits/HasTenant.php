<?php
namespace Modules\Core\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasTenant
{
    protected static function bootHasTenant(): void
    {
        static::addGlobalScope('tenant', function (Builder $query) {
            if (app()->has('currentTenant')) {
                $query->where(
                    (new static)->getTable() . '.tenant_id',
                    app('currentTenant')->id
                );
            }
        });

        static::creating(function ($model) {
            if (app()->has('currentTenant') && empty($model->tenant_id)) {
                $model->tenant_id = app('currentTenant')->id;
            }
        });
    }

    public function tenant()
    {
        // بنكتب string بدل ::class عشان منحتاجش import
        return $this->belongsTo('Modules\Core\Models\Tenant');
    }
}
