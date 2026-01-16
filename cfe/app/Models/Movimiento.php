<?php
//
//namespace App\Models;
//
//use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
//
//class Movimiento extends Model
//{
//    use SoftDeletes;
//
//    protected $perPage = 20;
//
//    /**
//     * The attributes that are mass assignable.
//     */
//    protected $fillable = [
//        'cantidad',
//        'fecha',
//        'material_id',
//        'tipo_movimiento_id',
//        'user_id',
//        'usuario_asignado_id',
//        'estado',
//        'es_sin_retorno',
//        'condiciones',
//        'notas',
//        'fecha_devolucion_estimada',
//        'fecha_devolucion_real'
//    ];
//
//    /**
//     * The attributes that should be cast.
//     */
//    protected $casts = [
//        'fecha' => 'date',
//        'fecha_devolucion_estimada' => 'date',
//        'fecha_devolucion_real' => 'date',
//        'es_sin_retorno' => 'boolean',
//        'created_at' => 'datetime',
//        'updated_at' => 'datetime',
//        'deleted_at' => 'datetime',
//    ];
//
//    /**
//     * RELACIONES
//     */
//    public function material()
//    {
//        return $this->belongsTo(Material::class);
//    }
//
//    public function tipoMovimiento()
//    {
//        return $this->belongsTo(TipoMovimiento::class, 'tipo_movimiento_id');
//    }
//
//    public function usuarioRegistro()
//    {
//        return $this->belongsTo(User::class, 'user_id');
//    }
//
//    public function usuarioAsignado()
//    {
//        return $this->belongsTo(User::class, 'usuario_asignado_id');
//    }
//
//    public function prestamo()
//    {
//        return $this->hasOne(Prestamo::class);
//    }
//
//    /**
//     * SCOPES
//     */
//    public function scopeActivos($query)
//    {
//        return $query->where('estado', 'activo');
//    }
//
//    public function scopeResguardos($query)
//    {
//        return $query->whereHas('tipoMovimiento', function($q) {
//            $q->where('descripcion', 'Resguardo');
//        });
//    }
//
//    public function scopePrestamos($query)
//    {
//        return $query->whereHas('tipoMovimiento', function($q) {
//            $q->where('descripcion', 'Préstamo');
//        });
//    }
//
//    public function scopeSinRetorno($query)
//    {
//        return $query->where('es_sin_retorno', true);
//    }
//
//    /**
//     * MÉTODOS DE AYUDA
//     */
//    public function esResguardo()
//    {
//        return $this->tipoMovimiento && $this->tipoMovimiento->descripcion === 'Resguardo';
//    }
//
//    public function esPrestamo()
//    {
//        return $this->tipoMovimiento && $this->tipoMovimiento->descripcion === 'Préstamo';
//    }
//
//    public function esSalidaSinRetorno()
//    {
//        return $this->es_sin_retorno ||
//            ($this->tipoMovimiento && $this->tipoMovimiento->descripcion === 'Salida Sin Retorno');
//    }
//
//    public function marcarComoDevuelto()
//    {
//        $this->estado = 'devuelto';
//        $this->fecha_devolucion_real = now();
//        $this->save();
//    }
//
//    public function marcarComoPerdido()
//    {
//        $this->estado = 'perdido';
//        $this->save();
//    }
//
//    public function devoluciones()
//    {
//        return $this->hasMany(Devolucion::class, 'movimiento_id');
//    }
//}
//
////
////namespace App\Models;
////
////use Illuminate\Database\Eloquent\Model;
////
/////**
//// * Class Movimiento
//// *
//// * @property $id
//// * @property $cantidad
//// * @property $fecha
//// * @property $created_at
//// * @property $updated_at
//// *
//// * @package App
//// * @mixin \Illuminate\Database\Eloquent\Builder
//// */
////class Movimiento extends Model
////{
////
////    protected $perPage = 20;
////
////    /**
////     * The attributes that are mass assignable.
////     *
////     * @var array<int, string>
////     */
////    protected $fillable = ['cantidad', 'fecha'];
////    public function material()
////    {
////        return $this->belongsTo(Material::class );
////    }
////
//////    public function tipoMovimiento()
//////    {
//////        return $this->belongsTo(TipoMovimiento::class );
//////    }
////
////    public function prestamo()
////    {
////        return $this->hasOne(Prestamo::class );
////    }
////
////
////}


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movimiento extends Model
{
    use SoftDeletes;

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'cantidad',
        'fecha',
        'material_id',
        'tipo_movimiento_id',
        'almacen_id',  // ¡IMPORTANTE! Este debe estar
        'user_id',              // Usuario que registra el movimiento
        'usuario_asignado_id',  // Usuario al que se asigna (empleado)
        'estado',
        'es_sin_retorno',
        'condiciones',
        'notas',
        'fecha_devolucion_estimada',
        'fecha_devolucion_real'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'fecha' => 'date',
        'fecha_devolucion_estimada' => 'date',
        'fecha_devolucion_real' => 'date',
        'es_sin_retorno' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * ===== RELACIONES PRINCIPALES =====
     */

    /**
     * Material relacionado
     */
    public function material()
    {
        return $this->belongsTo(Material::class)->withTrashed();
    }

    /**
     * Tipo de movimiento
     */
    public function TipoMovimiento()
    {
        return $this->belongsTo(TipoMovimiento::class, 'tipo_movimiento_id');
    }

    /**
     * Usuario que registra el movimiento
     */
    public function usuarioRegistro()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Usuario asignado (empleado que recibe)
     */
    public function usuarioAsignado()
    {
        return $this->belongsTo(User::class, 'usuario_asignado_id');
    }

    /**
     * ===== INTEGRACIÓN: RELACIONES CON SISTEMA ANTIGUO =====
     */

    /**
     * Relación con Prestamo (para migración y compatibilidad)
     */
    public function prestamo()
    {
        return $this->hasOne(Prestamo::class, 'movimiento_id');
    }

    /**
     * ===== NUEVAS RELACIONES =====
     */

    /**
     * Devoluciones asociadas a este movimiento
     */
//    public function devolucions(): HasMany
//    {
//        return $this->hasMany(Devolucion::class, 'movimiento_id');
//    }
    public function devolucions()
    {
        return $this->hasMany(Devolucion::class, 'movimiento_id');
    }

    /**
     * Stock relacionado (si existe)
     */
    public function stock()
    {
        return $this->hasOneThrough(
            Stock::class,
            Material::class,
            'id', // Foreign key on Material table
            'material_id', // Foreign key on Stock table
            'material_id', // Local key on Movimiento table
            'id' // Local key on Material table
        );
    }

    /**
     * ===== SCOPES =====
     */

    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    public function scopeResguardos($query)
    {
        return $query->whereHas('tipoMovimiento', function ($q) {
            $q->where('descripcion', 'Resguardo');
        });
    }

    public function scopePrestamos($query)
    {
        return $query->whereHas('tipoMovimiento', function ($q) {
            $q->where('descripcion', 'Préstamo');
        });
    }

    public function scopeSinRetorno($query)
    {
        return $query->where('es_sin_retorno', true);
    }

    public function scopeConDevolucionesPendientes($query)
    {
        return $query->whereHas('devoluciones', function ($q) {
            $q->selectRaw('SUM(cantidad_devuelta) as total_devuelto')
                ->havingRaw('SUM(cantidad_devuelta) < movimientos.cantidad');
        })->orWhereDoesntHave('devoluciones');
    }

    /**
     * ===== ACCESORES Y MUTADORES =====
     */

    /**
     * Cantidad total devuelta
     */
    /**
     * Cantidad total devuelta
     */
    public function getCantidadDevueltaAttribute()
    {
//        return $this->devolucions->sum('cantidad_devuelta');
        return $this->devolucions ? $this->devolucions->sum('cantidad_devuelta') : 0;
    }
    /**
     * Cantidad pendiente por devolver
     */
    public function getCantidadPendienteAttribute()
    {
        if ($this->esSalidaSinRetorno()) {
            return 0; // No hay devoluciones en salidas sin retorno
        }

        return $this->cantidad - $this->cantidad_devuelta;
    }

    /**
     * Verificar si está completamente devuelto
     */
    public function getCompletamenteDevueltoAttribute()
    {
        if ($this->esSalidaSinRetorno()) {
            return false;
        }

        return $this->cantidad_pendiente <= 0;
    }

    /**
     * ===== MÉTODOS DE AYUDA =====
     */

    public function esResguardo()
    {
        return $this->tipoMovimiento && $this->tipoMovimiento->descripcion === 'Resguardo';
    }

    public function esPrestamo()
    {
        return $this->tipoMovimiento && $this->tipoMovimiento->descripcion === 'Préstamo';
    }

    public function esSalidaSinRetorno()
    {
        return $this->es_sin_retorno ||
            ($this->tipoMovimiento && $this->tipoMovimiento->descripcion === 'Salida Sin Retorno');
    }

    /**
     * Marcar movimiento como devuelto (parcial o completo)
     */
    public function marcarComoDevuelto($cantidad = null)
    {
        if ($cantidad && ($this->cantidad_devuelta + $cantidad) >= $this->cantidad) {
            $this->estado = 'devuelto';
            $this->fecha_devolucion_real = now();
        }

        $this->save();
    }

    public function marcarComoPerdido()
    {
        $this->estado = 'perdido';
        $this->save();
    }

    /**
     * Verificar si puede recibir más devoluciones
     */
    public function puedeRecibirDevolucion()
    {
        return $this->estado === 'activo' &&
            !$this->esSalidaSinRetorno() &&
            $this->cantidad_pendiente > 0;
    }

    /**
     * Obtener el porcentaje devuelto
     */
    public function getPorcentajeDevueltoAttribute()
    {
        if ($this->cantidad == 0) {
            return 0;
        }

        return ($this->cantidad_devuelta / $this->cantidad) * 100;
    }

    /**
     * ===== EVENTOS DEL MODELO =====
     */

    protected static function boot()
    {
        parent::boot();

        // Al crear un movimiento, asegurar que el usuario asignado exista
        static::creating(function ($model) {
            if (empty($model->fecha)) {
                $model->fecha = now();
            }

            if ($model->esSalidaSinRetorno()) {
                $model->estado = 'perdido';
            } elseif (empty($model->estado)) {
                $model->estado = 'activo';
            }
        });

        // Al eliminar un movimiento, eliminar también las devoluciones asociadas
        static::deleting(function ($model) {
            if ($model->isForceDeleting()) {
                $model->devolucions()->forceDelete();
            } else {
                $model->devolucions()->delete();
            }
        });

        // Al restaurar un movimiento, restaurar también las devoluciones
        static::restoring(function ($model) {
            $model->devolucions()->withTrashed()->restore();
        });
    }
}
