<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatisticGithub extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('github_commits', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('project', 255)->index();
            $table->string('author', 255)->index();
            $table->string('sha', 255)->index();
            $table->unsignedInteger('comment_count')->default(0)->index();
            $table->text('message')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('github_commits');
    }
}
