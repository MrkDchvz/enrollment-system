<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_code')->unique();
            $table->string('course_name');
            $table->integer('lecture_units');
            $table->integer('lab_units')->nullable();
            $table->integer('lecture_hours');
            $table->integer('lab_hours')->nullable();
            $table->enum('program' ,['BSCS','BSIT'])->nullable();
            $table->enum('semester',['1st Semester','2nd Semester'])->nullable();
            $table->enum('year_level',['1st Year','2nd Year', '3rd Year', '4th Year'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
