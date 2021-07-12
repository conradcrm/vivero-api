<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plantas', function (Blueprint $table) {
            $table->bigIncrements('id_planta');
            $table->string('nombre');
            $table->longText('descripcion');
            $table->double('precio_venta', 15, 2);
            $table->double('precio_compra', 15, 2);
            $table->longText('imagen')->nullable();
            $table->integer('cantidad')->default(0);
            $table->integer('estado')->default(1);
            $table->unsignedBigInteger('id_categoria');
            $table->unsignedBigInteger('id_proveedor');
            $table->integer('delete')->default(1);
            #$table->timestamps();
            $table->foreign('id_categoria')->references('id_categoria')->on('categorias');
            $table->foreign('id_proveedor')->references('id_proveedor')->on('proveedores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plantas');
    }
}
