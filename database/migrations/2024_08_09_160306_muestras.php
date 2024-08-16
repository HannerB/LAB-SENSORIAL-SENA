    <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('muestras', function (Blueprint $table) {
            $table->id('id_muestras');
            $table->string('cod_muestra', 50);
            $table->unsignedBigInteger('producto_id')->nullable();
            $table->tinyInteger('prueba')->comment('1=TRIANGULAR,2=DUO-TRIO,3=ORDENAMIENTO');
            $table->string('atributo', 250)->nullable();
        
            $table->foreign('producto_id')->references('id_producto')->on('productos')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('muestras');
    }
};
