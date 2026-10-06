<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    public function entries()
    {
        return $this->hasMany(ThreadEntry::class);
    }
}
