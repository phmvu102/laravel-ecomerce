<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Tài khoản Admin hệ thống
        User::create([
            'name' => 'Hệ thống Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'phone' => '0912345678',
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Tài khoản Nhà bán hàng / Đối tác thương hiệu
        User::create([
            'name' => 'Apple Store VN',
            'email' => 'vendor@gmail.com',
            'password' => Hash::make('password'),
            'phone' => '0987654321',
            'role' => 'vendor',
            'status' => 'active',
        ]);

        // Tài khoản khách hàng mẫu
        User::create([
            'name' => 'Nguyễn Văn Khách',
            'email' => 'customer@gmail.com',
            'password' => Hash::make('password'),
            'phone' => '0933445566',
            'role' => 'customer',
            'status' => 'active',
        ]);
    }
}
