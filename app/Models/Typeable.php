<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Typeable extends Model
{
    protected $fillable = [
        'typeable_id',
        'typeable_type',
    ];

    public function typeable()
    {
        return $this->morphTo();
    }
}
