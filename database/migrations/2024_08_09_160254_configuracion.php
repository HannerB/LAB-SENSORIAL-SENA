<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('configuracion', function (Blueprint $table) {
            $table->id('id_config');
            $table->integer('num_cabina');
            $table->unsignedBigInteger('producto_habilitado')->nullable();
            $table->string('clave_acceso', 250);

            $table->foreign('producto_habilitado')->references('id_producto')->on('productos')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('configuracion');
    }
};
