<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedecinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medecins', function (Blueprint $table) {
            $table->increments('id_med');
            $table->string('nom');
            $table->string('prenom');
            $table->string('num_tel');
            $table->string('login')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('specialite');
            $table->integer('enService');
            $table->rememberToken();
            $table->timestamps();
            $table->integer('id_clq')->unsigned();
            $table->foreign('id_clq')->references('id_clq')->on('cliniques');
        });
        Schema::table('cliniques', function($table){
            $table->foreign('id_med_res')->references('id_med')->on('medecins');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('medecins');
    }
}
