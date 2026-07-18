<?php

namespace Modules\Fleet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('cars.create');
    }

    public function rules(): array
    {
        return [
            // ── Identity ──
            'code'           => ['nullable', 'string', 'max:50', 'unique:cars,code'],
            'plate_number'   => ['required', 'string', 'max:20', 'unique:cars,plate_number'],
            'chassis_number' => ['nullable', 'string', 'max:17', 'unique:cars,chassis_number'],
            'engine_number'  => ['nullable', 'string', 'max:50'],

            // ── Specs ──
            'brand_id'       => ['required', 'exists:car_brands,id'],
            'car_model_id'   => ['required', 'exists:car_models,id'],
            'year'           => ['required', 'integer', 'min:1990', 'max:' . (date('Y') + 1)],
            'color'          => ['nullable', 'string', 'max:50'],
            'color_hex'      => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'fuel_type'      => ['required', 'in:petrol,diesel,electric,hybrid,gas'],
            'transmission'   => ['nullable', 'in:automatic,manual'],
            'current_km'     => ['nullable', 'integer', 'min:0'],
            'seats'          => ['nullable', 'integer', 'min:1', 'max:60'],

            // ── Features ──
            'has_camera'      => ['nullable', 'boolean'],
            'has_sensors'     => ['nullable', 'boolean'],
            'features'        => ['nullable', 'array'],
            'features.abs'    => ['nullable', 'boolean'],
            'features.gps'    => ['nullable', 'boolean'],

            // ── Branch & ownership ──
            'branch_id'      => ['required', 'exists:branches,id'],
            'ownership'      => ['nullable', 'in:owned,leased,rented,company'],
            'purchase_date'  => ['nullable', 'date', 'before_or_equal:today'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],

            // ── Status & notes ──
            'status'         => ['nullable', 'in:ready,not_ready'],
            'notes'          => ['nullable', 'string', 'max:2000'],

            // ── Documents ──
            'documents'                  => ['nullable', 'array'],
            'documents.*.type'           => ['required_with:documents.*.file', 'in:license,insurance,inspection,other'],
            'documents.*.file'           => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'documents.*.expiry_date'    => ['nullable', 'date', 'after:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'plate_number.required' => 'Plate number is required.',
            'plate_number.unique'   => 'This plate number is already registered.',
            'chassis_number.unique' => 'This VIN/Chassis number is already registered.',
            'chassis_number.max'    => 'VIN must be exactly 17 characters or fewer.',
            'car_model_id.required' => 'Please select a model.',
            'brand_id.required'     => 'Please select a brand.',
            'branch_id.required'    => 'Please assign a branch.',
            'fuel_type.required'    => 'Fuel type is required.',
            'year.min'              => 'Year must be 1990 or later.',
            'documents.*.file.mimes'=> 'Documents must be PDF, JPG, or PNG.',
            'documents.*.file.max'  => 'Each document must be under 5 MB.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Normalize checkboxes — unchecked boxes don't appear in POST
        $this->merge([
            'has_camera'  => $this->boolean('has_camera'),
            'has_sensors' => $this->boolean('has_sensors'),
            'features'    => array_merge(
                ['abs' => false, 'gps' => false],
                array_map('boolval', $this->input('features', [])),
            ),
            // Default status per BR-020
            'status' => $this->input('status', 'not_ready'),
        ]);
    }
}
