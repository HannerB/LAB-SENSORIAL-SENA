<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resultado extends Model
{
    protected $table = 'resultados';
    public $timestamps = false;

    protected $fillable = [
        'producto',
        'prueba',
        'cod_muestra',
        'resultado',
        'fecha',
        'cabina',
        'atributo_evaluado'
    ];

    protected $casts = [
        'fecha' => 'date'
    ];

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
}
