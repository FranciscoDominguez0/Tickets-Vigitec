<?php

namespace App\Models;

use App\Traits\Tenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailAccount extends Model
{
    use HasFactory, Tenantable;

    protected $table = 'email_accounts';

    public const CREATED_AT = 'created';

    public const UPDATED_AT = 'updated';

    protected $guarded = [];

    protected $casts = [
        'is_default' => 'boolean',
        'created' => 'datetime',
        'updated' => 'datetime',
    ];

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }
}

