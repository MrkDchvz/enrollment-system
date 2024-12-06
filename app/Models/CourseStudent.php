<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseStudent extends Model
{
    protected $table = 'course_student';
    protected $fillable = ['grade', 'course_id', 'student_id', 'course_name', 'instructor_id'];

    public function course(): belongsTo {
        return $this->belongsTo(Course::class);
    }

    public function student(): belongsTo {
        return $this->belongsTo(Student::class);
    }
}
