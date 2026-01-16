<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    use HasFactory;

    protected $fillable = ['ubicacion'];

    protected $casts = [
        'protegida' => 'boolean',
    ];

    public function almacenes()
    {
        return $this->hasMany(Almacen::class);
    }

    protected static function booted()
    {
        // Prevenir eliminación de ubicaciones protegidas
        static::deleting(function ($ubicacion) {
            if ($ubicacion->protegida) {
                throw new \Exception('No se puede eliminar una ubicación protegida del sistema.');
            }
        });

        // Prevenir actualización del campo 'protegida' en ubicaciones protegidas
        static::updating(function ($ubicacion) {
            if ($ubicacion->isDirty('protegida') && $ubicacion->getOriginal('protegida')) {
                throw new \Exception('No se puede cambiar el estado de protección de una ubicación protegida.');
            }
        });
    }

    /**
     * Verificar si la ubicación está protegida
     */
    public function estaProtegida(): bool
    {
        return (bool) $this->protegida;
    }

    /**
     * Obtener ubicación formateada
     */
    public function getUbicacionFormateadaAttribute(): string
    {
        return ucwords(strtolower($this->ubicacion));
    }

    /**
     * Scope para ubicaciones no protegidas
     */
    public function scopeNoProtegidas($query)
    {
        return $query->where('protegida', false);
    }

    /**
     * Scope para ubicaciones protegidas
     */
    public function scopeProtegidas($query)
    {
        return $query->where('protegida', true);
    }
}




