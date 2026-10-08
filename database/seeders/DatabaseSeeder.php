<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Laravel\Passport\ClientRepository;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            DefaultUser::class,
        ]);

        if (!\Laravel\Passport\Client::where('personal_access_client', true)->exists()) {
            app(ClientRepository::class)->createPersonalAccessClient(
                null,
                'Personal Access Client',
                config('app.url')
            );
        }
    }
    
}
