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

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="js/html5shiv.js"></script>
        <script src="js/respond.min.js"></script>
        <![endif]-->
    </head>

    <body class="signin">
        <!-- Preloader -->
        <div id="preloader">
            <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
        </div>
        <section>
            <div class="signinpanel">
                <div class="row">
                    <div class="col-md-offset-3 col-md-6">
                        @if(isset($error))
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <strong>Signin failed!</strong> {{ $error }}
                            </div>
                        @endif
                    </div>
                </div>
                <!-- <div class="row">
                    <div class="col-md-offset-1 col-md-10">
                        <div class="logopanel">
                            <center>
                                <h1>
                                    <span>[</span> Kanban Information Transaction And Turn Over <span>]</span>
                                </h1>
                                <br />
                            </center>
                        </div>
                    </div>
                </div> -->
                <div class="row">
                    <div class="col-md-offset-3 col-md-6">
                        <ul class="pager">
                            <li class="previous"><a href="{{ url('/') }}">← Back</a></li>
                        </ul>
                    </div>
                    <div class="col-md-offset-3 col-md-6">
                        <form id ="basicForm" method="post" action="{{ url("signin")}}">
                            <h4 class="nomargin">Sign In</h4>
                            <p class="mt5 mb20">Login to access your account.</p>
                            <div class="form-group">
                                <input type="email" class="form-control uname" name="email" placeholder="Email" required />
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control pword" name="password" placeholder="Password" required />
                            </div>
                            <button class="btn btn-success btn-block">Sign In</button>
                        </form>
                    </div><!-- col-sm-5 -->
                </div><!-- row -->
                <div class="signup-footer">
                    <center>
                        Production Control &copy; 2017. All Rights Reserved.
                    </center>
                </div>
            </div><!-- signin -->
        </section>
        <script src="{{ url("js/jquery-1.10.2.min.js") }}"></script>
        <script src="{{ url("js/jquery-migrate-1.2.1.min.js") }}"></script>
        <script src="{{ url("js/bootstrap.min.js") }}"></script>
        <script src="{{ url("js/modernizr.min.js") }}"></script>
        <script src="{{ url("js/jquery.sparkline.min.js") }}"></script>
        <script src="{{ url("js/jquery.cookies.js") }}"></script>
        <script src="{{ url("js/toggles.min.js") }}"></script>
        <script src="{{ url("js/retina.min.js") }}"></script>
        <script src="{{ url("js/chosen.jquery.min.js") }}"></script>
        <script src="{{ url("js/jquery.validate.min.js") }}"></script>
        <script src="{{ url("js/custom.js") }}"></script> 
        <script>
            jQuery(document).ready(function() {
                // Basic Form
                jQuery("#basicForm").validate({
                    highlight: function(element) {
                        jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
                    },
                    success: function(element) {
                        jQuery(element).closest('.form-group').removeClass('has-error');
                    }
                });
            });
        </script>
    </body>
</html>
