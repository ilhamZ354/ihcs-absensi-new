<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrKuotaCutiTahunansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_kuota_cuti_tahunans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nik_simkar', 15);
            $table->smallInteger('kuota')->default(0);
            $table->smallInteger('terpakai')->default(0);
            $table->date('tgl_mulai');
            $table->date('tgl_berakhir');
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
        Schema::dropIfExists('tr_kuota_cuti_tahunans');
    }
}
