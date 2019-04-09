                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
					<link href="{{ url("css/bootstrap-timepicker.min.css") }}" rel="stylesheet" />
					<link href="{{ url("css/jquery.tagsinput.css") }}" rel="stylesheet" />
			        <style type="text/css">
			        	.bootstrap-timepicker-widget table td input {
			        		width: 104px;
			        	}
			        </style>
				@stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-bars"></i> Filter Completions History </h2>
                </div>
                <div class="contentpanel">
                    <div class="row">
                    	<div class="panel panel-default">
        					<div class="panel-body panel-body-nopadding">
        						<form id ="basicForm" class="form-horizontal form-bordered" method="get" action="{{ url("completions/history")}}">
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
											<textarea id="materialArea" class="form-control" rows="5" placecholder="Paste material number from excel here"></textarea>
											<input id="materialTags" type="text" placeholder="Material Number" class="form-control tags" name="material_number" />
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-6 ">
											<label class="control-label">Location Completion</label>
											<textarea id="locationArea" class="form-control" rows="5" placecholder="Paste location from excel here"></textarea>
											<input id="locationTags" type="text" placeholder="Location Completion" class="form-control tags" name="location_completion" />
										</div>
										<div class="col-md-6 ">
											<label class="control-label">Category</label>
											<select class="form-control chosen-select input-md" name="category" data-placeholder="Choose a Category..." required>
												<option value=""></option>
												<option value="completion">Completion</option>
												<option value="completion_cancel">Completion Cancel</option>
												<option value="completion_return">Completion Return</option>
												<option value="completion_repair">Completion Repair</option>
												<option value="completion_after_repair">Completion After Repair</option>
												<option value="completion_error">Completion Error</option>
												<option value="completion_adjustment">Completion Adjustment</option>
												<option value="completion_adjustment_excel">Completion Adjustment Excel</option>
												<option value="completion_adjustment_manual">Completion Adjustment Manual</option>
												<option value="completion_temporary_delete">Completion Temporary Delete</option>
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

				    	// var domain = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '');
				    	// if (location.hostname == "localhost") {
					    // 	domain = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '') + "/kitto/public";
					    // }
				    	// console.log(domain);
						// jQuery('.tags').tagsInput({ 
						// 	width: 'auto', 
						// 	autocomplete_url: domain + '/completions/barcode/suggestion',
						// 	autocomplete: { 
						// 		selectFirst:true, width:'100px', autoFill:true 
						// 	} 
						// });
					    $(".mainpanel").css("height", "");
						jQuery('.tags').tagsInput({ width: 'auto' });
						$('#materialTags').hide();
						$('#materialTags_tagsinput').hide();
						$('#barcodeTags').hide();
						$('#barcodeTags_tagsinput').hide();
						$('#locationTags').hide();
						$('#locationTags_tagsinput').hide();
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
						$('#locationArea').keydown(function(event) {
						    if (event.keyCode == 13) {
						        convertLocationToTags();
						        return false;
						     }
						});
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

					function convertLocationToTags() {
						var data = $('#locationArea').val();
				    	if (data.length > 0) {
							var rows = data.split('\n');
							if (rows.length > 0) {
								for (var i = 0; i < rows.length; i++) {
									var barcode = rows[i].trim();
									if (barcode.length > 0) {
										$('#locationTags').addTag(barcode);
									}
								}
								$('#locationTags').hide();
								$('#locationTags_tagsinput').show();
								$('#locationArea').hide();
							}
				    	}
					}
				</script>
                @stop