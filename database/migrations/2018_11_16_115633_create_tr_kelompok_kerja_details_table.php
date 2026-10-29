<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrKelompokKerjaDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_kelompok_kerja_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_kelompok_kerja');
            $table->string('nama', 50);
            $table->string('rentang_hari', 50);
            $table->time('jam_masuk');
            $table->time('jam_pulang');
            $table->time('jam_masuk_min');
            $table->time('jam_masuk_max');
            $table->time('jam_pulang_min');
            $table->time('jam_pulang_max');
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
        Schema::dropIfExists('tr_kelompok_kerja_details');
    }
}
