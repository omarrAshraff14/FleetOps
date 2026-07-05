<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Models\Branch;
use Modules\Core\Models\Tenant;
use Modules\Core\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('domain', 'demo')->first();
        $branch = Branch::where('tenant_id', $tenant->id)->first();

        $users = [
            [
                'name'  => 'Super Admin',
                'email' => 'admin@demo.com',
                'role'  => 'super_admin',
            ],
            [
                'name'  => 'مسؤول التشغيل',
                'email' => 'operations@demo.com',
                'role'  => 'operations',
            ],
            [
                'name'  => 'مسؤول الجودة',
                'email' => 'quality@demo.com',
                'role'  => 'quality',
            ],
            [
                'name'  => 'سائق تجريبي',
                'email' => 'driver@demo.com',
                'role'  => 'driver',
            ],
            [
                'name'  => 'الصيانة',
                'email' => 'maintenance@demo.com',
                'role'  => 'maintenance',
            ],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'email'     => $data['email'],
                ],
                [
                    'branch_id' => $branch->id,
                    'name'      => $data['name'],
                    'password'  => Hash::make('password'),
                    'is_active' => true,
                ]
            );

            $user->assignRole($data['role']);
        }
    }
}