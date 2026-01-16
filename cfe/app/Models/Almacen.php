<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'ubicacion_id'];

    protected $casts = [
        'protegida' => 'boolean',
    ];


    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class);
    }

    public function materials()
    {
        return $this->belongsToMany(Material::class)->withPivot('cantidad');
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class );
    }

    public function ingresos()
    {
        return $this->hasMany(Ingreso::class );
    }

    protected static function booted()
    {
        // Prevenir eliminación de almacenes protegidos
        static::deleting(function ($almacen) {
            if ($almacen->protegida) {
                throw new \Exception('No se puede eliminar un almacén protegido del sistema.');
            }
        });

        // Prevenir actualización del campo 'protegida' en almacenes protegidos
        static::updating(function ($almacen) {
            if ($almacen->isDirty('protegida') && $almacen->getOriginal('protegida')) {
                throw new \Exception('No se puede cambiar el estado de protección de un almacén protegido.');
            }
        });
    }

    /**
     * Verificar si el almacén está protegido
     */
    public function estaProtegido(): bool
    {
        return (bool) $this->protegida;
    }

    /**
     * Obtener nombre formateado
     */
    public function getNombreFormateadoAttribute(): string
    {
        return ucwords(strtolower($this->nombre));
    }

    /**
     * Obtener nombre completo con ubicación
     */
    public function getNombreCompletoAttribute(): string
    {
        if ($this->ubicacion) {
            return $this->nombre . ' (' . $this->ubicacion->ubicacion . ')';
        }
        return $this->nombre;
    }

    /**
     * Scope para almacenes no protegidos
     */
    public function scopeNoProtegidos($query)
    {
        return $query->where('protegida', false);
    }

    /**
     * Scope para almacenes protegidos
     */
    public function scopeProtegidos($query)
    {
        return $query->where('protegida', true);
    }

    /**
     * Scope para buscar por nombre
     */
    public function scopeBuscar($query, $search)
    {
        if ($search) {
            return $query->where('nombre', 'like', "%{$search}%")
                ->orWhereHas('ubicacion', function ($q) use ($search) {
                    $q->where('ubicacion', 'like', "%{$search}%");
                });
        }
        return $query;
    }

}
