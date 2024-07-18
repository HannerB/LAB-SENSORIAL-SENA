<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resultado extends Model
{
    protected $table = 'resultados';
    protected $fillable = ['producto', 'prueba', 'atributo', 'cod_muestra', 'resultado', 'fecha', 'cabina'];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto', 'id_producto');
    }
}
