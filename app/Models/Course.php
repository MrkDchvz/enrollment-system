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
        'semester',
        'year_level'
    ];

    public function schedules(): HasMany {
        return $this->hasMany(Schedule::class);
    }

    public function students(): belongsToMany {
        return $this->belongsToMany(Student::class)->with(['grade']);
    }
}
