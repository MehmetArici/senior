<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Target_gene extends Model
{
	public $timestamps = false;

   	protected $fillable = ["rbp_list_id", "isTarget", "file_path", "email", "job_id"];

   	public function rbp_list()
	{
	    return $this->belongsTo('App\Models\Rbp_list');
	}
}
