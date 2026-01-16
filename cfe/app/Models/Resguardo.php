<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Resguardo
 *
 * @property $id
 * @property $fecha_resguardo
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Resguardo extends Model
{

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fecha_resguardo',
        'estado',
//        'prestamo_id',
//        'material_id',
        'almacen_id',
    ];
    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class );
    }
//    public function material()
//    {
//        return $this->belongsTo(Material::class);
//    }
//
//    public function almacen()
//    {
//        return $this->belongsTo(Almacen::class);
//    }
    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }


}
