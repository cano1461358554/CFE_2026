<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Categoria
 *
 * @property $id
 * @property $nombre
 * @property $descripcion
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Categoria extends Model
{

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nombre', 'descripcion'];
    public function materials()
    {
        return $this->hasMany(Material::class );
    }
    protected $casts = [
        'protegida' => 'boolean',
    ];
    protected static function booted()
    {
        // Prevenir eliminación de categorías protegidas
        static::deleting(function ($categoria) {
            if ($categoria->protegida) {
                throw new \Exception('No se puede eliminar una categoría protegida del sistema.');
            }
        });

        // Opcional: Prevenir actualización del campo 'protegida'
        static::updating(function ($categoria) {
            if ($categoria->isDirty('protegida') && $categoria->getOriginal('protegida')) {
                throw new \Exception('No se puede cambiar el estado de protección de una categoría protegida.');
            }
        });
    }
    public function estaProtegida(): bool
    {
        return (bool) $this->protegida;
    }
}
