                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
				@stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-bars"></i> Add Materials </h2>
                </div>
                <div class="contentpanel">
                	@if(isset($errors) && count($errors) > 0)
                	<div class="row">
                		<div class="col-sm-12">
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <strong>
								@if ($errors->has('material_number'))
								    Material Number invalid<br>
								@endif
								@if ($errors->has('lead_time'))
								    Lead Time is required<br>
								@endif
								</strong>
                            </div>
                		</div>
                	</div>
			    	@endif
                    <div class="row">
                    	<div class="panel panel-default">
        					<div class="panel-body panel-body-nopadding">
        						<form id ="basicForm" class="form-horizontal form-bordered" method="post" action="{{ url("materials/add")}}">
									<div class="form-group">
										<label class="col-sm-3 control-label">Material Number <span class="asterisk">*</span></label>
										<div class="col-sm-6 ">
											<input type="text" placeholder="Material Number" class="form-control" name="material_number" required onkeyup="this.value=this.value.toUpperCase();" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Description <span class="asterisk">*</span></label>
										<div class="col-sm-6 ">
											<input type="text" placeholder="Description" class="form-control" name="description" required onkeyup="this.value=this.value.toUpperCase();" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Location <span class="asterisk">*</span></label>
										<div class="col-sm-6 ">
											<input type="text" placeholder="Location" class="form-control" name="location"  required onkeyup="this.value=this.value.toUpperCase();" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Lead Time <span class="asterisk">*</span></label>
										<div class="col-sm-6 ">
											<div class="input-group">
												<input type="number" placeholder="Lead Time in minute(s)" class="form-control" name="lead_time" required value="0" />
												<span class="input-group-addon">minute(s)</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-offset-3 col-sm-6">
											<button class="btn btn-primary" type="submit">Submit</button>&nbsp;
											<a class="btn btn-danger" href="{{ url('/materials') }}">Cancel</a>
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