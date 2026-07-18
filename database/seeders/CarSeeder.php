<?php
// database/seeders/CarSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Models\Tenant;
use Modules\Core\Models\Branch;
use Modules\Fleet\Models\Car;
use Modules\Fleet\Models\CarBrand;
use Modules\Fleet\Models\CarModel;
use Modules\Core\Models\User;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        $tenant  = Tenant::where('domain', 'demo')->first();
        $branches = Branch::where('tenant_id', $tenant->id)->get();
        $driver  = User::where('tenant_id', $tenant->id)
                       ->whereHas('roles', fn($q) => $q->where('name', 'driver'))
                       ->first();

        // ── Brands & Models ──────────────────────────────────────
        $brandsData = [
            'Hyundai'   => ['Elantra', 'Tucson', 'Sonata'],
            'Toyota'    => ['Corolla', 'Camry', 'Yaris'],
            'Kia'       => ['Cerato', 'Sportage', 'K5'],
            'MG'        => ['MG5', 'MG6', 'ZS'],
            'Nissan'    => ['Sunny', 'Sentra', 'Qashqai'],
            'Chevrolet' => ['Optra', 'Captiva', 'Aveo'],
            'Tesla'     => ['Model 3', 'Model Y'],
            'Renault'   => ['Duster', 'Logan', 'Symbol'],
        ];

        $models = [];
        foreach ($brandsData as $brandName => $modelNames) {
            $brand = CarBrand::firstOrCreate(
                ['tenant_id' => $tenant->id, 'name' => $brandName],
                ['is_active' => true]
            );
            foreach ($modelNames as $modelName) {
                $model = CarModel::firstOrCreate(
                    ['tenant_id' => $tenant->id, 'car_brand_id' => $brand->id, 'name' => $modelName],
                    ['is_active' => true]
                );
                $models["{$brandName}_{$modelName}"] = $model->id;
            }
        }

        // ── Cars ─────────────────────────────────────────────────
        $mainBranch = $branches->firstWhere('name', 'الفرع الرئيسي') ?? $branches->first();
        $alexBranch = $branches->skip(1)->first() ?? $mainBranch;
        $gizaBranch = $branches->skip(2)->first() ?? $mainBranch;

        $cars = [
            [
                'code'             => 'C-001',
                'plate_number'     => '8721 ABC',
                'car_model_id'     => $models['Hyundai_Elantra'],
                'branch_id'        => $mainBranch->id,
                'year'             => 2023,
                'color'            => 'White',
                'fuel_type'        => 'petrol',
                'current_km'       => 48210,
                'status'           => 'in_use',
                'current_driver_id'=> $driver?->id,
                'has_camera'       => true,
                'has_sensors'      => true,
                'features'         => ['abs' => true, 'bluetooth' => true, 'cruise_control' => false, 'airbags' => 6],
                'owner_name'       => 'الشركة',
                'account_manager'  => 'محمود سيد',
            ],
            [
                'code'         => 'C-002',
                'plate_number' => '4210 XYZ',
                'car_model_id' => $models['Toyota_Corolla'],
                'branch_id'    => $alexBranch->id,
                'year'         => 2022,
                'color'        => 'Silver',
                'fuel_type'    => 'petrol',
                'current_km'   => 63400,
                'status'       => 'ready',
                'has_camera'   => false,
                'has_sensors'  => true,
                'features'     => ['abs' => true, 'bluetooth' => true, 'cruise_control' => true, 'airbags' => 4],
            ],
            [
                'code'         => 'C-003',
                'plate_number' => '3390 KLM',
                'car_model_id' => $models['Kia_Cerato'],
                'branch_id'    => $mainBranch->id,
                'year'         => 2024,
                'color'        => 'Black',
                'fuel_type'    => 'petrol',
                'current_km'   => 12080,
                'status'       => 'ready',
                'has_camera'   => true,
                'has_sensors'  => true,
                'features'     => ['abs' => true, 'bluetooth' => true, 'cruise_control' => true, 'airbags' => 8, 'sunroof' => true],
            ],
            [
                'code'         => 'C-004',
                'plate_number' => '9021 QRS',
                'car_model_id' => $models['MG_MG5'],
                'branch_id'    => $gizaBranch->id,
                'year'         => 2023,
                'color'        => 'Red',
                'fuel_type'    => 'petrol',
                'current_km'   => 27540,
                'status'       => 'ready',
                'has_camera'   => true,
                'has_sensors'  => false,
                'features'     => ['abs' => true, 'bluetooth' => true, 'cruise_control' => false, 'airbags' => 6],
            ],
            [
                'code'         => 'C-005',
                'plate_number' => '1188 DEF',
                'car_model_id' => $models['Nissan_Sunny'],
                'branch_id'    => $alexBranch->id,
                'year'         => 2021,
                'color'        => 'Grey',
                'fuel_type'    => 'petrol',
                'current_km'   => 91230,
                'status'       => 'not_ready',
                'has_camera'   => false,
                'has_sensors'  => false,
                'features'     => ['abs' => false, 'bluetooth' => false, 'airbags' => 2],
            ],
            [
                'code'         => 'C-006',
                'plate_number' => '5540 TUV',
                'car_model_id' => $models['Chevrolet_Optra'],
                'branch_id'    => $gizaBranch->id,
                'year'         => 2020,
                'color'        => 'Blue',
                'fuel_type'    => 'diesel',
                'current_km'   => 118900,
                'status'       => 'retired',
                'has_camera'   => false,
                'has_sensors'  => false,
                'features'     => ['abs' => false, 'bluetooth' => false, 'airbags' => 2],
            ],
            [
                'code'             => 'C-007',
                'plate_number'     => '7734 JKL',
                'car_model_id'     => $models['Tesla_Model 3'],
                'branch_id'        => $mainBranch->id,
                'year'             => 2024,
                'color'            => 'White',
                'fuel_type'        => 'electric',
                'current_km'       => 8420,
                'status'           => 'in_use',
                'has_camera'       => true,
                'has_sensors'      => true,
                'features'         => ['abs' => true, 'bluetooth' => true, 'cruise_control' => true, 'airbags' => 8, 'sunroof' => true],
                'owner_name'       => 'الشركة',
                'account_manager'  => 'كريم عادل',
            ],
            [
                'code'         => 'C-008',
                'plate_number' => '2201 MNO',
                'car_model_id' => $models['Renault_Duster'],
                'branch_id'    => $gizaBranch->id,
                'year'         => 2022,
                'color'        => 'Beige',
                'fuel_type'    => 'diesel',
                'current_km'   => 54100,
                'status'       => 'maintenance',
                'has_camera'   => false,
                'has_sensors'  => true,
                'features'     => ['abs' => true, 'bluetooth' => false, 'airbags' => 4],
            ],
        ];

        foreach ($cars as $carData) {
            Car::firstOrCreate(
                [
                    'tenant_id'    => $tenant->id,
                    'plate_number' => $carData['plate_number'],
                ],
                array_merge($carData, ['tenant_id' => $tenant->id])
            );
        }
    }
}
