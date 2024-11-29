<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory;

    protected $fillable = [
        'student_number',
        'first_name',
        'last_name',
        'middle_name',
        'address',
        'contact_number',
        'email',
        'gender',
        'date_of_birth',
    ];

    public function enrollments(): HasMany {
        return $this->hasMany(Enrollment::class);
    }

}
