<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    protected $table = 'calificaciones';
    protected $primaryKey = 'id_calificacion';

    protected $fillable = [
        'idpane',
        'producto',
        'prueba',
        'cod_muestras',
        'comentario',
        'fecha',
        'cabina',
        'atributo_evaluado'
    ];

    protected $casts = [
        'fecha' => 'date'
    ];

    public function panelista()
    {
        return $this->belongsTo(Panelista::class, 'idpane', 'idpane');
    }

    public function productoRelacion()
    {
        return $this->belongsTo(Producto::class, 'producto', 'id_producto');
    }

    public function scopeOrdenamiento($query)
    {
        return $query->where('prueba', 3);
    }

    public function scopePorAtributo($query, $atributo)
    {
        return $query->where('atributo_evaluado', $atributo);
    }

    public function getMuestrasOrdenArray()
    {
        return explode(',', $this->cod_muestras);
    }
}
