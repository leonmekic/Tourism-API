<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\User::class, 1)->create(
            [
                'role_id'           => 1,
                'app_id'            => 1,
                'name'              => 'Super Admin',
                'email'             => 'superadmin@mail.com',
                'phone_number'      => '099888777',
                'email_verified_at' => NOW(),
                'password'          => bcrypt('123456789'),
                'active'            => 1,

            ]
        );

        factory(\App\Models\User::class, 1)->create(
            [
                'role_id'           => 3,
                'app_id'            => 1,
                'name'              => 'App 1 Admin',
                'email'             => 'app1admin@mail.com',
                'phone_number'      => '099888779',
                'email_verified_at' => NOW(),
                'password'          => bcrypt('123456789'),
                'active'            => 1,

            ]
        );

        factory(\App\Models\User::class, 1)->create(
            [
                'role_id'           => 4,
                'app_id'            => 2,
                'name'              => 'App 2 Admin',
                'email'             => 'app2admin@mail.com',
                'phone_number'      => '099888789',
                'email_verified_at' => NOW(),
                'password'          => bcrypt('123456789'),
                'active'            => 1,
            ]
        );
    }
}
