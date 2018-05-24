<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Rbp_list extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rbp_name',
    ];
    public function target_genes()
	{
	    return $this->hasMany('App\Models\Target_gene');
	}
}
