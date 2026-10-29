<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrLiburCutiBersamasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_libur_cuti_bersamas', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('is_cb')->default(false);
            $table->date('tgl');
            $table->string('uraian', 200);
            $table->tinyInteger('status')->default(1);
            $table->string('keterangan', 200)->nullable();
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
        Schema::dropIfExists('tr_libur_cuti_bersamas');
    }
}
