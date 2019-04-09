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
                    <h2><i class="fa fa-barcode"></i> Add Repair </h2>
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
                    	<div class="col-md-12">
							<div class="panel panel-default">
								<div class="panel-body panel-body-nopadding">
									<div id="validationWizard" class="basic-wizard">
										<ul class="nav nav-pills nav-justified">
											<li><a href="#vtab1" data-toggle="tab"><span>Step 1:</span> Input Location & Plant</a></li>
											<li><a href="#vtab2" data-toggle="tab"><span>Step 2:</span> Input Material Number & Quantity</a></li>
										</ul>
										<form id="firstForm" class="form-horizontal form-bordered" method="POST" action="{{ url('/repairs/add') }}">
							                <div class="tab-content">
							                	<div class="tab-pane" id="vtab1">
													<div class="form-group">
														<label class="col-sm-3 control-label">Issue Location <span class="asterisk">*</span></label>
														<div class="col-sm-6 ">
															<input type="hidden" name="user_id" value="{{ $user_id }}" />
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
													<div class="form-group" style="display: none;">
														<label class="col-sm-3 control-label">Issue Plant <span class="asterisk">*</span></label>
														<div class="col-sm-6 ">
															<select class="form-control chosen-select" name="issue_plant" data-placeholder="Choose a Issue Plant..." required>
																<option value=""></option>
																<option value="8190" selected>8190</option>
																<option value="8191">8191</option>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label">Receive Location <span class="asterisk">*</span></label>
														<div class="col-sm-6 ">
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
													<div class="form-group" style="display: none;">
														<label class="col-sm-3 control-label">Receive Plant <span class="asterisk">*</span></label>
														<div class="col-sm-6 ">
															<select class="form-control chosen-select" name="receive_plant" data-placeholder="Choose a Receive Plant..." required onchange="receivePlantOnChange();">
																<option value=""></option>
																<option value="8190" selected>8190</option>
																<option value="8191">8191</option>
															</select>
														</div>
													</div>
													<div class="form-group" style="display: none;">
														<label class="col-sm-3 control-label">Movement Type <span class="asterisk">*</span></label>
														<div class="col-sm-6">
															<select class="form-control chosen-select" name="movement_type" data-placeholder="Choose a Movement Type..." required onchange="receivePlantOnChange();">
																<option value=""></option>
																<option value="9I3" selected>9I3</option>
																<option value="9I4" >9I4</option>
															</select>
														</div>
													</div>
												</div>
							                	<div class="tab-pane" id="vtab2">
							                		<div class="form-group">
														<label class="col-sm-5">Material Number <span class="asterisk">*</span></label>
														<label class="col-sm-5">Quantity <span class="asterisk">*</span></label>
													</div>
													<div class="form-group">
														<div class="col-sm-5 col-md-5">
															<input type="text" placeholder="Material Number" class="form-control" name="material_number[]" required />
														</div>
														<div class="col-sm-5 col-md-5">
															<div class="input-group">
																<input type="number" placeholder="Quantity" class="form-control" name="lot[]" min="1" required />
																<span class="input-group-addon">item(s)</span>
															</div>
														</div>
														<div class="col-sm-1 col-md-1">
															<a href="javascript:void(0);" class="btn btn-success" onclick="addNewForm();"><span class="fa fa-plus"></span></a>
														</div>
													</div>
													<div id="listDocumentNumbers">
													</div>
							                	</div>
											</div>
										</form>
									</div>
								</div>
								<div class="panel-footer">
									<div class="row">
										<div class="col-md-2">
											<a id="previous" href="#" class="btn btn-default btn-block" onclick="previousForm();">Previous</a>
										</div>
										<div class="col-md-offset-8 col-md-2">
											<a id="next" href="#" class="btn btn-primary btn-block" onclick="nextForm();">Next</a>
											<a id="submit" href="#" class="btn btn-success btn-block" onclick="submitForm();">Submit</a>
										</div>
									</div>
								</div>
							</div>
						</div>
                    </div>
                </div>
                @stop

                @section('scripts')
				<script src="{{ url("js/jquery.gritter.min.js") }}"></script>
				<script src="{{ url("js/bootstrap-timepicker.min.js") }}"></script>
				<script src="{{ url("js/bootstrap-wizard.min.js") }}"></script>
				<script>

					var index = 0;
					var enabled = false;
					var histories = [];
					var rowindex = 0;

				    jQuery(document).ready(function() {

						jQuery('#validationWizard').bootstrapWizard({
							tabClass: 'nav nav-pills nav-justified nav-disabled-click',
							onTabClick: function(tab, navigation, index) {
								return false;
							}
						});

				        // Chosen
				        $(".mainpanel").css("height", "");
						jQuery(".chosen-select").chosen({'width':'100%','white-space':'nowrap'});
						jQuery('.datepicker').datepicker();
						jQuery('.timepicker').timepicker({showMeridian: false, minuteStep: 1});

						setupButton();
				    });

				    // function receivePlantOnChange() {
				    // 	var receive_plant = $('select[name="receive_plant"]').val();
				    // 	var movement_type = $('input[name="movement_type"]');
				    // 	if (receive_plant.length > 0) {
				    // 		switch (receive_plant) {
				    // 			case "8191":
				    // 				movement_type.val('9I3');
				    // 				break;
				    // 			default:
				    // 				movement_type.val('9I4');
				    // 				break;
				    // 		}
				    // 	}
				    // }

				    function setupButton() {
						if (rowindex == 0) {
							$("#previous").show();
							$("#submit").hide();
							$("#next").show();
						}
						else {
							$("#previous").show();
							$("#submit").show();
							$("#next").hide();
						}
					}

				    function addNewForm() {

				    	var addNew = true;
				    	var material_numbers = document.querySelectorAll('input[name="material_number[]"]');
				    	material_numbers.forEach(function(material) {
				    		addNew = (addNew && material.value.length > 0)
				    	})
				    	var lots = document.querySelectorAll('input[name="lot[]"]');
				    	lots.forEach(function(lot) {
				    		addNew = (addNew && lot.value.length > 0 && lot.value > 0)
				    	})

				    	rowindex += 1;
				    	// if (addNew) {
					    	var field = '<div class="form-group" id="' + rowindex + '">';
							field += '<div class="col-sm-5 col-md-5">';
							field += '<input type="text" placeholder="Material Number" class="form-control" name="material_number[]" required />';
							field += '</div>';
							field += '<div class="col-sm-5 col-md-5">';
							field += '<div class="input-group">';
							field += '<input type="number" placeholder="Quantity" class="form-control" name="lot[]" min="1" required />';
							field += '<span class="input-group-addon">item(s)</span>';
							field += '</div>';
							field += '</div>';
							field += '<div class="col-sm-1 col-md-1">';
							field += '<a href="javascript:void(0);" class="btn btn-success" onclick="addNewForm();"><span class="fa fa-plus"></span></a>';
							field += '</div>';
							field += '<div class="col-sm-1 col-md-1">';
							field += '<a href="javascript:void(0);" class="btn btn-danger" onclick="deleteForm(' + rowindex + ');"><span class="fa fa-minus"></span></a>';
							field += '</div>';
							field += '</div>';
					    	$("#listDocumentNumbers").append(field);
				    	// }
				    }

				    function deleteForm(id) {
				    	$("#" + id).remove();
				    }

				    function previousForm() {
				    	$('#validationWizard').bootstrapWizard('show',0);
				    	rowindex = 0;
				    	setupButton();
				    }

				    function nextForm() {
				    	var submit = true;
				    	submit = ($('select[name="issue_location"]').val().length > 0  && submit);
				    	submit = ($('select[name="issue_plant"]').val().length > 0 && submit);
				    	submit = ($('select[name="receive_location"]').val().length > 0 && submit);
				    	submit = ($('select[name="receive_plant"]').val().length > 0 && submit);
				    	// submit = ($('input[name="date"]').val().length > 0 && submit);
				    	// submit = ($('input[name="time"]').val().length > 0 && submit);
				    	if (submit) {
							$('#validationWizard').bootstrapWizard('show',1);
							rowindex = 1;
							setupButton();
						}
				    }

				    function submitForm() {
				    	var $valid = jQuery('#firstForm').valid();
				    	if ($valid) {
							$("#firstForm").submit();
				    	}
				    }
				    
				    $(document).on('keyup', 'input[name="material_number[]"]', function() {
				      var element = this;
				      var id = $(element).val();
					  $.get( "{{ url('/check-material/') }}/" + id , function( data ) {
						  if(data==''){
						  	$(element).addClass('has-error');
						  }else{
						  	$(element).removeClass('has-error');
						  }
						});
					});

				</script>
                @stop