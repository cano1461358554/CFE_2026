<?php
////
////namespace App\Models;
////
////use Illuminate\Database\Eloquent\Model;
////
////class Devolucion extends Model
////{
////    protected $table = 'devolucions';
////
////    protected $fillable = [
////        'prestamo_id',
////        'cantidad_devuelta',
////        'fecha_devolucion',
////        'descripcion_estado'
////    ];
////
//////    protected $casts = [
//////        'fecha_devolucion' => 'date',
//////        'cantidad_devuelta' => 'decimal:2'
//////    ];
////
////    public function prestamo()
////    {
////        return $this->belongsTo(Prestamo::class);
////    }
////
////    public function movimiento()
////    {
////        return $this->belongsTo(Movimiento::class, 'movimiento_id');
////    }
////}
//
//
//namespace App\Models;
//
//use Illuminate\Database\Eloquent\Model;
//
//class Devolucion extends Model
//{
//    protected $table = 'devolucions';
//
//    protected $fillable = [
//        'prestamo_id',        // Para compatibilidad con sistema antiguo
//        'movimiento_id',      // Para nuevo sistema
//        'cantidad_devuelta',
//        'fecha_devolucion',
//        'descripcion_estado',
//        'almacen_id'          // Para saber a qué almacén se devolvió
//    ];
//
//    protected $casts = [
//        'fecha_devolucion' => 'date',
//        'cantidad_devuelta' => 'decimal:2'
//    ];
//
//    /**
//     * Relación con Prestamo (sistema antiguo)
//     */
//    public function prestamo()
//    {
//        return $this->belongsTo(Prestamo::class);
//    }
//
//    /**
//     * Relación con Movimiento (sistema nuevo)
//     */
//    public function movimiento()
//    {
//        return $this->belongsTo(Movimiento::class, 'movimiento_id');
//    }
//
//    /**
//     * Relación con Almacén donde se devolvió
//     */
//    public function almacen()
//    {
//        return $this->belongsTo(Almacen::class, 'almacen_id');
//    }
//
//    /**
//     * Accesor para obtener el origen (préstamo o movimiento)
//     */
//    public function getOrigenAttribute()
//    {
//        if ($this->prestamo_id) {
//            return [
//                'tipo' => 'prestamo',
//                'objeto' => $this->prestamo
//            ];
//        } elseif ($this->movimiento_id) {
//            return [
//                'tipo' => 'movimiento',
//                'objeto' => $this->movimiento
//            ];
//        }
//
//        return null;
//    }
//
//    /**
//     * Accesor para obtener el material
//     */
//    public function getMaterialAttribute()
//    {
//        if ($this->prestamo) {
//            return $this->prestamo->material;
//        } elseif ($this->movimiento) {
//            return $this->movimiento->material;
//        }
//
//        return null;
//    }
//
//    /**
//     * Scope para devoluciones de préstamos
//     */
//    public function scopeDePrestamos($query)
//    {
//        return $query->whereNotNull('prestamo_id');
//    }
//
//    /**
//     * Scope para devoluciones de movimientos
//     */
//    public function scopeDeMovimientos($query)
//    {
//        return $query->whereNotNull('movimiento_id');
//    }
//}
// App\Models\Devolucion.php - VERSIÓN SIMPLIFICADA
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devolucion extends Model
{
    protected $table = 'devolucions';

    // IMPORTANTE: Todos los campos que intentas guardar
    protected $fillable = [
//        'prestamo_id',
        'movimiento_id',
        'cantidad_devuelta',
        'fecha_devolucion',
        'descripcion_estado',
        'almacen_id'  // Asegúrate que este campo existe en la tabla
    ];

    // Opcional: Si prefieres permitir todos los campos
    // protected $guarded = [];

    protected $casts = [
        'fecha_devolucion' => 'date',
        'cantidad_devuelta' => 'decimal:2'
    ];

//    public function prestamo()
//    {
//        return $this->belongsTo(Prestamo::class);
//    }

    public function movimiento()
    {
        return $this->belongsTo(Movimiento::class, 'movimiento_id');
    }

    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'almacen_id');
    }
}
