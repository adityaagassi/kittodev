                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
				@stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-tag"></i> Archived </h2>
                </div>
                <div class="contentpanel">
                    <div class="row">
                    	<div class="panel panel-default">
							<div class="panel-body">
								@if(isset($batchoutputs) && count($batchoutputs) > 0)
          						<div class="table-responsive">
          							<table class="table" id="table2">
										<thead>
											<tr>
												<th>Reference File</th>
												<th>Category</th>
												<th>Uploaded</th>
												<th>Uploaded At</th>
                                                @if(Session::has('level_id'))
                                                    @if(Session::get('level_id') == 1 || Session::get('level_id') == 2)
                                                        <th><center>Configuration</center></th>
                                                    @endif
                                                @endif
											</tr>
										</thead>
              							<tbody>
											<?php $index = 1; ?>
											@foreach ($batchoutputs as $batchoutput)
											@if($index % 2 == 1)
											<tr class="odd">
											@else
											<tr class="even">
											@endif
												<td><a href="{{ url("archive/resume/$batchoutput->reference_file") }}">{{ $batchoutput->reference_file }}</a></td>
												<td>
													@if(in_array($batchoutput->category, Array("completion", "completion_adjustment", "completion_adjustment_excel", "completion_adjustment_manual", "completion_cancel", "completion_return", "completion_error", "completion_temporary_delete")))
													Completion
													@else
													Transfer
													@endif
												</td>
												<td>Uploaded</td>
												<td>{{ $batchoutput->updated_at }}</td>
                                                @if(Session::has('level_id'))
                                                    @if(Session::get('level_id') == 1 || Session::get('level_id') == 2)
														<td><center><a href="{{ url("archive/resume/download/$batchoutput->reference_file") }}"><i class="fa fa-download"></i></a></center></td>
													@endif
												@endif
                                            </tr>
											@endforeach
              							</tbody>
          							</table>
          						</div>
								@else
          							<center>Resume History data is empty</center>
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
					function deleteConfirmation(url, time) {
						jQuery('.modal-body').text("Are you sure want to delete batch output with time '" + time + "'?");
						jQuery('#modalDeleteButton').attr("href", url);
					}
                </script>

                @stop