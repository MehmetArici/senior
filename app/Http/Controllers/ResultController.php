<?php

namespace App\Http\Controllers;

use App\Models\Analysis;
use App\Models\Pum2_kmer;
use App\Models\Table4;
use App\Models\Target_gene;
use Mail;
use function PHPSTORM_META\type;

class ResultController extends Controller
{
    public function index(){
        $rbps = Pum2_kmer::take(100)->orderBy('LOD_score', 'desc')->get();
        $rbp_name = "PUM2";
        return view("result", compact("rbps"))->with(array('rbp_name' => $rbp_name));
    }

    public function send_job(){
        $this->validate(request(), [
            'retrieve_email' =>  'required|email|exists:target_genes,email',
        ]);
        $analysis_gene = Target_gene::where("email", request("retrieve_email"))->first();
        $class = "\App\Models"."\\".ucfirst(strtolower($analysis_gene->rbp_list->rbp_name))."_kmer";
        $instance = new $class();
        $rbps = $instance::take(100)->orderBy('LOD_score', 'desc')->get();
        return view("result", compact("rbps"));
        /*
        $email = request("retrieve_email");
        $send = Mail::to($email)->send(new \App\Mail\DemoMail());
        return $send;
        */
    }

    public function load_genome($lncrna, $region){
/*
        $exploded_lncrna = explode("=", $lncrna);
        $var = "$exploded_lncrna[1]";
  */
        echo $region;
        echo $lncrna;
        return view("genome-maps")->with('gene', $lncrna)->with("region", $region);
    }

