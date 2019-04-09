                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
				@stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-bars"></i> Adjustment Transfer List</h2>
                </div>
                <div class="contentpanel">
                    <div class="row">
                    	@if($category == "manual")
							<div class="col-md-12">
								<div class="panel panel-success">
									<div class="panel-heading">
										<h3 class="panel-title">Success Adjustments</h3>
									</div>
									<div class="panel-body">
										<div class="form-horizontal form-bordered">
										    <div class="form-group">
										        <label class="col-sm-3">Material Number</label>
										        <div class="col-sm-6">
										            {{ $adjustments->material->material_number }}
										        </div>
										    </div>
										    <div class="form-group">
										        <label class="col-sm-3">Location Completion</label>
										        <div class="col-sm-6">
										            {{ $adjustments->receive_location }}
										        </div>
										    </div>
										    <div class="form-group">
										        <label class="col-sm-3">Issue Plant</label>
										        <div class="col-sm-6">
										            {{ $adjustments->issue_plant }}
										        </div>
										    </div>
										    <div class="form-group">
										        <label class="col-sm-3">Lot Completion</label>
										        <div class="col-sm-6">
										            {{ number_format($adjustments->lot_completion, 0, ',', '.') }}
										        </div>
										    </div>
	                                    </div>
									</div>
								</div>
							</div>
                    	@else
							@if(isset($adjustments['success']))
							<div class="col-md-6">
								<div class="panel panel-success">
									<div class="panel-heading">
										<h3 class="panel-title">Success Adjustments</h3>
									</div>
									<div class="panel-body">
										@if(count($adjustments['success']) > 0)
											<div class="table-responsive">
												<table class="table table-striped" id="table2">
												<thead>
													<tr>
														<th>Barcode Number</th>
							                            <th>Message</th>
													</tr>
												</thead>
													<tbody>
													<?php $index = 1; ?>
													@foreach ($adjustments['success'] as $success)
													<tr>
														<td>
															{{ $success['barcode'] }}
														</td>
														<td>
															{{ $success['message'] }}
														</td>
													</tr>
													<?php $index += 1; ?>
														@endforeach
													</tbody>
												</table>
											</div><!-- table-responsive -->
										@else
											<center>Success transfer adjustment is empty</center>
										@endif
									</div><!-- panel-body -->
								</div><!-- panel -->
							</div>
							@endif
							@if(isset($adjustments['failed']))
							<div class="col-md-6">
								<div class="panel panel-danger">
							    	<div class="panel-heading">
										<h3 class="panel-title">Failed Adjustments</h3>
									</div>
									<div class="panel-body">
										@if(count($adjustments['failed']) > 0)
											<div class="table-responsive">
												<table class="table table-striped" id="table2">
												<thead>
													<tr>
														<th>Barcode Number</th>
							                            <th>Message</th>
													</tr>
												</thead>
													<tbody>
													<?php $index = 1; ?>
													@foreach ($adjustments['failed'] as $failed)
													<tr>
														<td>
															{{ $failed['barcode'] }}
														</td>
														<td>
															{{ $failed['message'] }}
														</td>
													</tr>
													<?php $index += 1; ?>
														@endforeach
													</tbody>
												</table>
											</div><!-- table-responsive -->
										@else
											<center>Failed transfer adjustment is empty</center>
										@endif
									</div><!-- panel-body -->
								</div><!-- panel -->
							</div>
							@endif
						@endif
                    </div>
                </div>
                @stop

                @section('scripts')
					jQuery(document).ready(function() {
					    $(".mainpanel").css("height", "");
					});
                @stop