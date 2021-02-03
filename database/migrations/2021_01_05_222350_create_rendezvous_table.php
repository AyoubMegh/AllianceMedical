<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRendezvousTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rendezvouss', function (Blueprint $table) {
            $table->increments('id_rdv');
            $table->date('date_rdv');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->text('motif');
            $table->timestamps();
            $table->integer('id_med')->unsigned();
            $table->integer('id_sec')->nullable()->unsigned();
            $table->integer('id_pat')->unsigned();
            $table->foreign('id_med')->references('id_med')->on('medecins');
            $table->foreign('id_sec')->references('id_sec')->on('secretaires')->onDelete('set null');
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
        Schema::dropIfExists('rendezvouss');
    }
}
