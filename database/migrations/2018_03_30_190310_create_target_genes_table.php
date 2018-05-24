<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTargetGenesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('target_genes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rbp_list_id')->unsigned();
            $table->foreign('rbp_list_id')->references('id')->on('rbp_lists');
            $table->boolean('isTarget');
            $table->string('file_path');
            $table->string('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('target_genes');
    }
}
