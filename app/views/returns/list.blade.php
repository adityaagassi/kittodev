                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
				@stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-bars"></i> Return</h2>
                </div>
                <div class="contentpanel">
                    <div class="row">
						@if(isset($response['success']))
						<div class="col-md-6">
							<div class="panel panel-success">
								<div class="panel-heading">
									<h3 class="panel-title">Success Return</h3>
								</div>
								<div class="panel-body">
									@if(count($response['success']) > 0)
										<div class="table-responsive">
											<table class="table table-striped" id="table2">
											<thead>
												<tr>
													<th>MAterial Number</th>
						                            <th>Message</th>
												</tr>
											</thead>
												<tbody>
												<?php $index = 1; ?>
												@foreach ($response['success'] as $success)
												<tr>
													<td>
														{{ $success }}
													</td>
													<td>
														Return success
													</td>
												</tr>
												<?php $index += 1; ?>
													@endforeach
												</tbody>
											</table>
										</div><!-- table-responsive -->
									@else
										<center>Success return is empty</center>
									@endif
								</div><!-- panel-body -->
							</div><!-- panel -->
						</div>
						@endif
						@if(isset($response['failed']))
						<div class="col-md-6">
							<div class="panel panel-danger">
						    	<div class="panel-heading">
									<h3 class="panel-title">Failed Return</h3>
								</div>
								<div class="panel-body">
									@if(count($response['failed']) > 0)
										<div class="table-responsive">
											<table class="table table-striped" id="table2">
											<thead>
												<tr>
													<th>Material Number</th>
						                            <th>Message</th>
												</tr>
											</thead>
												<tbody>
												<?php $index = 1; ?>
												@foreach ($response['failed'] as $failed)
												<tr>
													<td>
														{{ $failed }}
													</td>
													<td>
														Material number not found in selected location
													</td>
												</tr>
												<?php $index += 1; ?>
													@endforeach
												</tbody>
											</table>
										</div><!-- table-responsive -->
									@else
										<center>Failed return is empty</center>
									@endif
								</div><!-- panel-body -->
							</div><!-- panel -->
						</div>
						@endif
                    </div>
                </div>
                @stop

                @section('scripts')
					jQuery(document).ready(function() {
					    $(".mainpanel").css("height", "");
					});
                @stop