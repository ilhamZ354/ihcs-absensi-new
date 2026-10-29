<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadFileCtcp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_file_upload_ctcps', function (Blueprint $table) {
           $table->increments('id');
            $table->smallInteger('tahun');
            $table->string('original_filename', 250);
            $table->string('path', 250);
            $table->string('kode_unit', 5);
            $table->string('kode_bagian', 15)->nullable();
            $table->tinyInteger('verified_status')->default(0)->nullable();
            $table->string('uploaded_by', 50);
            $table->string('updated_by', 50)->nullable();
            $table->timestamp('created_at')->default(DB::raw('getdate()'));
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
         Schema::dropIfExists('tr_file_upload_ctcps');
    }
}
