                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
					<link href="{{ url("css/bootstrap-timepicker.min.css") }}" rel="stylesheet" />
					<link href="{{ url("css/jquery.tagsinput.css") }}" rel="stylesheet" />
			        <link href="{{ url("css/custom.css") }}" rel="stylesheet">
			        <style type="text/css">
			        	.bootstrap-timepicker-widget table td input {
			        		width: 104px;
			        	}
			        </style>
				@stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-bars"></i> Filter Transfers History </h2>
                </div>
                <div class="contentpanel">
                    <div class="row">
                    	<div class="panel panel-default">
        					<div class="panel-body panel-body-nopadding">
        						<form id ="basicForm" class="form-horizontal form-bordered" method="get" action="{{ url("transfers/history")}}">
									<div class="form-group">
										<div class="col-md-3">
											<label class="control-label">Start Date</label>
											<input type="text" class="form-control datepicker" placeholder="mm/dd/yyyy" name="start_date" />
										</div>
										<div class="col-md-3">
											<label class="control-label">&nbsp;</label>
											<div class="bootstrap-timepicker">
												<input type="text" placeholder="11:59" class="form-control timepicker" name="start_time" value="" />
											</div>
										</div>
										<div class="col-md-3">
											<label class="control-label">End Date</label>
											<input type="text" class="form-control datepicker" placeholder="mm/dd/yyyy" name="end_date" />
										</div>
										<div class="col-md-3">
											<label class="control-label">&nbsp;</label>
											<div class="bootstrap-timepicker">
												<input type="text" placeholder="11:59" class="form-control timepicker" name="end_time" value="" />
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-6">
											<label class="control-label">Barcode Number</label>
											<textarea id="barcodeArea" class="form-control" rows="5" placecholder="Paste barcode number from excel here"></textarea>
											<input id="barcodeTags" type="text" placeholder="Barcode Number" class="form-control tags" name="barcode_number" />
										</div>
										<div class="col-md-6">
											<label class="control-label">Material Number</label>
											<textarea id="materialArea" class="form-control" rows="5" placecholder="Paste barcode number from excel here"></textarea>
											<input id="materialTags" type="text" placeholder="Material Number" class="form-control tags" name="material_number" />
											<!-- <select class="form-control chosen-select" name="material_id" data-placeholder="Choose a Material Number...">
												<option value=""></option>
												@if(isset($materials) && count($materials) > 0)
												@foreach($materials as $material)
												<option value="{{ $material->id }}">{{ $material->material_number }}</option>
												@endforeach
												@endif
											</select> -->
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-6 ">
											<label class="control-label">Issue Location</label>
											<textarea id="issueLocationArea" class="form-control" rows="5" placecholder="Paste issue location from excel here"></textarea>
											<input id="issueLocationTags" type="text" placeholder="Issue Location" class="form-control tags" name="issue_location" />
										</div>
										<div class="col-md-6 ">
											<label class="control-label">Issue Plant</label>
											<textarea id="issuePlantArea" class="form-control" rows="5" placecholder="Paste issue plant from excel here"></textarea>
											<input id="issuePlantTags" type="text" placeholder="Issue Plant" class="form-control tags" name="issue_plant" />
										</div>
									</div>
									<div class="form-group">
										
										<div class="col-md-6 ">
											<label class="control-label">Receive Location</label>
											<textarea id="receiveLocationArea" class="form-control" rows="5" placecholder="Paste receive location from excel here"></textarea>
											<input id="receiveLocationTags" type="text" placeholder="Receive Location" class="form-control tags" name="receive_location" />
										</div>
										<div class="col-md-6 ">
											<label class="control-label">Receive Plant</label>
											<textarea id="receivePlantArea" class="form-control" rows="5" placecholder="Paste receive plant from excel here"></textarea>
											<input id="receivePlantTags" type="text" placeholder="Receive Plant" class="form-control tags" name="receive_plant" />
										</div>
									</div>
									<div class="form-group">
									<div class="col-md-6 ">
											<label class="control-label">Category</label>
											<select class="form-control chosen-select input-md" name="category" data-placeholder="Choose a Category..." required>
												<option value=""></option>
												<option value="transfer">Transfer</option>
												<option value="transfer_cancel">Transfer Cancel</option>
												<option value="transfer_return">Transfer Return</option>
												<option value="transfer_repair">Transfer Repair</option>
												<option value="transfer_after_repair">Transfer After Repair</option>
												<option value="transfer_error">Transfer Error</option>
												<option value="transfer_adjustment">Transfer Adjustment</option>
												<option value="ctransfer_adjustment_excel">Transfer Adjustment Excel</option>
												<option value="transfer_adjustment_manual">Transfer Adjustment Manual</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-offset-3 col-md-6">
											<button class="btn btn-primary btn-block btn-lg" type="submit">Submit</button>&nbsp;
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
				<script src="{{ url("js/jquery.tagsinput.min.js") }}"></script>
				<script>
				    jQuery(document).ready(function() {

					    $(".mainpanel").css("height", "");
						jQuery('.tags').tagsInput({ width: 'auto' });
						$('#materialTags').hide();
						$('#materialTags_tagsinput').hide();
						$('#barcodeTags').hide();
						$('#barcodeTags_tagsinput').hide();
						$('#issueLocationTags').hide();
						$('#issueLocationTags_tagsinput').hide();
						$('#issuePlantTags').hide();
						$('#issuePlantTags_tagsinput').hide();
						$('#receiveLocationTags').hide();
						$('#receiveLocationTags_tagsinput').hide();
						$('#receivePlantTags').hide();
						$('#receivePlantTags_tagsinput').hide();
						jQuery('.datepicker').datepicker();
						jQuery('.timepicker').timepicker({showMeridian: false, minuteStep: 1, defaultTime: false});
					    
				        // Chosen
						jQuery(".chosen-select").chosen({'width':'100%','white-space':'nowrap'});
				        // Basic Form
				        jQuery("#basicForm").validate({
				            highlight: function(element) {
				                jQuery(element).closest('.form-group');
				            },
				            success: function(element) {
				                jQuery(element).closest('.form-group');
				            }
				        });

				        initKeyDown();
				    });

				    function initKeyDown() {
						$('#materialArea').keydown(function(event) {
						    if (event.keyCode == 13) {
						        convertMaterialToTags();
						        return false;
						     }
						});
						$('#barcodeArea').keydown(function(event) {
						    if (event.keyCode == 13) {
						        convertBarcodeToTags();
						        return false;
						     }
						});
						$('#issueLocationArea').keydown(function(event) {
						    if (event.keyCode == 13) {
						        convertIssueLocationToTags();
						        return false;
						     }
						});
						$('#issuePlantArea').keydown(function(event) {
						    if (event.keyCode == 13) {
						        convertIssuePlantToTags();
						        return false;
						     }
						});
						$('#receiveLocationArea').keydown(function(event) {
						    if (event.keyCode == 13) {
						        convertReceiveLocationToTags();
						        return false;
						     }
						});
						$('#receivePlantArea').keydown(function(event) {
						    if (event.keyCode == 13) {
						        convertReceivePlantToTags();
						        return false;
						     }
						});
					}

					function convertMaterialToTags() {
						var data = $('#materialArea').val();
				    	if (data.length > 0) {
							var rows = data.split('\n');
							if (rows.length > 0) {
								for (var i = 0; i < rows.length; i++) {
									var barcode = rows[i].trim();
									if (barcode.length > 0) {
										$('#materialTags').addTag(barcode);
									}
								}
								$('#materialTags').hide();
								$('#materialTags_tagsinput').show();
								$('#materialArea').hide();
							}
				    	}
					}

					function convertBarcodeToTags() {
						var data = $('#barcodeArea').val();
				    	if (data.length > 0) {
							var rows = data.split('\n');
							if (rows.length > 0) {
								for (var i = 0; i < rows.length; i++) {
									var barcode = rows[i].trim();
									if (barcode.length > 0) {
										$('#barcodeTags').addTag(barcode);
									}
								}
								$('#barcodeTags').hide();
								$('#barcodeTags_tagsinput').show();
								$('#barcodeArea').hide();
							}
				    	}
					}

					function convertIssueLocationToTags() {
						var data = $('#issueLocationArea').val();
				    	if (data.length > 0) {
							var rows = data.split('\n');
							if (rows.length > 0) {
								for (var i = 0; i < rows.length; i++) {
									var barcode = rows[i].trim();
									if (barcode.length > 0) {
										$('#issueLocationTags').addTag(barcode);
									}
								}
								$('#issueLocationTags').hide();
								$('#issueLocationTags_tagsinput').show();
								$('#issueLocationArea').hide();
							}
				    	}
					}

					function convertIssuePlantToTags() {
						var data = $('#issuePlantArea').val();
				    	if (data.length > 0) {
							var rows = data.split('\n');
							if (rows.length > 0) {
								for (var i = 0; i < rows.length; i++) {
									var barcode = rows[i].trim();
									if (barcode.length > 0) {
										$('#issuePlantTags').addTag(barcode);
									}
								}
								$('#issuePlantTags').hide();
								$('#issuePlantTags_tagsinput').show();
								$('#issuePlantArea').hide();
							}
				    	}
					}

					function convertReceiveLocationToTags() {
						var data = $('#receiveLocationArea').val();
				    	if (data.length > 0) {
							var rows = data.split('\n');
							if (rows.length > 0) {
								for (var i = 0; i < rows.length; i++) {
									var barcode = rows[i].trim();
									if (barcode.length > 0) {
										$('#receiveLocationTags').addTag(barcode);
									}
								}
								$('#receiveLocationTags').hide();
								$('#receiveLocationTags_tagsinput').show();
								$('#receiveLocationArea').hide();
							}
				    	}
					}

					function convertReceivePlantToTags() {
						var data = $('#receivePlantArea').val();
				    	if (data.length > 0) {
							var rows = data.split('\n');
							if (rows.length > 0) {
								for (var i = 0; i < rows.length; i++) {
									var barcode = rows[i].trim();
									if (barcode.length > 0) {
										$('#receivePlantTags').addTag(barcode);
									}
								}
								$('#receivePlantTags').hide();
								$('#receivePlantTags_tagsinput').show();
								$('#receivePlantArea').hide();
							}
				    	}
					}
				</script>
                @stop