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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->nullOnDelete()->nullOnUpdate();
            $table->foreignId('department_id')->constrained()->nullOnDelete()->nullOnUpdate();
            $table->foreignId('student_id')->constrained()->nullOnDelete()->nullOnUpdate();
            $table->enum('registration_status', ['REGULAR', 'IRREGULAR']);
            $table->enum('old_new_student',['Old Student', 'New Student']);
            $table->enum('year_level', ['1st Year', '2nd Year', '3rd Year', '4th Year']);
            $table->enum('semester', ['1st Semester', '2nd Semester']);
            $table->string('scholarship')->nullable();
            $table->string('school_year');
            $table->date('enrollment_date');
            $table->enum('class_number', [1,2,3,4,5,6,7]);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('enrollments');

    }
};
