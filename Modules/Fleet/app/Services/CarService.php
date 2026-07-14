<?php
// Modules/Fleet/app/Services/CarService.php

namespace Modules\Fleet\Services;

use Modules\Fleet\Models\Car;
use Modules\Fleet\Models\CarStatusHistory;
use Illuminate\Support\Facades\DB;

class CarService
{
    // إنشاء عربية جديدة
    public function create(array $data): Car
    {
        return DB::transaction(function () use ($data) {
            $car = Car::create($data);

            // تسجيل أول حالة في التاريخ
            $this->recordStatusHistory(
                car: $car,
                oldStatus: null,
                newStatus: $car->status,
                reason: 'إضافة السيارة للنظام',
                isOverride: false,
            );

            return $car;
        });
    }

    // تغيير حالة العربية
    public function changeStatus(
        Car $car,
        string $newStatus,
        string $reason = null,
        bool $isOverride = false
    ): Car {
        return DB::transaction(function () use ($car, $newStatus, $reason, $isOverride) {
            $oldStatus = $car->status;

            $updateData = ['status' => $newStatus];

            // لو override من المدير
            if ($isOverride) {
                $updateData['status_override_by']   = auth()->id();
                $updateData['status_override_note'] = $reason;
                $updateData['status_override_at']   = now();
            }

            $car->update($updateData);

            $this->recordStatusHistory(
                car: $car,
                oldStatus: $oldStatus,
                newStatus: $newStatus,
                reason: $reason,
                isOverride: $isOverride,
            );

            return $car->fresh();
        });
    }

    // تسجيل تاريخ الحالة
    private function recordStatusHistory(
        Car $car,
        ?string $oldStatus,
        string $newStatus,
        ?string $reason,
        bool $isOverride
    ): void {
        CarStatusHistory::create([
            'tenant_id'   => currentTenant()->id,
            'car_id'      => $car->id,
            'old_status'  => $oldStatus,
            'new_status'  => $newStatus,
            'changed_by'  => auth()->id(),
            'is_override' => $isOverride,
            'reason'      => $reason,
        ]);
    }

    // جلب العربيات المتاحة للحجز
    public function getAvailableCars(?string $branchId = null)
    {
        return Car::query()
            ->with(['model.brand', 'currentDriver'])
            ->where('status', 'ready')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->get();
    }
}