<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $table = 'organizations';

    public const CREATED_AT = 'created';

    public const UPDATED_AT = 'updated';

    protected $fillable = [
        'name',
        'empresa_id',
        'address',
        'phone',
        'phone_ext',
        'website',
        'notes',
        'plain_text_emails',
    ];

    protected $casts = [
        'created' => 'datetime',
        'updated' => 'datetime',
        'plain_text_emails' => 'boolean',
    ];

    public function usuarios()
    {
        return $this->hasMany(User::class, 'organization_id');
    }
}
