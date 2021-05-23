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
            $table->string('nombre')->unique();
            $table->longText('descripcion');
            $table->double('precio_venta', 5, 2);
            $table->double('precio_compra', 5, 2);
            $table->string('imagen');
            $table->integer('cantidad');
            $table->unsignedBigInteger('id_categoria');
            $table->unsignedBigInteger('id_proveedor');
            $table->string('estado')->default(1);
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
