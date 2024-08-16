<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Muestra extends Model
{
    protected $table = 'muestras';
    protected $primaryKey = 'id_muestras';
    protected $fillable = ['cod_muestra', 'producto_id', 'prueba', 'atributo'];
    public $timestamps = false; // Desactivar timestamps

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'id_producto');
    }   
}
