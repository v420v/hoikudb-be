<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => '管理ユーザー',
            'email' => 'ibuki420v@gmail.com',
            'password' => Hash::make('Test1234'),
        ]);
    }
}
