<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGithubPushes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('github_pushes', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('project', 255)->nullable();
            $table->string('pusher', 255)->nullable();
            $table->string('sender', 255)->nullable();
            $table->text('message')->nullable();
            $table->unsignedInteger('commit_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('github_pushes');
    }
}
