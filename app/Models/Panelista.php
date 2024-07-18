<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panelista extends Model
{
    protected $table = 'panelistas';
    protected $primaryKey = 'idpane';
    protected $fillable = ['nombres'];
}
