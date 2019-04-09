                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
				@stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-bars"></i> Update Error Report </h2>
                </div>
                <div class="contentpanel">
                	@if(isset($errors) && count($errors) > 0)
                	<div class="row">
                		<div class="col-sm-12">
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <strong>
								@if ($errors->has('completion_location'))
								    Location is required<br>
								@endif
								@if ($errors->has('completion_material_id'))
								    Material Number invalid<br>
								@endif
								@if ($errors->has('completion_issue_plant'))
								    Issue Plant is required<br>
								@endif
								@if ($errors->has('completion_reference_number'))
								    Reference Number is required<br>
								@endif
								</strong>
                            </div>
                		</div>
                	</div>
			    	@endif
			    	@if(isset($errorReport))
	                    <div class="row">
	                        <div class="col-sm-3 col-md-3">
	                            <a class="btn btn-info mb10" href="{{ url('errorreport/'. $filename) }}">← Back</a>
	                        </div>
	                    </div>
			    		@if($errorReport->category == "completion_error")
						<div class="row">
							<div class="panel panel-default">
								<div class="panel-body panel-body-nopadding">
									<form id ="basicForm" class="form-horizontal form-bordered" method="post" action="{{ url("errorreport/$filename/$errorReport->id/edit")}}">
										<div class="form-group">
											<label class="col-sm-3 control-label">Material Number</label>
											<div class="col-sm-6 ">
												<select class="form-control chosen-select" name="completion_material_id" data-placeholder="Choose a Material Number...">
													<option value=""></option>
													@if(isset($materials) && count($materials) > 0)
													@foreach($materials as $material)
													@if($errorReport->completion_material_id == $material->id)
													<option value="{{ $material->id }}" selected>{{ $material->material_number }}</option>
													@else
													<option value="{{ $material->id }}">{{ $material->material_number }}</option>
													@endif
													@endforeach
													@endif
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Location</label>
											<div class="col-sm-6 ">
												<input type="text" placeholder="Location Completion" class="form-control" name="completion_location" value="{{ $errorReport->completion_location }}" required />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Issue Plant</label>
											<div class="col-sm-6 ">
												<input type="text" placeholder="Issue Plant" class="form-control" name="completion_issue_plant" value="{{ $errorReport->completion_issue_plant }}" required />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Lot Completion</label>
											<div class="col-sm-6 ">
												<div class="input-group">
													<input type="number" placeholder="Lot Completion" class="form-control" name="lot" required value="{{ $errorReport->lot }}" />
													<span class="input-group-addon">item(s)</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Reference Number</label>
											<div class="col-sm-6 ">
												<input type="text" placeholder="Reference Number" class="form-control" name="completion_reference_number" value="{{ $errorReport->completion_reference_number }}" required />
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-3 col-sm-6">
												<button class="btn btn-primary" type="submit">Submit</button>&nbsp;
												<a class="btn btn-danger" href="{{ url('/errorreport/$filename') }}">Cancel</a>
											</div>
										</div>
									</form>
								</div><!-- panel-body -->
								</div><!-- panel -->
						</div>
				    	@else
	                    <div class="row">
	                    	<div class="panel panel-default">
	        					<div class="panel-body panel-body-nopadding">
	        						<form id ="basicForm" class="form-horizontal form-bordered" method="post" action="{{ url("errorreport/$filename/$errorReport->id/edit")}}">
										<div class="form-group">
											<label class="col-sm-3 control-label">Document Number</label>
											<div class="col-sm-6 ">
												<input type="text" placeholder="Document Number" class="form-control" name="transfer_document_number" value="{{ $errorReport->transfer_document_number }}" required />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Issue Plant</label>
											<div class="col-sm-6 ">
												<input type="text" placeholder="Issue Plant" class="form-control" name="transfer_issue_plant" value="{{ $errorReport->transfer_issue_plant }}" required />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Material Number</label>
											<div class="col-sm-6 ">
												<select class="form-control chosen-select" name="transfer_material_id" data-placeholder="Choose a Material Number...">
													<option value=""></option>
													@if(isset($materials) && count($materials) > 0)
													@foreach($materials as $material)
													@if($errorReport->transfer_material_id == $material->id)
													<option value="{{ $material->id }}" selected>{{ $material->material_number }}</option>
													@else
													<option value="{{ $material->id }}">{{ $material->material_number }}</option>
													@endif
													@endforeach
													@endif
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Issue Location</label>
											<div class="col-sm-6 ">
												<input type="text" placeholder="Issue Location" class="form-control" name="transfer_issue_location" value="{{ $errorReport->transfer_issue_location }}" required />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Receive Plant</label>
											<div class="col-sm-6 ">
												<input type="text" placeholder="Receive Location" class="form-control" name="transfer_receive_plant" value="{{ $errorReport->transfer_receive_plant }}" required />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Receive Location</label>
											<div class="col-sm-6 ">
												<input type="text" placeholder="Receive Location" class="form-control" name="transfer_receive_location" value="{{ $errorReport->transfer_receive_location }}" required />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Cost Center</label>
											<div class="col-sm-6 ">
												<input type="text" placeholder="Cost Center" class="form-control" name="transfer_cost_center" value="{{ $errorReport->transfer_cost_center }}" required />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">GL Account</label>
											<div class="col-sm-6 ">
												<input type="text" placeholder="GL Account" class="form-control" name="transfer_gl_account" value="{{ $errorReport->transfer_gl_account }}" required />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Transaction Code</label>
											<div class="col-sm-6 ">
												<input type="text" placeholder="Transaction Code" class="form-control" name="transfer_transaction_code" value="{{ $errorReport->transfer_transaction_code }}" required />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Movement Type</label>
											<div class="col-sm-6 ">
												<input type="text" placeholder="Movement Type" class="form-control" name="transfer_movement_type" value="{{ $errorReport->transfer_movement_type }}" required />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Reason Code</label>
											<div class="col-sm-6 ">
												<input type="text" placeholder="Reason Code" class="form-control" name="transfer_reason_code" value="{{ $errorReport->transfer_reason_code }}" required />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Lot Completion</label>
											<div class="col-sm-6 ">
												<div class="input-group">
													<input type="number" placeholder="Lot Completion" class="form-control" name="lot" required value="{{ $errorReport->lot }}" />
													<span class="input-group-addon">item(s)</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-3 col-sm-6">
												<button class="btn btn-primary" type="submit">Submit</button>&nbsp;
												<a class="btn btn-danger" href="{{ url('/errorreport/$filename') }}">Cancel</a>
											</div>
										</div>
									</form>
	        					</div><!-- panel-body -->
	      					</div><!-- panel -->
	                    </div>
			    		@endif
			    	@endif
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