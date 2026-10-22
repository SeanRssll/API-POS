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
            // Role
            'role_id'       => 1,

            // Personal information
            'f_name'        => 'Sean Russell',
            'm_name'        => 'Dira',
            'l_name'        => 'Vargas',
            'birthday'      => '1997-01-07',
            'gender'        => 'Male',

            // Address
            'house_number'  => 'Blk 6 Lot 23',
            'street'        => 'Daisy',
            'barangay'      => 'Brgy 175',
            'city'          => 'Caloocan City',
            'province'      => 'Metro Manila',
            'postal_code'   => '1400',

            // Contact and profile
            'contact_num'   => '+639308535255',
            'profile_photo' => null,

            // Authentication
            'date_hire'     => '2026-10-22',
            'email'         => 'laurenze97@gmail.com',
            'password'      => '$2y$10$CeRSl50z0l/wi0YagXKzPeBrqrBxZzoqZCKTjCbwfwveoyk/0jGja',
            'is_active'     => true,
        ]);

    }
}
