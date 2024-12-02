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
        Schema::create('course_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('grade', [
                '1.00', '1.25', '1.50', '1.75',
                '2.00', '2.25', '2.50', '2.75',
                '3.00', '4.00', '5.00', 'INC', 'DRP'
            ]);
            $table->string('course_name');
            $table->timestamps();

//            A student cannot take a course many times and have many grades for it.
            $table->unique(['course_id', 'student_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_student');
    }
};
