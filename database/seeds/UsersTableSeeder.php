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
        $app_1_users = factory(\App\Models\User::class, 1)->create(
            [
                'role_id' => 1,
                'app_id' => 1,
                'name' => 'Super Admin',
                'email' => 'superadmin@mail.com',
                'phone_number' => '099888777',
                'email_verified_at' => NOW(),
                'password'             => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'active' => 1,

            ]
        );

        $app_1_users = factory(\App\Models\User::class, 1)->create(
            [
                'role_id' => 3,
                'app_id' => 1,
                'name' => 'App 1 Admin',
                'email' => 'admin@mail.com',
                'phone_number' => '099888777',
                'email_verified_at' => NOW(),
                'password'             => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'active' => 1,

            ]
        );

        $app_1_users = factory(\App\Models\User::class, 1)->create(
            [
                'role_id' => 4,
                'app_id' => 1,
                'name' => 'App 2 Admin',
                'email' => 'admin2@mail.com',
                'phone_number' => '099888777',
                'email_verified_at' => NOW(),
                'password'             => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'active' => 1,
            ]
        );
    }
}
