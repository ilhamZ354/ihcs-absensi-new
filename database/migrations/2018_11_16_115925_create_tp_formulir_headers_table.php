<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTpFormulirHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_formulir_headers', function (Blueprint $table) {
            $table->increments('id');
            //$table->integer('id_peserta')->nullable();
            $table->integer('id_kuota')->nullable();
            $table->string('nik_simkar', 15);
            $table->string('nik_penyetuju', 15)->nullable();
            $table->string('nik_pengganti', 15)->nullable();
            $table->string('kode_jenis_absen', 4);
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->smallInteger('jml_hk');
            $table->string('alasan', 250);
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('tp_formulir_headers');
    }
}
