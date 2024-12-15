<?php

namespace Package\XAuth\seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Package\XAuth\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //developers
        Role::create(['name' => 'SUPER_ADMIN', 'display_name' => 'Super Admin']);
        //site admins
        Role::create(['name' => 'ADMIN', 'display_name' => 'Admin']);
        // users
        Role::create(['name' => 'USER', 'display_name' => 'User']);
    }
}
