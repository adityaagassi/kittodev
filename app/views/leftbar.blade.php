        <div class="leftpanel">
            <div class="logopanel">
                <h1><span>[</span> Kitto <span>]</span> - きっと</h1>
            </div>
            <div class="leftpanelinner">
                @if(Session::has('name'))
                <div class="visible-xs hidden-sm hidden-md hidden-lg">   
                    <div class="media userlogged">
                        <img alt="" src="{{ url("/images/photos/loggeduser.png") }}" class="media-object">
                        <div class="media-body">
                            <h4>{{ Session::get('name') }}</h4>
                        </div>
                    </div>
                    <h5 class="sidebartitle actitle">Account</h5>
                    <ul class="nav nav-pills nav-stacked nav-bracket mb30">
                      <li><a href="javascript:void(0)"><i class="fa fa-user"></i> <span>Profile</span></a></li>
                      <!--<li><a href=""><i class="fa fa-cog"></i> <span>Account Settings</span></a></li>-->
                      <li><a href="{{ url("/signout") }}"><i class="fa fa-sign-out"></i> <span>Sign Out</span></a></li>
                    </ul>
                </div>
                @endif
                @if(Session::has('level_id'))
                <h5 class="sidebartitle">Menu</h5>
                <ul class="nav nav-pills nav-stacked nav-bracket">
                    @if(isset($page) && $page == "dashboard")
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url("/dashboard") }}"><i class="glyphicon glyphicon-home"></i> <span>Dashboard</span></a>
                    </li>
                    @if(isset($page) && $page == "inventories")
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url("/inventories/filter") }}"><i class="glyphicon glyphicon-shopping-cart"></i> <span>Inventory</span></a>
                    </li>
                    @if(isset($page) && $page == "turnover")
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url("/turnover/filter") }}"><i class="glyphicon glyphicon-retweet"></i> <span>Turn Over</span></a>
                    </li>
                    <!--
                    @if(isset($page) && $page == "batchoutputs")
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url("/batchoutputs") }}"><i class="fa fa-inbox"></i> <span>Resume History</span></a>
                    </li>
                    @if(isset($page) && $page == "error_report")
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url("/errorreport") }}"><i class="fa fa-inbox"></i> <span>Error Report</span></a>
                    </li>
                    -->
                </ul>
                <h5 class="sidebartitle">Archive</h5>
                <ul class="nav nav-pills nav-stacked nav-bracket">
                    @if(isset($page) && $page == "archive_resume")
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url("/archive/resume") }}"><i class="glyphicon glyphicon-floppy-saved"></i> <span>Archive</span></a>
                    </li>
                    @if(isset($page) && $page == "archive_error")
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url("/archive/error") }}"><i class="glyphicon glyphicon-floppy-remove"></i> <span>Error Report</span></a>
                    </li>
                </ul>
                <h5 class="sidebartitle">Completions</h5>
                <ul class="nav nav-pills nav-stacked nav-bracket">
                    @if(isset($page) && in_array($page, Array("completion_adjustment", "completion_adjustment_excel", "completion_cancel")))
                    <li class="nav-parent nav-active active">
                    @else
                    <li class="nav-parent">
                    @endif
                        <a href="#"><i class="glyphicon glyphicon-wrench"></i> <span>Completion Adjustment</span></a>
                        @if(isset($page) && in_array($page, Array("completion_adjustment", "completion_adjustment_excel", "completion_cancel")))
                        <ul class="children" style="display: block;">
                        @else
                        <ul class="children">
                        @endif
                            @if(isset($page) && $page == "completion_adjustment")
                            <li class="active">
                            @else
                            <li>
                            @endif
                                <a href="{{ url("/completions/adjustment") }}"><i class="glyphicon glyphicon-barcode"></i> <span>Scan</span></a>
                            </li>
                            @if(isset($page) && $page == "completion_adjustment_excel")
                            <li class="active">
                            @else
                            <li>
                            @endif
                                <a href="{{ url("/completions/adjustment/excel") }}"><i class="glyphicon glyphicon-barcode"></i> <span>Excel</span></a>
                            </li>
                            @if(isset($page) && $page == "completion_cancel")
                            <li class="active">
                            @else
                            <li>
                            @endif
                                <a href="{{ url("/completions/cancel") }}"><i class="glyphicon glyphicon-barcode"></i> <span>Cancel</span></a>
                            </li>
                        </ul>
                    </li>
                    @if(isset($page) && $page == "completion_history")
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url("/completions/filter") }}"><i class="glyphicon glyphicon-folder-open"></i> <span>Completion History</span></a>
                    </li>
                    @if(isset($page) && $page == "completion_temporaries")
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url("/completions/temporary") }}"><i class="glyphicon glyphicon-time"></i> <span>Completion Temporary</span></a>
                    </li>
                </ul>
                <h5 class="sidebartitle">Transfers</h5>
                <ul class="nav nav-pills nav-stacked nav-bracket">
                    @if(isset($page) && in_array($page, Array("transfer_adjustment", "transfer_adjustment_excel", "transfer_cancel")))
                    <li class="nav-parent nav-active active">
                    @else
                    <li class="nav-parent">
                    @endif
                        <a href="#"><i class="glyphicon glyphicon-wrench"></i> <span>Transfer Adjustment</span></a>
                        @if(isset($page) && in_array($page, Array("transfer_adjustment", "transfer_adjustment_excel", "transfer_cancel")))
                        <ul class="children" style="display: block;">
                        @else
                        <ul class="children">
                        @endif
                            @if(isset($page) && $page == "transfer_adjustment")
                            <li class="active">
                            @else
                            <li>
                            @endif
                                <a href="{{ url("/transfers/adjustment") }}"><i class="glyphicon glyphicon-barcode"></i> <span>Scan</span></a>
                            </li>
                            @if(isset($page) && $page == "transfer_adjustment_excel")
                            <li class="active">
                            @else
                            <li>
                            @endif
                                <a href="{{ url("/transfers/adjustment/excel") }}"><i class="glyphicon glyphicon-barcode"></i> <span>Excel</span></a>
                            </li>
                            @if(isset($page) && $page == "transfer_cancel")
                            <li class="active">
                            @else
                            <li>
                            @endif
                                <a href="{{ url("/transfers/cancel") }}"><i class="glyphicon glyphicon-barcode"></i> <span>Cancel</span></a>
                            </li>
                        </ul>
                    </li>
                    @if(isset($page) && $page == "transfer_history")
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url("/transfers/filter") }}"><i class="glyphicon glyphicon-folder-open"></i> <span>Transfer History</span></a>
                    </li>
                    <!-- 
                    @if(isset($page) && $page == "transfer_temporaries")
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url("/transfers/temporary") }}"><i class="fa fa-clock-o"></i> <span>Transfer Temporary</span></a>
                    </li> 
                    -->
                </ul>
                <h5 class="sidebartitle">Return</h5>
                <ul class="nav nav-pills nav-stacked nav-bracket">
                    @if(isset($page) && $page == "returns")
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url("/returns/add") }}"><i class="glyphicon glyphicon-repeat"></i> <span>Return</span></a>
                    </li>
                    <!--
                    @if(isset($page) && $page == "return_history")
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url("/return/filter") }}"><i class="fa fa-clock-o"></i> <span>Return History</span></a>
                    </li>
                    -->
                </ul>
                <h5 class="sidebartitle">Repair</h5>
                <ul class="nav nav-pills nav-stacked nav-bracket">
                    @if(isset($page) && $page == "repairs")
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url("/repairs/add") }}"><i class="glyphicon glyphicon-repeat"></i> <span>Repair</span></a>
                    </li>
                   @if(isset($page) && $page == "after-repairs")
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url("/after-repairs/add") }}"><i class="glyphicon glyphicon-repeat"></i> <span>After Repair</span></a>
                    </li>
                </ul>
                @if(Session::get('level_id') == 1 || Session::get('level_id') == 2)
                <h5 class="sidebartitle">Manual</h5>
                <ul class="nav nav-pills nav-stacked nav-bracket">
                    @if(isset($page) && $page == "completion_adjustment_manual")
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url("/completions/adjustment/manual") }}"><i class="glyphicon glyphicon-edit"></i> <span>Manual Completion</span></a>
                    </li>
                    @if(isset($page) && $page == "transfer_adjustment_manual")
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url("/transfers/adjustment/manual") }}"><i class="glyphicon glyphicon-edit"></i> <span>Manual Transfer</span></a>
                    </li>
                </ul>
                <h5 class="sidebartitle">Master</h5>
                <ul class="nav nav-pills nav-stacked nav-bracket">
                    @if(isset($page) && $page == "materials")
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url("/materials/filter") }}"><i class="glyphicon glyphicon-tag"></i> <span>Materials</span></a>
                    </li>
                    @if(isset($page) && $page == "completions")
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url("/completions/list/filter") }}"><i class="glyphicon glyphicon-tag"></i> <span>Completion</span></a>
                    </li>
                    @if(isset($page) && $page == "transfers")
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url("/transfers/list/filter") }}"><i class="glyphicon glyphicon-tag"></i> <span>Transfer</span></a>
                    </li>
                    <!--
                    @if(isset($page) && $page == "batchoutputs")
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url("/batchoutputs") }}"><i class="fa fa-clock-o"></i> <span>Batch Output</span></a>
                    </li>
                    -->
                    @if(isset($page) && $page == "users")
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url("/users") }}"><i class="glyphicon glyphicon-user"></i> <span>Users</span></a>
                    </li>
                    @if(isset($page) && $page == "session")
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url("/sessions") }}"><i class="fa fa-users"></i> <span>Sessions</span></a>
                    </li>
                    @if(isset($page) && $page == "settings")
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url("/settings") }}"><i class="glyphicon glyphicon-cog"></i> <span>Settings</span></a>
                    </li>
                </ul>
                @endif  
                @else
                <h5 class="sidebartitle">Operator Navigation</h5>
                <ul class="nav nav-pills nav-stacked nav-bracket">
                    @if(isset($page) && $page == "completion")
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url("/completions") }}"><i class="fa fa-laptop"></i> <span>Completion</span></a>
                    </li>
                    @if(isset($page) && $page == "transfer")
                    <li class="active">
                    @else
                    <li>
                    @endif
                        <a href="{{ url("/transfers") }}"><i class="fa fa-laptop"></i> <span>Transfer</span></a>
                    </li>
                </ul>
                @endif
            </div>
        </div>