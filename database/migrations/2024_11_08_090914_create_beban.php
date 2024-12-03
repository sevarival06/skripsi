<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeban extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beban', function (Blueprint $table) {
            $table->bigIncrements('id_beban');
            $table->unsignedBigInteger('id_user');
            $table->string('no_reff', 11);
            $table->datetime('tgl_input');
            $table->date('tgl_beban');
            $table->enum('jenis_saldo', ['debit', 'kredit']);
            $table->bigInteger('saldo');
            $table->timestamps();
            
            $table->foreign('id_user')->references('id_user')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('beban');
    }
}
