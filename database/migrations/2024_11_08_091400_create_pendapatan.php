<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendapatan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pendapatan', function (Blueprint $table) {
            $table->bigIncrements('id_pendapatan');
            $table->unsignedBigInteger('id_user');
            $table->string('no_reff', 11);
            $table->datetime('tgl_input');
            $table->date('tgl_pendapatan');
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
        Schema::dropIfExists('pendapatan');
    }
}
