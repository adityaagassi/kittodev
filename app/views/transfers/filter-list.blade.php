                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
					<link href="{{ url("css/bootstrap-fileupload.min.css") }}" rel="stylesheet" />
			        <link href="{{ url("css/jquery.gritter.css") }}" rel="stylesheet">
				@stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-bars"></i> Transfers </h2>
                    <div class="breadcrumb-wrapper">
						<ol class="breadcrumb">
							<li>
								<a class="btn btn-black" href="{{ url("/transfers/list/all/delete?" . $parameter)}}">Delete All</a>
							</li>
							<li>
								<a class="btn btn-success" href="javascript:void(0)" data-toggle="modal" data-target="#importModal" >Import</a>
							</li>
							<li>
								<a class="btn btn-success" href="{{ url("/transfers/export?" . $parameter)}}">Export</a>
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
                                                <th>ID</th>
                                                <th>Barcode Transfer</th>
                                                <th>Material Number</th>
												<th>Description</th>
												<th>Lot</th>
												<th>Barcode Completion</th>
												<th>Issue Loc</th>
												<th>Receive Loc</th>
												<th><center>Configuration</center></th>
											</tr>
										</thead>
              							<tbody>
											<?php $index = 1; ?>
											@foreach ($transfers as $transfers)
											@if($index % 2 == 1)
											<tr class="odd" id="{{ $transfers->id }}">
											@else
											<tr class="even" id="{{ $transfers->id }}">
											@endif
												<td>
													<a href="{{ url("transfers/$transfers->id") }}">{{ $transfers->id }}</a>
												</td>
												<td>
													<a href="{{ url("transfers/$transfers->id") }}">{{ $transfers->barcode_number_transfer }}</a>
												</td>
												<td>
													<a href="{{ url("transfers/$transfers->id") }}">{{ $transfers->material_number }}</a>
												</td>
												<td>
													{{ $transfers->description }}
												</td>
												<td>
													{{ $transfers->lot_transfer }}
												</td>
												<td>
													{{ $transfers->barcode_number }}
												</td>
												<td>
													{{ $transfers->issue_location }}
												</td>
												<td>
													{{ $transfers->receive_location }}
												</td>
												<td class="center">
													<center>
														<a href="javascript:void(0)"  data-toggle="modal" data-target="#myModal" onclick="deleteConfirmation('{{ $transfers->id }}', '{{ $transfers->barcode_number_transfer }}');">
															<span class="fa fa-times"></span>
														</a>
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														<a href="{{ url("transfers/$transfers->id/edit?$parameter") }}">
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
          							<center>Materials data is empty</center>
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
		        <script src="{{ url("js/jquery.gritter.min.js") }}"></script>
                <script type="text/javascript">
	                var selectedID = -1;
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
					jQuery('#modalDeleteButton').click(function() {
	                    $.get('{{ url("api/v1/transfers") }}/' + selectedID + '/delete', function(result, status, xhr) {
	                    	$('#myModal').modal('toggle');
	                        if (xhr.status == 200) {
	                            if (result.status) {
	                                jQuery.gritter.add({
	                                    title: 'Hapus material berhasil dilakukan!',
	                                    text: result.message,
	                                    class_name: 'growl-success',
	                                    image: '{{ url("images/screen.png") }}',
	                                    sticky: false,
	                                    time: '1000'
	                                 });
	                               jQuery('#' + selectedID).remove();
	                            }
	                            else {
	                                var statusCode = result.status_code;
	                                var image = '{{ url("images/screen.png") }}';
	                                jQuery.gritter.add({
	                                    title: 'Hapus transfer gagal dilakukan!',
	                                    text: result.message,
	                                    class_name: 'growl-danger',
	                                    image: image,
	                                    sticky: false,
	                                    time: '2000'
	                                 });
	                                var audio = new Audio('{{ url("sound/error.mp3") }}');
	                                audio.play();
	                            }
	                        }
	                        else {
                                var statusCode = result.status_code;
                                var image = '{{ url("images/screen.png") }}';
                                jQuery.gritter.add({
                                    title: 'Hapus transfer gagal dilakukan!',
                                    text: 'Tidak terhubung ke server',
                                    class_name: 'growl-danger',
                                    image: image,
                                    sticky: false,
                                    time: '2000'
                                 });
                                var audio = new Audio('{{ url("sound/error.mp3") }}');
                                audio.play();
	                        }
	                    });
					});
					function deleteConfirmation(id, name) {
						selectedID = id;
						jQuery('.modal-body').text("Are you sure want to delete transfer with barcode number '" + name + "'?");
					}
                </script>
                @stop