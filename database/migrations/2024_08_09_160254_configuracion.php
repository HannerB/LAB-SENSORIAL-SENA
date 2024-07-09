<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('configuracions', function (Blueprint $table) {
            $table->id('id_config');
            $table->integer('num_cabina');
            $table->unsignedBigInteger('producto_habilitado')->nullable();
            $table->string('clave_acceso', 250);

            $table->foreign('producto_habilitado')->references('id_producto')->on('productos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracions');
    }
};
