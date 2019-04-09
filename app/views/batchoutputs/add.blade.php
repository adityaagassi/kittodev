                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/bootstrap-timepicker.min.css") }}" rel="stylesheet" />
			        <style type="text/css">
			        	.bootstrap-timepicker-widget table td input {
			        		width: 104px;
			        	}
			        </style>
				@stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-tag"></i> Add Batch Output </h2>
                </div>
                <div class="contentpanel">
                	@if(isset($errors) && count($errors) > 0)
                	<div class="row">
                		<div class="col-sm-12">
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <strong>
								@if ($errors->has('time'))
								    Time is required
								@endif
								</strong>
                            </div>
                		</div>
                	</div>
			    	@endif
                    <div class="row">
                    	<div class="panel panel-default">
        					<div class="panel-body panel-body-nopadding">
        						<form id ="basicForm" class="form-horizontal form-bordered" method="post" action="{{ url("batchoutputs/add")}}">
									<div class="form-group">
										<label class="col-sm-3 control-label">Time</label>
										<div class="col-sm-6 ">
											<div class="bootstrap-timepicker">
												<input id="timepicker" type="text" placeholder="11:59" class="form-control" name="time" required />
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Index</label>
										<div class="col-sm-6 ">
											<input type="text" placeholder="Index" class="form-control" name="slug" required />
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-offset-3 col-sm-6">
											<button class="btn btn-primary">Submit</button>&nbsp;
											<a class="btn btn-default">Cancel</a>
										</div>
									</div>
								</form>
        					</div><!-- panel-body -->
      					</div><!-- panel -->
                    </div>
                </div>
                @stop

                @section('scripts')
				<script src="{{ url("js/bootstrap-timepicker.min.js") }}"></script>
				<script>
				    jQuery(document).ready(function() {

					    $(".mainpanel").css("height", "");
						jQuery('#timepicker').timepicker({showMeridian: false, minuteStep: 1});
					 
				    });
				</script>
                @stop