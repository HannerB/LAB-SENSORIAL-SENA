<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    protected $table = 'calificaciones';
    protected $primaryKey = 'id_calificacion';
    protected $fillable = ['idpane', 'producto', 'prueba', 'atributo', 'cod_muestras', 'comentario', 'fecha', 'cabina'];

    public function panelista()
    {
        return $this->belongsTo(Panelista::class, 'idpane', 'idpane');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto', 'id_producto');
    }
}

