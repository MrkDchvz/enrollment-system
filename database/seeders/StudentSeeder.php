<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Spatie\Permission\Models\Role;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentsList = [
            ['first_name' => 'Liam James', 'last_name' => 'Fernandez', 'student_number' => 202211657, 'gender' => 'MALE'],
            ['first_name' => 'Ethan', 'last_name' => 'Navarro', 'student_number' => 202211664, 'gender' => 'MALE'],
            ['first_name' => 'Noah Gabriel', 'last_name' => 'Castillo', 'student_number' => 202211671, 'gender' => 'MALE'],
            ['first_name' => 'Lucas', 'last_name' => 'Morales', 'student_number' => 202211683, 'gender' => 'MALE'],
            ['first_name' => 'Oliver', 'last_name' => 'Villafuerte', 'student_number' => 202211703, 'gender' => 'MALE'],
            ['first_name' => 'Alexander John', 'last_name' => 'Cortez', 'student_number' => 202211708, 'gender' => 'MALE'],
            ['first_name' => 'Daniel', 'last_name' => 'Espinosa', 'student_number' => 202211715, 'gender' => 'MALE'],
            ['first_name' => 'Matthew Elijah', 'last_name' => 'Santiago', 'student_number' => 202211729, 'gender' => 'MALE'],
            ['first_name' => 'Ryan', 'last_name' => 'Del Rosario', 'student_number' => 202211724, 'gender' => 'MALE'],
            ['first_name' => 'Nathaniel', 'last_name' => 'Rivera', 'student_number' => 202211740, 'gender' => 'MALE'],
            ['first_name' => 'Adrian', 'last_name' => 'Lagman', 'student_number' => 202211745, 'gender' => 'MALE'],
            ['first_name' => 'Carter', 'last_name' => 'Gonzalez', 'student_number' => 202211784, 'gender' => 'MALE'],
            ['first_name' => 'Sebastian', 'last_name' => 'Tolentino', 'student_number' => 202211791, 'gender' => 'MALE'],
            ['first_name' => 'Jason', 'last_name' => 'Velasco', 'student_number' => 202211797, 'gender' => 'MALE'],
            ['first_name' => 'Zachary', 'last_name' => 'Manalang', 'student_number' => 202211804, 'gender' => 'MALE'],
            ['first_name' => 'Aaron Thomas', 'last_name' => 'Silva', 'student_number' => 202211810, 'gender' => 'MALE'],
            ['first_name' => 'Tyler', 'last_name' => 'Macapagal', 'student_number' => 202211819, 'gender' => 'MALE'],
            ['first_name' => 'Caleb Henry', 'last_name' => 'Ramos', 'student_number' => 202211825, 'gender' => 'MALE'],
            ['first_name' => 'Ashton David', 'last_name' => 'Bacani', 'student_number' => 202211838, 'gender' => 'MALE'],
            ['first_name' => 'Connor', 'last_name' => 'Dizon', 'student_number' => 202211806, 'gender' => 'MALE'],
            ['first_name' => 'Joshua', 'last_name' => 'Dimaculangan', 'student_number' => 202211834, 'gender' => 'MALE'],
            ['first_name' => 'Aiden Blake', 'last_name' => 'Torralba', 'student_number' => 202211843, 'gender' => 'MALE'],
            ['first_name' => 'Wyatt Logan', 'last_name' => 'Palacios', 'student_number' => 202211849, 'gender' => 'MALE'],
            ['first_name' => 'Miles', 'last_name' => 'Ocampo', 'student_number' => 202211865, 'gender' => 'MALE'],
            ['first_name' => 'Julian Cole', 'last_name' => 'Vergara', 'student_number' => 202211866, 'gender' => 'MALE'],
            ['first_name' => 'Declan Xavier', 'last_name' => 'Delos Reyes', 'student_number' => 202211878, 'gender' => 'MALE'],
            ['first_name' => 'Sophia', 'last_name' => 'Cruz', 'student_number' => 202211678, 'gender' => 'FEMALE'],
            ['first_name' => 'Isabella Claire', 'last_name' => 'Mendez', 'student_number' => 202211693, 'gender' => 'FEMALE'],
            ['first_name' => 'Amelia', 'last_name' => 'Santos', 'student_number' => 202211712, 'gender' => 'FEMALE'],
            ['first_name' => 'Mia Rose', 'last_name' => 'De Leon', 'student_number' => 202211772, 'gender' => 'FEMALE'],
            ['first_name' => 'Charlotte', 'last_name' => 'Pascual', 'student_number' => 202211787, 'gender' => 'FEMALE'],
            ['first_name' => 'Victoria Anne', 'last_name' => 'Valdez', 'student_number' => 202211876, 'gender' => 'FEMALE'],
        ];
        $studentRole = Role::firstOrCreate(
            ['name' => 'Student'],
            ['guard_name' => 'web']
        );

        foreach ($studentsList as $student) {
            $faker = Faker::create();

            $birthday = $this->generateBirthay();
            $email = $this->generateEmail($student['first_name'], $student['last_name']);
            $contact_number = $faker->regexify('^9\d{9}$');
            $address = $this->generateAddress();

            $firstName = strtoupper($student['first_name']);
            $lastName = strtoupper($student['last_name']);

            $user = User::factory()->create([
                'name' => strtoupper($student['first_name'] . ' ' . $student['last_name']),
                'email' => $email,
            ]);
            $user->assignRole($studentRole);

            $user->force_renew_password = true;

            DB::table('students')->insert([
                'user_id' => $user->id,
                'student_number' => $student['student_number'],
                'first_name' => $firstName,
                'last_name' => $lastName,
                'date_of_birth' => $birthday,
                'gender' => $student['gender'],
                'address' => $address,
                'contact_number' => $contact_number,
            ]);
        }

    }

    public function generateEmail($first_name, $last_name): string {
        $cleaned_first_name = str_replace(' ', '',strtolower($first_name));
        $cleaned_last_name = str_replace(' ', '',strtolower($last_name));
        $prefix = 'bc';
        $domain = 'cvsu.edu.ph';

        return $prefix . '.' . $cleaned_first_name . '.' . $cleaned_last_name . '@' . $domain;
    }

    public function generateBirthay() {
        $faker = Faker::create();

        $startDate = now()->subYears(25)->startOfYear();
        $endDate = now()->subYears(20)->endOfYear();

        return $faker->dateTimeBetween($startDate, $endDate);
    }

    public function generateAddress() {
        $faker = Faker::create();

        $citiesWithBarangays = [
            'Las Piñas' => [
                'Almanza', 'BF Resort Village', 'CAA', 'Manuyo Uno', 'Manuyo Dos', 'Pilar', 'Pulang Lupa Uno', 'Pulang Lupa Dos', 'Talon Uno', 'Talon Dos', 'Talon Tres', 'Talon Quatro', 'Zapote',
            ],
            'Bacoor' => [
                'Bayanan', 'Burol', 'Carissa', 'Don Enrique', 'Longos', 'Mambog', 'Molino I', 'Molino II', 'Molino III', 'Molino IV', 'Poblacion', 'San Isidro', 'San Nicolas', 'San Juan', 'Sampaloc',
            ],
            'Dasmariñas' => [
                'Burol I', 'Burol II', 'Paliparan I', 'Paliparan II', 'San Agustin', 'San Jose', 'San Juan', 'San Miguel', 'Santo Niño', 'Longos', 'Salitran I', 'Salitran II', 'Salitran III',
            ],
            'Imus' => [
                'Alapan I-A', 'Alapan I-B', 'Alapan II-A', 'Alapan II-B', 'Anabu I-A', 'Anabu I-B', 'Anabu II-A', 'Anabu II-B', 'Bahay Pari', 'Bagumbayan', 'Balante', 'Banay-Banay', 'Bucandala', 'Caña',
            ],
            'Tagaytay' => [
                'Alfonso', 'Kaylaway', 'Mag-asawang Ilog', 'Maitim II East', 'Maitim II West', 'San Jose', 'Sungay', 'Tagaytay',
            ],
            'General Trias' => [
                'Buenavista', 'Dulong Bayan', 'Manggahan', 'San Francisco', 'San Juan', 'San Isidro', 'San Jose', 'San Pedro',
            ],
            'Silang' => [
                'Banay-Banay', 'Barrio Bagumbayan', 'Barrio Longos', 'Barangay Malaking Pook',
            ],
        ];

        $city = $faker->randomElement(array_keys($citiesWithBarangays));
        $barangays = $citiesWithBarangays[$city];

        $barangay = $faker->randomElement($barangays);

        $address = $faker->streetAddress;

        return $address . ', ' . $barangay . ', ' . $city;
    }
}
