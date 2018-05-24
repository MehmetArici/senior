@extends('app')

@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/dropify/css/dropify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-select/css/bootstrap-select.min.css') }}">
@endsection
@section('js')
    <script type="text/javascript" src="{{ asset('plugins/dropify/js/dropify.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
    <script>
        $('.target').dropify({
            messages: {
                'default': 'Drag and drop target file here or click',
            }
        });
        $('.background').dropify({
            messages: {
                'default': 'Drag and drop background file here or click',
            }
        });
        $("a.my-tool-tip").tooltip();
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-8 col-md-offset-2 form-inline">
                <h2>Submit new analysis</h2>
                <a href="{{ route('load_exapmle') }}" class="button blue" href="#"><i style="margin-right: 5px" class="fa fa-file-alt"></i>Load Example</a>
            </div>
            <form method="post" action="{{ route('post_analyze') }}" class="col-md-8 col-md-offset-2 form-inline" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>1. Select your RBP</h3>
                        </div>
                        <div class="col-md-12">
                            <select class="selectpicker form-control show-tick" title="Choose one of the RBP..." name="rbp_id" data-live-search="true">
                                @foreach(\App\Models\Rbp_list::all() as $rbp)
                                    <option {{ @$loaded_rbp->id != $rbp->id || old('rbp_id') == $rbp->id ? "" : "selected" }} value="{{ $rbp->id }}">{{ $rbp->rbp_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h3>
                                2. Select your Gene Target or Background
                                (<a class='my-tool-tip' data-toggle="tooltip" data-placement="right" title="Optional!!! If you did not select, you automatically use systems data">
                                    <i class='fa fa-info-circle info-fa'></i>
                                </a>)
                            </h3>
                        </div>
                        @if (@$target_files)
                            @foreach($target_files as $target_file)
                                <div class="col-md-6">
                                    <div class="card">
                                        <input type="file" name="{{ $target_file->isTarget ? "target_file" : "background_file" }}" class="{{ $target_file->isTarget ? "target" : "background" }}" data-height="120" data-default-file="{{ asset($target_file->file_path) }}"/>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-md-6">
                                <div class="card">
                                    <input type="file" name="target_file" class="target" data-height="120" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <input type="file" name="background_file" class="background" data-height="120" />
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h3>
                                3. Insert Email
                                (<a class='my-tool-tip' data-toggle="tooltip" data-placement="right" title="Optional!!! If you type it, System sends you a link which you are reach your analysis.">
                                    <i class='fa fa-info-circle info-fa'></i>
                                </a>)
                            </h3>
                        </div>
                        <div class="container">
                            <div class="contact">
                                <div class="form-area">
                                    <div class="col-md-12 group form-group">
                                        <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}">
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                        <label class="material-label">Email Address</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <button type="submit" class="button blue" href="#"><i style="margin-right: 5px" class="fa fa-paper-plane"></i>Send Job</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection