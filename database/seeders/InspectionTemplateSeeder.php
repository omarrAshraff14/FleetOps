<?php
// database/seeders/InspectionTemplateSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\CarLog\Models\InspectionTemplate;
use Modules\CarLog\Models\InspectionTemplateItem;
use Modules\Core\Models\Tenant;

class InspectionTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('domain', 'demo')->first();

        $templates = [
            // فحص الخروج (كواليتي)
            [
                'name'       => 'فحص الخروج - كواليتي',
                'type'       => 'departure',
                'is_default' => true,
                'items'      => [
                    // إطارات
                    ['category' => 'إطارات', 'item_name' => 'ضغط الإطارات الأمامية',  'is_required' => true,  'order_index' => 1],
                    ['category' => 'إطارات', 'item_name' => 'ضغط الإطارات الخلفية',   'is_required' => true,  'order_index' => 2],
                    ['category' => 'إطارات', 'item_name' => 'حالة الإطارات (تآكل)',    'is_required' => true,  'order_index' => 3],
                    ['category' => 'إطارات', 'item_name' => 'الإطار الاحتياطي',        'is_required' => false, 'order_index' => 4],
                    // محرك
                    ['category' => 'محرك', 'item_name' => 'مستوى الزيت',              'is_required' => true,  'order_index' => 5],
                    ['category' => 'محرك', 'item_name' => 'مستوى ماء الراديتر',       'is_required' => true,  'order_index' => 6],
                    ['category' => 'محرك', 'item_name' => 'مستوى سائل الفرامل',       'is_required' => true,  'order_index' => 7],
                    ['category' => 'محرك', 'item_name' => 'سائل غسيل الزجاج',        'is_required' => false, 'order_index' => 8],
                    // كهرباء
                    ['category' => 'كهرباء', 'item_name' => 'البطارية',               'is_required' => true,  'order_index' => 9],
                    ['category' => 'كهرباء', 'item_name' => 'الأنوار الأمامية',       'is_required' => true,  'order_index' => 10],
                    ['category' => 'كهرباء', 'item_name' => 'الأنوار الخلفية',        'is_required' => true,  'order_index' => 11],
                    ['category' => 'كهرباء', 'item_name' => 'أنوار الطوارئ',          'is_required' => true,  'order_index' => 12],
                    ['category' => 'كهرباء', 'item_name' => 'المكيف',                 'is_required' => false, 'order_index' => 13],
                    // هيكل
                    ['category' => 'هيكل', 'item_name' => 'الزجاج الأمامي (شرخ)',    'is_required' => true,  'order_index' => 14],
                    ['category' => 'هيكل', 'item_name' => 'المساحات',                 'is_required' => true,  'order_index' => 15],
                    ['category' => 'هيكل', 'item_name' => 'المرايا',                  'is_required' => true,  'order_index' => 16],
                    // داخلي
                    ['category' => 'داخلي', 'item_name' => 'حالة المقاعد',            'is_required' => false, 'order_index' => 17],
                    ['category' => 'داخلي', 'item_name' => 'حزام الأمان',             'is_required' => true,  'order_index' => 18],
                    ['category' => 'داخلي', 'item_name' => 'الشبكة / عدة الطوارئ',   'is_required' => false, 'order_index' => 19],
                    ['category' => 'داخلي', 'item_name' => 'طفاية الحريق',            'is_required' => false, 'order_index' => 20],
                    // وثائق
                    ['category' => 'وثائق', 'item_name' => 'رخصة التسيير سارية',     'is_required' => true,  'order_index' => 21],
                    ['category' => 'وثائق', 'item_name' => 'التأمين ساري',            'is_required' => true,  'order_index' => 22],
                ],
            ],

            // فحص المندوب (مصغر)
            [
                'name'       => 'فحص المندوب - خروج',
                'type'       => 'departure',
                'is_default' => false,
                'items'      => [
                    ['category' => 'عام', 'item_name' => 'حالة الإطارات عامة',        'is_required' => true,  'order_index' => 1],
                    ['category' => 'عام', 'item_name' => 'الأنوار شغالة',             'is_required' => true,  'order_index' => 2],
                    ['category' => 'عام', 'item_name' => 'مستوى البنزين مطابق',       'is_required' => true,  'order_index' => 3],
                    ['category' => 'عام', 'item_name' => 'العداد مطابق للمسجل',       'is_required' => true,  'order_index' => 4],
                    ['category' => 'عام', 'item_name' => 'لا توجد خدوش جديدة ظاهرة', 'is_required' => true,  'order_index' => 5],
                ],
            ],

            // فحص الرجوع (كواليتي)
            [
                'name'       => 'فحص الرجوع - كواليتي',
                'type'       => 'return',
                'is_default' => true,
                'items'      => [
                    ['category' => 'هيكل',   'item_name' => 'خدوش أو دهانات جديدة',  'is_required' => true,  'order_index' => 1],
                    ['category' => 'هيكل',   'item_name' => 'الزجاج الأمامي',         'is_required' => true,  'order_index' => 2],
                    ['category' => 'هيكل',   'item_name' => 'المرايا',                'is_required' => true,  'order_index' => 3],
                    ['category' => 'إطارات', 'item_name' => 'حالة الإطارات',          'is_required' => true,  'order_index' => 4],
                    ['category' => 'داخلي',  'item_name' => 'نظافة السيارة الداخلية', 'is_required' => false, 'order_index' => 5],
                    ['category' => 'داخلي',  'item_name' => 'حالة المقاعد',           'is_required' => false, 'order_index' => 6],
                    ['category' => 'محرك',   'item_name' => 'مستوى الزيت',            'is_required' => true,  'order_index' => 7],
                    ['category' => 'وثائق',  'item_name' => 'العداد مطابق للمسجل',    'is_required' => true,  'order_index' => 8],
                ],
            ],

            // فحص الرجوع (مندوب)
            [
                'name'       => 'فحص المندوب - رجوع',
                'type'       => 'return',
                'is_default' => false,
                'items'      => [
                    ['category' => 'عام', 'item_name' => 'العداد مطابق للمسجل',        'is_required' => true,  'order_index' => 1],
                    ['category' => 'عام', 'item_name' => 'مستوى البنزين مطابق',        'is_required' => true,  'order_index' => 2],
                    ['category' => 'عام', 'item_name' => 'لا توجد أضرار جديدة ظاهرة', 'is_required' => true,  'order_index' => 3],
                ],
            ],
        ];

        foreach ($templates as $templateData) {
            $items = $templateData['items'];
            unset($templateData['items']);

            $template = InspectionTemplate::firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'name'      => $templateData['name'],
                ],
                array_merge($templateData, [
                    'tenant_id' => $tenant->id,
                    'is_active' => true,
                ])
            );

            foreach ($items as $item) {
                InspectionTemplateItem::firstOrCreate(
                    [
                        'tenant_id'               => $tenant->id,
                        'inspection_template_id'  => $template->id,
                        'item_name'               => $item['item_name'],
                    ],
                    array_merge($item, [
                        'tenant_id'              => $tenant->id,
                        'inspection_template_id' => $template->id,
                    ])
                );
            }
        }
    }
}