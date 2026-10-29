<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTpRolePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_role_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_group', 50);
            $table->string('slug', 100);
            $table->string('permission', 100)->nullable();
            $table->tinyInteger('status')->nullable()->default(1);
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
        Schema::dropIfExists('tp_role_permissions');
    }
}
