<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\StudentFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create an Admin Account
        $role = Role::create(['name' => 'Admin']);
        $admin = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin')
        ]);


        $admin->assignRole('Admin');

        // Create an Student Account
        $role = Role::create(['name' => 'Student']);

        $studentUser = User::factory()->create([
            'name' => 'student',
            'email' => 'student@gmail.com',
            'password' => Hash::make('student')
        ]);

        $studentUser->assignRole('Student');


        $student = Student::factory()->create([
            'first_name' => 'student',
            'last_name' => 'student',
            'user_id' => $studentUser->id,
        ]);






        $this->call([
            DepartmentSeeder::class,
            SectionSeeder::class,
            FeeSeeder::class,
            CourseSeeder::class,
            StudentUserSeeder::class,
        ]);

        Instructor::factory()->count(10)->create();






    }
}
