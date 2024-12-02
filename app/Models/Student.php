<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Random\RandomException;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'student_number',
        'first_name',
        'last_name',
        'middle_name',
        'address',
        'contact_number',
        'email',
        'gender',
        'date_of_birth',
    ];


    /**
     * @throws RandomException
     */
    public static function generateUniqueStudentNumber() : string {
//        Prevents generating a student number that is already on the database
        do {
            $currentYear = date('Y');
            $studentNumber =  $currentYear . random_int(100000, 999999);

        } while (DB::table('students')->where('student_number', $studentNumber)->exists());

        return $studentNumber;
    }



    public function enrollments(): HasMany {
        return $this->hasMany(Enrollment::class);
    }

    public function courses(): BelongsToMany {
        return $this->belongsToMany(Course::class)->withPivot(['grade']);
    }

    public function courseStudents(): HasMany {
        return $this->hasMany(CourseStudent::class);
    }


}
