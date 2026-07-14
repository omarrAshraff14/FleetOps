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

            $branches = [
            ['name' => 'الفرع الرئيسي',   'address' => 'القاهرة'],
            ['name' => 'فرع الإسكندرية',  'address' => 'الإسكندرية'],
            ['name' => 'فرع الجيزة',      'address' => 'الجيزة'],
        ];

        foreach ($branches as $branch) {
            Branch::firstOrCreate(
                ['tenant_id' => $tenant->id, 'name' => $branch['name']],
                array_merge($branch, ['tenant_id' => $tenant->id, 'phone' => null, 'is_active' => true])
            );
        }
    }
}
