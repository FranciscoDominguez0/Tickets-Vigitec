<?php

namespace App\Models;

use App\Traits\Tenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }

    public function getNombreCompletoAttribute(): string
    {
        $nombre = trim("{$this->firstname} {$this->lastname}");

        return $nombre !== '' ? $nombre : ($this->email ?? 'Usuario');
    }

    public function getInicialesAttribute(): string
    {
        $primera = mb_substr($this->firstname ?? '', 0, 1);
        $segunda = mb_substr($this->lastname ?? '', 0, 1);
        $iniciales = mb_strtoupper($primera.$segunda);

        return $iniciales !== '' ? $iniciales : mb_strtoupper(mb_substr($this->email ?? 'U', 0, 2));
    }

    public function esActivo(): bool
    {
        return $this->status === 'active' || $this->status == 1;
    }
}
