<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuracion';
    protected $primaryKey = 'id_config';
    protected $fillable = ['num_cabina', 'producto_habilitado', 'clave_acceso'];

    public $timestamps = false; // Desactivar timestamps

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_habilitado', 'id_producto');
    }
}
