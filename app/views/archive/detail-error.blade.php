                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
				@stop
	 	 		

                @section('content')

                <div class="pageheader">
                    <h2><i class="fa fa-clock-o"></i> Detail Error Report Histories </h2>
                </div>

                <div class="contentpanel">
                    <div class="row">
                        <div class="col-sm-3 col-md-3">
                            <a class="btn btn-info mb10" href="{{ url('archive/error') }}">← Back</a>
                        </div>
                    </div>
                    <div class="row">
                    	<div class="panel panel-default">
							<div class="panel-body">
                                @if(isset($histories) && count($histories) > 0)
          						<div class="table-responsive">
          							<table class="table" id="table2">
                                        @if($category == "completion_error")
                                        <thead>
                                            <tr>
                                                <th>Material</th>
                                                <th>Location</th>
                                                <th>Issue Plant</th>
                                                <th>Reference Number</th>
                                                <th>Lot</th>
                                                <th>Error</th>
                                                <th><center>Date</center></th>
                                                <th><center>Conf</center></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $index = 1; ?>
                                            @foreach ($histories as $history)
                                            @if($index % 2 == 1)
                                            <tr class="odd">
                                            @else
                                            <tr class="even">
                                            @endif
                                                <td>{{ $history->material_number }}</td>
                                                <td>{{ $history->completion_location }}</td>
                                                <td>{{ $history->completion_issue_plant }}</td>
                                                <td>{{ $history->completion_reference_number }}</td>
                                                <td>{{ $history->lot }}</td>
                                                <td>{{ $history->error_description }}</td>
                                                <td><center>{{ $history->created_at }}</center></td>
                                                <td class="center">
                                                    <center>
                                                        <a href="javascript:void(0)"  data-toggle="modal" data-target="#myModal" onclick="deleteConfirmation('{{ url("errorreport/$filename/$history->id/delete") }}', '{{ $history->material_number }}');">
                                                            <span class="fa fa-times"></span>
                                                        </a>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <a href="{{ url("errorreport/$filename/$history->id/edit") }}">
                                                            <span class="fa fa-pencil"></span>
                                                        </a>
                                                    </center>
                                                </td>
                                             </tr>
                                             @endforeach
                                        </tbody>
                                        @else
                                        <thead>
                                            <tr>
                                                <th>Material</th>
                                                <th>Issue Loc</th>
                                                <th>Issue Plant</th>
                                                <th>Receive Loc</th>
                                                <th>Receive Plant</th>
                                                <th>Cost Center</th>
                                                <th>GL Account</th>
                                                <th>Trans Code</th>
                                                <th>Movement Type</th>
                                                <th>Reason Code</th>
                                                <th>Lot</th>
                                                <th>Error</th>
                                                <th><center>Date</center></th>
                                                <th><center>Conf</center></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $index = 1; ?>
                                            @foreach ($histories as $history)
                                            @if($index % 2 == 1)
                                            <tr class="odd">
                                            @else
                                            <tr class="even">
                                            @endif
                                                <td>{{ $history->material_number }}</td>
                                                <td>{{ $history->transfer_issue_location }}</td>
                                                <td>{{ $history->transfer_issue_plant }}</td>
                                                <td>{{ $history->transfer_receive_location }}</td>
                                                <td>{{ $history->transfer_receive_plant }}</td>
                                                <td>
                                                    @if($history->transfer_cost_center != null)
                                                    {{ $history->transfer_cost_center }}
                                                    @else
                                                    -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($history->transfer_gl_account != null)
                                                    {{ $history->transfer_gl_account }}
                                                    @else
                                                    -
                                                    @endif
                                                </td>
                                                <td>{{ $history->transfer_transaction_code }}</td>
                                                <td>{{ $history->transfer_movement_type }}</td>
                                                <td>
                                                    @if($history->transfer_reason_code != null)
                                                    {{ $history->transfer_reason_code }}
                                                    @else
                                                    -
                                                    @endif
                                                </td>
                                                <td>{{ $history->lot }}</td>
                                                <td>{{ $history->error_description }}</td>
                                                <td><center>{{ $history->created_at }}</center></td>
                                                <td class="center">
                                                    <center>
                                                        <a href="javascript:void(0)"  data-toggle="modal" data-target="#myModal" onclick="deleteConfirmation('{{ url("errorreport/$filename/$history->id/delete") }}', '{{ $history->material_number }}');">
                                                            <span class="fa fa-times"></span>
                                                        </a>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <a href="{{ url("errorreport/$filename/$history->id/edit") }}">
                                                            <span class="fa fa-pencil"></span>
                                                        </a>
                                                    </center>
                                                </td>
                                             </tr>
                                             @endforeach
                                        </tbody>
                                        @endif
           							</table>
          						</div>
                                @else
                                    <center>Histories data is empty</center>
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
                        jQuery('.modal-body').text("Are you sure want to delete error report with barcode number '" + name + "'?");
                        jQuery('#modalDeleteButton').attr("href", url);
                    }
                </script>

                @stop