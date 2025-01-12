<?php

use Carbon\Carbon;
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
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('department_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('section_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('year_level', ["1st Year","2nd Year","3rd Year","4th Year"])->default("1st Year");
            $table->string('school_year')->default(static::getCurrentSchoolYear());
            $table->enum('registration_status', ['REGULAR', 'IRREGULAR'])->default("REGULAR");
            $table->enum('old_new_student',['Old Student', 'New Student'])->default("Old Student");
            $table->enum('semester', ['1st Semester', '2nd Semester'])->default(self::getCurrentSemester());
            $table->string('scholarship')->nullable();
            $table->date('enrollment_date')->default(Carbon::now());
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public static function getCurrentSemester() : string {
        $currentMonth = Carbon::now()->month;
        // 1st Semester is around September to February
        if ($currentMonth >= 9 || $currentMonth <= 2) {
            return "1st Semester";
            // 2nd Semester is around March to June
        } else {
            return "2nd Semester";
        }
    }

    public static function getCurrentSchoolYear() : string {
        // Current Date
        $date = Carbon::now();
        // Current Year $ Month
        $year = $date->year;
        $month = $date->month;
        // Set a new school year if the enrollment is in around august
        // If the year is 2024 and the student enrolled around august 2024
        // Then the school year will be 2024 - 2024
        if ($month >= 8) {
            $startYear = $date->year;
            $endYear = $date->year + 1;
        }
        // Retain the current school year if the enrollment is around february
        // If the year is 2024 and the student enrolled around february 2024
        // Then the school year is 2023-2024
        else {
            $startYear = $date->year - 1;
            $endYear = $date->year;
        }
        return trim(
            sprintf('%s-%s', $startYear, $endYear)
        );
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
