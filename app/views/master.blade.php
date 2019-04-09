<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="{{ url("images/favicon.png") }}" type="image/png">

        <title>KITTO きっと - Kanban Information, Transaction & Turn Over.</title>
        
        <link href="{{ url("css/style.default.css") }}" rel="stylesheet">
        <link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
        <link href="{{ url("css/custom.css") }}" rel="stylesheet">

		@yield('stylesheets')

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="js/html5shiv.js"></script>
        <script src="js/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>
        
        <!-- Preloader -->
        <div id="preloader">
            <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
        </div>

		@include('leftbar')
		<section>
			<div class="mainpanel">
				@include('header')
				@yield('content')
			</div>
		</section>

        <script src="{{ url("js/jquery-1.10.2.min.js") }}"></script>
        <script src="{{ url("js/jquery-migrate-1.2.1.min.js") }}"></script>
        <script src="{{ url("js/jquery-ui-1.10.3.min.js") }}"></script>
        <script src="{{ url("js/bootstrap.min.js") }}"></script>
        <script src="{{ url("js/modernizr.min.js") }}"></script>
        <script src="{{ url("js/jquery.sparkline.min.js") }}"></script>
        <script src="{{ url("js/toggles.min.js") }}"></script>
        <script src="{{ url("js/retina.min.js") }}"></script>
        <script src="{{ url("js/jquery.cookies.js") }}"></script>

        <script src="{{ url("js/jquery.datatables.min.js") }}"></script>
        <script src="{{ url("js/chosen.jquery.min.js") }}"></script>
        <script src="{{ url("js/jquery.validate.min.js") }}"></script>
		@yield('scripts')
        <script src="{{ url("js/custom.js") }}"></script>
    </body>
</html>