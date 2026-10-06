<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketReportItem extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;

    public function report()
    {
        return $this->belongsTo(TicketReport::class, 'report_id');
    }
}
