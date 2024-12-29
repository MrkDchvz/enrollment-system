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
        Schema::create('course_enrollment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('course_name');
            $table->integer('lecture_units');
            $table->integer('lab_units');
            $table->integer('lecture_hours');
            $table->integer('lab_hours');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_enrollment');
    }
};
