<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'name',
        'enrollment_id',
        'reference',
        'method',
        'amount',
    ];
    public function enrollment(): BelongsTo {
        return $this->belongsTo(Enrollment::class);
    }
}
