                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
				@stop
	 	 		
                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-users"></i> Users </h2>
                    <div class="breadcrumb-wrapper">
						<ol class="breadcrumb">
							<li>
								<a class="btn btn-success" href="{{ url("/users/add")}}">Add New</a>
							</li>
						</ol>
					</div>
                </div>
                <div class="contentpanel">
                    <div class="row">
                    	<div class="panel panel-default">
							<div class="panel-body">
								@if(isset($users) && count($users) > 0)
          						<div class="table-responsive">
          							<table class="table" id="table2">
										<thead>
											<tr>
												<th>Name</th>
                                                <th>Level</th>
                                                @if(Session::has('level_id') && Session::get('level_id') < 3)
                                                <th><center>Configuration</center></th>
                                                @endif
											</tr>
										</thead>
              							<tbody>
											<?php $index = 1; ?>
											@foreach ($users as $user)
											@if($index % 2 == 1)
											<tr class="odd">
											@else
											<tr class="even">
											@endif
											    <td>{{ $user->name }}</td>
                                                <td>{{ $user->level->name}}</td>
												@if(Session::has('level_id') && Session::get('level_id') < 3)
                                                <td class="center">
													<center>
														@if($user->level->id > 1)
														<a href="javascript:void(0)"  data-toggle="modal" data-target="#myModal" onclick="deleteConfirmation('{{ url("users/$user->id/delete") }}', '{{ $user->name }}');">
															<span class="fa fa-times"></span>
														</a>
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														<a href="{{ url("users/$user->id/edit") }}">
															<span class="fa fa-pencil"></span>
														</a>
														@else
															Unconfigurable account
														@endif
													</center>
												</td>
												@endif
											 </tr>
											 @endforeach
              							</tbody>
           							</table>
          						</div>
          						@else
          							<center>User data is empty</center>
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
                @stop

                @section('scripts')

                <script type="text/javascript">
					jQuery(document).ready(function() {
					    // Chosen
					    $(".mainpanel").css("height", "");
					});
                	jQuery('#table2').dataTable({
						"sPaginationType": "full_numbers"
					});
					jQuery("select").chosen({
						'min-width': '100px',
						'white-space': 'nowrap',
						disable_search_threshold: 10
					});
					function deleteConfirmation(url, name) {
						jQuery('.modal-body').text("Are you sure want to delete product with name '" + name + "'?");
						jQuery('#modalDeleteButton').attr("href", url);
					}
                </script>

                @stop