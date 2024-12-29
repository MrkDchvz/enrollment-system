<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnrollmentFee extends Model
{
    protected $table = 'enrollment_fee';
    protected $fillable = ['course_id', 'fee_id', 'amount'];

    public function enrollment(): BelongsTo {
        return $this->belongsTo(Enrollment::class);
    }



    public function fee(): BelongsTo {
        return $this->belongsTo(Fee::class);
    }
}
