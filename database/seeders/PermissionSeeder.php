<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserModels\Permission as Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            ['name' => 'users.view', 'description' => 'View users'],
            ['name' => 'users.create', 'description' => 'Create users'],
            ['name' => 'users.update', 'description' => 'Update users'],
            ['name' => 'users.delete', 'description' => 'Delete users'],

            ['name' => 'products.view', 'description' => 'View products'],
            ['name' => 'products.create', 'description' => 'Create products'],
            ['name' => 'products.update', 'description' => 'Update products'],
            ['name' => 'products.delete', 'description' => 'Delete products'],

            ['name' => 'inventory.view', 'description' => 'View inventory'],
            ['name' => 'inventory.adjust', 'description' => 'Adjust inventory'],

            ['name' => 'sales.view', 'description' => 'View sales'],
            ['name' => 'sales.create', 'description' => 'Create sales'],
            ['name' => 'sales.refund', 'description' => 'Process refunds'],

            ['name' => 'customers.view', 'description' => 'View customers'],
            ['name' => 'customers.create', 'description' => 'Create customers'],
            ['name' => 'customers.update', 'description' => 'Update customers'],

            ['name' => 'reports.view', 'description' => 'View reports'],
            ['name' => 'reports.export', 'description' => 'Export reports'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}