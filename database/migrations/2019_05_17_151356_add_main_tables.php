<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMainTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('screens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->unique();
            $table->string('name');
            $table->timestamps();
            $table->unsignedInteger('user_id')->nullable();
        });
        Schema::create('scenes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
            $table->unsignedInteger('screen_id')->nullable();
        });
        Schema::create('scene_widgets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('widget_type', 255);
            $table->unsignedInteger('scene_id')->index();
            $table->json('data')->nullable();
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
        Schema::dropIfExists('screens');
        Schema::dropIfExists('scenes');
        Schema::dropIfExists('scene_widgets');
    }
}
