<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id_producto';
    protected $fillable = ['nombre'];
    public $timestamps = false; // Desactivar timestamps
}

