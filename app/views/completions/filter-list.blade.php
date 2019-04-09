                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
					<link href="{{ url("css/bootstrap-fileupload.min.css") }}" rel="stylesheet" />
			        <link href="{{ url("css/jquery.gritter.css") }}" rel="stylesheet">
				@stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-bars"></i> Completions </h2>
                    <div class="breadcrumb-wrapper">
						<ol class="breadcrumb">
							@if (strpos($parameter, 'active=1') !== false)
							<li>
								<a class="btn btn-danger" href="{{ url("/completions/list/all/nonactive?" . $parameter)}}">Non Active All</a>
							</li>
							@else
							<li>
								<a class="btn btn-primary" href="{{ url("/completions/list/all/active?" . $parameter)}}">Active All</a>
							</li>
							@endif
							<li>
								<a class="btn btn-black" href="{{ url("/completions/list/all/delete?" . $parameter)}}">Delete All</a>
							</li>
							<li>
								<a class="btn btn-success" href="javascript:void(0)" data-toggle="modal" data-target="#importModal" >Import</a>
							</li>
							<li>
								<a class="btn btn-success" href="{{ url("/completions/export?" . $parameter)}}">Export</a>
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
								@if(isset($completions) && count($completions) > 0)
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
												<th>Created At</th>
												<th><center>Configuration</center></th>
											</tr>
										</thead>
              							<tbody>
											<?php $index = 1; ?>
											@foreach ($completions as $completions)
											@if($index % 2 == 1)
											<tr class="odd" id="{{ $completions->id }}">
											@else
											<tr class="even" id="{{ $completions->id }}">
											@endif
												<td>
													<a href="{{ url("completions/$completions->id") }}">{{ $completions->id }}</a>
												</td>
												<td>
													{{ $completions->barcode_number }}
												</td>
												<td>
													{{ $completions->material_number }}
												</td>
												<td>
													{{ $completions->description }}
												</td>
												<td>
													{{ $completions->location }}
												</td>
												<td>
													{{ $completions->lot_completion }}
												</td>
												<td>
													{{ $completions->limit_used }}
												</td>
												<td>
													@if($completions->active)
														Active
													@else
														Non Active
													@endif
												</td>
												<td>
													{{ $completions->created_at }}
												</td>
												<td class="center">
													<center>
														<a href="javascript:void(0)"  data-toggle="modal" data-target="#myModal" onclick="deleteConfirmation('{{ $completions->id }}', '{{ $completions->barcode_number }}');">
															<span class="fa fa-times"></span>
														</a>
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														<a href="{{ url("completions/$completions->id/edit?$parameter") }}">
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
          							<center>Completions data is empty</center>
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
	                    $.get('{{ url("api/v1/completions") }}/' + selectedID + '/delete', function(result, status, xhr) {
	                    	$('#myModal').modal('toggle');
	                        if (xhr.status == 200) {
	                            if (result.status) {
	                                jQuery.gritter.add({
	                                    title: 'Hapus completion berhasil dilakukan!',
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
	                                    title: 'Hapus completion gagal dilakukan!',
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
                                    title: 'Hapus material gagal dilakukan!',
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
						jQuery('.modal-body').text("Are you sure want to delete completions with barcode number '" + name + "'?");
					}
                </script>
                @stop