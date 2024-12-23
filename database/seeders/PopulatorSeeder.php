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
            ['course_code' => $courseMapping['GNED 08'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSCS'],
            ['course_code' => $courseMapping['DCIT 25'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSCS'],
            ['course_code' => $courseMapping['DCIT 55'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSCS'],
            ['course_code' => $courseMapping['FITT 4'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSCS'],
            ['course_id' => $courseMapping['GNED 14'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSCS'],
            ['course_code' => $courseMapping['MATH 02'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSCS'],
            ['course_code' => $courseMapping['COSC 65'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSCS'],
            ['course_code' => $courseMapping['COSC 70'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSCS'],

            // BSIT 2ND YEAR, 2ND SEM SUBJECTS
            ['course_code' => $courseMapping['GNED 08'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSIT'],
            ['course_code' => $courseMapping['DCIT 25'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSIT'],
            ['course_code' => $courseMapping['DCIT 55'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSIT'],
            ['course_code' => $courseMapping['FITT 4'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSIT'],
            ['course_code' => $courseMapping['ITEC 60'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSIT'],
            ['course_code' => $courseMapping['ITEC 65'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSIT'],
            ['course_code' => $courseMapping['ITEC 70'], 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'program' => 'BSIT'],

            // BSCS MIDYEAR

            // BSIT MIDYEAR



        ]);







    }
}
