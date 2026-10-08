<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserModels\Roles as Role;
use App\Models\UserModels\Permission as Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        /*
        |--------------------------------------------------------------------------
        | Administrator
        |--------------------------------------------------------------------------
        | Full access
        */
        $administrator = Role::where('name', 'Administrator')->first();

        if ($administrator) {
            $administrator->permissions()->sync(
                Permission::pluck('id')->toArray()
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Manager
        |--------------------------------------------------------------------------
        */
        $manager = Role::where('name', 'Manager')->first();

        if ($manager) {
            $manager->permissions()->sync(
                Permission::whereIn('name', [
                    'users.view',
                    'users.create',
                    'users.update',

                    'products.view',
                    'products.create',
                    'products.update',

                    'inventory.view',
                    'inventory.adjust',

                    'sales.view',
                    'sales.create',
                    'sales.refund',

                    'reports.view',
                    'reports.export',
                ])->pluck('id')->toArray()
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Cashier
        |--------------------------------------------------------------------------
        */
        $cashier = Role::where('name', 'Cashier')->first();

        if ($cashier) {
            $cashier->permissions()->sync(
                Permission::whereIn('name', [
                    'products.view',

                    'sales.view',
                    'sales.create',

                    'customers.view',
                    'customers.create',
                    'customers.update',
                ])->pluck('id')->toArray()
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Inventory Staff
        |--------------------------------------------------------------------------
        */
        $inventoryStaff = Role::where('name', 'Inventory Staff')->first();

        if ($inventoryStaff) {
            $inventoryStaff->permissions()->sync(
                Permission::whereIn('name', [
                    'products.view',
                    'products.create',
                    'products.update',

                    'inventory.view',
                    'inventory.adjust',
                ])->pluck('id')->toArray()
            );
        }
    }
}