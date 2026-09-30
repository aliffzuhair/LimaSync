<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'description' => 'System Administrator'],
            ['name' => 'operations', 'description' => 'Operations Staff'],
            ['name' => 'sales', 'description' => 'Sales Staff'],
            ['name' => 'finance', 'description' => 'Finance Staff'],
            ['name' => 'logistics', 'description' => 'Logistics Staff'],
            ['name' => 'client_view', 'description' => 'Client View Only'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insertOrIgnore([
                'name' => $role['name'],
                'description' => $role['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}