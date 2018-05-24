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
    public function index()
    {
        $requested_rbp_name = request('rbp');
        $requested_lnc_rna = request('lncrna');

        $rbp_model_name = "\\App\Models\\".ucfirst(strtolower($requested_rbp_name))."_kmer";
        $rbp = $rbp_model_name::where('lncRNA_id', $requested_lnc_rna)->first();
        $lnc_rna_features = Table4::where('transcript_id', $requested_lnc_rna)->first();

        $response = new stdClass();

        $list_exons = explode(',', $lnc_rna_features->list_exons);
        $i = 1;
        $exon_array = [];
        foreach ($list_exons as $exon){
            $splitted_exons = explode(':', $exon);

            $exon_object = new stdClass();
            $exon_object->end = (int)$splitted_exons[0];
            $exon_object->exon_number = $i;
            $exon_object->start = (int)$splitted_exons[1];
            array_push($exon_array, $exon_object);
            $i++;
        }

        $motif_list = explode(',', $rbp->motif_list);
        $motif_array = [];
        foreach ($motif_list as $motif){
            $splitted_motifs = explode(':', $motif);
            $motif_array[] = $splitted_motifs[0];
        }

        $response->biotype = $lnc_rna_features->gene_type;
        $response->chrom = substr($lnc_rna_features->gene_chr, 3);
        $response->compl_score = '';
        $response->end = $lnc_rna_features->epos;
        $response->exons = $exon_array;
        $response->gene_id = $lnc_rna_features->gene_id;
        $response->gene_name = $lnc_rna_features->gene_name;
        $response->kmer = '';
        $response->kmer_size = '';
        $response->lenght = '';
        $response->lod_score = $rbp->LOD_score;
        $response->median_distance = '';
        $response->mirnas = [];
        $response->occ = '';
        $response->positions = $motif_array;
        $response->start = $lnc_rna_features->spos;
        $response->strand = $lnc_rna_features->transcript_strand;
        $response->total = '';
        $response->trans_end = $lnc_rna_features->transcript_epos;
        $response->trans_id = $lnc_rna_features->transcript_id;
        $response->trans_name = $lnc_rna_features->transcript_name;
        $response->trans_start = $lnc_rna_features->transcript_spos;
        $response->w_size = '';

        return response()->json(['response' => $response]);

    }
}
