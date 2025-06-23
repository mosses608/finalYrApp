<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $admin1 = Admin::create([
            'name' => 'Nancy Admin',
            'phone' => '0769123409',
            'email' => 'admin1@gmail.com',
            'gender' => 'Female',
            'profile' => null,
        ]);

        User::create([
            'username' => $admin1->email,
            'user_type' => 1,
            'user_id' => $admin1->id,
            'password' => Hash::make('password'),
            'login_attempts' => 0,
            'blocked_at' => null,
            'is_new' => null,
        ]);
    }
}
