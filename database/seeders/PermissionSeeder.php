<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = ['Role', 'User', 'Category', 'Brand', 'Size', 'Product', 'Order', 'District', 'Area', 'Coupon', 'Setting', 'Slider', 'Subscriber', 'Page', 'Bank', 'Expense', 'Supplier'];
        $permissions = [
        	'Role' => [
        		'role.index',
        		'role.view',
        		'role.create',
        		'role.edit',
        		'role.delete',
        	],
        	'User' => [
        		'user.index',
        		'user.view',
        		'user.create',
        		'user.edit',
        		'user.delete',
        	],
        	'Category' => [
        		'category.index',
        		'category.view',
        		'category.create',
        		'category.edit',
        		'category.delete',
        	],
        	'Brand' => [
        		'brand.index',
        		'brand.view',
        		'brand.create',
        		'brand.edit',
        		'brand.delete',
        	],
        	'Size' => [
        		'size.index',
        		'size.view',
        		'size.create',
        		'size.edit',
        		'size.delete',
        	],
        	'Product' => [
        		'product.index',
        		'product.view',
        		'product.create',
        		'product.edit',
        		'product.delete',
        	],
        	'Order' => [
        		'order.index',
        		'order.view',
        		'order.create',
        		'order.edit',
        		'order.delete',
        	],
            'District' => [
                'district.index',
                'district.view',
                'district.create',
                'district.edit',
                'district.delete',
            ],
            'Area' => [
                'area.index',
                'area.view',
                'area.create',
                'area.edit',
                'area.delete',
            ],
            'Coupon' => [
                'coupon.index',
                'coupon.view',
                'coupon.create',
                'coupon.edit',
                'coupon.delete',
            ],
            'Setting' => [
                'setting.index',
                'setting.view',
                'setting.create',
                'setting.edit',
                'setting.delete',
            ],
            'Slider' => [
                'slider.index',
                'slider.view',
                'slider.create',
                'slider.edit',
                'slider.delete',
            ],
            'Subscriber' => [
                'subscriber.index',
                'subscriber.delete',
            ],
            'Page' => [
                'page.index',
                'page.view',
                'page.create',
                'page.edit',
                'page.delete',
            ],
            'Bank' => [
                'bank.index',
                'bank.view',
                'bank.create',
                'bank.edit',
                'bank.delete',
            ],
            'Expense' => [
                'expense.index',
                'expense.view',
                'expense.create',
                'expense.edit',
                'expense.delete',
            ],
            'Supplier' => [
                'supplier.index',
                'supplier.view',
                'supplier.create',
                'supplier.edit',
                'supplier.delete',
                'supplier.payment',
            ],
        ];
        foreach ($groups as $group) {
            foreach ($permissions[$group] as $permission) {
                $new_permission = new Permission;
                $new_permission->name = $permission;
                $new_permission->group_name = $group;
                $new_permission->save();
            }
        }
    }
}
