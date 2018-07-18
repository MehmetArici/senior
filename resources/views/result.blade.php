@extends('app')

@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/datatable/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-select/css/bootstrap-select.min.css') }}">
@endsection
@section('js')
    <script type="text/javascript" src="{{ asset('plugins/datatable/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/datatable/ColReorderWithResize.js') }}"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script type="text/javascript" src="{{ asset('plugins/bootstrap-select/js/bootstrap-select.min.js') }}"></script>

    <script>
        $(document).on("click", ".select-analysis", function () {
            var id = $(this).data('id');
            $(".modal-body #lncrna_id").val( id );
        });

        // displaying the Genome viewer
        function showup()  {
            var tbl = document.getElementById("example");

            if (tbl != null) {
                for (var i = 0; i < tbl.rows.length; i++) {
                    tbl.rows[i].cells[15].onclick = function(){
                        document.getElementById('htmlpopup').src='load_genome/' + getval(this.parentElement.cells[0]) + "/" + getval(this.parentElement.cells[1]);
                    }
                }
            }
        }

        function getval(cel) {
            return cel.innerHTML;
        }
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $("#see_analysis").click(function(e) {
            e.preventDefault();
            $(document).ajaxSend(function(event, request, settings) {
                $('#selectanalysis').hide();
                $('#loading').removeClass('hidden');
            });
            $(document).ajaxComplete(function(event, request, settings) {
                $('#selectanalysis').show();
                $('#loading').addClass('hidden');
            });
            var lncrna_id = $("#lncrna_id").val();
            var rbp_name = $("#rbp_name").val();
            var dataset_id = $("#dataset_id").val();
            $.ajax({
                type: 'POST',
                url: '/see_analysis',
                data: {lncrna_id: lncrna_id, rbp_name: rbp_name, dataset_id: dataset_id},
                success: function (data) {

                    $('#rbp_tab').html(data.plots_data.rbp_name);
                    $('#lncrna_tab').html(data.plots_data.lncrna_id);

                    if (!data.plots_data.rbp_plot){
                        $('#rbp_tab_content').html('<p style="padding: 30px" class="text-danger">ERROR: This gene do not exist in expression data</p>');
                    }else{
                        var rbp_plot = data.plots_data.rbp_plot;
                        if(rbp_plot.includes("EMTAB2770")){
                            var rbp_images = data.plots_data.rbp_plot.split(";");
                            var first_image = document.location.origin + "/results/analysis0_plot/" + rbp_images[0];
                            var second_image = document.location.origin + "/results/analysis0_plot/" + rbp_images[1];
                            var rbp_second_tab = '<div class="tabbable">';
                            rbp_second_tab += '<ul class="nav tab-group pt-10">';
                            rbp_second_tab += '<li class="active"><a href="#part1" data-toggle="tab">Part1</a></li>';
                            rbp_second_tab += '<li><a href="#part2" data-toggle="tab">Part2</a></li>';
                            rbp_second_tab += '</ul>';
                            rbp_second_tab += '<div class="tab-content">';
                            rbp_second_tab += '<div class="tab-pane active" id="part1">';
                            rbp_second_tab += '<img src="'+ first_image +'" style="height: 620px">';
                            rbp_second_tab += '</div>';
                            rbp_second_tab += '<div class="tab-pane" id="part2">';
                            rbp_second_tab += '<img src="'+ second_image +'" style="height: 620px">';
                            rbp_second_tab += '</div>';
                            rbp_second_tab += '</div>';
                            rbp_second_tab += '</div>';
                        }else{
                            var image = document.location.origin + "/results/analysis0_plot/" + data.plots_data.rbp_plot;
                            var rbp_second_tab ='<img src="' + image + '" style="height: 620px">';
                        }
                        $('#rbp_tab_content').html(rbp_second_tab)
                    }

                    if (!data.plots_data.lncrna_plot){
                        $('#lncrna_tab_content').html('<p style="padding: 30px" class="text-danger">ERROR: This gene do not exist in expression data</p>');
                    }else{
                        var lncrna_plot = data.plots_data.lncrna_plot;
                        if(lncrna_plot.includes("EMTAB2770")){
                            var lncrna_images = lncrna_plot.split(";");
                            var first_image = document.location.origin + "/results/analysis0_plot/" + lncrna_images[0];
                            var second_image = document.location.origin + "/results/analysis0_plot/" + lncrna_images[1];
                            var lncrna_second_tab = '<div class="tabbable">';
                            lncrna_second_tab += '<ul class="nav tab-group pt-10">';
                            lncrna_second_tab += '<li class="active"><a href="#part11" data-toggle="tab">Part1</a></li>';
                            lncrna_second_tab += '<li><a href="#part22" data-toggle="tab">Part2</a></li>';
                            lncrna_second_tab += '</ul>';
                            lncrna_second_tab += '<div class="tab-content">';
                            lncrna_second_tab += '<div class="tab-pane active" id="part11">';
                            lncrna_second_tab += '<img src="'+ first_image +'" style="height: 620px">';
                            lncrna_second_tab += '</div>';
                            lncrna_second_tab += '<div class="tab-pane" id="part22">';
                            lncrna_second_tab += '<img src="'+ second_image +'" style="height: 620px">';
                            lncrna_second_tab += '</div>';
                            lncrna_second_tab += '</div>';
                            lncrna_second_tab += '</div>';
                        }else{
                            var image = document.location.origin + "/results/analysis0_plot/" + data.plots_data.lncrna_plot;
                            var lncrna_second_tab ='<img src="' + image + '" style="height: 620px">';
                        }
                        $('#lncrna_tab_content').html(lncrna_second_tab)
                    }

                    if (!data.plots_data.corr_plot){
                        $('#lncrna_tab_content').html('<p style="padding: 30px" class="text-danger">ERROR: This lncRna do not exist in expression data</p>');
                    }else{
                        var corr_plot = data.plots_data.corr_plot;
                        var image = document.location.origin + "/results/analysis1_correlation/" + corr_plot;
                        var text = document.location.origin + "/results/analysis1_correlation/" + data.plots_data.corr_text;
                        var corr_second_tab ='<a class="btn btn-primary pull-right" href="' + text + '" download>Download File</a>';
                        corr_second_tab += '<img src="' + image + '" style="height: 620px" alt="">'
                        $('#corr_tab_content').html(corr_second_tab)
                    }

                    if (!data.plots_data.regression_plot){
                        $('#regression_tab_content').html('<p style="padding: 30px" class="text-danger">ERROR: There is no expression data for this lncRNA</p>');
                    }else{
                        var regression_plot = data.plots_data.regression_plot;
                        var image = document.location.origin + "/results/analysis2_regression/" + regression_plot;
                        var text = document.location.origin + "/results/analysis2_regression/" + data.plots_data.regression_text;
                        var regression_second_tab ='<a class="btn btn-primary pull-right" href="' + text + '" download>Download File</a>';
                        regression_second_tab += '<img src="' + image + '" style="height: 620px" alt="">'
                        $('#regression_tab_content').html(regression_second_tab)
                    }

                    if (!data.plots_data.knockdown_plot){
                        $('#knockdown_tab_content').html('<p style="padding: 30px" class="text-danger">ERROR: There is no knockdown data for this lncRNA</p>');
                    }else{
                        var knockdown_plot = data.plots_data.knockdown_plot;
                        var knockdown_images = knockdown_plot.split(";");
                        var knockdown_texts = data.plots_data.knockdown_text.split(";");
                        var first_image = document.location.origin + "/results/analysis3_lncRNA_KD_CDF_analysis/" + rbp_images[0];
                        var second_image = document.location.origin + "/results/analysis3_lncRNA_KD_CDF_analysis/" + rbp_images[1];
                        var text1 = document.location.origin + "/results/analysis3_lncRNA_KD_CDF_analysis/" + knockdown_texts[0];
                        var text2 = document.location.origin + "/results/analysis3_lncRNA_KD_CDF_analysis/" + knockdown_texts[1];

                        var knockdown_second_tab = '<div class="tabbable">';
                        knockdown_second_tab += '<ul class="nav tab-group pt-10">';
                        knockdown_second_tab += '<li class="active"><a href="#KDdata1" data-toggle="tab">KDdata1</a></li>';
                        knockdown_second_tab += '<li><a href="#KDdata2" data-toggle="tab">KDdata2</a></li>';
                        knockdown_second_tab += '</ul>';
                        knockdown_second_tab += '<div class="tab-content">';
                        knockdown_second_tab += '<div class="tab-pane active" id="KDdata1">';
                        knockdown_second_tab += '<a class="btn btn-primary pull-right" href="' + text1 + '" download>Download File</a>';
                        knockdown_second_tab += '<img src="'+ first_image +'" style="height: 620px">';
                        knockdown_second_tab += '</div>';
                        knockdown_second_tab += '<div class="tab-pane" id="KDdata2">';
                        knockdown_second_tab += '<a class="btn btn-primary pull-right" href="' + text2 + '" download>Download File</a>';
                        knockdown_second_tab += '<img src="'+ second_image +'" style="height: 620px">';
                        knockdown_second_tab += '</div>';
                        knockdown_second_tab += '</div>';
                        knockdown_second_tab += '</div>';
                        $('#knockdown_tab_content').html(knockdown_second_tab)
                    }

                    $('#chart').modal('show');
                },
            });
        });
    </script>
