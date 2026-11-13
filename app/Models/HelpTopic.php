<?php

namespace App\Models;

use App\Traits\Tenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HelpTopic extends Model
{
    use HasFactory, Tenantable;

    protected $table = 'help_topics';

    public const CREATED_AT = 'created';

    public const UPDATED_AT = null;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'is_public' => 'boolean',
        'created' => 'datetime',
    ];

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'topic_id');
    }

    public function totalTickets(): int
    {
        return Ticket::where('topic_id', $this->id)->count();
    }

    public function estaEnUso(): bool
    {
        return Ticket::where('topic_id', $this->id)->exists();
    }
}
