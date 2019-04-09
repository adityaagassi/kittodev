                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
				@stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-bars"></i> Update Completions </h2>
                </div>
                <div class="contentpanel">
                	@if(isset($errors) && count($errors) > 0)
                	<div class="row">
                		<div class="col-sm-12">
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <strong>
								@if ($errors->has('barcode_number'))
								    Barcode Number is required<br>
								@endif
								@if ($errors->has('material_id'))
								    Material Number invalid<br>
								@endif
								@if ($errors->has('issue_plant'))
								    Issue Plant is required<br>
								@endif
								@if ($errors->has('lot_completion'))
								    Lot Completion is required<br>
								@endif
								@if ($errors->has('lead_time'))
								    Lead Time is required<br>
								@endif
								@if ($errors->has('limit_used'))
								    Limit is required<br>
								@endif
								@if ($errors->has('active'))
								    Status is required<br>
								@endif
								</strong>
                            </div>
                		</div>
                	</div>
			    	@endif
                    <div class="row">
                    	<div class="panel panel-default">
        					<div class="panel-body panel-body-nopadding">
        						<form id ="basicForm" class="form-horizontal form-bordered" method="post" action="{{ url("completions/$completion->id/edit")}}">
									<div class="form-group">
										<label class="col-sm-3 control-label">Barcode Number <span class="asterisk">*</span></label>
										<div class="col-sm-6 ">
											<input type="text" placeholder="Barcode Number" class="form-control" name="barcode_number" value="{{ $completion->barcode_number }}" required />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Material Number <span class="asterisk">*</span></label>
										<div class="col-sm-6 ">
											<select class="form-control chosen-select" name="material_id" data-placeholder="Choose a Material Number...">
												<option value=""></option>
												@if(isset($materials) && count($materials) > 0)
												@foreach($materials as $material)
												@if($completion->material_id == $material->id)
												<option value="{{ $material->id }}" selected>{{ $material->material_number }}</option>
												@else
												<option value="{{ $material->id }}">{{ $material->material_number }} - {{ $material->description }}</option>
												@endif
												@endforeach
												@endif
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Issue Plant <span class="asterisk">*</span></label>
										<div class="col-sm-6 ">
											<select class="form-control chosen-select" name="issue_plant" data-placeholder="Choose a Issue Plant..." required>
												@if($completion->issue_plant == "8190")
												<option value="8190" selected>8190</option>
												<option value="8191">8191</option>
												@else
												<option value="8190">8190</option>
												<option value="8191" selected>8191</option>
												@endif
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Lot Completion <span class="asterisk">*</span></label>
										<div class="col-sm-6 ">
											<div class="input-group">
												<input type="number" placeholder="Lot Completion" class="form-control" name="lot_completion" required value="{{ $completion->lot_completion }}" />
												<span class="input-group-addon">item(s)</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Limit</label>
										<div class="col-sm-6 ">
											<div class="input-group">
												<input type="number" placeholder="Limit" class="form-control" name="limit_used" required value="{{ $completion->limit_used }}"  />
												<span class="input-group-addon">used</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Status</label>
										<div class="col-sm-6 ">
											<select class="form-control chosen-select" name="active" data-placeholder="Choose a Status...">
												@if($completion->active == 1)
												<option value="1" selected>Active</option>
												<option value="0">Non Active</option>
												@else
												<option value="1">Active</option>
												<option value="0" selected>Non Active</option>
												@endif
											</select>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-offset-3 col-sm-6">
											<button class="btn btn-primary" type="submit">Submit</button>&nbsp;
											<a class="btn btn-danger" href="{{ url('/completions/list') }}">Cancel</a>
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
				        var lot_completion = jQuery('#lot_completion').spinner();
						lot_completion.spinner('value', 0);
				        // Spinner
				        var lead_time = jQuery('#lead_time').spinner();
						lead_time.spinner('value', 0);
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