@endsection

@section('content')
    <!--//genome viewer displayer-->
    <style>
        .modal.modal-wide .modal-dialog {

            width: 90%;
        }
        .modal-wide .modal-body {
            height: 750px;

            /* overflow-y: auto; */
        }

        /* irrelevant styling */
        body { text-align: center; }
        body p {
            max-width: 400px;

            margin: 20px auto;
        }
        #tallModal .modal-body p { margin-bottom: 900px }

    </style>

    <div class="modal  modal-wide fade" id="externalpage" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <!-- <div id="shortModal" class="modal modal-wide fade"> -->
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                </div>
                <div class="modal-body">
                    <iframe width="100%" height="100%" id="htmlpopup" src="" seamless>
                    </iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="document.getElementById('htmlpopup').src=''" data-dismiss="modal">Close</button>

                </div>
            </div>
        </div>

    </div>

    <!--/////////////////////////////////////////////////////////////////////////////////////-->
    <!--//END of genome viewer-->
    <!--/////////////////////////////////////////////////////////////////////////////////////-->

    <div class="preloader-wrapper1 hidden" id="loading">
        <div class="preloader1">
            <img src="https://media.giphy.com/media/jOYlYHqQmkcaA/giphy.gif" alt="NILA">
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <table id="example" class="display table table-bordered table-hover" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th style="width: 180px">LncRNA</th>
                    <th>Gene Name</th>
                    <th>Motifs Num.</th>
                    <th>Motifs Num. in CLIP</th>
                    <th>LOD Score</th>
                    <th>Dispersity</th>
                    <th>CLIPdb CLIP</th>
                    <th>EMTAB2706 Median</th>
                    <th>EMTAB2706 Max</th>
                    <th>EMTAB2770 Median</th>
                    <th>EMTAB2770 Max</th>
                    <th>GTEX Median</th>
                    <th>GTEX Max</th>
                    <th class="graph-th">Graph</th>
                    <th class="button-in-table"></th>
                    <th class="none">Binding Sites: </th>
                </tr>
                </thead>
                <tbody>
                @php $i = 0 @endphp
                @foreach ($rbps as $rbp)
                    @php
                        $result = App\Models\Table4::where("transcript_id", $rbp->lncRNA_id)->first();
                        $i++;
                    @endphp
                    <tr>
                        <td class="transcript_id">{{ $rbp->lncRNA_id }} </td>
                        <td class="hidden">1:21202-23745</td>
                        <td class="kmers">{{ $result->gene_name }}</td>
                        <td>{{ $rbp->num_motifs }}</td>
                        <td>{{ $rbp->num_motifs_in_CLIP_peaks }}</td>
                        <td>{{ $rbp->LOD_score }}</td>
                        <td>{{ $rbp->disp_score }}</td>
                        <td>{{ $rbp->num_eCLIP_peaks }}</td>
                        <td>{{ $rbp->EMTAB2706_med_exp }}</td>
                        <td>{{ $rbp->EMTAB2706_max_exp }}</td>
                        <td>{{ $rbp->EMTAB2770_med_exp }}</td>
                        <td>{{ $rbp->EMTAB2770_max_exp }}</td>
                        <td>{{ $rbp->GTEX_max_exp }}</td>
                        <td>{{ $rbp->GTEX_max_exp }}</td>
                        <td class="genome-cell">
                            <svg id="genome{{ $rbp->lncRNA_id }}" class="genome-in-table" width="100%" height="20"></svg>
                            <span class="hidden rangeLncRNA">{{ $result->transcript_epos - $result->transcript_spos }}</span>
                            <span class="hidden motifList">{{ $rbp->motif_list }}</span>
                        </td>
                        <td class="iconned-td">
                            <a  data-toggle="modal" style="margin-right: 7px;" onclick = "showup()" data-target="#externalpage"><i class="fa fa-eye"></i></a>
                            <a class="select-analysis" data-toggle="modal" style="color: #880E4F;" data-id="{{$rbp->lncRNA_id}}" data-target="#selectanalysis"><i class="far fa-chart-bar"></i></a>
                        </td>
                        <td class="genome-cell">
                            <svg id="bigGenome{{ $rbp->lncRNA_id }}" class="genome-in-table" width="100%" height="20"></svg>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="genomeviewer" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLongTitle">Genome Viewer</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="selectanalysis" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header padding-20-30">
                    <h4 class="modal-title" id="exampleModalLongTitle">Select your interested Dataset</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="ajaxForm">
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="text" class="hidden" name="lncrna_id" id="lncrna_id">
                                    <input type="text" class="hidden" name="job_id" value="{{ @$job_id }}"{>
                                    <input type="text" class="hidden" name="rbp_name" id="rbp_name" value="{{ $rbp_name }}">
                                    <select class="selectpicker form-control show-tick" title="Choose one of the Dataset..." required name="dataset_id" id="dataset_id" data-live-search="true">
                                        <optgroup label="EMTABs">
                                            <option value="EMTAB2706">EMTAB2706</option>
                                            <option value="EMTAB2770">EMTAB2770</option>
                                        </optgroup>
                                        <optgroup label="GTEX">
                                            <option value="GTEX_Muscle">Muscle - 430</option>
                                            <option value="GTEX_Blood_Vessel">Blood Vessel - 689</option>
                                            <option value="GTEX_Heart">Heart - 412</option>
                                            <option value="GTEX_Adipose_Tissue">Adipose Tissue - 577</option>
                                            <option value="GTEX_Uterus">Uterus - 83</option>
                                            <option value="GTEX_Vagina">Vagina - 96</option>
                                            <option value="GTEX_Breast">Breast - 214</option>
                                            <option value="GTEX_Skin">Skin - 891</option>
                                            <option value="GTEX_Salivary_Gland">Salivary Gland - 57</option>
                                            <option value="GTEX_Brain">Brain - 1259</option>
                                            <option value="GTEX_Adrenal_Gland">Adrenal Gland - 145</option>
                                            <option value="GTEX_Thyroid">Thyroid - 323</option>
                                            <option value="GTEX_Lung">Lung - 320</option>
                                            <option value="GTEX_Pancreas">Pancreas - 171</option>
                                            <option value="GTEX_Esophagus">Esophagus - 686</option>
                                            <option value="GTEX_Stomach">Stomach - 193</option>
                                            <option value="GTEX_Colon">Colon - 345</option>
                                            <option value="GTEX_Small_Intestine">Small Intestine - 88</option>
                                            <option value="GTEX_Prostate">Prostate - 106</option>
                                            <option value="GTEX_Testis">Testis - 172</option>
                                            <option value="GTEX_Nerve">Nerve - 304</option>
                                            <option value="GTEX_Blood">Blood - 510</option>
                                            <option value="GTEX_Spleen">Spleen - 104</option>
                                            <option value="GTEX_Pituitary">Pituitary - 103</option>
                                            <option value="GTEX_Ovary">Ovary - 97</option>
                                            <option value="GTEX_Liver">Liver - 119</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="see_analysis">See Analysis</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="chart" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lgrbp_plot extra-large-modal" role="document" style="width: 800px">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Expression Table</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fa fa-times-circle"></i></span>
                    </button>
                </div>
                <div class="modal-body" style="height: 730px;">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="tabbable">
                                    <ul class="nav tab-group" id="generalChart"><!--nav-tabs nav-justified-->
                                        <li class="active"><a href="#rbp_plot" data-toggle="tab" id="rbp_tab"></a></li>
                                        <li><a href="#lncrna_plot" data-toggle="tab" id="lncrna_tab"></a></li>
                                        <li><a href="#correlation" data-toggle="tab">Correlation</a></li>
                                        <li><a href="#regression" data-toggle="tab">Regression</a></li>
                                        <li><a href="#knockdown" data-toggle="tab">Knockdown Data</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="rbp_plot">
                                            <div id="rbp_tab_content"></div>
                                        </div>
                                        <div class="tab-pane" id="lncrna_plot">
                                            <div id="lncrna_tab_content"></div>
                                        </div>
                                        <div class="tab-pane" id="correlation">
                                            <div id="corr_tab_content"></div>
                                        </div>
                                        <div class="tab-pane" id="regression">
                                            <div id="regression_tab_content"></div>
                                        </div>
                                        <div class="tab-pane" id="knockdown">
                                            <div id="knockdown_tab_content"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
