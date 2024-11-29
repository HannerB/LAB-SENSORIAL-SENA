<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('calificaciones', function (Blueprint $table) {
            $table->id('id_calificacion');
            $table->unsignedBigInteger('idpane')->nullable();
            $table->unsignedBigInteger('producto');
            $table->tinyInteger('prueba')->comment('1=TRIANGULAR,2=DUO-TRIO,3=ORDENAMIENTO');
            $table->string('cod_muestra', 50);  // Cambiado de cod_muestras a cod_muestra

            // Valores de calificación para cada atributo
            $table->integer('valor_sabor')->nullable();
            $table->integer('valor_olor')->nullable();
            $table->integer('valor_color')->nullable();
            $table->integer('valor_textura')->nullable();
            $table->integer('valor_apariencia')->nullable();

            // Para pruebas triangular y duo-trio
            $table->boolean('es_diferente')->nullable();  // Para prueba triangular
            $table->boolean('es_igual_referencia')->nullable();  // Para prueba duo-trio

            $table->string('comentario', 250)->nullable();
            $table->date('fecha')->nullable();
            $table->integer('cabina');
            $table->timestamps();

            $table->foreign('idpane')->references('idpane')->on('panelistas')->onDelete('set null');
            $table->foreign('producto')->references('id_producto')->on('productos')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('calificaciones');
    }
};
