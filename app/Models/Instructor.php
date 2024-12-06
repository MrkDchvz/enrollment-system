<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instructor extends Model
{
    /** @use HasFactory<\Database\Factories\InstructorFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'department_id',
        'email',
        'contact_number',
    ];

    public function department() : BelongsTo {
        return $this->belongsTo(Department::class);
    }



}
