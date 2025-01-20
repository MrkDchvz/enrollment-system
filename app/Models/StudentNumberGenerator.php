<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class StudentNumberGenerator extends Model
{
    protected $table = 'student_number_generators';
    protected $fillable = ['iterator', 'school_year'];

    protected function studentNumber(): Attribute {
        return Attribute::make(
            get: function($value, $attributes) {
                $startYear = explode('-', $attributes['school_year'])[0];
                return sprintf('%d%05d', $startYear, $attributes['iteration']);
            }
        );
    }

}
