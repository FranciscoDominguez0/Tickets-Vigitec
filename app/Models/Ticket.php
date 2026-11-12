<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'created' => 'datetime',
        'updated' => 'datetime',
    ];

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

    public function helpTopic()
    {
        return $this->belongsTo(HelpTopic::class, 'topic_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Determina si el ticket se encuentra en estado cerrado o resuelto.
     */
    public function estaCerrado(): bool
    {
        if (! is_null($this->closed)) {
            return true;
        }

        $nombreEstado = strtolower((string) optional($this->status)->name);

        return in_array($nombreEstado, ['cerrado', 'resuelto'], true);
    }
}
