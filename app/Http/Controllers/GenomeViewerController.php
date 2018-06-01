<?php

namespace App\Http\Controllers;

use App\Http\Resources\GenomeViewer;
use App\Models\Table4;
use Illuminate\Http\Request;
use stdClass;

class GenomeViewerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($lncrna, $rbp_name)
    {
        $rbp_model_name = "\\App\Models\\".ucfirst(strtolower($rbp_name))."_kmer";
        $rbp = $rbp_model_name::where('lncRNA_id', $lncrna)->first();
        $lnc_rna_features = Table4::where('transcript_id', $lncrna)->first();

        $response = [];


        $motif_list = explode(',', $rbp->motif_list);
        $result = [];
        $ends = [];
        $starts = [];
        foreach ($motif_list as $motif){
            $splitted_motifs = explode(':', $motif);

            $starts[] = (int) $splitted_motifs[0];
            $ends[] = (int) $splitted_motifs[1];

            $r = new stdClass();
            $r->chromosome = substr($lnc_rna_features->gene_chr, 3);
            $r->end = (int) $splitted_motifs[1];
            $r->id = "";
            $r->name = "";
            $r->start = (int) $splitted_motifs[0];
            $r->strand = $lnc_rna_features->transcript_strand;
            array_push($result, $r);
        }

        $returning = new stdClass();
        $returning->id = substr($lnc_rna_features->gene_chr, 3) . ":" . min($starts) . "-" . max($ends);
        $returning->numResult = count($motif_list);
        $returning->result = $result;

        array_push($response, $returning);

        return response()->json(['response' => $response]);
    }
}
