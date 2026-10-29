<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrIndexNamaHarisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_index_nama_haris', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_hari', 50);
            $table->string('english_name', 50);
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
        Schema::dropIfExists('tr_index_nama_haris');
    }
}
