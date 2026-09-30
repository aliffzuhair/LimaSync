<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Get all role IDs
        $roles = DB::table('roles')->pluck('id', 'name');

        $users = [
            [
                'role_id' => $roles['admin'] ?? null,
                'username' => 'admin',
                'name' => 'Admin User',
                'full_name' => 'System Administrator',
                'email' => 'admin@limasync.com',
                'password' => Hash::make('password123'),
                'department' => 'IT',
                'phone' => '012-3456789',
                'is_active' => 1,
            ],
            [
                'role_id' => $roles['operations'] ?? null,
                'username' => 'ops',
                'name' => 'Ops User',
                'full_name' => 'Operations Staff',
                'email' => 'ops@limasync.com',
                'password' => Hash::make('password123'),
                'department' => 'Operations',
                'phone' => '012-3456790',
                'is_active' => 1,
            ],
            [
                'role_id' => $roles['sales'] ?? null,
                'username' => 'sales',
                'name' => 'Sales User',
                'full_name' => 'Sales Staff',
                'email' => 'sales@limasync.com',
                'password' => Hash::make('password123'),
                'department' => 'Sales',
                'phone' => '012-3456791',
                'is_active' => 1,
            ],
            [
                'role_id' => $roles['finance'] ?? null,
                'username' => 'finance',
                'name' => 'Finance User',
                'full_name' => 'Finance Staff',
                'email' => 'finance@limasync.com',
                'password' => Hash::make('password123'),
                'department' => 'Finance',
                'phone' => '012-3456792',
                'is_active' => 1,
            ],
            [
                'role_id' => $roles['logistics'] ?? null,
                'username' => 'logistics',
                'name' => 'Logistics User',
                'full_name' => 'Logistics Staff',
                'email' => 'logistics@limasync.com',
                'password' => Hash::make('password123'),
                'department' => 'Logistics',
                'phone' => '012-3456793',
                'is_active' => 1,
            ],
            [
                'role_id' => $roles['client_view'] ?? null,
                'username' => 'client',
                'name' => 'Client User',
                'full_name' => 'Client View',
                'email' => 'client@limasync.com',
                'password' => Hash::make('password123'),
                'department' => 'Client',
                'phone' => '012-3456794',
                'is_active' => 1,
            ],
        ];

        foreach ($users as $user) {
            if ($user['role_id']) {
                DB::table('users')->insertOrIgnore([
                    'role_id' => $user['role_id'],
                    'username' => $user['username'],
                    'name' => $user['name'],
                    'full_name' => $user['full_name'],
                    'email' => $user['email'],
                    'password' => $user['password'],
                    'department' => $user['department'],
                    'phone' => $user['phone'],
                    'is_active' => $user['is_active'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}