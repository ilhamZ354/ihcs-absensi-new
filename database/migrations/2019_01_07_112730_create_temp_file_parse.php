<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempFileParse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('temp_file_parses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_upload');
            $table->smallInteger('tahun')->nullable();
            $table->integer('no');
            $table->integer('th_row')->nullable();
            $table->integer('pj_row')->nullable();
            $table->string('nik', 30);
            $table->string('nik_sap', 30);
            $table->string('nama', 150)->nullable();
            $table->tinyInteger('tipe_karyawan')->nullable();
            $table->date('tanggal_bekerja')->nullable();
            $table->string('namaunit', 200)->nullable();
            $table->string('namabagian', 200)->nullable();
            $table->string('namajabatan', 200)->nullable();
            $table->date('tgl_mulai_tahunan')->nullable();
            $table->date('tgl_berakhir_tahunan')->nullable();
            $table->smallInteger('kuota_cuti_tahunan')->nullable();
            $table->date('tgl_mulai_panjang')->nullable();
            $table->date('tgl_berakhir_panjang')->nullable();
            $table->smallInteger('kuota_cuti_panjang')->nullable();
            $table->string('created_by', 50);
            $table->string('updated_by', 50)->nullable();
            $table->timestamp('created_at')->default(\DB::raw('getdate()'));
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::dropIfExists('temp_file_parses');
    }
}
