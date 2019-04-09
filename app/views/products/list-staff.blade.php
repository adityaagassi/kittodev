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
          						<div class="table-responsive">
          							<table class="table" id="table2">
										<thead>
											<tr>
												<th>Item name</th>
                                                <th>Category</th>
												<th>Quantity</th>
												<th>Price</th>
											</tr>
										</thead>
              							<tbody>
											 <tr class="odd">
											    <td>Havana</td>
                                                <td>Kacamata</td>
											    <td class="center">2</td>
											    <td>200000</td>
											 </tr>
											<tr class="even">
												<td>Oktavia</td>
                                                <td>Kacamata</td>
												<td class="center">3</td>
												<td>200000</td>
											</tr>
											 <tr class="odd">
											    <td>III M</td>
                                                <td>Kacamata</td>
											    <td class="center">1</td>
											    <td>200000</td>
											 </tr>
											<tr class="even">
												<td>Libra</td>
                                                <td>Kacamata</td>
												<td class="center">4</td>
												<td>200000</td>
											</tr>
											 <tr class="odd">
											    <td>Boss</td>
                                                <td>Kacamata</td>
											    <td class="center">1</td>
											    <td>200000</td>
											 </tr>
              							</tbody>
           							</table>
          						</div><!-- table-responsive -->
        					</div><!-- panel-body -->
      					</div><!-- panel -->
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
                </script>

                @stop