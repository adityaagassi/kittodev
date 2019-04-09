                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
				@stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-camera"></i> Products </h2>
                    <div class="breadcrumb-wrapper">
						<ol class="breadcrumb">
							<li>
								<a class="btn btn-success" href="{{ url("/products/add")}}">Add New</a>
							</li>
						</ol>
					</div>
                </div>
                <div class="contentpanel">
                    <div class="row">
                    	<div class="panel panel-default">
							<div class="panel-body">
								@if(isset($products) && count($products) > 0)
          						<div class="table-responsive">
          							<table class="table" id="table2">
										<thead>
											<tr>
												<th>ID</th>
												<th>Item name</th>
                                                <th>Category</th>
												<th><center>Quantity</center></th>
												@if(Session::has('level_id') && Session::get('level_id') < 3)
												<th>Buy price</th>
												<th>Sell price</th>
												<th><center>Configuration</center></th>
												@else
												<th>Price</th>
												@endif
											</tr>
										</thead>
              							<tbody>
											<?php $index = 1; ?>
											@foreach ($products as $product)
											@if($index % 2 == 1)
											<tr class="odd">
											@else
											<tr class="even">
											@endif
												<td><a href="{{ url("products/$product->id") }}">{{ $product->id }}</a></td>
												<td><a href="{{ url("products/$product->id") }}">{{ $product->name }}</a></td>
												<td>{{ $product->category->name }}</td>
												<td class="center"><center>{{ number_format($product->quantity, 0, ',', '.') }}</center></td>
												@if(Session::has('level_id') && Session::get('level_id') < 3)
												<td>{{ number_format($product->buy_price, 0, ',', '.') }}</td>
												<td>{{ number_format($product->sell_price, 0, ',', '.') }}</td>
												<td class="center">
													<center>
														<a href="javascript:void(0)"  data-toggle="modal" data-target="#myModal" onclick="deleteConfirmation('{{ url("products/$product->id/delete") }}', '{{ $product->name }}');">
															<span class="fa fa-times"></span>
														</a>
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														<a href="{{ url("products/$product->id/edit") }}">
															<span class="fa fa-pencil"></span>
														</a>
													</center>
												</td>
												@else
												<td>{{ number_format($product->sell_price, 0, ',', '.') }}</td>
												@endif
											</tr>
											<?php $index += 1; ?>
              								@endforeach
              							</tbody>
           							</table>
          						</div><!-- table-responsive -->
          						@else
          							<center>Product data is empty</center>
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