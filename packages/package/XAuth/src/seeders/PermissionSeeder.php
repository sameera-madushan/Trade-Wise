<?php

namespace Package\XAuth\seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Package\XAuth\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //settings
        $manage_settings = Permission::create(['name' => 'MANAGE_SETTING', 'display_name' => 'Manage setting'])->assignRole('SUPER_ADMIN');

        //settings->auth
        $manage_roles = Permission::create(['name' => 'MANAGE_ROLES', 'display_name' => 'Manage roles'])->assignRole('SUPER_ADMIN');
        Permission::create(['name' => 'ADD_ROLES', 'display_name' => 'Add roles', 'parent_id' => $manage_roles->id])->assignRole('SUPER_ADMIN');
        Permission::create(['name' => 'EDIT_ROLES', 'display_name' => 'Edit roles', 'parent_id' => $manage_roles->id])->assignRole('SUPER_ADMIN');
        Permission::create(['name' => 'DELETE_ROLES', 'display_name' => 'Delete roles', 'parent_id' => $manage_roles->id])->assignRole('SUPER_ADMIN');

        $manage_permissions = Permission::create(['name' => 'MANAGE_PERMISSIONS', 'display_name' => 'Manage permissions'])->assignRole('SUPER_ADMIN');
        Permission::create(['name' => 'ADD_PERMISSIONS', 'display_name' => 'Add permissions', 'parent_id' => $manage_permissions->id])->assignRole('SUPER_ADMIN');
        Permission::create(['name' => 'EDIT_PERMISSIONS', 'display_name' => 'Edit permissions', 'parent_id' => $manage_permissions->id])->assignRole('SUPER_ADMIN');
        Permission::create(['name' => 'DELETE_PERMISSIONS', 'display_name' => 'Delete permissions', 'parent_id' => $manage_permissions->id])->assignRole('SUPER_ADMIN');

        $manage_users = Permission::create(['name' => 'MANAGE_USERS', 'display_name' => 'Manage users'])->assignRole('SUPER_ADMIN');
        Permission::create(['name' => 'ADD_USERS', 'display_name' => 'Add users', 'parent_id' => $manage_users->id])->assignRole('SUPER_ADMIN');
        Permission::create(['name' => 'EDIT_USERS', 'display_name' => 'Edit users', 'parent_id' => $manage_users->id])->assignRole('SUPER_ADMIN');
        Permission::create(['name' => 'DELETE_USERS', 'display_name' => 'Delete users', 'parent_id' => $manage_users->id])->assignRole('SUPER_ADMIN');

        //settings->config
        $manage_config = Permission::create(['name' => 'MANAGE_CONFIGURATION', 'display_name' => 'Manage configuration'])->assignRole('SUPER_ADMIN');
        Permission::create(['name' => 'ADD_CONFIGURATIONS', 'display_name' => 'Add configurations', 'parent_id' => $manage_config->id])->assignRole('SUPER_ADMIN');
        Permission::create(['name' => 'EDIT_CONFIGURATIONS', 'display_name' => 'Edit configurations', 'parent_id' => $manage_config->id])->assignRole('SUPER_ADMIN');
        Permission::create(['name' => 'DELETE_CONFIGURATIONS', 'display_name' => 'Delete configurations', 'parent_id' => $manage_config->id])->assignRole('SUPER_ADMIN');

        $manage_config_ctegories = Permission::create(['name' => 'MANAGE_CONFIGURATION_CATEGORIES', 'display_name' => 'Manage configuration categories'])->assignRole('SUPER_ADMIN');
        Permission::create(['name' => 'ADD_CONFIGURATION_CATEGORIES', 'display_name' => 'Add configuration categories', 'parent_id' => $manage_config_ctegories->id])->assignRole('SUPER_ADMIN');
        Permission::create(['name' => 'EDIT_CONFIGURATION_CATEGORIES', 'display_name' => 'Edit configuration categories', 'parent_id' => $manage_config_ctegories->id])->assignRole('SUPER_ADMIN');
        Permission::create(['name' => 'DELETE_CONFIGURATION_CATEGORIES', 'display_name' => 'Delete configuration categories', 'parent_id' => $manage_config_ctegories->id])->assignRole('SUPER_ADMIN');

    }
}
