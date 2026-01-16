<?php
// app/Models/Ingreso.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // AGREGAR

class Ingreso extends Model
{
    use SoftDeletes; // AGREGAR

    protected $perPage = 20;

    protected $fillable = [
        'material_id',
        'user_id',
        'cantidad_ingresada',
        'fecha',
        'nota' // AGREGAR ESTE CAMPO PARA REGISTROS
    ];

    protected $dates = [
        'fecha',
        'created_at',
        'updated_at',
        'deleted_at' // AGREGAR
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function almacen()
    {
        return $this->belongsTo(Almacen::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

//    public function stock()
//    {
//        return $this->hasOne(Stock::class, 'material_id', 'material_id');
//    }
    public function stock()
    {
        return $this->belongsTo(Stock::class)->withTrashed();
    }
    // Método para verificar si está eliminado
    public function isTrashed()
    {
        return $this->deleted_at !== null;
    }

}
//<?php
//namespace App\Models;
//
//use Illuminate\Database\Eloquent\Model;
//
//class Ingreso extends Model
//{
//    protected $perPage = 20;
//
//    protected $fillable = [
//        'material_id',
//        'user_id',
//        'cantidad_ingresada',
//        'fecha',
//        'almacen_id'
//    ];
//    protected $dates = [
//        'fecha',
//        'created_at',
//        'updated_at'
//    ];
//
//// O mejor aún en versiones más recientes de Laravel:
//    protected $casts = [
//        'fecha' => 'datetime',
//    ];
//    public function material()
//    {
//        return $this->belongsTo(Material::class);
//    }
//
//    public function almacen()
//    {
//        return $this->belongsTo(Almacen::class);
//    }
//
//    public function user()
//    {
//        return $this->belongsTo(user::class);
//    }
//    public function stock()
//    {
//        return $this->hasOne(Stock::class);
//    }
//}
