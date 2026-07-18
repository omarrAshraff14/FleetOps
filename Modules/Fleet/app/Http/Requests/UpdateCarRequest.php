<?php

namespace Modules\Fleet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('cars.edit');
    }

    public function rules(): array
    {
        $carId = $this->route('car')->id;

        return [
            // ── Identity ──
            'code'           => ['nullable', 'string', 'max:50', Rule::unique('cars', 'code')->ignore($carId)],
            'plate_number'   => ['required', 'string', 'max:20', Rule::unique('cars', 'plate_number')->ignore($carId)],
            'chassis_number' => ['nullable', 'string', 'max:17', Rule::unique('cars', 'chassis_number')->ignore($carId)],
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
            'purchase_date'  => ['nullable', 'date'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],

            // ── Notes ──
            'notes'          => ['nullable', 'string', 'max:2000'],

            // ── Documents (new uploads only — existing managed separately) ──
            'documents'               => ['nullable', 'array'],
            'documents.*.type'        => ['required_with:documents.*.file', 'in:license,insurance,inspection,other'],
            'documents.*.file'        => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'documents.*.expiry_date' => ['nullable', 'date'],

            // Existing doc actions
            'delete_documents'   => ['nullable', 'array'],
            'delete_documents.*' => ['exists:car_documents,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'plate_number.required' => 'Plate number is required.',
            'plate_number.unique'   => 'This plate number is already registered to another car.',
            'chassis_number.unique' => 'This VIN/Chassis is already registered to another car.',
            'car_model_id.required' => 'Please select a model.',
            'brand_id.required'     => 'Please select a brand.',
            'branch_id.required'    => 'Please assign a branch.',
            'fuel_type.required'    => 'Fuel type is required.',
            'documents.*.file.mimes'=> 'Documents must be PDF, JPG, or PNG.',
            'documents.*.file.max'  => 'Each document must be under 5 MB.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'has_camera'  => $this->boolean('has_camera'),
            'has_sensors' => $this->boolean('has_sensors'),
            'features'    => array_merge(
                ['abs' => false, 'gps' => false],
                array_map('boolval', $this->input('features', [])),
            ),
        ]);
    }
}
