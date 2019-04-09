            <div class="headerbar">
                <a class="menutoggle"><i class="fa fa-bars"></i></a>
                <div class="header-right">
                    <ul class="headermenu">
                        <li>
                            @if(Session::has('name'))
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                    <!-- <img src="{{ url("/images/photos/loggeduser.png") }}" alt="" /> -->
                                    {{ Session::get('name') }}
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                                    <li>
                                        <a href="{{ url("/users/" . Session::get('id') . "/edit") }}"><i class="glyphicon glyphicon-cog"></i> Account Settings</a>
                                    </li>
                                    <li>
                                        <a href="{{ url("/signout") }}"><i class="glyphicon glyphicon-log-out"></i> Sign Out</a>
                                    </li>
                                </ul>
                            </div>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>