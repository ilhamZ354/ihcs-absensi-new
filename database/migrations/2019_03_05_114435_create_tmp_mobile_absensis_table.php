<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTmpMobileAbsensisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmp_mobile_absensis', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_sync_log');
            $table->string('nik', 15);
            $table->string('msisdn', 20);
            $table->string('nama', 200);
            $table->date('tanggal')->nullable();
            $table->time('jam_clockin')->nullable();
            $table->time('jam_clockout')->nullable();
            $table->string('reason_in', 255)->nullable();
            $table->string('created_by', 50);
            $table->string('updated_by', 50)->nullable();
            $table->timestamp('created_at')->default(\DB::raw("getdate()"));
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
        Schema::dropIfExists('tmp_mobile_absensis');
    }
}
