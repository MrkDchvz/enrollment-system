<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Enrollment extends Model
{
    /** @use HasFactory<\Database\Factories\EnrollmentFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'student_id',
        'registration_status',
        'old_new_student',
        'year_level',
        'semester',
        'school_year',
        'enrollment_date'
    ];

    public function student(): BelongsTo {
        return $this->belongsTo(Student::class);
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
    public function schedule() : BelongsToMany {
        return $this->belongsToMany(Schedule::class);
    }
}