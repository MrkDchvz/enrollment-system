<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Requirement extends Model
{
    protected $fillable = [
        'enrollment_id',
        'name',
        'image',
    ];

    public function enrollment() : BelongsTo {
        return $this->belongsTo(Enrollment::class);
    }
}
