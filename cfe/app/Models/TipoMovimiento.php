<?php
//
//namespace App\Models;
//
//use Illuminate\Database\Eloquent\Model;
//
///**
// * Class TipoMovimiento
// *
// * @property $id
// * @property $descripcion
// * @property $created_at
// * @property $updated_at
// *
// * @package App
// * @mixin \Illuminate\Database\Eloquent\Builder
// */
//class TipoMovimiento extends Model
//{
//
//    protected $perPage = 20;
//
//    /**
//     * The attributes that are mass assignable.
//     *
//     * @var array<int, string>
//     */
//    protected $fillable = ['descripcion'];
//    public function movimientos()
//    {
//        return $this->hasMany(Movimiento::class );
//    }
//
//
//}


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoMovimiento extends Model
{
    protected $table = 'tipo_movimientos';

    protected $fillable = [
        'descripcion',
        'requiere_usuario_asignado',
        'afecta_stock',
        'permite_devolucion'
    ];

    protected $casts = [
        'requiere_usuario_asignado' => 'boolean',
        'afecta_stock' => 'boolean',
        'permite_devolucion' => 'boolean'
    ];

    /**
     * Movimientos de este tipo
     */
    public function movimientos()
    {
        return $this->hasMany(Movimiento::class, 'tipo_movimiento_id');
    }

    /**
     * Tipos que permiten devoluciones
     */
    public function scopePermiteDevoluciones($query)
    {
        return $query->where('permite_devolucion', true);
    }

    /**
     * Tipos que requieren usuario asignado
     */
    public function scopeRequiereUsuarioAsignado($query)
    {
        return $query->where('requiere_usuario_asignado', true);
    }

    /**
     * Tipos que afectan el stock
     */
    public function scopeAfectaStock($query)
    {
        return $query->where('afecta_stock', true);
    }
}
