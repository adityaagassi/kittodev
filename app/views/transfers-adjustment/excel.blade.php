                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
			        <link href="{{ url("css/jquery.gritter.css") }}" rel="stylesheet">
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
								<form id="multipleForm" class="form-horizontal form-bordered" method="POST" action="{{ url('/transfers/adjustment/excel') }}">
									<div id="excelForm">
										<!-- <div class="form-group">
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
										</div> -->
										<div class="form-group">
											<label class="col-sm-3 control-label">Barcode Numbers from Excel</label>
											<div class="col-sm-6 ">
												<textarea id="barcodeArea" class="form-control" rows="5" placecholder="Paste barcode number from excel here"></textarea>
												<input id="barcodeTags" type="text" placeholder="Barcode Number" class="form-control tags" name="barcode_numbers" />
												<input type="hidden" name="user_id" value="{{ $user_id }}" />
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-offset-3 col-sm-3">
											<button class="btn btn-success" id="multiplesubmit">Submit</button>
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
				<script src="{{ url("js/jquery.tagsinput.min.js") }}"></script>
				<script>

					var index = 0;
					var userID = "{{ $user_id }}";
					var enabled = false;
					var histories = [];

				    jQuery(document).ready(function() {

				        // Chosen
				        $(".mainpanel").css("height", "");
						jQuery(".chosen-select").chosen({'width':'100%','white-space':'nowrap'});
						// jQuery('.datepicker').datepicker();
						// jQuery('.timepicker').timepicker({showMeridian: false, minuteStep: 1});
						jQuery('.tags').tagsInput({ width: 'auto' });
						$('#barcodeTags').hide();
						$('#barcodeTags_tagsinput').hide();
						initKeyDown();
				    });

				    function initKeyDown() {
						$('#barcodeArea').keydown(function(event) {
						    if (event.keyCode == 13) {
						        convertBarcodeToTags();
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

				</script>
                @stop