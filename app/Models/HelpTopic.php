<?php

namespace App\Models;

use App\Traits\Tenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}

