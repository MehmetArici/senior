<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTable4sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table4s', function (Blueprint $table) {
            $table->increments('id');
            $table->string("transcript_id");
            $table->string("transcript_name");
            $table->string("transcript_chr");
            $table->integer("transcript_spos");
            $table->integer("transcript_epos");
            $table->string("transcript_strand");
            $table->string("list_exons");
            $table->string("gene_id");
            $table->string("gene_chr");
            $table->integer("spos");
            $table->integer("epos");
            $table->string("gene_type");
            $table->string("gene_name");
            $table->string("havana_gene_name");
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table4s');
    }
}
