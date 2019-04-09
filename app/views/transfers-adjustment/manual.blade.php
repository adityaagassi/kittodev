                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
			        <link href="{{ url("css/jquery.gritter.css") }}" rel="stylesheet">
					<link href="{{ url("css/bootstrap-timepicker.min.css") }}" rel="stylesheet" />
			        <style type="text/css">
			        	.bootstrap-timepicker-widget table td input {
			        		width: 104px;
			        	}
			        </style>
				@stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-barcode"></i> Add Adjustment Transfer </h2>
                </div>
                <div class="contentpanel">
                	@if(isset($errors) && count($errors) > 0)
                	<div class="row">
                		<div class="col-sm-12">
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <strong>
								@if ($errors->has('lot_transfer'))
								    Lot Transfer is required<br>
								@endif
								</strong>
                            </div>
                		</div>
                	</div>
			    	@endif
                    <div class="row">
						<div class="panel panel-default">
							<div class="panel-body panel-body-nopadding">
								<form id="basicForm" class="form-horizontal form-bordered" method="POST" action="{{ url('/transfers/adjustment/manual') }}">
									<div class="form-group">
										<label class="col-sm-3 control-label">Document Number</label>
										<div class="col-sm-6 ">
											<input type="text" placeholder="Document Number" class="form-control" name="document_number" />
											<input type="hidden" name="user_id" value="{{ $user_id }}" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Material Number <span class="asterisk">*</span></label>
										<div class="col-sm-6 ">
											<select class="form-control chosen-select" name="material_id" data-placeholder="Choose a Material Number...">
												<option value=""></option>
												@if(isset($materials) && count($materials) > 0)
												@foreach($materials as $material)
												<option value="{{ $material->id }}">{{ $material->material_number }}</option>
												@endforeach
												@endif
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Issue Plant <span class="asterisk">*</span></label>
										<div class="col-sm-6 ">
											<input type="text" placeholder="Issue Plant" class="form-control" name="issue_plant" maxlength="4" required />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Issue Location <span class="asterisk">*</span></label>
										<div class="col-sm-6 ">
											<input type="text" placeholder="Issue Location" class="form-control" name="issue_location" maxlength="4" required />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Receive Plant <span class="asterisk">*</span></label>
										<div class="col-sm-6 ">
											<input type="text" placeholder="Receive Plant" class="form-control" name="receive_plant" maxlength="4" required />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Receive Location <span class="asterisk">*</span></label>
										<div class="col-sm-6 ">
											<input type="text" placeholder="Receive Location" class="form-control" name="receive_location" maxlength="4" required />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Quantity <span class="asterisk">*</span></label>
										<div class="col-sm-6 ">
											<div class="input-group">
												<input type="number" placeholder="Quantity" class="form-control" name="lot" value="1" min="1" required />
												<span class="input-group-addon">item(s)</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Cost Center</label>
										<div class="col-sm-6 ">
											<input type="text" placeholder="Cost Center" class="form-control" name="cost_center" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">GL Account</label>
										<div class="col-sm-6 ">
											<input type="text" placeholder="GL Account" class="form-control" name="gl_account" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Date <span class="asterisk">*</span></label>
										<div class="col-sm-6">
											<input type="text" class="form-control datepicker" placeholder="mm/dd/yyyy" name="date" required />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Time <span class="asterisk">*</span></label>
										<div class="col-sm-6">
							                <div class="bootstrap-timepicker">
							                	<input type="text" placeholder="11:59" class="form-control timepicker" name="time" required />
							                </div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Transaction Code</label>
										<div class="col-sm-6 ">
											<input type="text" placeholder="Transaction Code" class="form-control" name="transaction_code" value="MB1B" required />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Movement Type</label>
										<div class="col-sm-6 ">
											<input type="text" placeholder="Movement Type" class="form-control" name="movement_type" required />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Reason Code</label>
										<div class="col-sm-6 ">
											<input type="text" placeholder="Reason Code" class="form-control" name="reason_code" />
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-offset-3 col-sm-3">
											<button class="btn btn-success">Submit</button>
										</div>
									</div>
								</form>
							</div>
						</div>
                    </div>
                </div>
                @stop

                @section('scripts')
				<script src="{{ url("js/jquery.gritter.min.js") }}"></script>
				<script src="{{ url("js/bootstrap-timepicker.min.js") }}"></script>
				<script>

					var index = 0;
					var userID = "{{ $user_id }}";
					var enabled = false;
					var histories = [];

				    jQuery(document).ready(function() {

				        // Chosen
				        $(".mainpanel").css("height", "");
						jQuery(".chosen-select").chosen({'width':'100%','white-space':'nowrap'});
						jQuery('.datepicker').datepicker();
						jQuery('.timepicker').timepicker({showMeridian: false, minuteStep: 1});
				    });

				</script>
                @stop