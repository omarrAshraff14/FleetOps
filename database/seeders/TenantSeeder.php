<?php
// database/seeders/TenantSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Models\Tenant;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        Tenant::firstOrCreate(
            ['domain' => 'demo'],
            [
                'name'      => 'Demo Company',
                'logo'      => null,
                'settings'  => [
                    'timezone'    => 'Africa/Cairo',
                    'currency'    => 'EGP',
                    'date_format' => 'd/m/Y',
                ],
                'is_active' => true,
            ]
        );
    }
}