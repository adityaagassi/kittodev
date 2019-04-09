                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
				@stop
	 	 		

                @section('content')

                <div class="pageheader">
                    <h2><i class="fa fa-clock-o"></i> Error Report Histories </h2>
                </div>

                <div class="contentpanel">
                    <div class="row">
                    	<div class="panel panel-default">
							<div class="panel-body">
                                @if(isset($histories) && count($histories) > 0)
          						<div class="table-responsive">
          							<table class="table" id="table2">
										<thead>
											<tr>
                                                <th>Filename</th>
                                                <th>Filename</th>
                                                <th>Category</th>
                                                <th><center>Date</center></th>
                                                @if(Session::get('level_id') == 1 || Session::get('level_id') == 2)
                                                <th><center>Configuration</center></th>
                                                @endif
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
											    <td><a href={{ url("errorreport/$history->reference_file") }}>{{ $history->reference_file }}</a></td>
											    <td>
                                                    @if($history->category == "completion_error")
                                                    Error Completion
                                                    @else
                                                    Error Transfer
                                                    @endif
                                                </td>
                                                <td><center>{{ $history->created_at }}</center></td>

                                                @if(Session::get('level_id') == 1 || Session::get('level_id') == 2)
                                                <td><center>-</center></td>
                                                @endif
											 </tr>
                                             @endforeach
              							</tbody>
           							</table>
          						</div>
                                @else
                                    <center>Histories data is empty</center>
                                @endif
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