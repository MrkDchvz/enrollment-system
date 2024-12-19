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
        Role::Create(['name' => 'Admin']);
        Role::Create(['name' => 'Registrar']);
        Role::Create(['name' => 'Student']);
        Role::Create(['name' => 'Officer']);
    }
}
