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





        $this->call([
            DepartmentSeeder::class,
            SectionSeeder::class,
            FeeSeeder::class,
            CourseSeeder::class,
            RoleSeeder::class,
            StudentSeeder::class,
            InstructorSeeder::class,
            PopulatorSeeder::class,
        ]);

//        Instructor::factory()->count(10)->create();


        // Create an Admin Account
        $admin = User::factory()
            ->afterCreating(fn ($user) => $user->assignRole('Admin'))
            ->create([
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin')
            ]);


        // Create an Officer Account
        $officer = User::factory()
            ->afterCreating(fn ($user) => $user->assignRole('Officer'))
            ->create([
                'name' => 'officer',
                'email' => 'officer@gmail.com',
                'password' => Hash::make('officer')
            ]);

        // Create A Faculty Account
        $faculty = User::factory()
            ->afterCreating(fn ($user) => $user->assignRole('Faculty'))
            ->create([
                'name' => 'faculty',
                'email' => 'faculty@gmail.com',
                'password' => Hash::make('faculty')
            ]);


        // Create a Registrar Account
        $registrar = User::factory()
            ->afterCreating(fn ($user) => $user->assignRole('Registrar'))
            ->create([
                'name' => 'registrar',
                'email' => 'registrar@gmail.com',
                'password' => Hash::make('registrar')
            ]);


//         Create a Student Account
//        Role::firstOrCreate(
//            ['name' => 'Student'],
//            ['guard_name' => 'web']
//        );
//        $student = Student::factory()
//            ->count(10)
//            ->afterCreating(function ($student) {
//                Enrollment::factory()->create([
//                    'student_id' => $student->id,
//                ]);
//            })
//            ->create();

    }
}
