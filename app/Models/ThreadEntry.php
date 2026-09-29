<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThreadEntry extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'thread_entry_id');
    }
}
