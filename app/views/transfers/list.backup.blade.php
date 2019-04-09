                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
					<link href="{{ url("css/bootstrap-fileupload.min.css") }}" rel="stylesheet" />
				@stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-bars"></i> Master Transfers </h2>
                    <div class="breadcrumb-wrapper">
						<ol class="breadcrumb">
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
							<div class="panel-body">
								@if(isset($transfers) && count($transfers) > 0)
          						<div class="table-responsive">
          							<table class="table" id="table2">
										<thead>
											<tr>
												<th>Barcode</th>
                                                <th>Material</th>
                                                <th>Description</th>
												<th>Lot</th>
												<th>Barcode Completions</th>
												<th>Issue Loc</th>
												<th>Receive Loc</th>
												<th>Created By</th>
												<th>Created At</th>
												<th>Updated At</th>
												<th><center>Configuration</center></th>
											</tr>
										</thead>
              							<tbody>
											<?php $index = 1; ?>
											@foreach ($transfers as $transfers)
											@if($index % 2 == 1)
											<tr class="odd">
											@else
											<tr class="even">
											@endif
												<td>
													<a href="{{ url("transfers/$transfers->id") }}">{{ $transfers->barcode_number_transfer }}</a>
												</td>
												<td>
													@if(isset($transfers->completion))
													<a href="{{ url("materials/" . $transfers->completion->material->id) }}">
														{{ $transfers->completion->material->material_number }}
													</a>
													@else
														-
													@endif
												</td>
												<td>
													@if(isset($transfers->completion->material))
													{{ $transfers->completion->material->description }}
													@else
													-
													@endif
												</td>
												<td>{{ $transfers->lot_transfer }}</td>
												<td>{{ $transfers->completion->barcode_number }}</td>
												<td>{{ $transfers->issue_location }}</td>
												<td>{{ $transfers->receive_location }}</td>
												<td>{{ $transfers->user->name }}</td>
												<td>{{ $transfers->created_at }}</td>
												<td>{{ $transfers->updated_at }}</td>
												<td class="center">
													<center>
														<a href="javascript:void(0)"  data-toggle="modal" data-target="#myModal" onclick="deleteConfirmation('{{ url("transfers/$transfers->id/delete") }}', '{{ $transfers->barcode_number_transfer }}');">
															<span class="fa fa-times"></span>
														</a>
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														<a href="{{ url("transfers/$transfers->id/edit") }}">
															<span class="fa fa-pencil"></span>
														</a>
													</center>
												</td>
											</tr>
											<?php $index += 1; ?>
              								@endforeach
              							</tbody>
           							</table>
          						</div><!-- table-responsive -->
          						@else
          							<center>Transfer data is empty</center>
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
				<script src="{{ url('js/bootstrap-fileupload.min.js') }}"></script>
                <script type="text/javascript">
                	jQuery('#table2').dataTable({
                		"aaSorting": [[0, 'desc']],
						"sPaginationType": "full_numbers",
						"iDisplayLength": 25
					});
					jQuery("select").chosen({
						'min-width': '100px',
						'white-space': 'nowrap',
						disable_search_threshold: 10
					});
					function deleteConfirmation(url, name) {
						jQuery('.modal-body').text("Are you sure want to delete transfer with barcode number '" + name + "'?");
						jQuery('#modalDeleteButton').attr("href", url);
					}
                </script>
                @stop