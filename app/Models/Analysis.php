<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Analysis extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rbp_name', 'rbp_plot', 'rbp_job_id', 'lncrna_id', 'lncrna_plot', 'lncrna_job_id', 'dataset'
    ];
}
