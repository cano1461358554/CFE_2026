<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoMaterial extends Model
{
    protected $fillable = ['descripcion'];

    protected $casts = [
        'protegida' => 'boolean',
    ];

    public function materials()
    {
        return $this->hasMany(Material::class, 'tipomaterial_id');
    }
    protected static function booted()
    {
        // Prevenir eliminación de tipos protegidos
        static::deleting(function ($tipo) {
            if ($tipo->protegida) {
                throw new \Exception('No se puede eliminar un tipo de material protegido del sistema.');
            }
        });

        // Opcional: Prevenir actualización del campo 'protegida'
        static::updating(function ($tipo) {
            if ($tipo->isDirty('protegida') && $tipo->getOriginal('protegida')) {
                throw new \Exception('No se puede cambiar el estado de protección de un tipo de material protegido.');
            }
        });
    }

    /**
     * Verificar si el tipo está protegido
     */
    public function estaProtegido(): bool
    {
        return (bool) $this->protegida;
    }

    /**
     * Obtener descripción formateada
     */
    public function getDescripcionFormateadaAttribute(): string
    {
        return ucfirst(strtolower($this->descripcion));
    }
}
