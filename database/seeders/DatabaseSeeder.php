<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Package\XAuth\seeders\AdminSeeder;
use Package\XAuth\seeders\PermissionSeeder;
use Package\XAuth\seeders\RoleSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(
            [
                RoleSeeder::class,
                AdminSeeder::class,
                PermissionSeeder::class,
                CleanupDatabaseUsersSeeder::class
            ]
        );
    }
}
