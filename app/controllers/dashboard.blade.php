                @extends('master')
                @section('content')

                <div class="pageheader">
                    <h2><i class="fa fa-home"></i> Dashboard </h2>
                </div>

                <div class="contentpanel">


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
