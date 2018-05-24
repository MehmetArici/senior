<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHnrnpfKmersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hnrnpf_kmers', function (Blueprint $table) {
            $table->string('lncRNA_id');
            $table->integer('num_motifs');
            $table->integer('num_motifs_in_CLIP_peaks');
            $table->string('motif_list');
            $table->string('LOD_score');
            $table->string('disp_score');
            $table->string('num_eCLIP_peaks');
            $table->string('eCLIP_peak_list');
            $table->string('num_CLIPdb_peaks');
            $table->string('CLIPdb_peak_list');
            $table->string('EMTAB2706_med_exp');
            $table->string('EMTAB2706_max_exp');
            $table->string('EMTAB2770_med_exp');
            $table->string('EMTAB2770_max_exp');
            $table->string('GTEX_med_exp');
            $table->string('GTEX_max_exp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hnrnpf_kmers');
    }
}
