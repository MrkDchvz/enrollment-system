<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class InstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $instructors = [
            ["name" => "Roi M. Francisco", "email" => "bc.roifrancisco@cvsu.edu.ph"],
            ["name" => "Clarissa Rostrollo", "email" => "bc.clarissarostrollo@cvsu.edu.ph"],
            ["name" => "Ely Rose Panganiban-Briones", "email" => "bc.elyrosepanganibanbriones@cvsu.edu.ph"],
            ["name" => "Jovelyn D. Ocampo", "email" => "bc.jovelyndocampo@cvsu.edu.ph"],
            ["name" => "Donnalyn Montallana", "email" => "bc.donnalynmontallana@cvsu.edu.ph"],
            ["name" => "Steffanie M. Bato", "email" => "bc.steffaniembato@cvsu.edu.ph"],
            ["name" => "Russel Adrianne Villareal", "email" => "bc.russeladriannevillareal@cvsu.edu.ph"],
            ["name" => "Stephen Bacolor", "email" => "bc.stephenbacolor@cvsu.edu.ph"],
            ["name" => "Aida M. Penson", "email" => "bc.aidampenson@cvsu.edu.ph"],
            ["name" => "Jessica Sambrano", "email" => "bc.jessicasambrano@cvsu.edu.ph"],
            ["name" => "Joven Rios", "email" => "bc.jovenrios@cvsu.edu.ph"],
            ["name" => "Benedick Sarimiento", "email" => "bc.benedicksarimiento@cvsu.edu.ph"],
            ["name" => "Rufino Dela Cruz", "email" => "bc.rufinodelacruz@cvsu.edu.ph"],
            ["name" => "Julios Mojas", "email" => "bc.juliosmojas@cvsu.edu.ph"],
            ["name" => "Mariel E. Castillo", "email" => "bc.marielecastillo@cvsu.edu.ph"],
            ["name" => "Edan Belgica", "email" => "bc.edanbelgica@cvsu.edu.ph"],
            ["name" => "Ralph Christian Bolarda", "email" => "bc.ralphchristianbolarda@cvsu.edu.ph"],
            ["name" => "Jerico Castillo", "email" => "bc.jericocastillo@cvsu.edu.ph"],
            ["name" => "Edmund Martinez", "email" => "bc.edmundmartinez@cvsu.edu.ph"],
            ["name" => "Lawrence Jimenez", "email" => "bc.lawrencejimenez@cvsu.edu.ph"],
            ["name" => "Cesar Talibong II", "email" => "bc.cesartalibongii@cvsu.edu.ph"],
            ["name" => "Rachel Rodriguez", "email" => "bc.rachelrodriguez@cvsu.edu.ph"],
            ["name" => "Nino Rodil", "email" => "bc.ninorodil@cvsu.edu.ph"],
            ["name" => "Jen Jerome Dela Pena", "email" => "bc.jenjeromedelapena@cvsu.edu.ph"],
            ["name" => "Jay-Ar Racadio", "email" => "bc.jayarracadio@cvsu.edu.ph"],
            ["name" => "Alvin Celino", "email" => "bc.alvincelino@cvsu.edu.ph"],
            ["name" => "Lorenzo Moreno Jr.", "email" => "bc.lorenzomorenojr@cvsu.edu.ph"],
            ["name" => "Mikaela Arciaga", "email" => "bc.mikaelaarciaga@cvsu.edu.ph"],
            ["name" => "Redem Decipulo", "email" => "bc.redemdecipulo@cvsu.edu.ph"],
            ["name" => "Alvina Ramallosa", "email" => "bc.alvinaramallosa@cvsu.edu.ph"],
            ["name" => "Ryan Paul Roy", "email" => "bc.ryanpaulroy@cvsu.edu.ph"],
            ["name" => "Nestor Miguel T. Pimentel", "email" => "bc.nestormigueltpimentel@cvsu.edu.ph"],
            ["name" => "James Manozo", "email" => "bc.jamesmanozo@cvsu.edu.ph"],
            ["name" => "Jerome Tacata", "email" => "bc.jerometacata@cvsu.edu.ph"],
            ["name" => "Clarence B. Salvador", "email" => "bc.clarencebsalvador@cvsu.edu.ph"],
        ];

        foreach ($instructors as $instructor) {
            DB::table('instructors')->insert([
                'name' => $instructor['name'],
                'email' => $instructor['email'],
                'contact_number' => $faker->regexify('^9\d{9}$'), // Generate contact number dynamically
            ]);
        }
    }
}
