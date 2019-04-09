                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
				@stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-users"></i> Settings </h2>
                </div>
                <div class="contentpanel">
                	@if(isset($errors) && count($errors) > 0)
                	<div class="row">
                		<div class="col-sm-12">
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <strong>
								@if ($errors->has('upload_resume'))
								    Upload resume is required<br>
								@endif
								@if ($errors->has('download_report'))
								    Download resume is invalid<br>
								@endif
								</strong>
                            </div>
                		</div>
                	</div>
			    	@endif
                    <div class="row">
                    	<div class="panel panel-default">
        					<div class="panel-body panel-body-nopadding">
        						<form id ="basicForm" class="form-horizontal form-bordered" method="post" action="{{ url("settings/$setting->id")}}">
									<div class="form-group">
										<label class="col-sm-3 control-label">Upload Resume</label>
										<div class="col-sm-6">
											<select class="form-control input-sm" name="upload_resume">
												@if($setting->upload_resume == 1)
													<option value="1" selected>ON</option>
													<option value="0">OFF</option>
												@else
													<option value="1">ON</option>
													<option value="0" selected>OFF</option>
												@endif
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Download Report</label>
										<div class="col-sm-6">
											<select class="form-control input-sm" name="download_report">
												@if($setting->download_report == 1)
													<option value="1" selected>ON</option>
													<option value="0">OFF</option>
												@else
													<option value="1">ON</option>
													<option value="0" selected>OFF</option>
												@endif
											</select>
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
				<script>
				    jQuery(document).ready(function() {
					    $(".mainpanel").css("height", "");
				        // Spinner
				        var quantity = jQuery('#quantity').spinner();
						quantity.spinner('value', 0);
				        // Chosen
						jQuery(".chosen-select").chosen({'width':'100%','white-space':'nowrap'});
				        // Basic Form
				        jQuery("#basicForm").validate({
				            highlight: function(element) {
				                jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
				            },
				            success: function(element) {
				                jQuery(element).closest('.form-group').removeClass('has-error');
				            }
				        });
				    });
				</script>
                @stop