<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'nombre',
        'clave',
        'marca',
        'descripcion',
        'estante',
        'categoria_id',
        'tipomaterial_id',
        'unidadmedida_id',
        'almacen_id'
    ];
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function tipomaterial()
    {
        return $this->belongsTo(TipoMaterial::class, 'tipomaterial_id');
    }

    public function unidadmedida()
    {
        return $this->belongsTo(UnidadMedida::class, 'unidadmedida_id');
    }
    public function ingresos()
    {
        return $this->hasMany(Ingreso::class);
    }
    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'almacen_id');
    }
    // En app/Models/Material.php
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
    public function prestamos()
    {
        return $this->hasMany(Prestamo::class);
    }
//    protected static function boot()
//    {
//        parent::boot();
//
//        static::creating(function ($material) {
//            // Generar la clave automáticamente
//            $material->clave = 'MAT-' . str_pad(Material::count() + 1, 5, '0', STR_PAD_LEFT);
//        });
//    }
//    protected static function boot()
//    {
//        parent::boot();
//
//        static::creating(function ($material) {
//            $material->clave = 'MAT-' . str_pad(Material::count() + 1, 5, '0', STR_PAD_LEFT);
//
//        });
//        static::addGlobalScope('withoutSoftDeletes', function ($builder) {
//            $builder->withoutGlobalScope('Illuminate\Database\Eloquent\SoftDeletingScope');
//        });
//    }

// En app/Models/Material.php
//    protected static function boot()
//    {
//        parent::boot();
//
//        static::creating(function ($model) {
//            if (self::withTrashed()->where('clave', $model->clave)->exists()) {
//                $originalClave = $model->clave;
//                $counter = 1;
//
//                while (self::withTrashed()->where('clave', $model->clave)->exists()) {
//                    $model->clave = $originalClave . '-' . $counter;
//                    $counter++;
//                }
//            }
//        });
//
//        static::restoring(function ($model) {
//            if (self::where('clave', $model->clave)->exists()) {
//                $originalClave = $model->clave;
//                $counter = 1;
//
//                while (self::withTrashed()->where('clave', $model->clave)->exists()) {
//                    $model->clave = preg_replace('/-\d+$/', '', $originalClave) . '-' . $counter;
//                    $counter++;
//                }
//            }
//        });
//    }
// En app/Models/Material.php


    public function ingresosEliminados()
    {
        return $this->hasMany(Ingreso::class)->onlyTrashed();
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Solo validar si la clave ha cambiado o es un nuevo registro
            if ($model->isDirty('clave') || !$model->exists) {
                $query = self::withTrashed()->where('clave', $model->clave);

                if ($model->exists) {
                    $query->where('id', '!=', $model->id);
                }

                if ($query->exists()) {
                    throw new \Illuminate\Database\Eloquent\ModelNotFoundException(
                        "La clave '{$model->clave}' ya está en uso por otro material"
                    );
                }
            }
        });
    }
}
