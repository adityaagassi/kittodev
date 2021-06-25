                @extends('master')

                @section('stylesheets')
                <link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
                <link href="{{ url("css/jquery.gritter.css") }}" rel="stylesheet">
                <link href="{{ url("css/bootstrap-fileupload.min.css") }}" rel="stylesheet" />
                <link href="{{ url("css/bootstrap-timepicker.min.css") }}" rel="stylesheet" />
                <style type="text/css">
                	.bootstrap-timepicker-widget table td input {
                		width: 104px;
                	}
                </style>
                @stop

                @section('content')
                <div class="pageheader">
                	<h2><i class="fa fa-barcode"></i> Add Adjustment Completions </h2>
                	<div class="breadcrumb-wrapper">
                		<ol class="breadcrumb">
                			<li>
                				<a class="btn btn-success" href="javascript:void(0)" data-toggle="modal" data-target="#importModal" >Import</a>
                			</li>
                		</ol>
                	</div>
                </div>
                <div class="contentpanel">
                	@if(isset($errors) && count($errors) > 0)
                	<div class="row">
                		<div class="col-sm-12">
                			<div class="alert alert-danger">
                				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                				<strong>
                					@if ($errors->has('lot_completion'))
                					Lot Completion is required<br>
                					@endif
                				</strong>
                			</div>
                		</div>
                	</div>
                	@endif
                	<div class="row">
                		<div class="panel panel-default">
                			<div class="panel-body panel-body-nopadding">
                				<form id="basicForm" class="form-horizontal form-bordered" method="POST" action="{{ url('/completions/adjustment/manual') }}">
                					<div class="form-group">
                						<label class="col-sm-3 control-label">Plant <span class="asterisk">*</span></label>
                						<div class="col-sm-6 ">
                							<input type="text" placeholder="Plant" class="form-control" name="issue_plant" value="8190" maxlength="4" required />
                							<input type="hidden" name="user_id" value="{{ $user_id }}" />
                						</div>
                					</div>
                					<div class="form-group">
                						<label class="col-sm-3 control-label">Storage Location <span class="asterisk">*</span></label>
                						<div class="col-sm-6 ">
                							<input type="text" placeholder="Storage Location" class="form-control" name="location_completion" maxlength="4" required />
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
                						<label class="col-sm-3 control-label">Quantity <span class="asterisk">*</span></label>
                						<div class="col-sm-6 ">
                							<div class="input-group">
                								<input type="number" placeholder="Quantity" class="form-control" name="lot_completion" value="0" required />
                								<span class="input-group-addon">item(s)</span>
                							</div>
                						</div>
                					</div>
                					<div class="form-group">
                						<label class="col-sm-3 control-label">Reference Number</label>
                						<div class="col-sm-6 ">
                							<input type="text" placeholder="Reference Number" class="form-control" name="reference_number" />
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
                						<div class="col-sm-offset-3 col-sm-3">
                							<button class="btn btn-success">Submit</button>
                						</div>
                					</div>
                				</form>
                			</div>
                		</div>
                	</div>
                </div>

                <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                	<div class="modal-dialog modal-lg">
                		<div class="modal-content">
                			<form id="importForm" method="POST" action="{{ url('/completions/adjustment/import') }}">

                				<div class="modal-header">
                					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                					<h4 class="modal-title" id="myModalLabel">Import Adjustment Completions</h4>
                				</div>
                				<div class="modal-body">
                					<div  class="form-horizontal form-bordered">
                						<div class="form-group">
                							<label class="col-sm-10 col-sm-offset-1">Format Import: [PLANT][SLOC][GMC][QUANTITY]</label>
                							<div class="col-sm-10 col-sm-offset-1">
                								<input type="hidden" name="import_user_id" value="{{ $user_id }}" />
                								<textarea class="form-control" name="import_text" style="height: 100px; width: 100%;" required></textarea>
                							</div>
                						</div>
                					</div>
                				</div>
                				<div class="modal-footer">
                					<button id="modalImportButton" type="submit" class="btn btn-success">Import</button>
                				</div>
                			</form>
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

				    	// Scan barcode

				    	$("#finish").hide();
				    	$("#submit").show();
				    	$("#histories_table").empty();

				        // Chosen
				        $(".mainpanel").css("height", "");
				        jQuery(".chosen-select").chosen({'width':'100%','white-space':'nowrap'});
				        jQuery('.datepicker').datepicker();
				        jQuery('.timepicker').timepicker({showMeridian: false, minuteStep: 1});
				    });

				</script>
				@stop