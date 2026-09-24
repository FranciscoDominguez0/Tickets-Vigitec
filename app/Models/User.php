<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\Tenantable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Tenantable;

    protected $table = 'users';

    // Personalización de timestamps según la base de datos importada
    public const CREATED_AT = 'created';
    public const UPDATED_AT = 'updated';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'firstname',
        'lastname',
        'phone',
        'company',
        'empresa_id',
        'status',
        'last_login',
        'address',
        'latitude',
        'longitude',
        'dark_mode',
        'org_tickets_view',
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
        'dark_mode' => 'boolean',
        'org_tickets_view' => 'boolean',
    ];
}
