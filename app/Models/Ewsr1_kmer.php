<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Ewsr1_kmer extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lncRNA_id', 'num_motifs', 'num_motifs_in_CLIP_peaks', 'motif_list', 'LOD_score', 'disp_score', 'num_eCLIP_peaks', 'eCLIP_peak_list',
        'num_CLIPdb_peaks', 'CLIPdb_peak_list', 'EMTAB2706_med_exp', 'EMTAB2706_max_exp', 'EMTAB2770_med_exp', 'EMTAB2770_max_exp', 'GTEX_med_exp',
        'GTEX_max_exp',
    ];
}
