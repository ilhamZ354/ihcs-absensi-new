<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrConfigAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_config_apps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key', 100);
            $table->string('value', 250);
            $table->tinyInteger('status')->default(1)->nullable();
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
        Schema::dropIfExists('tr_config_apps');
    }
}
