<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrescriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->increments('id_pres');
            $table->date('date_pres');
            $table->timestamps();
            $table->integer('id_med')->nullable()->unsigned();
            $table->integer('id_pat')->unsigned();
            $table->foreign('id_med')->references('id_med')->on('medecins')->onDelete('set null');
            $table->foreign('id_pat')->references('id_pat')->on('patients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prescriptions');
    }
}
