<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLettreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lettres', function (Blueprint $table) {
            $table->increments('id_lettre');
            $table->date('date_lettre');
            $table->text('contenu');
            $table->timestamps();
            $table->integer('id_med')->unsigned();
            $table->integer('id_pat')->unsigned();
            $table->foreign('id_med')->references('id_med')->on('medecins');
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
        Schema::dropIfExists('lettres');
    }
}
