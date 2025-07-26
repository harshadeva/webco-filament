<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Typeable extends Model
{
    protected $fillable = [
        'typeable_id',
        'typeable_type',
    ];

    public function typeable(): MorphTo
    {
        return $this->morphTo();
    }
}
