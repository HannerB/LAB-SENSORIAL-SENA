<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('calificaciones', function (Blueprint $table) {
            $table->bigIncrements('id_calificacion');
            $table->bigInteger('idpane')->nullable();
            $table->bigInteger('producto')->nullable(false);
            $table->tinyInteger('prueba')->nullable(false)->comment('1=TRIANGULAR,2=DUO-TRIO,3=ORDENAMIENTO');
            $table->string('atributo', 50)->nullable(false);
            $table->string('cod_muestras', 250)->nullable(false);
            $table->string('comentario', 250)->nullable();
            $table->date('fecha')->nullable();
            $table->integer('cabina')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calificaciones');
    }
};
