<?php
// database/seeders/RolesAndPermissionsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // =====================
        // PERMISSIONS
        // =====================

        $permissions = [

            // --- Cars ---
            'cars.view',
            'cars.create',
            'cars.edit',
            'cars.delete',
            'cars.change_status',        // تغيير حالة العربية
            'cars.override_status',      // Admin override

            // --- Car Documents ---
            'car_documents.view',
            'car_documents.manage',

            // --- Customers ---
            'customers.view',
            'customers.create',
            'customers.edit',
            'customers.delete',

            // --- Requests ---
            'requests.view',
            'requests.create',
            'requests.edit',
            'requests.cancel',
            'requests.view_all_branches', // يشوف requests كل الفروع

            // --- Allocations & Assignments ---
            'allocations.manage',         // تعيين عربية للـ request
            'assignments.manage',         // تعيين سائق للـ request

            // --- Car Log ---
            'car_logs.view',
            'car_logs.create',            // فتح لوج جديد
            'car_logs.close',             // إغلاق اللوج
            'car_logs.approve_departure', // اعتماد الخروج
            'car_logs.approve_return',    // اعتماد الرجوع

            // --- Inspections ---
            'inspections.quality',        // فحص كواليتي (خروج/دخول)
            'inspections.driver',         // فحص مندوب (مصغر)

            // --- Kroky ---
            'kroky.view',
            'kroky.create_version',       // إنشاء version جديدة
            'kroky.edit_on_return',       // المندوب يعدل عند الاستلام من العميل

            // --- Damage Reports ---
            'damage_reports.view',
            'damage_reports.create',      // Driver أو Quality
            'damage_reports.manage',      // تغيير status

            // --- Repair Orders ---
            'repair_orders.view',
            'repair_orders.create',
            'repair_orders.manage',       // تغيير status + approve
            'repair_orders.approve',

            // --- Repair Reports ---
            'repair_reports.view',
            'repair_reports.create',      // Quality بس

            // --- Timesheets ---
            'timesheets.view_own',
            'timesheets.view_all',
            'timesheets.manage',

            // --- Reports ---
            'reports.operational',        // تقارير التشغيل
            'reports.financial',          // تقارير مالية
            'reports.quality',            // تقارير الجودة

            // --- Notifications ---
            'notifications.view',
            'notifications.manage_templates',

            // --- Settings ---
            'settings.view',
            'settings.manage',

            // --- Users ---
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.manage_roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // =====================
        // ROLES + PERMISSIONS
        // =====================

        // 1. Super Admin - كل صلاحيات
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->syncPermissions(Permission::all());

        // 2. Operations - مسؤول التشغيل
        $operations = Role::firstOrCreate(['name' => 'operations']);
        $operations->syncPermissions([
            'cars.view',
            'car_documents.view',
            'customers.view',
            'customers.create',
            'customers.edit',
            'requests.view',
            'requests.create',
            'requests.edit',
            'requests.cancel',
            'allocations.manage',
            'assignments.manage',
            'car_logs.view',
            'car_logs.create',
            'car_logs.close',
            'car_logs.approve_departure',
            'car_logs.approve_return',
            'damage_reports.view',
            'repair_orders.view',
            'repair_reports.view',
            'timesheets.view_all',
            'reports.operational',
            'notifications.view',
        ]);

        // 3. Quality - مسؤول الجودة
        $quality = Role::firstOrCreate(['name' => 'quality']);
        $quality->syncPermissions([
            'cars.view',
            'cars.change_status',
            'requests.view',
            'car_logs.view',
            'inspections.quality',
            'kroky.view',
            'kroky.create_version',
            'damage_reports.view',
            'damage_reports.create',
            'damage_reports.manage',
            'repair_orders.view',
            'repair_reports.view',
            'repair_reports.create',
            'notifications.view',
            'reports.quality',
        ]);

        // 4. Driver / Rep - سائق أو مندوب
        $driver = Role::firstOrCreate(['name' => 'driver']);
        $driver->syncPermissions([
            'requests.view',
            'car_logs.view',
            'inspections.driver',
            'kroky.view',
            'kroky.edit_on_return',     // يعدل بس عند استلام العربية من العميل
            'damage_reports.view',
            'damage_reports.create',    // يبلغ عن تلف بس
            'timesheets.view_own',
            'notifications.view',
        ]);

        // 5. Maintenance - الصيانة
        $maintenance = Role::firstOrCreate(['name' => 'maintenance']);
        $maintenance->syncPermissions([
            'cars.view',
            'damage_reports.view',
            'damage_reports.manage',
            'repair_orders.view',
            'repair_orders.create',
            'repair_orders.manage',
            'notifications.view',
        ]);

        // 6. Accountant - المحاسب (read only + تقارير مالية)
        $accountant = Role::firstOrCreate(['name' => 'accountant']);
        $accountant->syncPermissions([
            'cars.view',
            'customers.view',
            'requests.view',
            'car_logs.view',
            'damage_reports.view',
            'repair_orders.view',
            'reports.operational',
            'reports.financial',
            'notifications.view',
        ]);

        // 7. HR
        $hr = Role::firstOrCreate(['name' => 'hr']);
        $hr->syncPermissions([
            'users.view',
            'timesheets.view_all',
            'timesheets.manage',
            'reports.operational',
            'notifications.view',
        ]);
    }
}