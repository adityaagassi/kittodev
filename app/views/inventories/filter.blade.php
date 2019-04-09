                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
					<link href="{{ url("css/bootstrap-timepicker.min.css") }}" rel="stylesheet" />
					<link href="{{ url("css/bootstrap-fileupload.min.css") }}" rel="stylesheet" />
					<link href="{{ url("css/jquery.tagsinput.css") }}" rel="stylesheet" />
			        <style type="text/css">
			        	.bootstrap-timepicker-widget table td input {
			        		width: 104px;
			        	}
			        </style>
				@stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-bars"></i> Filter Inventory </h2>
                </div>
                <div class="contentpanel">
                    <div class="row">
                    	<div class="panel panel-default">
        					<div class="panel-body panel-body-nopadding">
        						<form id ="basicForm" class="form-horizontal form-bordered" method="get" action="{{ url("/inventories/list")}}">
									<div class="form-group">
										<div class="col-md-3 ">
											<label class="control-label">Barcode Number</label>
											<input type="text" placeholder="Barcode Number" class="form-control" name="barcode_number" />
										</div>
										<div class="col-md-3 ">
											<label class="control-label">&nbsp;</label>
											<select class="form-control chosen-select input-md" name="barcode_state" data-placeholder="Choose a State..." required>
												<option value="equal" select>Equal</option>
												<option value="contain">Contain</option>
											</select>
										</div>
										<div class="col-md-6 ">
											<label class="control-label">Description</label>
											<input type="text" placeholder="Description" class="form-control" name="description" />
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-6">
											<label class="control-label">Material Number</label>
											<textarea id="materialArea" class="form-control" rows="5" placecholder="Paste barcode number from excel here"></textarea>
											<input id="materialTags" type="text" placeholder="Material Number" class="form-control tags" name="material_numbers" />
										</div>
										<div class="col-md-6">
											<label class="control-label">Location</label>
											<textarea id="locationArea" class="form-control" rows="5" placecholder="Paste location from excel here"></textarea>
											<input id="locationTags" type="text" placeholder="Material Number" class="form-control tags" name="locations" />
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
				<script src="{{ url('js/bootstrap-fileupload.min.js') }}"></script>
				<script src="{{ url("js/bootstrap-timepicker.min.js") }}"></script>
				<script src="{{ url("js/jquery.tagsinput.min.js") }}"></script>
				<script>
				    jQuery(document).ready(function() {

					    $(".mainpanel").css("height", "");
						jQuery('.tags').tagsInput({ width: 'auto' });
						$('#materialTags').hide();
						$('#materialTags_tagsinput').hide();
						$('#locationTags').hide();
						$('#locationTags_tagsinput').hide();
						jQuery('.datepicker').datepicker();
						jQuery('.timepicker').timepicker({showMeridian: false, minuteStep: 1, defaultTime: false});
						initKeyDown();

				    });

				    function initKeyDown() {
						$('#materialArea').keydown(function(event) {
						    if (event.keyCode == 13) {
						        convertMaterialToTags();
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