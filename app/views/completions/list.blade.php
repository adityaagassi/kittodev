                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
					<link href="{{ url("css/bootstrap-fileupload.min.css") }}" rel="stylesheet" />
				@stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-bars"></i> Master Completions </h2>
                    <div class="breadcrumb-wrapper">
						<ol class="breadcrumb">
							<li>
								<a class="btn btn-success" href="{{ url("/completions/list/filter")}}">Filter</a>
							</li>
							<li>
								<a class="btn btn-success" href="javascript:void(0)" data-toggle="modal" data-target="#importModal" >Import</a>
							</li>
							<li>
								<a class="btn btn-success" href="{{ url("/completions/export")}}">Export</a>
							</li>
							<li>
								<a class="btn btn-success" href="{{ url("/completions/add")}}">Add New</a>
							</li>
						</ol>
					</div>
                </div>
                <div class="contentpanel">
                    <div class="row">
                    	<div class="panel panel-default">
							<div class="panel-body">
								@if(count($completions) > 0)
          						<div class="table-responsive">
          							<table class="table" id="table2">
										<thead>
											<tr>
												<th>ID</th>
												<th>Barcode</th>
                                                <th>Material</th>
												<th>Description</th>
												<th>Loc</th>
												<th>Lot</th>
												<th>Limit</th>
												<th>Status</th>
												<th>Created By</th>
												<th>Created At</th>
												<th>Last Updated</th>
												<th><center>Config</center></th>
											</tr>
										</thead>
           							</table>
          						</div><!-- table-responsive -->
          						@else
          							<center>Completion data is empty</center>
          						@endif
        					</div><!-- panel-body -->
      					</div><!-- panel -->
                    </div>
                </div>

				<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
							</div>
							<div class="modal-body">
								Are you sure delete?
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
								<a id="modalDeleteButton" href="#" type="button" class="btn btn-danger">Delete</a>
							</div>
						</div>
					</div>
				</div>

				<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<form id ="importForm" method="post" action="{{ url('completions/import') }}" enctype="multipart/form-data">
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
				<script src="{{ url('js/bootstrap-fileupload.min.js') }}"></script>
                <script type="text/javascript">
				    jQuery(document).ready(function() {
					    jQuery('.mainpanel').css({height: ''});
					});
	                var ajaxURL = "{{ url('api/v1') }}";
                	jQuery('#table2').dataTable({
                		"aaSorting": [[0, 'desc']],
						"iDisplayLength": 25,
						"sPaginationType": "full_numbers",
						"iDisplayStart": 0,
						"iDisplayLength": 25,
						"bProcessing": true,
						"bServerSide": true,
						"sAjaxSource": ajaxURL + "/completions",
						"aoColumns": [
					        null,
					        null,
					        null,
					        { mData: 'description' },
					        { mData: 'location' },
					        { mData: 'lot_completion' },
					        { mData: 'limit_used' },
					        { mData: 'active' },
					        { mData: 'name' },
					        { mData: 'created_at' },
					        { mData: 'updated_at' },
					        null
						],
						"aoColumnDefs": [ {
							"aTargets": [ 0 ],
							"mData": function ( source, type, val ) {
								return source.id + "-" + source.barcode_number;
							},
							"mRender": function ( data, type, full ) {
									var id = data.split("-")[0];
									var barcodeNumber = data.split("-")[1];
									var detailURL = generateCompletionURL(id, "detail");
									var str = '<a href="' + detailURL + '">' + id + '</a>';
									return str;
								}
						}, {
							"aTargets": [ 1 ],
							"mData": function ( source, type, val ) {
								return source.id + "-" + source.barcode_number;
							},
							"mRender": function ( data, type, full ) {
									var id = data.split("-")[0];
									var barcodeNumber = data.split("-")[1];
									var detailURL = generateCompletionURL(id, "detail");
									var str = '<a href="' + detailURL + '">' + barcodeNumber + '</a>';
									return str;
								}
						}, {
							"aTargets": [ 2 ],
							"mData": function ( source, type, val ) {
								return source.material_id + "-" + source.material_number;
							},
							"mRender": function ( data, type, full ) {
									var id = data.split("-")[0];
									var barcodeNumber = data.split("-")[1];
									var detailURL = generateMaterialURL(id, "detail");
									var str = '<a href="' + detailURL + '">' + barcodeNumber + '</a>';
									return str;
								}
						}, {
							"aTargets": [ 7 ],
							"mData": function ( source, type, val ) {
								return source.active;
							},
							"mRender": function ( data, type, full ) {
									var str = "Non Active";
									if (str) {
										str = "Active";
									}
									return str;
								}
						}, {
							"aTargets": [ 11 ],
							"mData": function ( source, type, val ) {
								return source.id + "-" + source.barcode_number;
							},
							"mRender": function ( data, type, full ) {
									var id = data.split("-")[0];
									var barcodeNumber = data.split("-")[1];
									var deleteURL = generateCompletionURL(id, "delete");
									var editURL = generateCompletionURL(id, "edit");
									var str = '<center>';
									str += '<a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="deleteConfirmation(\'' + deleteURL + '\', \'' + barcodeNumber + '\');">';
									str += '<span class="fa fa-times"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
									str += '<a href="' + editURL + '">';
									str += '<span class="fa fa-pencil"></span>';
									str += '</a>';
									str += '</center>';
									return str;
								}
						} ],
						"bFilter": false,
						"bLengthChange": false
					});
					function generateCompletionURL(id, mode) {
						var phpURL = "{{ url('completions') }}";
						var url = ""
						if (mode == "delete") {
							url = phpURL + "/" + id + "/delete";	
						}
						else if (mode == "edit") {
							url = phpURL + "/" + id + "/edit";
						}
						else {
							url = phpURL + "/" + id;
						}
						return url;
					}
					function generateMaterialURL(id, mode) {
						var phpURL = "{{ url('materials') }}";
						var url = phpURL + "/" + id;
						return url;
					}
					jQuery("select").chosen({
						'min-width': '100px',
						'white-space': 'nowrap',
						disable_search_threshold: 10
					});
					function deleteConfirmation(url, name) {
						jQuery('.modal-body').text("Are you sure want to delete completion with barcode number '" + name + "'?");
						jQuery('#modalDeleteButton').attr("href", url);
					}
                </script>
                @stop