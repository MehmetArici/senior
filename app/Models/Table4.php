<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table4 extends Model
{
	public $timestamps = false;

	   protected $fillable = ["transcript_id", "transcript_name", "transcript_chr", "transcript_spos",
			"transcript_epos", "transcript_strand", "list_exons", "gene_id",
			"transcript_epos", "transcript_strand", "list_exons", "gene_id",
			"gene_chr", "spos", "epos", "gene_type", "gene_name", "havana_gene_name"
		];

   	public function rbp_list()
	{
	    return $this->belongsTo('App\Models\Rbp_list');
	}
}
