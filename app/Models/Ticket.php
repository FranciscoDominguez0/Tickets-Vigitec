<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function thread()
    {
        return $this->hasOne(Thread::class);
    }
}
