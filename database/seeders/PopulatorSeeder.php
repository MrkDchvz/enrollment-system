<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PopulatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courseMapping = Course::pluck('id', 'course_code')->toArray();
        DB::table('populators')->insert([
            // BSCS 1ST YEAR, 1ST SEM SUBJECTS


            ['course_id' => $courseMapping["GNED 02"], 'semester' => '1st Semester', 'year_level' => '1st Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping["GNED 05"], 'semester' => '1st Semester', 'year_level' => '1st Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping["GNED 11"], 'semester' => '1st Semester', 'year_level' => '1st Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping["COSC 50"], 'semester' => '1st Semester', 'year_level' => '1st Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping["DCIT 21"], 'semester' => '1st Semester', 'year_level' => '1st Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping["DCIT 22"], 'semester' => '1st Semester', 'year_level' => '1st Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping["FITT 1"], 'semester' => '1st Semester', 'year_level' => '1st Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping["NSTP 1"], 'semester' => '1st Semester', 'year_level' => '1st Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping["CvSU 101"], 'semester' => '1st Semester', 'year_level' => '1st Year', 'program' => 'BSCS'],

            //BSIT 1ST YEAR, 1ST SEM SUBJECTS
            ['course_id' => $courseMapping["GNED 02"], 'semester' => '1st Semester', 'year_level' => '1st Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping["GNED 05"], 'semester' => '1st Semester', 'year_level' => '1st Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping["GNED 11"], 'semester' => '1st Semester', 'year_level' => '1st Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping["COSC 50"], 'semester' => '1st Semester', 'year_level' => '1st Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping["DCIT 21"], 'semester' => '1st Semester', 'year_level' => '1st Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping["DCIT 22"], 'semester' => '1st Semester', 'year_level' => '1st Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping["FITT 1"], 'semester' => '1st Semester', 'year_level' => '1st Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping["NSTP 1"], 'semester' => '1st Semester', 'year_level' => '1st Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping["CvSU 101"], 'semester' => '1st Semester', 'year_level' => '1st Year', 'program' => 'BSIT'],


            // BSCS 1ST YEAR, 2ND SEM SUBJECTS
            ['course_id' => $courseMapping['GNED 01'], 'semester' => '2nd Semester', 'year_level' => '1st Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['GNED 03'], 'semester' => '2nd Semester', 'year_level' => '1st Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['GNED 06'], 'semester' => '2nd Semester', 'year_level' => '1st Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['ITEC 50'], 'semester' => '2nd Semester', 'year_level' => '1st Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['DCIT 23'], 'semester' => '2nd Semester', 'year_level' => '1st Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['GNED 12'], 'semester' => '2nd Semester', 'year_level' => '1st Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['FITT 2'], 'semester' => '2nd Semester', 'year_level' => '1st Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['NSTP 2'], 'semester' => '2nd Semester', 'year_level' => '1st Year', 'program' => 'BSCS'],

            // BSIT 1ST YEAR, 2ND SEM SUBJECTS
            ['course_id' => $courseMapping['GNED 01'], 'semester' => '2nd Semester', 'year_level' => '1st Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['GNED 03'], 'semester' => '2nd Semester', 'year_level' => '1st Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['GNED 06'], 'semester' => '2nd Semester', 'year_level' => '1st Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['ITEC 50'], 'semester' => '2nd Semester', 'year_level' => '1st Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['DCIT 23'], 'semester' => '2nd Semester', 'year_level' => '1st Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['GNED 12'], 'semester' => '2nd Semester', 'year_level' => '1st Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['FITT 2'], 'semester' => '2nd Semester', 'year_level' => '1st Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['NSTP 2'], 'semester' => '2nd Semester', 'year_level' => '1st Year', 'program' => 'BSIT'],

            // BSCS 2ND YEAR, 1ST SEM SUBJECTS
            ['course_id' => $courseMapping['GNED 04'], 'semester' => '1st Semester', 'year_level' => '2nd Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['DCIT 50'], 'semester' => '1st Semester', 'year_level' => '2nd Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['DCIT 24'], 'semester' => '1st Semester', 'year_level' => '2nd Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['FITT 3'], 'semester' => '1st Semester', 'year_level' => '2nd Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['MATH 01'], 'semester' => '1st Semester', 'year_level' => '2nd Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['COSC 55'], 'semester' => '1st Semester', 'year_level' => '2nd Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['COSC 60'], 'semester' => '1st Semester', 'year_level' => '2nd Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['INSY 50'], 'semester' => '1st Semester', 'year_level' => '2nd Year', 'program' => 'BSCS'],


            // BSIT 2ND YEAR, 1ST SEM SUBJECTS
            ['course_id' => $courseMapping['GNED 04'], 'semester' => '1st Semester', 'year_level' => '2nd Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['DCIT 50'], 'semester' => '1st Semester', 'year_level' => '2nd Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['DCIT 24'], 'semester' => '1st Semester', 'year_level' => '2nd Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['FITT 3'], 'semester' => '1st Semester', 'year_level' => '2nd Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['GNED 07'], 'semester' => '1st Semester', 'year_level' => '2nd Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['GNED 10'], 'semester' => '1st Semester', 'year_level' => '2nd Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['GNED 14'], 'semester' => '1st Semester', 'year_level' => '2nd Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['ITEC 55'], 'semester' => '1st Semester', 'year_level' => '2nd Year', 'program' => 'BSIT'],

            // BSCS 2ND YEAR, 2ND SEM SUBJECTS
            ['course_id' => $courseMapping['GNED 08'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['DCIT 25'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['DCIT 55'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['FITT 4'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['GNED 14'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['MATH 02'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['COSC 65'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['COSC 70'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSCS'],

            // BSIT 2ND YEAR, 2ND SEM SUBJECTS
            ['course_id' => $courseMapping['GNED 08'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['DCIT 25'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['DCIT 55'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['FITT 4'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['ITEC 60'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['ITEC 65'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['ITEC 70'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSIT'],

            // BSCS 3RD YEAR, 1ST SEM SUBJECTS
            ['course_id' => $courseMapping['DCIT 65'], 'semester' => '1st Semester', 'year_level' => '3rd Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['DCIT 26'], 'semester' => '1st Semester', 'year_level' => '3rd Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['MATH 3'], 'semester' => '1st Semester', 'year_level' => '3rd Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['COSC 75'], 'semester' => '1st Semester', 'year_level' => '3rd Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['COSC 80'], 'semester' => '1st Semester', 'year_level' => '3rd Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['COCS 101'], 'semester' => '1st Semester', 'year_level' => '3rd Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['COSC 85'], 'semester' => '1st Semester', 'year_level' => '3rd Year', 'program' => 'BSCS'],

            // BSIT 3RD YEAR, 1ST SEM SUBJECTS
            ['course_id' => $courseMapping['DCIT 26'], 'semester' => '1st Semester', 'year_level' => '3rd Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['ITEC 85'], 'semester' => '1st Semester', 'year_level' => '3rd Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['ITEC 80'], 'semester' => '1st Semester', 'year_level' => '3rd Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['ITEC 90'], 'semester' => '1st Semester', 'year_level' => '3rd Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['INSY 55'], 'semester' => '1st Semester', 'year_level' => '3rd Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['DCIT 60'], 'semester' => '1st Semester', 'year_level' => '3rd Year', 'program' => 'BSIT'],

            // BSCS 3RD YEAR, 2ND SEM SUBJECTS
            ['course_id' => $courseMapping['ITEC 85'], 'semester' => '2nd Semester', 'year_level' => '3rd Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['DCIT 60'], 'semester' => '2nd Semester', 'year_level' => '3rd Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['GNED 09'], 'semester' => '2nd Semester', 'year_level' => '3rd Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['MATH 4'], 'semester' => '2nd Semester', 'year_level' => '3rd Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['COSC 90'], 'semester' => '2nd Semester', 'year_level' => '3rd Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['COSC 95'], 'semester' => '2nd Semester', 'year_level' => '3rd Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['COSC 106'], 'semester' => '2nd Semester', 'year_level' => '3rd Year', 'program' => 'BSCS'],

            // BSIT 3RD YEAR, 2ND SEM SUBJECTS
            ['course_id' => $courseMapping['GNED 09'], 'semester' => '2nd Semester', 'year_level' => '3rd Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['ITEC 95'], 'semester' => '2nd Semester', 'year_level' => '3rd Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['ITEC 101'], 'semester' => '2nd Semester', 'year_level' => '3rd Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['ITEC 106'], 'semester' => '2nd Semester', 'year_level' => '3rd Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['ITEC 100'], 'semester' => '2nd Semester', 'year_level' => '3rd Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['ITEC 200A'], 'semester' => '2nd Semester', 'year_level' => '3rd Year', 'program' => 'BSIT'],

            // BSCS 4TH YEAR, 1ST SEM SUBJECTS
            ['course_id' => $courseMapping['ITEC 80'], 'semester' => '1st Semester', 'year_level' => '4th Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['COSC 100'], 'semester' => '1st Semester', 'year_level' => '4th Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['COSC 105'], 'semester' => '1st Semester', 'year_level' => '4th Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['COSC 111'], 'semester' => '1st Semester', 'year_level' => '4th Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['COSC 200A'], 'semester' => '1st Semester', 'year_level' => '4th Year', 'program' => 'BSCS'],

            // BSIT 4TH YEAR, 1ST SEM SUBJECTS
            ['course_id' => $courseMapping['DCIT 65'], 'semester' => '1st Semester', 'year_level' => '4th Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['ITEC 111'], 'semester' => '1st Semester', 'year_level' => '4th Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['ITEC 116'], 'semester' => '1st Semester', 'year_level' => '4th Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['ITEC 110'], 'semester' => '1st Semester', 'year_level' => '4th Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['ITEC 200B'], 'semester' => '1st Semester', 'year_level' => '4th Year', 'program' => 'BSIT'],

            // BSCS 4TH YEAR, 2ND SEM SUBJECTS
            ['course_id' => $courseMapping['GNED 07'], 'semester' => '2nd Semester', 'year_level' => '4th Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['GNED 10'], 'semester' => '2nd Semester', 'year_level' => '4th Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['COSC 110'], 'semester' => '2nd Semester', 'year_level' => '4th Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['COSC 200B'], 'semester' => '2nd Semester', 'year_level' => '4th Year', 'program' => 'BSCS'],

            // BSIT 4TH YEAR, 2ND SEM SUBJECTS
            ['course_id' => $courseMapping['ITEC 199'], 'semester' => '2nd Semester', 'year_level' => '3rd Year', 'program' => 'BSIT'],

            // BSCS MIDYEAR
            ['course_id' => $courseMapping['COSC 199'], 'semester' => null, 'year_level' => 'Mid Year', 'program' => 'BSCS'],

            // BSIT MIDYEAR
            ['course_id' => $courseMapping['STAT 2'], 'semester' => null, 'year_level' => 'Mid Year', 'program' => 'BSIT'],
            ['course_id' => $courseMapping['ITEC 75'], 'semester' => null, 'year_level' => 'Mid Year', 'program' => 'BSIT'],




        ]);







    }
}
