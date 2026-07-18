<?php
// database/seeders/RequestTypeSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Operations\Models\RequestType;
use Modules\Core\Models\Tenant;

class RequestTypeSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('domain', 'demo')->first();

        $types = [
            [
                'name'               => 'تأجير سيارة',
                'slug'               => 'rental',
                'requires_customer'  => true,
                'requires_return'    => true,
                'custom_fields'      => [
                    ['key' => 'contract_number', 'label' => 'رقم العقد',     'type' => 'text',   'required' => false],
                    ['key' => 'daily_rate',       'label' => 'السعر اليومي', 'type' => 'number', 'required' => true],
                    ['key' => 'rental_days',      'label' => 'عدد الأيام',   'type' => 'number', 'required' => true],
                    ['key' => 'daily_km_limit',   'label' => 'كم يومي مسموح','type' => 'number', 'required' => false],
                ],
            ],
            [
                'name'               => 'مشوار داخلي',
                'slug'               => 'internal_trip',
                'requires_customer'  => false,
                'requires_return'    => true,
                'custom_fields'      => [],
            ],
            [
                'name'               => 'مشوار مندوب',
                'slug'               => 'rep_trip',
                'requires_customer'  => true,
                'requires_return'    => true,
                'custom_fields'      => [
                    ['key' => 'visit_purpose', 'label' => 'غرض الزيارة', 'type' => 'text', 'required' => false],
                ],
            ],
            [
                'name'               => 'شحن',
                'slug'               => 'shipping',
                'requires_customer'  => true,
                'requires_return'    => false,
                'custom_fields'      => [
                    ['key' => 'waybill_number',    'label' => 'رقم بوليصة الشحن', 'type' => 'text',   'required' => true],
                    ['key' => 'shipment_weight',   'label' => 'وزن الشحنة (كجم)', 'type' => 'number', 'required' => false],
                    ['key' => 'destination',       'label' => 'الوجهة',            'type' => 'text',   'required' => true],
                    ['key' => 'recipient_name',    'label' => 'اسم المستلم',       'type' => 'text',   'required' => false],
                    ['key' => 'recipient_phone',   'label' => 'تليفون المستلم',    'type' => 'text',   'required' => false],
                ],
            ],
            [
                'name'               => 'نقل موظفين',
                'slug'               => 'employee_transfer',
                'requires_customer'  => false,
                'requires_return'    => true,
                'custom_fields'      => [
                    ['key' => 'route_name',      'label' => 'اسم الخط',        'type' => 'text',   'required' => true],
                    ['key' => 'employee_count',  'label' => 'عدد الموظفين',    'type' => 'number', 'required' => false],
                ],
            ],
            [
                'name'               => 'فحص جودة',
                'slug'               => 'quality_test_drive',
                'requires_customer'  => false,
                'requires_return'    => true,
                'custom_fields'      => [],
            ],
        ];

        foreach ($types as $type) {
            RequestType::firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'slug'      => $type['slug'],
                ],
                [
                    'name'              => $type['name'],
                    'requires_customer' => $type['requires_customer'],
                    'requires_return'   => $type['requires_return'],
                    'custom_fields'     => $type['custom_fields'],
                    'is_active'         => true,
                ]
            );
        }
    }
}