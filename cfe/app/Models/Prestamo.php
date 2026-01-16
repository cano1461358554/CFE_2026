<?php
//
//namespace App\Models;
//
//use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
//
//class Prestamo extends Model
//{
//    protected $perPage = 20;
//    protected $table = 'prestamos';
//    use SoftDeletes;
//    protected $dates = ['deleted_at'];
//
//    protected $fillable = [
//        'fecha_prestamo',
//        'cantidad_prestada',
//        'material_id',
//        'user_id',
//        'descripcion'
//    ];
//    public function user()
//    {
//        return $this->belongsTo(User::class);
//    }
////    public function material()
////{
////    return $this->belongsTo(Material::class, 'material_id');
////}
//    public function material()
//    {
//        return $this->belongsTo(Material::class)->withTrashed();
//    }
//
//    public function devolucions()
//    {
//        return $this->hasMany(Devolucion::class);
//    }
//
//
//
//
//
////    public function user()
////    {
////        return $this->belongsTo(User::class);
////    }
//
////    public function resguardo()
////    {
////        return $this->hasOne(Resguardo::class);
////    }
////
////    public function getCantidadPendienteAttribute()
////    {
////        return $this->cantidad_prestada - $this->devolucions->sum('cantidad_devuelta');
////    }
////
////    public function getCompletamenteDevueltoAttribute()
////    {
////        return $this->cantidad_pendiente <= 0;
////    }
//
//
//
//}


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prestamo extends Model
{
    use SoftDeletes;

    protected $perPage = 20;
    protected $table = 'prestamos';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'fecha_prestamo',
        'cantidad_prestada',
        'material_id',
        'user_id',
        'descripcion',
        'movimiento_id' // Para relacionar con el nuevo sistema
    ];

    /**
     * Usuario asignado (empleado)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Material relacionado (con soft deletes)
     */
    public function material()
    {
        return $this->belongsTo(Material::class)->withTrashed();
    }

    /**
     * Devoluciones asociadas
     */
    public function devolucions()
    {
        return $this->hasMany(Devolucion::class);
    }

    /**
     * Movimiento relacionado (nuevo sistema)
     */
    public function movimiento()
    {
        return $this->belongsTo(Movimiento::class, 'movimiento_id');
    }

    /**
     * ===== ACCESORES =====
     */

    /**
     * Cantidad total devuelta
     */
    public function getCantidadDevueltaAttribute()
    {
        return $this->devolucions->sum('cantidad_devuelta');
    }

    /**
     * Cantidad pendiente por devolver
     */
    public function getCantidadPendienteAttribute()
    {
        return $this->cantidad_prestada - $this->cantidad_devuelta;
    }

    /**
     * Verificar si está completamente devuelto
     */
    public function getCompletamenteDevueltoAttribute()
    {
        return $this->cantidad_pendiente <= 0;
    }

    /**
     * ===== MÉTODOS DE AYUDA =====
     */

    /**
     * Crear un movimiento equivalente en el nuevo sistema
     */
    public function crearMovimientoEquivalente()
    {
        if ($this->movimiento_id) {
            return $this->movimiento; // Ya existe
        }

        $tipoPrestamo = TipoMovimiento::where('descripcion', 'Préstamo')->first();

        if (!$tipoPrestamo) {
            throw new \Exception('Tipo de movimiento "Préstamo" no encontrado');
        }

        $movimiento = Movimiento::create([
            'tipo_movimiento_id' => $tipoPrestamo->id,
            'material_id' => $this->material_id,
            'cantidad' => $this->cantidad_prestada,
            'fecha' => $this->fecha_prestamo,
            'user_id' => $this->user_id, // Usuario que creó el préstamo
            'usuario_asignado_id' => $this->user_id, // Usuario asignado
            'notas' => $this->descripcion . ' (Migrado desde sistema de préstamos)',
            'es_sin_retorno' => false,
            'estado' => $this->completamente_devuelto ? 'devuelto' : 'activo',
        ]);

        // Actualizar el préstamo con la referencia al movimiento
        $this->update(['movimiento_id' => $movimiento->id]);

        // Migrar devoluciones si existen
        if ($this->devolucions->count() > 0) {
            foreach ($this->devolucions as $devolucion) {
                $devolucion->update([
                    'movimiento_id' => $movimiento->id,
                    'prestamo_id' => $this->id // Mantener referencia al préstamo original
                ]);
            }
        }

        return $movimiento;
    }

    /**
     * ===== SCOPES =====
     */

    /**
     * Préstamos con devoluciones pendientes
     */
    public function scopeConDevolucionesPendientes($query)
    {
        return $query->whereHas('devolucions', function ($q) {
            $q->selectRaw('SUM(cantidad_devuelta) as total_devuelto')
                ->havingRaw('SUM(cantidad_devuelta) < prestamos.cantidad_prestada');
        })->orWhereDoesntHave('devolucions');
    }

    /**
     * Préstamos completamente devueltos
     */
    public function scopeCompletamenteDevueltos($query)
    {
        return $query->whereHas('devolucions', function ($q) {
            $q->selectRaw('SUM(cantidad_devuelta) as total_devuelto')
                ->havingRaw('SUM(cantidad_devuelta) >= prestamos.cantidad_prestada');
        });
    }

    /**
     * Préstamos sin movimiento asociado
     */
    public function scopeSinMovimiento($query)
    {
        return $query->whereNull('movimiento_id');
    }

    /**
     * ===== EVENTOS DEL MODELO =====
     */

    protected static function boot()
    {
        parent::boot();

        // Al eliminar un préstamo, no eliminar el movimiento asociado
        static::deleting(function ($model) {
            // Si el préstamo tiene un movimiento asociado, solo eliminamos la relación
            if ($model->movimiento) {
                $model->movimiento()->dissociate();
            }
        });
    }
}
