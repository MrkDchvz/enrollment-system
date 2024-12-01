<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('courses')->insert([
            //BSCS
            // 1st Semester, 1st Year
            ['course_code' => "GNED 02", 'course_name' => "Ethics", 'lecture_units' => 3, 'lab_units' => 0, 'lecture_hours' => 3, 'lab_hours' => 0, 'semester' => '1st Semester', 'year_level' => '1st Year'],
            ['course_code' => "GNED 05", 'course_name' => "Purposive Communication", 'lecture_units' => 3, 'lab_units' => 0, 'lecture_hours' => 3, 'lab_hours' => 0, 'semester' => '1st Semester', 'year_level' => '1st Year'],
            ['course_code' => "GNED 11", 'course_name' => "Kontesktwalisadong Komunikasyon sa Filipino", 'lecture_units' => 3, 'lab_units' => 0, 'lecture_hours' => 3, 'lab_hours' => 0, 'semester' => '1st Semester', 'year_level' => '1st Year'],
            ['course_code' => "COSC 50", 'course_name' => "Discrete Structures I", 'lecture_units' => 3, 'lab_units' => 0, 'lecture_hours' => 3, 'lab_hours' => 0, 'semester' => '1st Semester', 'year_level' => '1st Year'],
            ['course_code' => "DCIT 21", 'course_name' => "Introduction to Computing", 'lecture_units' => 2, 'lab_units' => 1, 'lecture_hours' => 2, 'lab_hours' => 6, 'semester' => '1st Semester', 'year_level' => '1st Year'],
            ['course_code' => "DCIT 22", 'course_name' => "Computer Programming I", 'lecture_units' => 1, 'lab_units' => 2, 'lecture_hours' => 1, 'lab_hours' => 3, 'semester' => '1st Semester', 'year_level' => '1st Year'],
            ['course_code' => "FITT 1", 'course_name' => "Movement Enhancement", 'lecture_units' => 2, 'lab_units' => 0, 'lecture_hours' => 3, 'lab_hours' => 0, 'semester' => '1st Semester', 'year_level' => '1st Year'],
            ['course_code' => "NSTP 1", 'course_name' => "National Service Training Program 1", 'lecture_units' => 3, 'lab_units' => 0, 'lecture_hours' => 2, 'lab_hours' => 0, 'semester' => '1st Semester', 'year_level' => '1st Year'],
            ['course_code' => 'CvSU 101', 'course_name' => 'Institutional Orientation', 'lecture_units' => 1, 'lab_units' => 0, 'lecture_hours' => 1, 'lab_hours' => 0, 'semester' => '1st Semester', 'year_level' => '1st Year'],
            //2nd Semester, 1st Year
            ['course_code' => 'GNED 01', 'course_name' => 'Art Appreciation', 'lecture_units' => 3, 'lab_units' => 0, 'lecture_hours' => 3, 'lab_hours' => 0, 'semester' => '2nd Semester', 'year_level' => '1st Year'],
            ['course_code' => 'GNED 03', 'course_name' => 'Mathematics in the Modern World', 'lecture_units' => 3, 'lab_units' => 0, 'lecture_hours' => 3, 'lab_hours' => 0, 'semester' => '2nd Semester', 'year_level' => '1st Year'],
            ['course_code' => 'GNED 06', 'course_name' => 'Science, Technology and Society', 'lecture_units' => 3, 'lab_units' => 0, 'lecture_hours' => 3, 'lab_hours' => 0, 'semester' => '2nd Semester', 'year_level' => '1st Year'],
            ['course_code' => 'ITEC 50', 'course_name' => 'Web Systems and Technologies', 'lecture_units' => 2, 'lab_units' => 1, 'lecture_hours' => 2, 'lab_hours' => 3, 'semester' => '2nd Semester', 'year_level' => '1st Year'],
            ['course_code' => "DCIT 23", 'course_name' => "Computer Programming II", 'lecture_units' => 1, 'lab_units' => 2, 'lecture_hours' => 1, 'lab_hours' => 6, 'semester' => '2nd Semester', 'year_level' => '1st Year'],
            ['course_code' => 'GNED 12', 'course_name' => 'Dalumat Ng/Sa Filipino', 'lecture_units' => 3, 'lab_units' => 0, 'lecture_hours' => 3, 'lab_hours' => 0, 'semester' => '2nd Semester', 'year_level' => '1st Year'],
            ['course_code' => 'FITT 2', 'course_name' => 'Fitness Exercises', 'lecture_units' => 2, 'lab_units' => 0, 'lecture_hours' => 2, 'lab_hours' => 0, 'semester' => '2nd Semester', 'year_level' => '1st Year'],
            ['course_code' => 'NSTP 2', 'course_name' => 'National Service Training Program 2', 'lecture_units' => 3, 'lab_units' => 0, 'lecture_hours' => 3, 'lab_hours' => 0, 'semester' => '2nd Semester', 'year_level' => '1st Year'],


//            // 1st Semester, 2nd Year
//            ['course_code' => 'GNED 04', 'course_name' => 'Mga Babasahin Hinggil sa Kasaysayan ng Pilipinas', 'lecture_units' => 3, 'lecture_hours' => 3, 'semester' => '1st Semester', 'year_level' => '2nd Year'],
//            ['course_code' => 'MATH 01', 'course_name' => 'Analytic Geometry', 'lecture_units' => 3, 'lecture_hours' => 3, 'semester' => '1st Semester', 'year_level' => '2nd Year'],
//            ['course_code' => 'COSC 55', 'course_name' => 'Discrete Structures', 'lecture_units' => 3, 'lecture_hours' => 3, 'semester' => '1st Semester', 'year_level' => '2nd Year'],
//            ['course_code' => 'DCIT 50', 'course_name' => 'Object Oriented Programming', 'lecture_units' => 2, 'lab_units' => 1, 'lecture_hours' => 2, 'lab_hours' => 3, 'semester' => '1st Semester', 'year_level' => '2nd Year'],
//            ['course_code' => 'COSC 60', 'course_name' => 'Digital Logic Design', 'lecture_units' => 2, 'lab_units' => 1, 'lecture_hours' => 2, 'lab_hours' => 3, 'semester' => '1st Semester', 'year_level' => '2nd Year'],
//            ['course_code' => 'DCIT 24', 'course_name' => 'Information Management', 'lecture_units' => 2, 'lab_units' => 1, 'lecture_hours' => 2, 'lab_hours' => 3, 'semester' => '1st Semester', 'year_level' => '2nd Year'],
//            ['course_code' => 'INSY 50', 'course_name' => 'Fundamentals of Information Systems', 'lecture_units' => 3, 'lecture_hours' => 3, 'semester' => '1st Semester', 'year_level' => '2nd Year'],
//            ['course_code' => 'FITT 3', 'course_name' => 'Physical Activities towards Health and Fitness 1', 'lecture_units' => 2, 'lecture_hours' => 2, 'semester' => '1st Semester', 'year_level' => '2nd Year'],
//
//            // 2nd Semester, 2nd Year
//            ['course_code' => 'GNED 08', 'course_name' => 'Understanding the Self', 'lecture_units' => 3, 'lecture_hours' => 3, 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'lab_units' => 0, 'lab_hours' => 0],
//            ['course_code' => 'GNED 14', 'course_name' => 'Panitikan Panlipunan', 'lecture_units' => 3, 'lecture_hours' => 3, 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'lab_units' => 0, 'lab_hours' => 0],
//            ['course_code' => 'MATH 02', 'course_name' => 'Calculus', 'lecture_units' => 3, 'lecture_hours' => 3, 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'lab_units' => 0, 'lab_hours' => 0],
//            ['course_code' => 'COSC 65', 'course_name' => 'Architecture and Organization', 'lecture_units' => 2, 'lab_units' => 1, 'lecture_hours' => 2, 'lab_hours' => 3, 'semester' => '2nd Semester', 'year_level' => '2nd Year'],
//            ['course_code' => 'COSC 70', 'course_name' => 'Software Engineering I', 'lecture_units' => 3, 'lecture_hours' => 3, 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'lab_units' => 0, 'lab_hours' => 0],
//            ['course_code' => 'DCIT 25', 'course_name' => 'Data Structures and Algorithms', 'lecture_units' => 2, 'lab_units' => 1, 'lecture_hours' => 2, 'lab_hours' => 3, 'semester' => '2nd Semester', 'year_level' => '2nd Year'],
//            ['course_code' => 'DCIT 55', 'course_name' => 'Advanced Database Management System', 'lecture_units' => 2, 'lab_units' => 1, 'lecture_hours' => 2, 'lab_hours' => 3, 'semester' => '2nd Semester', 'year_level' => '2nd Year'],
//            ['course_code' => 'FITT 4', 'course_name' => 'Physical Activities towards Health and Fitness 2', 'lecture_units' => 2, 'lecture_hours' => 2, 'semester' => '2nd Semester', 'year_level' => '2nd Year', 'lab_units' => 0, 'lab_hours' => 0],
//
//            // 1st Semester, 3rd Year
//            ['course_code' => 'MATH 3', 'course_name' => 'Linear Algebra', 'lecture_units' => 3, 'lecture_hours' => 3, 'semester' => '1st Semester', 'year_level' => '3rd Year', 'lab_units' => 0, 'lab_hours' => 0],
//            ['course_code' => 'COSC 75', 'course_name' => 'Software Engineering II', 'lecture_units' => 2, 'lab_units' => 1, 'lecture_hours' => 2, 'lab_hours' => 3, 'semester' => '1st Semester', 'year_level' => '3rd Year'],
//            ['course_code' => 'COSC 80', 'course_name' => 'Operating Systems', 'lecture_units' => 2, 'lab_units' => 1, 'lecture_hours' => 2, 'lab_hours' => 3, 'semester' => '1st Semester', 'year_level' => '3rd Year'],
//            ['course_code' => 'COCS 101', 'course_name' => 'CS Elective 1 (Computer Graphics and Visual Computing)', 'lecture_units' => 2, 'lab_units' => 1, 'lecture_hours' => 2, 'lab_hours' => 3, 'semester' => '1st Semester', 'year_level' => '3rd Year'],
//            ['course_code' => 'COSC 85', 'course_name' => 'Networks and Communication', 'lecture_units' => 2, 'lab_units' => 1, 'lecture_hours' => 2, 'lab_hours' => 3, 'semester' => '1st Semester', 'year_level' => '3rd Year'],
//            ['course_code' => 'DCIT 26', 'course_name' => 'Applications Dev\'t and Emerging Technologies', 'lecture_units' => 2, 'lab_units' => 1, 'lecture_hours' => 2, 'lab_hours' => 3, 'semester' => '1st Semester', 'year_level' => '3rd Year'],
//            ['course_code' => 'DCIT 65', 'course_name' => 'Social and Professional Issues', 'lecture_units' => 3, 'lecture_hours' => 3, 'semester' => '1st Semester', 'year_level' => '3rd Year', 'lab_units' => 0, 'lab_hours' => 0],
//
//
//            // 2nd Semester, 3rd Year
//            ['course_code' => 'GNED 09', 'course_name' => 'Life and Works of Rizal', 'lecture_units' => 3, 'lecture_hours' => 3, 'semester' => '2nd Semester', 'year_level' => '3rd Year', 'lab_units' => 0, 'lab_hours' => 0],
//            ['course_code' => 'MATH 4', 'course_name' => 'Experimental Statistics', 'lecture_units' => 2, 'lab_units' => 1, 'lecture_hours' => 2, 'lab_hours' => 3, 'semester' => '2nd Semester', 'year_level' => '3rd Year'],
//            ['course_code' => 'COSC 90', 'course_name' => 'Design and Analysis of Algorithm', 'lecture_units' => 3, 'lecture_hours' => 3, 'semester' => '2nd Semester', 'year_level' => '3rd Year', 'lab_units' => 0, 'lab_hours' => 0],
//            ['course_code' => 'COSC 95', 'course_name' => 'Programming Languages', 'lecture_units' => 3, 'lecture_hours' => 3, 'semester' => '2nd Semester', 'year_level' => '3rd Year', 'lab_units' => 0, 'lab_hours' => 0],
//            ['course_code' => 'COSC 106', 'course_name' => 'CS Elective 2 (Introduction to Game Development)', 'lecture_units' => 2, 'lab_units' => 1, 'lecture_hours' => 2, 'lab_hours' => 3, 'semester' => '2nd Semester', 'year_level' => '3rd Year'],
//            ['course_code' => 'DCIT 60', 'course_name' => 'Methods of Research', 'lecture_units' => 3, 'lecture_hours' => 3, 'semester' => '2nd Semester', 'year_level' => '3rd Year', 'lab_units' => 0, 'lab_hours' => 0],
//            ['course_code' => 'ITEC 85', 'course_name' => 'Information Assurance and Security', 'lecture_units' => 3, 'lecture_hours' => 3, 'semester' => '2nd Semester', 'year_level' => '3rd Year', 'lab_units' => 0, 'lab_hours' => 0],
//            ['course_code' => 'COSC 199', 'course_name' => 'Practicum (240 Hours)', 'lecture_units' => 3, 'lecture_hours' => 3, 'semester' => '2nd Semester', 'year_level' => '3rd Year', 'lab_units' => 0, 'lab_hours' => 0],
//
//
//            // 1st Semester, 4th Year
//            ['course_code' => 'ITEC 80', 'course_name' => 'Human Computer Interaction', 'lecture_units' => 1, 'lecture_hours' => 3, 'semester' => '1st Semester', 'year_level' => '4th Year', 'lab_units' => 0, 'lab_hours' => 0],
//            ['course_code' => 'COSC 100', 'course_name' => 'Automata Theory and Formal Languages', 'lecture_units' => 3, 'lecture_hours' => 3, 'semester' => '1st Semester', 'year_level' => '4th Year', 'lab_units' => 0, 'lab_hours' => 0],
//            ['course_code' => 'COSC 105', 'course_name' => 'Intelligent Systems', 'lecture_units' => 2, 'lab_units' => 1, 'lecture_hours' => 2, 'lab_hours' => 3, 'semester' => '1st Semester', 'year_level' => '4th Year'],
//            ['course_code' => 'COSC 111', 'course_name' => 'CS Elective 3(Internet of Things)', 'lecture_units' => 2, 'lab_units' => 1, 'lecture_hours' => 2, 'lab_hours' => 3, 'semester' => '1st Semester', 'year_level' => '4th Year'],
//            ['course_code' => 'COSC 200A', 'course_name' => 'Undergraduate Thesis I', 'lecture_units' => 3, 'lecture_hours' => 1, 'semester' => '1st Semester', 'year_level' => '4th Year', 'lab_units' => 0, 'lab_hours' => 0],
//
//            // 2nd Semester, 4th Year
//            ['course_code' => 'GNED 07', 'course_name' => 'The Contemporary World', 'lecture_units' => 3, 'lecture_hours' => 3, 'semester' => '2nd Semester', 'year_level' => '4th Year', 'lab_units' => 0, 'lab_hours' => 0],
//            ['course_code' => 'GNED 10', 'course_name' => 'Gender and Society', 'lecture_units' => 3, 'lecture_hours' => 3, 'semester' => '2nd Semester', 'year_level' => '4th Year', 'lab_units' => 0, 'lab_hours' => 0],
//            ['course_code' => 'COSC 110', 'course_name' => 'Numerical and Symbolic Computation', 'lecture_units' => 2, 'lab_units' => 1, 'lecture_hours' => 2, 'lab_hours' => 3, 'semester' => '2nd Semester', 'year_level' => '4th Year'],
//            ['course_code' => 'COSC 200B', 'course_name' => 'Undergraduate Thesis II', 'lecture_units' => 3, 'lecture_hours' => 1, 'semester' => '2nd Semester', 'year_level' => '4th Year', 'lab_units' => 0, 'lab_hours' => 0]

        ]);
    }
}
