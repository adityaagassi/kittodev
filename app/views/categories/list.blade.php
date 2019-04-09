                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
				@stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-tag"></i> Categories </h2>
                    <div class="breadcrumb-wrapper">
						<ol class="breadcrumb">
							<li>
								<a class="btn btn-success" href="{{ url("/categories/add")}}">Add New</a>
							</li>
						</ol>
					</div>
                </div>
                <div class="contentpanel">
                    <div class="row">
                    	<div class="panel panel-default">
							<div class="panel-body">
								@if(isset($categories) && count($categories) > 0)
          						<div class="table-responsive">
          							<table class="table" id="table2">
										<thead>
											<tr>
												<th>ID</th>
												<th>Name</th>
												<th><center>Total Products</center></th>
												@if(Session::has('level_id') && Session::get('level_id') < 3)
												<th><center>Configuration</center></th>
												@endif
											</tr>
										</thead>
              							<tbody>
											<?php $index = 1; ?>
											@foreach ($categories as $category)
											@if($index % 2 == 1)
											<tr class="odd">
											@else
											<tr class="even">
											@endif
												<td><a href="{{ url("categories/$category->id") }}">{{ $category->id }}</a></td>
												<td><a href="{{ url("categories/$category->id") }}">{{ $category->name }}</a></td>
                                                <td class="center"><center>{{ number_format(count($category->products), 0, ',', '.') }}</center></td>
                                                @if(Session::has('level_id') && Session::get('level_id') < 3)
												<td>
													<center>
														<a href="javascript:void(0)"  data-toggle="modal" data-target="#myModal" onclick="deleteConfirmation('{{ url("categories/$category->id/delete") }}', '{{ $category->name }}');">
															<span class="fa fa-times"></span>
														</a>
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														<a href="{{ url("categories/$category->id/edit") }}">
															<span class="fa fa-pencil"></span>
														</a>
													</center>
												</td>
												@endif
                                            </tr>
											@endforeach
              							</tbody>
          							</table>
          						</div>
								@else
          							<center>Category data is empty</center>
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
						jQuery('.modal-body').text("Are you sure want to delete category with name '" + name + "'?");
						jQuery('#modalDeleteButton').attr("href", url);
					}
                </script>

                @stop