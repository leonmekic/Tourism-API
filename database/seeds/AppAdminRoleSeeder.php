<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppAdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'App 1 Admin',
            'display_name' => 'App 1 Admin',
            'created_at' => NOW(),
            'updated_at' => NOW(),
        ]);

        DB::table('roles')->insert([
            'name' => 'App 2 Admin',
            'display_name' => 'App 2 Admin',
            'created_at' => NOW(),
            'updated_at' => NOW(),
        ]);
    }
}