    public function see_analysis(){

        $this->validate(request(), [
            'dataset_id' =>  'required',
        ]);
        $dataset = request("dataset_id");
        $rbp_name = request("rbp_name");
        $lncrna_id = request('lncrna_id');
        $job_id = request('job_id') ? request('job_id') : sha1(time());

        $engs = Table4::where('transcript_id', request('lncrna_id'))->first();
        $gene_id = $engs->gene_id;

        $is_there_analysis = Analysis::where('rbp_name', $rbp_name)->where('lncrna_id', $gene_id)->where('dataset', $dataset)->first();

        if ($is_there_analysis == null){
            $is_gtex = strpos($dataset, 'GTEX') !== false ? 'GTEX' : $dataset;

            $requested_analyze = Target_gene::where('job_id', $job_id)->get();

            if (!$requested_analyze->isEmpty()){
                if ($requested_analyze[0]->file_path and $requested_analyze[1]->file_path){
                    $rbp_plot = shell_exec("Rscript /var/www/html/analysis_code/analysis0_plot/analysis0.R $rbp_name $is_gtex $job_id");
                    $lncRna_plot = shell_exec("Rscript /var/www/html/analysis_code/analysis0_plot/analysis0.R $gene_id $is_gtex $job_id");
                    $analysis1 = shell_exec("Rscript /var/www/html/analysis_code/analysis1_correlation/analysis1.R  $gene_id $rbp_name $dataset $job_id $requested_analyze[0]->file_path $requested_analyze[1]->file_path");
                    $analysis2 = shell_exec("Rscript /var/www/html/analysis_code/analysis2_regression/analysis2.R  $gene_id $rbp_name $dataset $job_id $requested_analyze[0]->file_path $requested_analyze[1]->file_path");
                    $analysis3 = shell_exec("Rscript /var/www/html/analysis_code/analysis3_lncRNA_KD_CDF_analysis/analysis3.R  $gene_id $rbp_name $job_id $requested_analyze[0]->file_path $requested_analyze[1]->file_path");
                }
            }else{

                $rbp_plot = shell_exec("Rscript /var/www/html/analysis_code/analysis0_plot/analysis0.R $rbp_name $is_gtex $job_id");
                $lncRna_plot = shell_exec("Rscript /var/www/html/analysis_code/analysis0_plot/analysis0.R $gene_id $is_gtex $job_id");
                $analysis1 = shell_exec("Rscript /var/www/html/analysis_code/analysis1_correlation/analysis1.R  $gene_id $rbp_name $dataset $job_id");
                $analysis2 = shell_exec("Rscript /var/www/html/analysis_code/analysis2_regression/analysis2.R  $gene_id $rbp_name $dataset $job_id");
                $analysis3 = shell_exec("Rscript /var/www/html/analysis_code/analysis3_lncRNA_KD_CDF_analysis/analysis3.R  $gene_id $rbp_name $job_id ");
            }

            if ($dataset == 'EMTAB2770'){
                $rbp_image_path = $rbp_name.'_analysis0_'.$dataset.'_part1.jpeg;'.$rbp_name.'_analysis0_'.$dataset.'_part2.jpeg';
            }elseif (strpos($dataset, 'GTEX') !== false){
                $rbp_image_path = $rbp_name.'_analysis0_GTEX_.jpeg';
            }else{
                $rbp_image_path = $rbp_name.'_analysis0_'.$dataset.'_.jpeg';
            }
            $lncrna_image_path = '';
            $analysis1_image_path =  '';
            $analysis1_text_path =  '';
            $analysis2_image_path =  '';
            $analysis2_text_path =  '';
            $analysis3_image_path =  '';
            $analysis3_text_path =  '';

            $file = \File::get(public_path($job_id.'_analysis0_info.txt'));
            if (empty($file)){
                if ($dataset == 'EMTAB2770'){
                    $lncrna_image_path = $gene_id.'_analysis0_'.$dataset.'_part1.jpeg;'.$gene_id.'_analysis0_'.$dataset.'_part2.jpeg';
                }elseif (strpos($dataset, 'GTEX') !== false){
                    $lncrna_image_path = $gene_id.'_analysis0_GTEX_.jpeg';
                }else{
                    $lncrna_image_path = $gene_id.'_analysis0_'.$dataset.'_.jpeg';
                }
            }
            $file = \File::get(public_path($job_id.'_analysis1_info.txt'));
            if (empty($file) or strpos($file, '[1]') !== false){
                $analysis1_image_path =  $rbp_name.'_'.$gene_id.'_analysis1_'.$dataset.'.jpeg';
                $analysis1_text_path =  $rbp_name.'_'.$gene_id.'_analysis1_'.$dataset.'.txt';
            }
            $file = \File::get(public_path($job_id.'_analysis2_info.txt'));
            if (empty($file)){
                $analysis2_image_path =  $rbp_name.'_'.$gene_id.'_analysis2_'.$dataset.'.jpeg';
                $analysis2_text_path =  $rbp_name.'_'.$gene_id.'_analysis2_'.$dataset.'.txt';
            }
            $file = \File::get(public_path($job_id.'_analysis3_info.txt'));
            if (empty($file)){
                $analysis3_image_path =  $rbp_name.'_'.$gene_id.'_analysis3_KDdata1.jpeg;'.$rbp_name.'_'.$gene_id.'_analysis3_KDdata2.jpeg';
                $analysis3_text_path =  $rbp_name.'_'.$gene_id.'_analysis3_KDdata1.txt;'.$rbp_name.'_'.$gene_id.'_analysis3_KDdata2.txt';
            }

            \File::delete($job_id.'_analysis0_error.txt', $job_id.'_analysis0_info.txt', $job_id.'_analysis1_error.txt', $job_id.'_analysis1_info.txt',
                $job_id.'_analysis2_error.txt', $job_id.'_analysis2_info.txt', $job_id.'_analysis3_error.txt', $job_id.'_analysis3_info.txt');

            $analysis = new Analysis();
            $analysis->rbp_name = $rbp_name;
            $analysis->rbp_plot = $rbp_image_path;
            $analysis->lncrna_id = $gene_id;
            $analysis->lncrna_plot = $lncrna_image_path;
            $analysis->job_id = $job_id;
            $analysis->corr_plot = $analysis1_image_path;
            $analysis->corr_text = $analysis1_text_path;
            $analysis->regression_plot = $analysis2_image_path;
            $analysis->regression_text = $analysis2_text_path;
            $analysis->knockdown_plot = $analysis3_image_path;
            $analysis->knockdown_text = $analysis3_text_path;
            $analysis->dataset = $dataset;
            $analysis->save();
            return response()->json(['plots_data' => $analysis]);
        }
        return response()->json(['plots_data' => $is_there_analysis]);
    }
}
