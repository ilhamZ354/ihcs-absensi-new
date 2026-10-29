<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTpTimetablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_timetables', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_unit', 5);
            $table->string('dayname', 25);
            $table->boolean('satpam')->default(false);
            $table->time('jam_masuk');
            $table->time('jam_masuk_min');
            $table->time('jam_masuk_max');
            $table->time('jam_pulang');
            $table->time('jam_pulang_min');
            $table->time('jam_pulang_max');
            $table->boolean('status')->nullable()->default(true);
            $table->string('created_by', 50);
            $table->string('updated_by', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_timetables');
    }
}
