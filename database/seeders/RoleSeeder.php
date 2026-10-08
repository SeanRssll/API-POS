<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserModels\Roles as Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'Administrator',
            'description' => 'Full access to the POS system.',
        ]);

        Role::create([
            'name' => 'Manager',
            'description' => 'Manages sales, products, inventory, and reports.',
        ]);

        Role::create([
            'name' => 'Cashier',
            'description' => 'Handles sales and customer transactions.',
        ]);

        Role::create([
            'name' => 'Inventory Staff',
            'description' => 'Manages products and inventory.',
        ]);

        Role::create([
            'name' => 'New User',
            'description' => 'N/A',
        ]);
    }
}
