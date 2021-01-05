<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCliniqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cliniques', function (Blueprint $table) {
            $table->increments('id_clq');
            $table->string('nom')->unique();
            $table->string('adresse');
            $table->string('num_tel');
            $table->timestamps();
            $table->integer('id_med_res')->unsigned();
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
        Schema::dropIfExists('cliniques');
    }
}
