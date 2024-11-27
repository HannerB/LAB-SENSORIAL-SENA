<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Muestra extends Model
{
    protected $table = 'muestras';
    protected $primaryKey = 'id_muestras';
    public $timestamps = false;

    protected $fillable = [
        'cod_muestra',
        'producto_id',
        'prueba',
        'tiene_sabor',
        'tiene_olor',
        'tiene_color',
        'tiene_textura',
        'tiene_apariencia'
    ];

    protected $casts = [
        'tiene_sabor' => 'boolean',
        'tiene_olor' => 'boolean',
        'tiene_color' => 'boolean',
        'tiene_textura' => 'boolean',
        'tiene_apariencia' => 'boolean'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'id_producto');
    }

    public function getAtributosSeleccionadosAttribute()
    {
        $atributos = [];
        if ($this->tiene_sabor) $atributos[] = 'sabor';
        if ($this->tiene_olor) $atributos[] = 'olor';
        if ($this->tiene_color) $atributos[] = 'color';
        if ($this->tiene_textura) $atributos[] = 'textura';
        if ($this->tiene_apariencia) $atributos[] = 'apariencia';
        return $atributos;
    }
}
