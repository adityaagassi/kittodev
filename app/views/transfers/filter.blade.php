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
                    <h2><i class="fa fa-bars"></i> Filter Transfers </h2>
                    <div class="breadcrumb-wrapper">
						<ol class="breadcrumb">
							<li>
								<a class="btn btn-success" href="{{ url("/transfers/list/filter")}}">Filter</a>
							</li>
							<li>
								<a class="btn btn-success" href="javascript:void(0)" data-toggle="modal" data-target="#importModal" >Import</a>
							</li>
							<li>
								<a class="btn btn-success" href="{{ url("/transfers/export")}}">Export</a>
							</li>
							<li>
								<a class="btn btn-success" href="{{ url("/transfers/add")}}">Add New</a>
							</li>
						</ol>
					</div>
                </div>
                <div class="contentpanel">
                    <div class="row">
                    	<div class="panel panel-default">
        					<div class="panel-body panel-body-nopadding">
        						<form id ="basicForm" class="form-horizontal form-bordered" method="get" action="{{ url("/transfers/list/filter/list")}}">
									<div class="form-group">
										<div class="col-md-3 ">
											<label class="control-label">Barcode Transfer</label>
											<input type="text" placeholder="Barcode Transfer" class="form-control" name="barcode_transfer" />
										</div>
										<div class="col-md-3 ">
											<label class="control-label">&nbsp;</label>
											<select class="form-control chosen-select input-md" name="barcode_transfer_state" data-placeholder="Choose a State..." required>
												<option value="equal" select>Equal</option>
												<option value="contain">Contain</option>
											</select>
										</div>
										<div class="col-md-3 ">
											<label class="control-label">Barcode Completion</label>
											<input type="text" placeholder="Barcode Completion" class="form-control" name="barcode_completion" />
										</div>
										<div class="col-md-3 ">
											<label class="control-label">&nbsp;</label>
											<select class="form-control chosen-select input-md" name="barcode_completion_state" data-placeholder="Choose a State..." required>
												<option value="equal" select>Equal</option>
												<option value="contain">Contain</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-6">
											<label class="control-label">Material Number</label>
											<textarea id="materialArea" class="form-control" rows="5" placecholder="Paste barcode number from excel here"></textarea>
											<input id="materialTags" type="text" placeholder="Material Number" class="form-control tags" name="material_numbers" />
										</div>
										<div class="col-md-6">
											<div class="col-md-12">
												<label class="control-label">Description</label>
												<input type="text" placeholder="Description" class="form-control" name="description" />
											</div>
											<div class="col-md-6">
												<label class="control-label">Lot</label>
												<input type="number" placeholder="Lot from" class="form-control" name="lot_from" />
											</div>
											<div class="col-md-6">
												<label class="control-label">To</label>
												<input type="number" placeholder="Lot until" class="form-control" name="lot_until" />
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-6">
											<label class="control-label">Issue Location</label>
											<textarea id="issueLocationArea" class="form-control" rows="5" placecholder="Paste issue location from excel here"></textarea>
											<input id="issueLocationTags" type="text" placeholder="Issue Location" class="form-control tags" name="issue_locations" />
										</div>
										<div class="col-md-6">
											<label class="control-label">Receive Location</label>
											<textarea id="receiveLocationArea" class="form-control" rows="5" placecholder="Paste receive location from excel here"></textarea>
											<input id="receiveLocationTags" type="text" placeholder="Receive Location" class="form-control tags" name="receive_locations" />
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

				<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<form id ="importForm" method="post" action="{{ url('transfers/import') }}" enctype="multipart/form-data">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title" id="myModalLabel">Import File</h4>
								</div>
								<div class="modal-body">
	        						<div  class="form-horizontal form-bordered">
										<div class="form-group">
											<label class="col-sm-3 control-label">File Upload</label>
											<div class="col-sm-9">
												<div class="fileupload fileupload-new" data-provides="fileupload">
													<div class="input-append">
														<div class="uneditable-input">
															<i class="glyphicon glyphicon-file fileupload-exists"></i>
															<span class="fileupload-preview"></span>
														</div>
														<span class="btn btn-default btn-file">
															<span class="fileupload-new">Select file</span>
															<span class="fileupload-exists">Change</span>
															<input type="file" name="excel" accept="application/vnd.ms-excel, application/msexcel, application/x-msexcel, application/x-ms-excel, application/x-excel, application/x-dos_ms_excel, application/xls, application/x-xls, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"/>
														</span>
														<a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<!-- <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button> -->
									<button id="modalImportButton" type="submit" class="btn btn-success">Import</button>
								</div>
							</form>
						</div>
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
						$('#issueLocationTags').hide();
						$('#issueLocationTags_tagsinput').hide();
						$('#receiveLocationTags').hide();
						$('#receiveLocationTags_tagsinput').hide();
						initKeyDown();

				    });

				    function initKeyDown() {
						$('#materialArea').keydown(function(event) {
						    if (event.keyCode == 13) {
						        convertMaterialToTags();
						        return false;
						     }
						});
						$('#issueLocationArea').keydown(function(event) {
						    if (event.keyCode == 13) {
						        convertIssueLocationToTags();
						        return false;
						     }
						});
						$('#receiveLocationArea').keydown(function(event) {
						    if (event.keyCode == 13) {
						        convertReceiveLocationToTags();
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
				</script>
                @stop