<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rbp_list;
use App\Models\Target_gene;

class AnalysisController extends Controller
{
    public function index()
    {
        return view('analysis');
    }

    public function load_example()
    {
        $loaded_rbp = Rbp_list::find(rand(1, 40));
        $target_files = $loaded_rbp->target_genes;
        return view('analysis', compact("loaded_rbp", "target_files"));
    }

    public function post_analyze()
    {
        $this->validate(request(), [
            'rbp_id'            =>  'required|bail|integer|digits_between:1,40',
            'target_file'       =>  'nullable',
            'background_file'   =>  'nullable',
            'email'             =>  'nullable|email',
        ]);
        $rbp = Rbp_list::find(request('rbp_id'));
        $job_id = sha1(time());
        $rbp_name = "\\App\Models\\".ucfirst(strtolower($rbp->rbp_name))."_kmer";
        $rbps = $rbp_name::take(100)->orderBy('LOD_score', 'desc')->get();

        if (request()->target_file)
        {
            $fileName = $rbp->rbp_name.'_'.$job_id.'_target_genes.txt';
            request()->file('target_file')->storeAs('results', $fileName);
            Target_gene::create([
                'rbp_list_id'       =>  request('rbp_id'),
                'isTarget'          =>  1,
                'file_path'         =>  $fileName,
                'email'             =>  request('email'),
                'job_id'            =>  $job_id
            ]);
        }
        if (request()->background_file)
        {
            $fileName = $rbp->rbp_name.'_'.$job_id.'_nontarget_genes.txt';
            request('background_file')->storeAs('results', $fileName);
            Target_gene::create([
                'rbp_list_id'            =>  request('rbp_id'),
                'isTarget'          =>  0,
                'file_path'         =>  $fileName,
                'email'             =>  request('email'),
                'job_id'            =>   $job_id
            ]);
        }
        return view("result", compact("rbps"))->with(array('job_id' => $job_id, 'rbp_name' => $rbp->rbp_name));
    }

}
