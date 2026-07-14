<?php
// Modules/Fleet/app/Http/Requests/UpdateCarRequest.php

namespace Modules\Fleet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('cars.edit');
    }

    public function rules(): array
    {
        $carId = $this->route('car')->id;

        return [
            'car_model_id'    => ['sometimes', 'ulid', 'exists:car_models,id'],
            'branch_id'       => ['sometimes', 'ulid', 'exists:branches,id'],
            'plate_number'    => [
                'sometimes',
                'string',
                'max:20',
                "unique:cars,plate_number,{$carId},id,tenant_id," . currentTenant()->id . ",deleted_at,NULL",
            ],
            'year'            => ['sometimes', 'integer', 'min:1990', 'max:' . (date('Y') + 1)],
            'color'           => ['sometimes', 'string', 'max:50'],
            'fuel_type'       => ['sometimes', 'in:petrol,diesel,electric,hybrid'],
            'current_km'      => ['sometimes', 'integer', 'min:0'],
            'code'            => [
                'nullable',
                'string',
                'max:50',
                "unique:cars,code,{$carId},id,tenant_id," . currentTenant()->id . ",deleted_at,NULL",
            ],
            'chassis_number'  => ['nullable', 'string', 'max:100'],
            'engine_number'   => ['nullable', 'string', 'max:100'],
            'owner_name'      => ['nullable', 'string', 'max:100'],
            'supplier_name'   => ['nullable', 'string', 'max:100'],
            'account_manager' => ['nullable', 'string', 'max:100'],
            'features'        => ['nullable', 'array'],
            'has_camera'      => ['nullable', 'boolean'],
            'has_sensors'     => ['nullable', 'boolean'],
            'notes'           => ['nullable', 'string', 'max:500'],
        ];
    }
}
