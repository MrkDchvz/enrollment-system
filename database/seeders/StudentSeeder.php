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
            ['first_name' => 'Raven', 'last_name' => 'Ampere', 'student_number' => 202211661, 'gender' => 'MALE'],
            ['first_name' => 'John Vincent', 'last_name' => 'Arino', 'student_number' => 202211668, 'gender' => 'MALE'],
            ['first_name' => 'Renlly', 'last_name' => 'Bacolina', 'student_number' => 202211672, 'gender' => 'MALE'],
            ['first_name' => 'Bryan', 'last_name' => 'Bergonia', 'student_number' => 202211682, 'gender' => 'MALE'],
            ['first_name' => 'Christopher Sebastian', 'last_name' => 'Candava', 'student_number' => 202211702, 'gender' => 'MALE'],
            ['first_name' => 'Brant Yadi', 'last_name' => 'Cordova', 'student_number' => 202211709, 'gender' => 'MALE'],
            ['first_name' => 'Joshua', 'last_name' => 'Cuebillas', 'student_number' => 202211716, 'gender' => 'MALE'],
            ['first_name' => 'Mark Adrian', 'last_name' => 'Dechavez', 'student_number' => 202211728, 'gender' => 'MALE'],
            ['first_name' => 'Jesus', 'last_name' => 'Dela Cruz', 'student_number' => 202211729, 'gender' => 'MALE'],
            ['first_name' => 'Christian Dave', 'last_name' => 'Estores', 'student_number' => 202211744, 'gender' => 'MALE'],
            ['first_name' => 'Ric Michael', 'last_name' => 'Estremadura', 'student_number' => 202211746, 'gender' => 'MALE'],
            ['first_name' => 'Jeff', 'last_name' => 'Lopez', 'student_number' => 202211789, 'gender' => 'MALE'],
            ['first_name' => 'Aldrin', 'last_name' => 'Lumpot', 'student_number' => 202211792, 'gender' => 'MALE'],
            ['first_name' => 'Marvin', 'last_name' => 'Macabale', 'student_number' => 202211796, 'gender' => 'MALE'],
            ['first_name' => 'Jenro', 'last_name' => 'Magbanua', 'student_number' => 202211801, 'gender' => 'MALE'],
            ['first_name' => 'Exequiel', 'last_name' => 'Mercolita', 'student_number' => 202211813, 'gender' => 'MALE'],
            ['first_name' => 'Eduardo', 'last_name' => 'Molina', 'student_number' => 202211816, 'gender' => 'MALE'],
            ['first_name' => 'Melvin Jr.', 'last_name' => 'Pacete', 'student_number' => 202211827, 'gender' => 'MALE'],
            ['first_name' => 'Marco Isaiah', 'last_name' => 'Pedrablanca', 'student_number' => 202211830, 'gender' => 'MALE'],
            ['first_name' => 'Raysan', 'last_name' => 'Perez', 'student_number' => 20011207, 'gender' => 'MALE'],
            ['first_name' => 'Christian', 'last_name' => 'Ponce', 'student_number' => 202211835, 'gender' => 'MALE'],
            ['first_name' => 'Sebastian Miguel', 'last_name' => 'Raule', 'student_number' => 202211841, 'gender' => 'MALE'],
            ['first_name' => 'Mark Anthony', 'last_name' => 'Saguid', 'student_number' => 202211847, 'gender' => 'MALE'],
            ['first_name' => 'Dave', 'last_name' => 'Trampe', 'student_number' => 202211863, 'gender' => 'MALE'],
            ['first_name' => 'Jon Ken Heron', 'last_name' => 'Vergara', 'student_number' => 202211868, 'gender' => 'MALE'],
            ['first_name' => 'Titus Daniel', 'last_name' => 'Villa', 'student_number' => 202211869, 'gender' => 'MALE'],
            ['first_name' => 'Hazel', 'last_name' => 'Baldava', 'student_number' => 202211675, 'gender' => 'FEMALE'],
            ['first_name' => 'Alshiyana Gwen', 'last_name' => 'Cabase', 'student_number' => 202211696, 'gender' => 'FEMALE'],
            ['first_name' => 'Mariel', 'last_name' => 'Costales', 'student_number' => 202211713, 'gender' => 'FEMALE'],
            ['first_name' => 'Fiona Yvonne', 'last_name' => 'Guzman', 'student_number' => 202211774, 'gender' => 'FEMALE'],
            ['first_name' => 'Angelie', 'last_name' => 'Larioque', 'student_number' => 202211785, 'gender' => 'FEMALE'],
            ['first_name' => 'Mariella April', 'last_name' => 'Vizcarra', 'student_number' => 202211875, 'gender' => 'FEMALE'],
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
