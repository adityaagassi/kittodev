                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
				@stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-inbox"></i> Inventories </h2>
                    <div class="breadcrumb-wrapper">
						<ol class="breadcrumb">
							<li>
								<a class="btn btn-success" href="{{ url("/inventories/export?" . $parameter)}}">Export</a>
							</li>
						</ol>
					</div>
                </div>
                <div class="contentpanel">
                    <div class="row">
                    	<div class="panel panel-default">
							<div class="panel-body">
								@if(isset($inventories) && count($inventories) > 0)
          						<div class="table-responsive">
          							<table class="table" id="table2">
										<thead>
											<tr>
												<th>No</th>
												<th>Barcode</th>
												<th>Material Number</th>
												<th>Description</th>
												<th>Location</th>
												<th>No. Kanban</th>
												<th>Qty</th>
												<th>Last Update</th>
											</tr>
										</thead>
              							<tbody>
											<?php $index = 1; ?>
											@foreach ($inventories as $inventories)
											@if($index % 2 == 1)
											<tr class="odd">
											@else
											<tr class="even">
											@endif
												<td>{{ $index++; }}</td>
												<td>{{ $inventories->barcode_number }}</td>
												<td>{{ $inventories->material_number }}</td>
												<td>
												@if(isset($inventories->description)) 
													{{ $inventories->description }}
												@else
													-
												@endif
												</td>
												<td>
												@if(isset($inventories->issue_location)) 
													{{ $inventories->issue_location }}
												@else
													-
												@endif
												</td>
												<td>{{ substr($inventories->barcode_number, 11, strlen($inventories->barcode_number) - 1) }}</td>
												<td>{{ $inventories->lot }}</td>
												<td>{{ date_format($inventories->updated_at,"d M Y H:i:s") }}</td>
											</tr>
              								@endforeach
              							</tbody>
           							</table>
          						</div><!-- table-responsive -->
          						@else
          							<center>Inventory data is empty</center>
          						@endif
        					</div><!-- panel-body -->
      					</div><!-- panel -->
                    </div>
                </div>
                @stop

                @section('scripts')
                <script type="text/javascript">
                	jQuery('#table2').dataTable({
                		"aaSorting": [[0, 'asc']],
						"sPaginationType": "full_numbers",
						"iDisplayLength": 25
					});
					jQuery("select").chosen({
						'min-width': '100px',
						'white-space': 'nowrap',
						disable_search_threshold: 10
					});
                </script>
                @stop