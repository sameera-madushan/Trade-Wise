<?php

namespace Package\XAuth\seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Package\XAuth\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $createdBy = User::create([
            'name' => 'Admin',
            'email' => 'admin@localhost.com',
            'email_verified_at' => now(),
            'password' => Hash::make('0000'),
        ])->assignRole('SUPER_ADMIN');
    }
}
