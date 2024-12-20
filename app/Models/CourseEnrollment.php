<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseEnrollment extends Model
{
    protected $table = 'course_enrollment';
    protected $fillable = ['course_id', 'enrollment_id',];

    public function enrollment(): BelongsTo {
        return $this->belongsTo(Enrollment::class);
    }

    public function course(): BelongsTo {
        return $this->belongsTo(Course::class);
    }


}
