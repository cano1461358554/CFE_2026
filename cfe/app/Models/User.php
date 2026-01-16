<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasRoles;
        use Notifiable;

    protected $perPage = 20;

    protected $fillable = ['nombre', 'apellido', 'RP', 'tipo_usuario', 'password'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

      public function ingresos()
    {
        return $this->hasMany(\App\Models\Ingreso::class, 'user_id', 'id');
    }

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class);
    }

     public function profile()
    {
        return $this->hasOne(\App\Models\Profile::class, 'user_id', 'id');
    }

     public function role(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($role)
    {
        return $this->roles->contains('name', $role);
    }

    public function getRoleNamesAttribute()
   {
        return $this->roles->pluck('name') ?? [$this->tipo_usuario];
  }
}
