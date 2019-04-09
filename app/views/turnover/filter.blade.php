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
                    <h2><i class="fa fa-bars"></i> Filter Turn Over </h2>
                </div>
                <div class="contentpanel">
                    <div class="row">
                    	<div class="panel panel-default">
        					<div class="panel-body panel-body-nopadding">
        						<form id ="basicForm" class="form-horizontal form-bordered" method="get" action="{{ url("/turnover/list")}}">
									<div class="form-group">
										<div class="col-md-6">
											<div class="col-md-12 ">
												<label class="control-label">Location</label>
												<select class="form-control chosen-select input-md" name="location" data-placeholder="Choose a Location...">
													<option value="" select>All</option>
													@if(isset($locations))
														@foreach($locations as $location)
															<option value="{{ $location->location }}">{{ $location->location }}</option>
														@endforeach
													@endif
												</select>
											</div>
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
										<div class="col-md-6">
											<label class="control-label">Material Number</label>
											<textarea id="materialArea" class="form-control" rows="5" placecholder="Paste barcode number from excel here"></textarea>
											<input id="materialTags" type="text" placeholder="Material Number" class="form-control tags" name="material_numbers" />
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
				<script src="{{ url("js/bootstrap-fileupload.min.js") }}"></script>
				<script src="{{ url("js/jquery.tagsinput.min.js") }}"></script>
				<script>
				    jQuery(document).ready(function() {

					    $(".mainpanel").css("height", "");
						jQuery('.tags').tagsInput({ width: 'auto' });
						$('#materialTags').hide();
						$('#materialTags_tagsinput').hide();
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
				</script>
                @stop