<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Populator extends Model
{
    protected  $fillable = [
        "course_id",
        'semester',
        'year_level',
        'program'
    ];

    public function course(): BelongsTo {
        return $this->belongsTo(Course::class);
    }
}
