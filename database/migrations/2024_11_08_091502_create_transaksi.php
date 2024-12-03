<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->bigIncrements('id_transaksi');
            $table->unsignedBigInteger('id_user');
            $table->string('no_reff', 11);
            $table->datetime('tgl_input')->nullable();
            $table->date('tgl_transaksi');
            $table->enum('jenis_saldo', ['debit', 'kredit']);
            $table->bigInteger('saldo');
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('id_user')->references('id')->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
                  
            $table->foreign('no_reff')->references('no_reff')->on('akun')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
            
            // Indexes
            $table->index('tgl_transaksi');
            $table->index('jenis_saldo');
            $table->index(['id_user', 'tgl_transaksi']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi');
    }
}