<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Muestra extends Model
{
    protected $table = 'muestras';
    protected $primaryKey = 'id_muestras';
    protected $fillable = ['cod_muestra', 'id_producto', 'prueba', 'atributo'];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
}
