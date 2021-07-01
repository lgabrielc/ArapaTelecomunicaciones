<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTorreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('torre', function (Blueprint $table) {
            $table->id();
            $table->string('nombreTorre');
            $table->string('direccion');
            $table->string('dueñoLocal');
            $table->string('telefono');
            $table->float('pago');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('torre');
    }
}
