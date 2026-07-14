<?php
// Modules/Fleet/app/Http/Requests/StoreCarRequest.php

namespace Modules\Fleet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('cars.create');
    }

    public function rules(): array
    {
        return [
            // إجباري
            'car_model_id'   => ['required', 'ulid', 'exists:car_models,id'],
            'branch_id'      => ['required', 'ulid', 'exists:branches,id'],
            'plate_number'   => [
                'required',
                'string',
                'max:20',
                // unique per tenant
                "unique:cars,plate_number,NULL,id,tenant_id," . currentTenant()->id . ",deleted_at,NULL",
            ],
            'year'           => ['required', 'integer', 'min:1990', 'max:' . (date('Y') + 1)],
            'color'          => ['required', 'string', 'max:50'],
            'fuel_type'      => ['required', 'in:petrol,diesel,electric,hybrid'],
            'current_km'     => ['required', 'integer', 'min:0'],

            // اختياري
            'code'           => [
                'nullable',
                'string',
                'max:50',
                "unique:cars,code,NULL,id,tenant_id," . currentTenant()->id . ",deleted_at,NULL",
            ],
            'chassis_number' => ['nullable', 'string', 'max:100'],
            'engine_number'  => ['nullable', 'string', 'max:100'],
            'owner_name'     => ['nullable', 'string', 'max:100'],
            'supplier_name'  => ['nullable', 'string', 'max:100'],
            'account_manager'=> ['nullable', 'string', 'max:100'],

            // Features
            'features'                    => ['nullable', 'array'],
            'features.abs'                => ['nullable', 'boolean'],
            'features.cruise_control'     => ['nullable', 'boolean'],
            'features.bluetooth'          => ['nullable', 'boolean'],
            'features.aux'                => ['nullable', 'boolean'],
            'features.airbags'            => ['nullable', 'integer', 'min:0', 'max:12'],
            'features.sunroof'            => ['nullable', 'boolean'],
            'has_camera'                  => ['nullable', 'boolean'],
            'has_sensors'                 => ['nullable', 'boolean'],

            'status'         => ['nullable', 'in:ready,not_ready'],
            'notes'          => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'car_model_id.required'  => 'الموديل مطلوب',
            'branch_id.required'     => 'الفرع مطلوب',
            'plate_number.required'  => 'رقم اللوحة مطلوب',
            'plate_number.unique'    => 'رقم اللوحة موجود بالفعل',
            'year.required'          => 'سنة الصنع مطلوبة',
            'color.required'         => 'اللون مطلوب',
            'fuel_type.required'     => 'نوع الوقود مطلوب',
            'current_km.required'    => 'الكيلومتر الحالي مطلوب',
        ];
    }

    // تنظيف البيانات قبل الـ validation
    protected function prepareForValidation(): void
    {
        $this->merge([
            'has_camera'  => $this->boolean('has_camera'),
            'has_sensors' => $this->boolean('has_sensors'),
            'current_km'  => (int) $this->current_km,
            'status'      => $this->status ?? 'ready',
        ]);
    }
}
