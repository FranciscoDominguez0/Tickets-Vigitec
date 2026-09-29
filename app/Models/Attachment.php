<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $table = 'attachments';
    public $timestamps = false; // Maneja 'created' en lugar de created_at/updated_at

    protected $fillable = [
        'thread_entry_id',
        'empresa_id',
        'filename',
        'original_filename',
        'mimetype',
        'size',
        'path',
        'hash',
        'created',
    ];

    public function entry()
    {
        return $this->belongsTo(ThreadEntry::class, 'thread_entry_id');
    }
}
