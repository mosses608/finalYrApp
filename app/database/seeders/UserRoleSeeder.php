<?php

namespace Database\Seeders;

use App\Models\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        UserRole::create([
            'name' => 'Admin',
            'slug' => 'admin',
        ]);

        UserRole::create([
            'name' => 'Waste Collector',
            'slug' => 'waste collector',
        ]);

        UserRole::create([
            'name' => 'Resident',
            'slug' => 'resident',
        ]);
    }
}
