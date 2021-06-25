                @extends('master')

                @section('stylesheets')
                <link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
                @stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-bars"></i> Adjustment Completion Report</h2>
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Row : {{ count($ok_count) + count($error_count) }} Row(s)</h3>
                </div>
                <div class="contentpanel">
                	<div class="row">
                        @if(count($error_count) > 0)
                        <div class="col-md-12">
                            <div class="panel panel-danger">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Error Adjustments {{ count($error_count) }} Row(s)</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="form-horizontal form-bordered">
                                        <div class="form-group">
                                            @foreach($error_count as $row) 
                                            <div class="col-sm-12">
                                                {{ $row }}
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(count($ok_count) > 0)
                        <div class="col-md-12">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Success Adjustments {{ count($ok_count) }} Row(s)</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="form-horizontal form-bordered">
                                        <div class="form-group">
                                            @foreach($ok_count as $row) 
                                            <div class="col-sm-12">
                                                {{ $row }}
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @stop

                @section('scripts')
                <script>
                    jQuery(document).ready(function() {
                        $(".mainpanel").css("height", "");
                    });
                </script>
                @stop