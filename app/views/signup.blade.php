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
                    <div class="col-md-6">
                        <div class="signup-info">
                            <div class="logopanel">
                                <h1><span>[</span> optico <span>]</span></h1>
                            </div><!-- logopanel -->
                            <div class="mb20"></div>
                            <h5><strong>Procurement Management Apps</strong></h5>
                            <p>Optico is a procurement management system that is perfect if you want to monitoring product procurement of your business.</p>
                            <p>Below are some of the benefits you can have when purchasing this app.</p>
                            <div class="mb20"></div>
                            <div class="feat-list">
                                <i class="fa fa-wrench"></i>
                                <h4 class="text-success">Easy to Use</h4>
                                <p>Optico is made using Laravel 4.2 and Bootstrap 3 so you can easily customize any element of this template following the structure of Bootstrap 3.</p>
                            </div>
                            <div class="feat-list mb20">
                                <i class="fa fa-compress"></i>
                                <h4 class="text-success">Fully Responsive Layout</h4>
                                <p>Optico is design to fit on all browser widths and all resolutions on all mobile devices. Try to scale your browser and see the results.</p>
                            </div>
                            <h4 class="mb20">and much more...</h4>
                        </div><!-- signup-info -->
                    </div><!-- col-sm-6 -->
                    <div class="col-md-6">
                        <form id="basicForm" method="post" action="{{ url("signup") }}">
                            <h3 class="nomargin">Sign Up</h3>
                            <p class="mt5 mb20">Already a member? <a href="{{ url("signin") }}"><strong>Sign In</strong></a></p>
                            <div class="mb10">
                                <label class="control-label">Name</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Your name" name="name" required />
                                </div>
                            </div>
                            <div class="mb10">
                                <label class="control-label">Email Address</label>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="ie. test@gmail.com" name="email" required/>
                                </div>
                            </div>
                            <div class="mb10">
                                <label class="control-label">Password</label>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password" required/>
                                </div>
                            </div>
                            <div class="mb10">
                                <label class="control-label">Retype Password</label>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password_confirmation" required/>
                                </div>
                            </div>
                            <br />
                            <button class="btn btn-success btn-block">Sign Up</button>     
                        </form>
                    </div><!-- col-sm-6 -->
                </div><!-- row -->
                <div class="signup-footer">
                    <div class="pull-left">
                        Production Control &copy; 2017. All Rights Reserved.
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
