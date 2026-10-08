<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;


class DefaultUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'role_id' => 1,
            'name' => 'Sean Vargas',
            'email' => 'laurenze97@gmail.com',
            'password' => '$2y$10$CeRSl50z0l/wi0YagXKzPeBrqrBxZzoqZCKTjCbwfwveoyk/0jGja',
            'is_active' => 1,
        ]);

    }
}
