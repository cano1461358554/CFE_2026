<?php

namespace App\Models;
//
//use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
//

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // ← Esto debe estar
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['material_id', 'almacen_id', 'cantidad'];

//    public function material()
//    {
//        return $this->belongsTo(Material::class);
//    }
// En tu modelo Stock.php

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class)->withTrashed();
    }
    public function almacen()
    {
        return $this->belongsTo(Almacen::class);
    }

    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }
    public function ingresos()
    {
        return $this->hasMany(Ingreso::class)->withTrashed();
    }
    /**
     * Scope para obtener solo registros eliminados.
     */
    public function scopeTrashed($query)
    {
        return $query->whereNotNull('deleted_at');
    }

    /**
     * Verificar si el stock está eliminado.
     */
    public function isTrashed(): bool
    {
        return $this->deleted_at !== null;
    }

}
