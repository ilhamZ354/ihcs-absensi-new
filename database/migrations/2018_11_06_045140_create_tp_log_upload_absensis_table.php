<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTpLogUploadAbsensisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_log_upload_absensis', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('tahun');
            $table->smallInteger('bulan');
            $table->string('jenis_data', 15);
            $table->string('kode_unit', 3)->nullable();
            $table->string('kode_bagian', 15)->nullable();
            $table->string('nik_peserta', 15)->nullable();
            $table->string('nama_peserta', 100)->nullable();
            $table->string('file_absensi', 255)->nullable();
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
        Schema::dropIfExists('tp_log_upload_absensis');
    }
}
