<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    //

    protected  $fillable = [
        "course_code",
        "course_name",
        'lecture_units',
        'lab_units',
        'lecture_hours',
        'lab_hours',
    ];


    public function students(): belongsToMany {
        return $this->belongsToMany(Student::class)->with(['grade']);
    }

    public function enrollment(): belongsToMany {
        return $this->belongsToMany(Enrollment::class);
    }

    public function populators() : HasMany {
        return $this->hasMany(Populator::class);
    }

    public function courseEnrollments(): HasMany {
        return $this->hasMany(CourseEnrollment::class);
    }


}
