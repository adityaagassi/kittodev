                @extends('master')
                @section('content')

                <div class="pageheader">
                    <h2><i class="fa fa-home"></i> Dashboard </h2>
                </div>

                <div class="contentpanel">
                    <div class="row">
                        <div class="col-sm-12">
                          <div class="panel panel-primary">
                            <div class="panel-heading">
                              <div class="panel-btns">
                                <a href="" class="minimize">-</a>
                              </div><!-- panel-btns -->
                              <h3 class="panel-title">Announcement</h3>
                            </div>
                            <div class="panel-body">
                              <h4>Technical Support : 1189 Agassi</h4>
                              <h4>Transaction Support : 1157 Istiqomah</h4>
                            </div>
                          </div><!-- panel -->
                        </div><!-- col-sm-6 -->
                        
                        <div class="col-sm-6">
                          <div class="panel panel-success">
                            <div class="panel-heading">
                              <div class="panel-btns">
                                <a href="" class="minimize">−</a>
                              </div><!-- panel-btns -->
                              <h3 class="panel-title">Guide User</h3>
                            </div>
                            <div class="panel-body">
                              <ol>
                                  <li><a href="{{ url('/dashboard/Manual_Completion.PDF')}}">How To Completion</a></li>
                                  <li><a href="{{ url('/dashboard/Manual_Transfer.PDF')}}">How To Transfer</a></li>
                                  <li><a href="{{ url('/dashboard/Manual_Cancel_Completion.PDF')}}">How To Cancel Completion</a></li>
                                  <li><a href="{{ url('/dashboard/Manual_Cancel_Transfer.PDF')}}">How To Cancel Transfer</a></li>
                                  <li><a href="{{ url('/dashboard/Manual_Return.PDF')}}">How To Return</a></li>
                                  <li><a href="{{ url('/dashboard/Manual_Inventory_History.PDF')}}">Inventory, Completion History, Transfer History</a></li>
                                  <li><a href="{{ url('/dashboard/Change_Password.PDF')}}">How To Change Password</a></li>
                                  <li><a href="{{ url('/dashboard/Manual_Repair.PDF')}}">Manual Repair</a></li>
                                  <li><a href="{{ url('/dashboard/Manual_After_Repair.PDF')}}">Manual After Repair</a></li>
                              </ol>
                            </div>
                          </div><!-- panel -->
                        </div><!-- col-sm-6 -->
                        
                        <div class="col-sm-6">
                          <div class="panel panel-success">
                            <div class="panel-heading">
                              <div class="panel-btns">
                                <a href="" class="minimize">−</a>
                              </div><!-- panel-btns -->
                              <h3 class="panel-title">Guide Admin</h3>
                            </div>
                            <div class="panel-body">
                              <ol>
                                  <li><a href="{{ url('/dashboard/Master_Material.xls')}}">Excel for Import Master Material</a></li>
                                  <li><a href="{{ url('/dashboard/Master_Completion.xls')}}">Excel for Import Master Completion</a></li>
                                  <li><a href="{{ url('/dashboard/Master_Transfer.xls')}}">Excel for Import Master Transfer</a></li>
                                  <li><a href="{{ url('/dashboard/Error_Report.pdf')}}">Error Report</a></li>
                                  <li><a href="{{ url('/dashboard/Settings.pdf')}}">Settings</a></li>
                                  <li><a href="{{ url('/dashboard/Completion_Temporary.pdf')}}">Completion Temporary</a></li>
                                  <li><a href="{{ url('/dashboard/Manual_Import_Master.pdf')}}">How To Import Master</a></li>
                            </div>
                          </div><!-- panel -->
                        </div><!-- col-sm-6 -->
                        
                        <div class="col-sm-12">
                          <div class="panel panel-dark">
                            <div class="panel-heading">
                              <div class="panel-btns">
                                <a href="" class="minimize">−</a>
                              </div><!-- panel-btns -->
                              <h3 class="panel-title">Graph of Inventory</h3>
                            </div>
                            <div class="panel-body">
                                <div class="row mb15">
                                    <div class="col-sm-4">
                                        Location
                                    </div>
                                    <div class="col-sm-4">
                                        Filled Kanban at Store
                                    </div>
                                    <div class="col-sm-4">
                                        Percentage of Inventory
                                    </div>
                                </div>
                                @if (isset($graphpercentages))
                                @foreach($graphpercentages as $graphpercentage)
                                <div class="row">
                                    <div class="col-sm-4">
                                    {{ $graphpercentage->location }}
                                    </div>
                                    <div class="col-sm-4">
                                    {{ $graphpercentage->qty }}
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="progress progress-sm" style="margin-top: 5px;">
                                            <div style="width: {{ $graphpercentage->percentage }}%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="{{ $graphpercentage->percentage }}" role="progressbar" class="progress-bar progress-bar-primary"></div>
                                        </div><!-- progress -->
                                    </div>
                                    <div class="col-sm-1">
                                    {{ $graphpercentage->percentage }} %
                                    </div>
                                </div>
                                @endforeach
                                @endif
                            </div>
                          </div><!-- panel -->
                        </div><!-- col-sm-6 -->
                        
                      </div>
                </div><!-- contentpanel -->

                @stop

                @section('scripts')
                <script src="{{ url("js/flot/flot.min.js") }}"></script>
                <script src="{{ url("js/flot/flot.resize.min.js") }}"></script>
                <script src="{{ url("js/flot/flot.symbol.min.js") }}"></script>
                <script src="{{ url("js/flot/flot.crosshair.min.js") }}"></script>
                <script src="{{ url("js/flot/flot.categories.min.js") }}"></script>
                <script src="{{ url("js/flot/flot.pie.min.js") }}"></script>
                <script src="{{ url("js/raphael-2.1.0.min.js") }}"></script>
                @stop