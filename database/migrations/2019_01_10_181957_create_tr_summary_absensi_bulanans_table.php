<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrSummaryAbsensiBulanansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_summary_absensi_bulanans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_peserta')->nullable();
            $table->string('nik_simkar', 15);
            $table->string('nik_sap', 10)->nullable();
            $table->string('nama', 100);
            $table->smallInteger('tahun');
            $table->smallInteger('bulan');
            for($i = 1; $i <=28; $i++) {
                $table->string($i, 5);
            }
            $table->string('29', 5)->nullable();
            $table->string('30', 5)->nullable();
            $table->string('31', 5)->nullable();
            $table->smallInteger('jl_k')->default(0);
            $table->smallInteger('jl_l')->default(0);
            $table->smallInteger('jl_s')->default(0);
            $table->smallInteger('jl_dl')->default(0);
            $table->smallInteger('jl_s1')->default(0);
            $table->smallInteger('jl_s2')->default(0);
            $table->smallInteger('jl_m')->default(0);
            $table->smallInteger('jl_m1')->default(0);
            $table->smallInteger('jl_p1')->default(0);
            $table->smallInteger('jl_p2')->default(0);
            $table->smallInteger('jl_p3')->default(0);
            $table->smallInteger('jl_p4')->default(0);
            $table->smallInteger('jl_cb')->default(0);
            $table->smallInteger('jl_ct')->default(0);
            $table->smallInteger('jl_cp')->default(0);
            $table->smallInteger('jl_cl')->default(0);
            $table->smallInteger('jl_h1')->default(0);
            $table->smallInteger('jl_h2')->default(0);
            $table->smallInteger('jl_h3')->default(0);
            $table->smallInteger('jl_ch')->default(0);
            $table->smallInteger('jl_na')->default(0);
            $table->smallInteger('jl_abs')->default(0);

            $table->string('created_by', 50);
            $table->string('created_at')->default(\DB::raw("getdate()"));
            $table->string('updated_by', 50)->nullable();
            $table->string('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tr_summary_absensi_bulanans');
    }
}
