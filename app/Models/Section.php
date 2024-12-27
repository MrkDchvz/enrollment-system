<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    protected $fillable = [
        'department_id',
        'year_level',
        'class_number',
        'school_year'
    ];

    public function department() : BelongsTo {
        return $this->belongsTo(Department::class);
    }

    public function enrollments() : HasMany {
        return $this->hasMany(Enrollment::class);
    }

    protected function sectionName() : Attribute
//    WARNING: this can result to duplicates because the school_year is not included
//    If there is a BSCS 3-1 in 2012 and BSCS 3-1 in 2024
//    Both of them will be displayed as "BSCS 3-1"
    {
        return Attribute::make(
            get: fn ($value, $attributes) => trim(
                "{$this->department->department_code} {$attributes['year_level']}-{$attributes['class_number']}"
            )
        );
    }

    protected function fullSectionName() : Attribute {
        return Attribute::make(
            get: fn ($value, $attributes) => trim(
                "{$this->department->department_code} {$attributes['year_level']}-{$attributes['class_number']} {$attributes['school_year']}"
            )
        );
    }
}
