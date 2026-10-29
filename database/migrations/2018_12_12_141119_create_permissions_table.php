<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_group', 50);
            $table->string('slug', 50);
            $table->string('action', 30);
            $table->string('created_by', 50);
            $table->timestamp('created_at')->default(\DB::raw('getdate()'));
            $table->string('updated_by', 50)->nullable();
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
        Schema::dropIfExists('tr_permissions');
    }
}
