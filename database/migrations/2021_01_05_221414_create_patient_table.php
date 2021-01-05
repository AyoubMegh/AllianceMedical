<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->increments('id_pat');
            $table->string('nom');
            $table->string('prenom');
            $table->string('num_ss')->unique();
            $table->date('date_naissance');
            $table->string('num_tel');
            $table->string('email')->unique();
            $table->text('maladies');
            $table->text('allergies');
            $table->text('antecedents');
            $table->text('commentaires');
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
        Schema::dropIfExists('patients');
    }
}
