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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class);
    }

    public function status()
    {
        return $this->belongsTo(TicketStatus::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function report()
    {
        return $this->hasOne(TicketReport::class);
    }
}
