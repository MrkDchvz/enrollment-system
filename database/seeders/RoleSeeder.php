<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::Create(['name' => 'Admin', 'guard_name' => 'web']);
        Role::Create(['name' => 'Registrar', 'guard_name' => 'web']);
        Role::Create(['name' => 'Student', 'guard_name' => 'web']);
        Role::Create(['name' => 'Officer', 'guard_name' => 'web']);
        Role::Create(['name' => 'Faculty', 'guard_name' => 'web']);
    }
}
