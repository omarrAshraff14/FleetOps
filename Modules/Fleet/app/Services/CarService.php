<?php

namespace Modules\Fleet\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Fleet\Models\Car;
use Modules\Fleet\Models\CarStatusHistory;

class CarService
{
    // ─────────────────────────────────────────────
    //  CREATE
    // ─────────────────────────────────────────────
    /**
     * Create a new car, persist documents, and log the initial status.
     *
     * Accepts the full validated array from StoreCarRequest.
     * Strips non-fillable keys (brand_id, documents) before Car::create().
     */
    public function create(array $data): Car
    {
        return DB::transaction(function () use ($data) {

            $documents = $data['documents'] ?? [];

            // brand_id is only used for the cascade UI — not a Car column
            unset($data['documents'], $data['brand_id']);

            // Auto-generate code if left empty
            if (empty($data['code'])) {
                $data['code'] = $this->generateCode();
            }

            $car = Car::create($data);

            $this->persistDocuments($car, $documents);

            $this->recordStatusHistory(
                car: $car,
                oldStatus: null,
                newStatus: $car->status,
                reason: 'Car registered in system.',
                isOverride: false,
            );

            return $car;
        });
    }

    // ─────────────────────────────────────────────
    //  UPDATE
    // ─────────────────────────────────────────────
    /**
     * Update an existing car's data, handle document deletions and new uploads.
     *
     * Accepts the full validated array from UpdateCarRequest.
     */
    public function update(Car $car, array $data): Car
    {
        return DB::transaction(function () use ($car, $data) {

            $newDocuments = $data['documents']        ?? [];
            $deleteDocIds = $data['delete_documents'] ?? [];

            unset($data['documents'], $data['delete_documents'], $data['brand_id']);

            $car->update($data);

            if ($deleteDocIds) {
                $this->deleteDocuments($car, $deleteDocIds);
            }

            $this->persistDocuments($car, $newDocuments);

            return $car->fresh();
        });
    }

    // ─────────────────────────────────────────────
    //  CHANGE STATUS
    // ─────────────────────────────────────────────
    /**
     * Transition a car to a new status with a mandatory reason and audit trail.
     *
     * $isOverride = true when a manager forces a car from not_ready → ready
     * without a completed inspection (BR-020 override).
     */
    public function changeStatus(
        Car $car,
        string $newStatus,
        ?string $reason = null,
        bool $isOverride = false,
    ): Car {
        return DB::transaction(function () use ($car, $newStatus, $reason, $isOverride) {

            $oldStatus  = $car->status;
            $updateData = ['status' => $newStatus];

            if ($isOverride) {
                $updateData['status_override_by']   = Auth::id();
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

    // ─────────────────────────────────────────────
    //  AVAILABLE CARS
    // ─────────────────────────────────────────────
    /**
     * Return all ready cars, optionally scoped to a branch.
     * Used by Operations when creating a new request.
     */
    public function getAvailableCars(?string $branchId = null)
    {
        return Car::query()
            ->with(['model.brand', 'currentDriver'])
            ->where('status', 'ready')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->get();
    }

    // ─────────────────────────────────────────────
    //  CODE GENERATOR
    // ─────────────────────────────────────────────
    /**
     * Generate a unique sequential internal code: C-001, C-002, …
     *
     * Locks the table row to avoid race conditions under concurrent requests.
     */
    public function generateCode(): string
    {
        // Find the highest existing numeric suffix
        $last = Car::withTrashed()
            ->where('code', 'like', 'C-%')
            ->lockForUpdate()
            ->orderByRaw("CAST(SUBSTRING(code, 3) AS UNSIGNED) DESC")
            ->value('code');

        $next = $last ? ((int) substr($last, 2)) + 1 : 1;

        return 'C-' . str_pad($next, 3, '0', STR_PAD_LEFT);
    }

    // ─────────────────────────────────────────────
    //  PRIVATE HELPERS
    // ─────────────────────────────────────────────

    /**
     * Store uploaded document files and create CarDocument records.
     * Skips rows where no file was actually uploaded (empty doc rows in the form).
     */
    private function persistDocuments(Car $car, array $documents): void
    {
        foreach ($documents as $doc) {
            if (empty($doc['file']) || ! ($doc['file'] instanceof UploadedFile)) {
                continue;
            }

            $path = $doc['file']->store("fleet/cars/{$car->id}/documents", 'public');

            $car->documents()->create([
                'tenant_id'   => $car->tenant_id,
                'type'        => $doc['type']        ?? 'other',
                'file_path'   => $path,
                'expiry_date' => $doc['expiry_date'] ?? null,
            ]);
        }
    }

    /**
     * Delete specific documents belonging to a car.
     * Removes the physical file from storage before deleting the DB record.
     */
    private function deleteDocuments(Car $car, array $docIds): void
    {
        $docs = $car->documents()->whereIn('id', $docIds)->get();

        foreach ($docs as $doc) {
            Storage::disk('public')->delete($doc->file_path);
            $doc->delete();
        }
    }

    /**
     * Insert a row into car_status_history.
     * Called on initial creation and on every status transition.
     */
    private function recordStatusHistory(
        Car $car,
        ?string $oldStatus,
        string $newStatus,
        ?string $reason,
        bool $isOverride,
    ): void {
        CarStatusHistory::create([
            'tenant_id'   => currentTenant()->id,
            'car_id'      => $car->id,
            'old_status'  => $oldStatus,
            'new_status'  => $newStatus,
            'changed_by'  => Auth::id(),
            'is_override' => $isOverride,
            'reason'      => $reason,
        ]);
    }
}
