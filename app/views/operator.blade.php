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
        <link href="{{ url("css/jquery.gritter.css") }}" rel="stylesheet">

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
                    <div class="col-md-offset-7 col-md-5">
                        @if(isset($error))
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <strong>Signin failed!</strong> {{ $error }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-3 col-md-6">
                        <div class="logopanel">
                            <center>
                                <h1>
                                    <span>[</span> KITTO <span>]</span>
                                </h1>
                                <h4>
                                    Kanban Information Transaction And Turn Over
                                </h4>
                                <br />
                                <br />
                            </center>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-3 col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">Pilih Menu</h3>
                            </div>
                            <div class="panel-body">
                            <a href="{{ url("completions") }}" class="btn btn-lg btn-success btn-block">Completion</a>
                            <a href="{{ url("transfers") }}" class="btn btn-lg btn-danger btn-block">Transfer</a>
                            </div>
                        </div>
                    </div><!-- col-sm-5 -->
                </div><!-- row -->
                <div class="signup-footer">
                    <div class="pull-left">
                        Production Control &copy; 2017. All Rights Reserved.
                    </div>
                    <div class="pull-right">
                        <a href="{{ url("signin") }}" target="_blank">Login</a>
                    </div>
                    <center>
                        
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
        <script src="{{ url("js/jquery.gritter.min.js") }}"></script>
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
