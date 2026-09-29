<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketStatus extends Model
{
    public $timestamps = false;
    protected $table = 'ticket_status';
    protected $guarded = [];
}
