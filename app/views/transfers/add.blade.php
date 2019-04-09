                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
				@stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-bars"></i> Add Transfer </h2>
                </div>
                <div class="contentpanel">
                	@if(isset($errors) && count($errors) > 0)
                	<div class="row">
                		<div class="col-sm-12">
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <strong>
                                {{ json_encode($errors) }}
								@if ($errors->has('barcode_number_transfer'))
								    Barcode Number is required<br>
								@endif
								@if ($errors->has('issue_location'))
								    Issue Location is required<br>
								@endif
								@if ($errors->has('issue_plant'))
								    Issue Plant is required<br>
								@endif
								@if ($errors->has('receive_location'))
								    Receive Location is required<br>
								@endif
								@if ($errors->has('receive_plant'))
								    Receive Plant is required<br>
								@endif
								@if ($errors->has('transaction_code'))
								    Transaction Code is required<br>
								@endif
								@if ($errors->has('movement_type'))
								    Movement Type is required<br>
								@endif
								@if ($errors->has('lot_transfer'))
								    Lot Transfer is required<br>
								@endif
								@if ($errors->has('completion_id'))
								    Completion Barcode Number is required<br>
								@endif
								</strong>
                            </div>
                		</div>
                	</div>
			    	@endif
                    <div class="row">
                    	<div class="panel panel-default">
        					<div class="panel-body panel-body-nopadding">
        						<form id ="basicForm" class="form-horizontal form-bordered" method="post" action="{{ url("transfers/add")}}">
									<div class="form-group">
										<label class="col-sm-3 control-label">Barcode Number <span class="asterisk">*</span></label>
										<div class="col-sm-6 ">
											<input type="text" placeholder="Barcode Number" class="form-control" name="barcode_number_transfer" required />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Issue Location <span class="asterisk">*</span></label>
										<div class="col-sm-6 ">
											<select class="form-control chosen-select" name="issue_location" data-placeholder="Choose a Issue Location..." required>
												<option value=""></option>
												@if(isset($issue_locations) && count($issue_locations) > 0)
												@foreach($issue_locations as $issue_location)
												<option value="{{ $issue_location->location }}">{{ $issue_location->location }}</option>
												@endforeach
												@endif
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Issue Plant <span class="asterisk">*</span></label>
										<div class="col-sm-6 ">
											<select class="form-control chosen-select" name="issue_plant" data-placeholder="Choose a Issue Plant..." required>
												<option value="8190" selected>8190</option>
												<option value="8191">8191</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Receive Location <span class="asterisk">*</span></label>
										<div class="col-sm-6 ">
											<!-- <input type="text" placeholder="Receive Location" class="form-control" name="receive_location" required /> -->
											<select class="form-control chosen-select" name="receive_location" data-placeholder="Choose a Receive Location..." required>
												<option value=""></option>
												@if(isset($receive_locations) && count($receive_locations) > 0)
												@foreach($receive_locations as $receive_location)
												<option value="{{ $receive_location }}">{{ $receive_location }}</option>
												@endforeach
												@endif
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Receive Plant <span class="asterisk">*</span></label>
										<div class="col-sm-6 ">
											<select class="form-control chosen-select" name="receive_plant" data-placeholder="Choose a Receive Plant..." required>
												<option value="8190" selected>8190</option>
												<option value="8191">8191</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Transaction Code <span class="asterisk">*</span></label>
										<div class="col-sm-6 ">
											<input type="text" placeholder="Transaction Code" class="form-control" name="transaction_code" value="MB1B" required />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Movement Type <span class="asterisk">*</span></label>
										<div class="col-sm-6 ">
											<input type="text" placeholder="Movement Type" class="form-control" name="movement_type" value="9I3"  required />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Reason Code</label>
										<div class="col-sm-6 ">
											<input type="text" placeholder="Reason Code" class="form-control" name="reason_code" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Lot Transfer <span class="asterisk">*</span></label>
										<div class="col-sm-6 ">
											<div class="input-group">
												<input type="number" placeholder="Lot Transfer" class="form-control" name="lot_transfer" required value="0" />
												<span class="input-group-addon">item(s)</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Completion Barcode Number <span class="asterisk">*</span></label>
										<div class="col-sm-6 ">
											<select class="form-control chosen-select" name="completion_id" data-placeholder="Choose a Completion Barcode Number..." required>
												<option value=""></option>
												@if(isset($completions) && count($completions) > 0)
												@foreach($completions as $completion)
												@if(isset($completion_id))
													@if($completion_id == $completion->id)
													<option value="{{ $completion->id }}" selected>{{ $completion->barcode_number }}</option>
													@else
													<option value="{{ $completion->id }}">{{ $completion->barcode_number }}</option>
													@endif
												@else
													<option value="{{ $completion->id }}">{{ $completion->barcode_number }}</option>
												@endif
												@endforeach
												@endif

											</select>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-offset-3 col-sm-6">
											<button class="btn btn-primary" type="submit">Submit</button>&nbsp;
											@if(isset($completion_id))
											<a class="btn btn-danger" href="{{ url('/completions/' . $completion_id) }}">Cancel</a>
											@else
											<a class="btn btn-danger" href="{{ url('/transfers/list') }}">Cancel</a>
											@endif
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