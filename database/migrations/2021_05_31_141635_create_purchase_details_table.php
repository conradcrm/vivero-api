<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_compra', function (Blueprint $table) {
            $table->bigIncrements('id_detallecompra');
            $table->integer('cantidad');
            $table->unsignedBigInteger('id_planta');
            $table->unsignedBigInteger('folio_compra');
            $table->foreign('id_planta')->references('id_planta')->on('plantas');
            $table->foreign('folio_compra')->references('folio_compra')->on('compras'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_compra');
    }
}
