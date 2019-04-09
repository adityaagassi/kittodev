                @extends('master')
                @section('content')

                <div class="pageheader">
                    <h2><i class="fa fa-home"></i> Dashboard </h2>
                </div>

                <div class="contentpanel">
                    
                    <!-- Today -->
                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <div class="panel panel-success panel-stat">
                                <div class="panel-heading">

                                    <div class="stat">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <small class="stat-label">Completion Today</small>
                                                <h1>{{ $completion_today }}</h1>
                                            </div>
                                        </div>
                                        <div class="mb15"></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="panel panel-danger panel-stat">
                                <div class="panel-heading">

                                    <div class="stat">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <small class="stat-label">Transfer Today</small>
                                                <h1>{{ $transfer_today }}</h1>
                                            </div>
                                        </div><!-- row -->
                                        <div class="mb15"></div>
                                    </div><!-- stat -->

                                </div><!-- panel-heading -->
                            </div><!-- panel -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb30">
                            <h5 class="subtitle mb5">Completion Today</h5>
                            <div id="barchart_completion_daily" style="width: 100%; height: 300px;"></div>
                        </div>
                        <div class="col-md-6 mb30">
                            <h5 class="subtitle mb5">Transfer Today</h5>
                            <div id="barchart_transfer_daily" style="width: 100%; height: 300px;"></div>
                        </div>
                    </div>

                    <!-- Weekly -->
                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <div class="panel panel-success panel-stat">
                                <div class="panel-heading">

                                    <div class="stat">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <small class="stat-label">Completion This Week</small>
                                                <h1>{{ $completion_weekly }}</h1>
                                            </div>
                                        </div>
                                        <div class="mb15"></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="panel panel-danger panel-stat">
                                <div class="panel-heading">
                                    <div class="stat">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <small class="stat-label">Transfer This Week</small>
                                                <h1>{{ $transfer_weekly }}</h1>
                                            </div>
                                        </div>
                                        <div class="mb15"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb30">
                            <h5 class="subtitle mb5">Completion This Week</h5>
                            <div id="barchart_completion_weekly" style="width: 100%; height: 300px;"></div>
                        </div>
                        <div class="col-md-6 mb30">
                            <h5 class="subtitle mb5">Transfer This Week</h5>
                            <div id="barchart_transfer_weekly" style="width: 100%; height: 300px;"></div>
                        </div>
                    </div>

                    <!-- Monthly -->
                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <div class="panel panel-success panel-stat">
                                <div class="panel-heading">

                                    <div class="stat">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <small class="stat-label">Completion This Month</small>
                                                <h1>{{ $completion_monthly }}</h1>
                                            </div>
                                        </div>
                                        <div class="mb15"></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="panel panel-danger panel-stat">
                                <div class="panel-heading">
                                    <div class="stat">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <small class="stat-label">Transfer This Month</small>
                                                <h1>{{ $transfer_monthly }}</h1>
                                            </div>
                                        </div>
                                        <div class="mb15"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6 mb30">
                            <h5 class="subtitle mb5">Completion This Month</h5>
                            <div id="barchart_completion_monthly" style="width: 100%; height: 300px;"></div>
                        </div>
                        <div class="col-md-6 mb30">
                            <h5 class="subtitle mb5">Transfer This Month</h5>
                            <div id="barchart_transfer_monthly" style="width: 100%; height: 300px;"></div>
                        </div>

                    </div>

                    <!-- Yearly -->
                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <div class="panel panel-success panel-stat">
                                <div class="panel-heading">
                                    <div class="stat">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <small class="stat-label">Completion This Year</small>
                                                <h1>{{ $completion_yearly }}</h1>
                                            </div>
                                        </div>
                                        <div class="mb15"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="panel panel-danger panel-stat">
                                <div class="panel-heading">

                                    <div class="stat">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <small class="stat-label">Transfer This Year</small>
                                                <h1>{{ $transfer_yearly }}</h1>
                                            </div>
                                        </div>
                                        <div class="mb15"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb30">
                            <h5 class="subtitle mb5">Completion This Year</h5>
                            <div id="barchart_completion_yearly" style="width: 100%; height: 300px;"></div>
                        </div>
                        <div class="col-md-6 mb30">
                            <h5 class="subtitle mb5">Transfer This Year</h5>
                            <div id="barchart_transfer_yearly" style="width: 100%; height: 300px;"></div>
                        </div>
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
                <script type="text/javascript">
                    jQuery(document).ready(function() {
                        /***** BAR CHART *****/
                        var barchart_completion_daily_data = {{ json_encode($completion_today_graphs); }};

                         jQuery.plot("#barchart_completion_daily", [ barchart_completion_daily_data ], {
                            series: {
                                lines: {
                                  lineWidth: 1  
                                },
                                bars: {
                                    show: true,
                                    barWidth: 0.5,
                                    align: "center",
                                   lineWidth: 0,
                                   fillColor: "#1CAF9A"
                                }
                            },
                            grid: {
                                borderColor: '#ddd',
                                borderWidth: 1,
                                labelMargin: 10
                              },
                            xaxis: {
                                mode: "categories",
                                tickLength: 0
                            }
                         });

                         var barchart_completion_weekly_data = {{ json_encode($completion_weekday_graphs); }};

                         jQuery.plot("#barchart_completion_weekly", [ barchart_completion_weekly_data ], {
                            series: {
                                lines: {
                                  lineWidth: 1  
                                },
                                bars: {
                                    show: true,
                                    barWidth: 0.5,
                                    align: "center",
                                   lineWidth: 0,
                                   fillColor: "#1CAF9A"
                                }
                            },
                            grid: {
                                borderColor: '#ddd',
                                borderWidth: 1,
                                labelMargin: 10
                              },
                            xaxis: {
                                mode: "categories",
                                tickLength: 0
                            }
                         });

                         var barchart_completion_monthly_data = {{ json_encode($completion_monthday_graphs); }};

                         jQuery.plot("#barchart_completion_monthly", [ barchart_completion_monthly_data ], {
                            series: {
                                lines: {
                                  lineWidth: 1  
                                },
                                bars: {
                                    show: true,
                                    barWidth: 0.5,
                                    align: "center",
                                   lineWidth: 0,
                                   fillColor: "#1CAF9A"
                                }
                            },
                            grid: {
                                borderColor: '#ddd',
                                borderWidth: 1,
                                labelMargin: 10
                              },
                            xaxis: {
                                mode: "categories",
                                tickLength: 0
                            }
                         });
                         
                         var barchart_completion_yearly_data = {{ json_encode($completion_yearday_graphs); }};

                         jQuery.plot("#barchart_completion_yearly", [ barchart_completion_yearly_data ], {
                            series: {
                                lines: {
                                  lineWidth: 1  
                                },
                                bars: {
                                    show: true,
                                    barWidth: 0.5,
                                    align: "center",
                                   lineWidth: 0,
                                   fillColor: "#1CAF9A"
                                }
                            },
                            grid: {
                                borderColor: '#ddd',
                                borderWidth: 1,
                                labelMargin: 10
                              },
                            xaxis: {
                                mode: "categories",
                                tickLength: 0
                            }
                         });





                        var barchart_transfer_daily_data = {{ json_encode($transfer_today_graphs); }};

                         jQuery.plot("#barchart_transfer_daily", [ barchart_transfer_daily_data ], {
                            series: {
                                lines: {
                                  lineWidth: 1  
                                },
                                bars: {
                                    show: true,
                                    barWidth: 0.5,
                                    align: "center",
                                   lineWidth: 0,
                                   fillColor: "#D9534F"
                                }
                            },
                            grid: {
                                borderColor: '#ddd',
                                borderWidth: 1,
                                labelMargin: 10
                              },
                            xaxis: {
                                mode: "categories",
                                tickLength: 0
                            }
                         });

                        var barchart_transfer_weekly_data = {{ json_encode($transfer_weekday_graphs); }};

                         jQuery.plot("#barchart_transfer_weekly", [ barchart_transfer_weekly_data ], {
                            series: {
                                lines: {
                                  lineWidth: 1  
                                },
                                bars: {
                                    show: true,
                                    barWidth: 0.5,
                                    align: "center",
                                   lineWidth: 0,
                                   fillColor: "#D9534F"
                                }
                            },
                            grid: {
                                borderColor: '#ddd',
                                borderWidth: 1,
                                labelMargin: 10
                              },
                            xaxis: {
                                mode: "categories",
                                tickLength: 0
                            }
                         });

                        var barchart_transfer_monthly_data = {{ json_encode($transfer_monthday_graphs); }};

                         jQuery.plot("#barchart_transfer_monthly", [ barchart_transfer_monthly_data ], {
                            series: {
                                lines: {
                                  lineWidth: 1  
                                },
                                bars: {
                                    show: true,
                                    barWidth: 0.5,
                                    align: "center",
                                   lineWidth: 0,
                                   fillColor: "#D9534F"
                                }
                            },
                            grid: {
                                borderColor: '#ddd',
                                borderWidth: 1,
                                labelMargin: 10
                              },
                            xaxis: {
                                mode: "categories",
                                tickLength: 0
                            }
                         });

                        var barchart_transfer_yearly_data = {{ json_encode($transfer_yearday_graphs); }};

                         jQuery.plot("#barchart_transfer_yearly", [ barchart_transfer_yearly_data ], {
                            series: {
                                lines: {
                                  lineWidth: 1  
                                },
                                bars: {
                                    show: true,
                                    barWidth: 0.5,
                                    align: "center",
                                   lineWidth: 0,
                                   fillColor: "#D9534F"
                                }
                            },
                            grid: {
                                borderColor: '#ddd',
                                borderWidth: 1,
                                labelMargin: 10
                              },
                            xaxis: {
                                mode: "categories",
                                tickLength: 0
                            }
                         });
                     });
                </script>
                @stop
