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
        'cod_muestra',
        'valor_sabor',
        'valor_olor',
        'valor_color',
        'valor_textura',
        'valor_apariencia',
        'es_diferente',
        'es_igual_referencia',
        'comentario',
        'fecha',
        'cabina'
    ];

    protected $casts = [
        'fecha' => 'date',
        'es_diferente' => 'boolean',
        'es_igual_referencia' => 'boolean',
        'valor_sabor' => 'integer',
        'valor_olor' => 'integer',
        'valor_color' => 'integer',
        'valor_textura' => 'integer',
        'valor_apariencia' => 'integer'
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

    // Método para obtener todos los valores de atributos como array
    public function getValoresAtributos()
    {
        return [
            'sabor' => $this->valor_sabor,
            'olor' => $this->valor_olor,
            'color' => $this->valor_color,
            'textura' => $this->valor_textura,
            'apariencia' => $this->valor_apariencia
        ];
    }

    // Método para verificar si un atributo específico fue evaluado
    public function tieneCalificacionAtributo($atributo)
    {
        $campo = 'valor_' . $atributo;
        return isset($this->$campo);
    }

    public function scopeTriangular($query)
    {
        return $query->where('prueba', 1);
    }

    public function scopeDuoTrio($query)
    {
        return $query->where('prueba', 2);
    }

    public function scopePorCabina($query, $cabina)
    {
        return $query->where('cabina', $cabina);
    }

    public function scopePorFecha($query, $fecha)
    {
        return $query->whereDate('fecha', $fecha);
    }
}
