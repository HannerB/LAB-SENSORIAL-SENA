<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('resultados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto')->nullable();
            $table->tinyInteger('prueba')->comment('1=TRIANGULAR,2=DUO-TRIO,3=ORDENAMIENTO');
            $table->string('cod_muestra', 50)->nullable();
            $table->string('resultado', 50)->nullable();
            $table->date('fecha')->nullable();
            $table->integer('cabina');
            // Campo para almacenar qué atributo se está evaluando en ordenamiento
            $table->string('atributo_evaluado', 50)->nullable();

            $table->foreign('producto')->references('id_producto')->on('productos')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('resultados');
    }
};
