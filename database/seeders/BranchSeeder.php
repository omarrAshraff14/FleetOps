<?php
// database/seeders/BranchSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Models\Branch;
use Modules\Core\Models\Tenant;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('domain', 'demo')->first();

        Branch::firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'name'      => 'الفرع الرئيسي',
            ],
            [
                'address'   => 'القاهرة',
                'phone'     => '01000000000',
                'is_active' => true,
            ]
        );
    }
}