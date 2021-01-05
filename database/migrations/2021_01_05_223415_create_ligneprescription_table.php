<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLigneprescriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ligneprescriptions', function (Blueprint $table) {
            $table->increments('id_ligne_pres');
            $table->string('medicament');
            $table->string('dose');
            $table->string('moment');
            $table->string('duree');
            $table->timestamps();
            $table->integer('id_pres')->unsigned();
            $table->foreign('id_pres')->references('id_pres')->on('prescriptions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ligneprescriptions');
    }
}
