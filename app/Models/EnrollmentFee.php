<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnrollmentFee extends Model
{
    protected $table = 'enrollment_fee';
    protected $fillable = ['course_id', 'fee_id',];

    public function enrollment(): BelongsTo {
        return $this->belongsTo(Enrollment::class);
    }

    public function course(): BelongsTo {
        return $this->belongsTo(Fee::class);
    }
}
