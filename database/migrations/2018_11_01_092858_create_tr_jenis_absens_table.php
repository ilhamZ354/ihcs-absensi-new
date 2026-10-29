<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrJenisAbsensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_jenis_absens', function (Blueprint $table) {
            $table->string('kode', 4);
            $table->string('nama', 100);
            $table->tinyInteger('status')->default(1);
            $table->string('created_by', 50);
            $table->string('updated_by', 50)->nullable();
            $table->timestamp('created_at')->default(\DB::raw('getdate()'));
            $table->timestamp('updated_at')->nullable();
            // $table->timestamps();
            $table->primary('kode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tr_jenis_absens');
    }
}
