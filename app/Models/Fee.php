<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Fee extends Model
{
    protected $fillable = ['name', 'amount'];

    public function enrollment () : BelongsToMany {
        return $this->belongsToMany(Enrollment::class);
    }

}
