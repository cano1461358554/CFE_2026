<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnidadMedida extends Model
{
    protected $fillable = ['descripcion_unidad'];

    public function materials()
    {
        return $this->hasMany(Material::class, 'unidadmedida_id');
    }
    protected static function booted()
    {
        // Prevenir eliminación de registros protegidos
        static::deleting(function ($unidad) {
            if ($unidad->protegida) {
                // Lanzar excepción que previene la eliminación
                throw new \Exception('No se puede eliminar una unidad de medida protegida del sistema.');
            }
        });

        // Opcional: Prevenir actualización del campo 'protegida' en registros protegidos
//        static::updating(function ($unidad) {
//            if ($unidad->isDirty('protegida') && $unidad->getOriginal('protegida')) {
//                throw new \Exception('No se puede cambiar el estado de protección de una unidad protegida.');
//            }
//        });
    }

    /**
     * Verificar si la unidad está protegida
     */
    public function estaProtegida(): bool
    {
        return (bool) $this->protegida;
    }

}
