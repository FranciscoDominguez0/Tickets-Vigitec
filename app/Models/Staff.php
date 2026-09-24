<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\Tenantable;

class Staff extends Authenticatable
{
    use HasFactory, Notifiable, Tenantable;

    protected $table = 'staff';

    // Personalización de timestamps según la base de datos importada
    public const CREATED_AT = 'created';
    public const UPDATED_AT = 'updated';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'firstname',
        'lastname',
        'dept_id',
        'empresa_id',
        'role',
        'is_active',
        'last_login',
        'signature',
        'dark_mode',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'created' => 'datetime',
        'updated' => 'datetime',
        'last_login' => 'datetime',
        'is_active' => 'boolean',
        'dark_mode' => 'boolean',
    ];
}
