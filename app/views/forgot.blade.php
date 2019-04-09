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
            <div class="signuppanel">
                <div class="row">
                    <div class="col-md-offset-6 col-md-6">
                        @if(isset($error))
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <strong>Forgot password failed!</strong> {{ $error }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="signup-info">
                            <div class="logopanel">
                                <h1><span>[</span> optico <span>]</span></h1>
                            </div><!-- logopanel -->
                            <div class="mb20"></div>
                            <h5><strong>Procurement Management Apps</strong></h5>
                            <p>Optico is a procurement management system that is perfect if you want to monitoring product procurement of your business.</p>
                            <div class="mb50"></div>
                        </div><!-- signup-info -->
                    </div><!-- col-sm-6 -->
                    <div class="col-md-6">
                        <form id ="basicForm" method="post" action="{{ url("forgot") }}">
                            <h3 class="nomargin">Forgot Password</h3>
                            <p class="mt5 mb20">Remember your password? <a href="{{ url("signin") }}"><strong>Sign In</strong></a></p>
                            <div class="mb10">
                                <label class="control-label">Email Address</label>
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" placeholder="ie. test@gmail.com" required />
                                </div>  
                            </div>
                            <br />
                            <button class="btn btn-success btn-block">Submit</button>     
                        </form>
                    </div><!-- col-sm-6 -->
                    
                </div><!-- row -->
                
                <div class="signup-footer">
                    <div class="pull-left">
                        &copy; 2016. All Rights Reserved.
                    </div>
                    <div class="pull-right">
                        Created By: <a href="http://github.com/aizcheryz" target="_blank">Moch. Fariz Al Hazmi</a>
                    </div>
                </div>
                
            </div><!-- signuppanel -->
          
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
                // Chosen Select
                jQuery(".chosen-select").chosen({
                    'width':'100%',
                    'white-space':'nowrap',
                    disable_search: true
                });
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
