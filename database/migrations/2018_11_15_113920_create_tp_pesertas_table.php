<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTpPesertasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_pesertas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nik_simkar', 15);
            $table->integer('pin');
            $table->string('nama_mesin', 50)->nullable();
            $table->char('kodeunitbagian', 3)->nullable();
            $table->date('tgl_bekerja')->nullable();
            $table->tinyInteger('status')->nullable()->default((int) 1);
            $table->boolean('satpam')->nullable();
            $table->string('created_by', 50);
            $table->string('updated_by', 50)->nullable();
            $table->timestamp('created_at')->default(\DB::raw('getdate()'));
            $table->timestamp('updated_at')->nullable();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_pesertas');
    }
}
