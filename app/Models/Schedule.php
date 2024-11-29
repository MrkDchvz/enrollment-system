<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Schedule extends Model
{
    /** @use HasFactory<\Database\Factories\ScheduleFactory> */
    use HasFactory;
    protected $fillable = [
        'course_code',
        'course_id',
        'instructor_id',
        'start_time',
        'end_time',
        'day',
        'room',
    ];

    public function instructor() : BelongsTo {
        return $this->belongsTo(Instructor::class);
    }

    public function course() : BelongsTo {
        return $this->belongsTo(Course::class);
    }

    public function enrollments() : BelongsToMany {
        return $this->belongsToMany(Enrollment::class);
    }
}
