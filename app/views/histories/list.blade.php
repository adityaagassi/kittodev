                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
				@stop
	 	 		

                @section('content')

                <div class="pageheader">
                    <h2><i class="fa fa-clock-o"></i> Histories </h2>
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
                                                <th width="20%">Username</th>
                                                <th width="55%">Description</th>
                                                <th width="25%"><center>Date</center></th>
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
											    <td>{{ $history->user->name }}</td>
											    <td>{{ $history->description }}</td>
                                                <td><center>{{ date_format($history->created_at, 'j F Y, H:i:s') }}</center></td>
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