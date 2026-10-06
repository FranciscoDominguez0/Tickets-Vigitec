<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketReport extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function items()
    {
        return $this->hasMany(TicketReportItem::class, 'report_id');
    }
}
