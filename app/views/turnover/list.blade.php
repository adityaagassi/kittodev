                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
				@stop
	 	 		

                @section('content')

                <div class="pageheader">
                    <h2><i class="fa fa-clock-o"></i> List Turn Over </h2>
                </div>

                <div class="contentpanel">
                    <div class="row">
        
                        <div class="col-sm-6 col-md-3">
                          <div class="panel panel-primary panel-stat">
                            <div class="panel-heading">
                              
                              <div class="stat">
                                <div class="row">
                                
                                <div class="mt15"></div>
                                  <div class="col-xs-12">
                                    <small class="stat-label">Active</small>
                                    <h1>{{ $active}}</h1>
                                  </div>
                                </div><!-- row -->
                                  
                              </div><!-- stat -->
                              
                            </div><!-- panel-heading -->
                          </div><!-- panel -->
                        </div><!-- col-sm-6 -->

                        <div class="col-sm-6 col-md-3">
                          <div class="panel panel-success panel-stat">
                            <div class="panel-heading">
                              
                              <div class="stat">
                                <div class="row">
                                  <div class="col-xs-12">
                                    <small class="stat-label">Max</small>
                                    <h1>{{ $max }}</h1>
                                  </div>
                                </div><!-- row -->
                              </div><!-- stat -->
                              
                            </div><!-- panel-heading -->
                          </div><!-- panel -->
                        </div><!-- col-sm-6 -->
                        
                        <div class="col-sm-6 col-md-3">
                          <div class="panel panel-danger panel-stat">
                            <div class="panel-heading">
                              
                              <div class="stat">
                                <div class="row">
                                  <div class="col-xs-12">
                                    <small class="stat-label">Min</small>
                                    <h1>{{ $min }}</h1>
                                  </div>
                                </div><!-- row -->
                              </div><!-- stat -->
                              
                            </div><!-- panel-heading -->
                          </div><!-- panel -->
                        </div><!-- col-sm-6 -->
                        
                        <div class="col-sm-6 col-md-3">
                          <div class="panel panel-dark panel-stat">
                            <div class="panel-heading">
                              
                              <div class="stat">
                                <div class="row">
                                  <div class="col-xs-12">
                                    <small class="stat-label">Average</small>
                                    <h1>{{ $avg }}</h1>
                                  </div>
                                </div><!-- row -->
                                  
                              </div><!-- stat -->
                              
                            </div><!-- panel-heading -->
                          </div><!-- panel -->
                        </div><!-- col-sm-6 -->

                    </div>
                    <div class="row">
                    	<div class="panel panel-default">
							           <div class="panel-body">
                            @if(isset($turnovers) && count($turnovers) > 0)
                            		<div class="table-responsive">
                            			<table class="table" id="table2">
                            	<thead>
                            		<tr>
                                    <th>Location</th>
                                    <th>Material Number</th>
                                    <th>Description</th>
                                    <th>Barcode Number</th>
                                    <th>Completion Qty</th>
                                    <th>Frequency of Completion</th>
                                    <th>Transfer Qty</th>
                                    <th>Frequency of Transfer</th>
                                    <th>Cycle</th>
                            		</tr>
                            	</thead>
                            					<tbody>
                                        <?php $index = 1; ?>
                                        @foreach ($turnovers as $turnover)
                                        @if($index % 2 == 1)
                                        <tr class="odd">
                                        @else
                                        <tr class="even">
                                        @endif
                                            <td>{{ $turnover->location }}</td>
                                            <td>{{ $turnover->material_number }}</td>
                                            <td>{{ $turnover->description }}</td>
                                            <td>{{ $turnover->barcode_number }}</td>
                                            <td>{{ $turnover->completion_lot }}</td>
                                            <td>{{ $turnover->completion }}</td>
                                            <td>{{ $turnover->transfer_lot }}</td>
                                            <td>{{ $turnover->transfer }}</td>
                                            <td>{{ $turnover->cycle }}</td>
                                        </tr>
                                        @endforeach
                            					</tbody>
                            		</div>
                                @else
                                    <center>Turn Over data is empty</center>
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