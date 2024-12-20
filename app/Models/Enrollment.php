<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enrollment extends Model
{
    /** @use HasFactory<\Database\Factories\EnrollmentFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'department_id',
        'student_id',
        'section_id',
        'registration_status',
        'old_new_student',
        'year_level',
        'semester',
        'school_year',
        'enrollment_date',
        'scholarship'
    ];

    public function student(): BelongsTo {
        return $this->belongsTo(Student::class)->withTrashed();
    }

    public function department() : BelongsTo {
        return $this->belongsTo(Department::class);
    }

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function fees () : BelongsToMany {
        return $this->belongsToMany(Fee::class);
    }

    public function section () : BelongsTo {
        return $this->belongsTo(Section::class);
    }

    public function courses() : BelongsToMany {
        return $this->belongsToMany(Course::class);
    }

    public function courseEnrollments(): HasMany {
        return $this->hasMany(CourseEnrollment::class);
    }


}
