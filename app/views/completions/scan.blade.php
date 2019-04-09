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
        <link href="{{ url("css/custom.css") }}" rel="stylesheet">

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
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="panel-title">Completion</h3>
                            </div>
                            <div class="panel-body">
                                <!-- <form id ="basicForm" method="post" action="{{ url("/completions") }}"> -->
                                    <!-- <input id="barcode" type="text" placeholder="Barcode Number" class="form-control input-lg" name="barcode_number" required />
                                    <button class="btn btn-lg btn-success btn-block">Completion</button> -->
                                <!-- </div> -->
                                <input id="barcode" type="text" placeholder="Barcode Number" class="form-control input-lg" name="barcode_number" disabled />
                                <button id="submit" class="btn btn-lg btn-success btn-block">&#9655;&nbsp;&nbsp;&nbsp;Mulai</button>
                                <button id="finish" class="btn btn-lg btn-danger btn-block">&#9723;&nbsp;&nbsp;&nbsp;Selesai</button>
                            </div>
                        </div>
                    </div><!-- col-sm-5 -->
                </div><!-- row -->
                <div class="signup-footer">
                    <center>
                        Production Control &copy; 2017. All Rights Reserved.
                    </center>
                </div>
                <div id="failedModal" class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header modal-danger">
                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                                <h4 class="modal-title-danger" id="titleModal">Completion Gagal</h4>
                            </div>
                            <div class="modal-body modal-danger">
                                <div class="row">
                                    <div class="col-md-3">
                                        <img id="iconModal" src="{{ url('images/image-unregistered.png') }}" width="120" height="120" />
                                    </div>
                                    <div class="col-md-9" id="messageModal">
                                        <h4>Barcode tidak dapat di completion karena masih dalam waktu lead time.</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="listModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                                <h3 class="modal-title">Completion List</h3>
                            </div>
                            <div class="modal-body">
                                <div class="row" id="histories">
                                    <div class="col-md-12">
                                        <h3 id="totalCompletion" class="text-color-yamaha">30</h3>
                                        <div class="table-responsive">
                                            <table class="table table-hover mb30">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Barcode</th>
                                                        <th>Lot</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="histories_table">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

            var enabled = false;
            var histories = [];

            jQuery(document).ready(function() {

                $("#finish").hide();
                $("#submit").show();
                $("#histories_table").empty();

                // Basic Form
                /*
                jQuery("#basicForm").validate({
                    highlight: function(element) {
                        jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
                    },
                    success: function(element) {
                        jQuery(element).closest('.form-group').removeClass('has-error');
                    }
                });
                */

                $('#barcode').keydown(function(event) {
                    if (event.keyCode == 13) {
                        scanBarcode()
                        return false;
                     }
                });

                $('#submit').on('click', function() {
                    enabled = true;
                    $("#barcode").prop('disabled', !enabled);
                    $('#barcode').focus();
                    $("#finish").show();
                    $("#submit").hide();
                    histories = [];
                    $("#histories_table").empty();
                });

                $('#finish').on('click', function() {
                    enabled = false;
                    $('#barcode').val("");
                    $("#barcode").blur();
                    $("#barcode").prop('disabled', !enabled);
                    $("#finish").hide();
                    $("#submit").show();
                    if (histories.length > 0) {
                        var html = "";
                        for (var i = 0; i < histories.length; i++) {
                            var history = JSON.parse(histories[i]);
                            html += "<tr>";
                            html += "<td>" + ( i + 1 ) + "</td>";
                            html += "<td>" + history.completion_barcode_number + "</td>";
                            html += "<td>" + history.completion_description + "</td>";
                            html += "<td>" + history.lot + "</td>";
                            html += "</tr>";
                        }
                        $("#histories_table").append(html);
                        $("#totalCompletion").text("Total Completion: " + histories.length);
                        openListModal();
                    }
                });

            });

            function openListModal() {
                $('#listModal').modal('show');
            }

            function openErrorModal(title, icon, message) {
                $('#titleModal').text(title);
                $('#iconModal').attr("src", icon);
                $('#messageModal').html(message);
                $('#failedModal').modal('show');
            }

            function scanBarcode() {
                var barcode = $("#barcode").val();
                if (barcode.length > 0) {
                    var data = {
                        barcode_number: barcode
                    };
                    console.log(data);
                    $("#barcode").prop('disabled', true);
                    $.post('{{ url("/completions") }}', data, function(result, status, xhr) {
                        console.log(status);
                        console.log(result);
                        console.log(xhr);
                        $("#barcode").prop('disabled', false);
                        if (xhr.status == 200) {
                            if (result.status) {
                                $('#barcode').val("");
                                $('#barcode').focus();
                                histories.push(result.data)
                                console.log(result.data)
                                console.log(histories)
                                jQuery.gritter.add({
                                    title: 'Completion Berhasil Dilakukan!',
                                    text: "-",
                                    class_name: 'growl-success',
                                    image: '{{ url("images/screen.png") }}',
                                    sticky: false,
                                    time: '1000'
                                 });
                            }
                            else {
                                $('#barcode').val("");
                                $('#barcode').focus();
                                var statusCode = result.status_code;
                                var image = '{{ url("images/screen.png") }}' 
                                switch(statusCode) {
                                    case 1000:
                                        image = '{{ url("images/image-unregistered.png") }}';
                                    break;
                                    case 1001:
                                        image = '{{ url("images/image-lock.png") }}';
                                        break;
                                    case 1002:
                                        image = '{{ url("images/image-clock.png") }}';
                                        break;
                                    case 1003:
                                        image = '{{ url("images/image-stop.png") }}';
                                        break;
                                    default:
                                        image = '{{ url("images/screen.png") }}';
                                }
                                openErrorModal("Completion Gagal!", image, "Barcode number " + barcode + " <br /> " + result.message);
                                /*
                                jQuery.gritter.add({
                                    title: 'Completion Gagal Dilakukan!',
                                    text: result.message,
                                    class_name: 'growl-danger',
                                    image: image,
                                    sticky: false,
                                    time: '3000'
                                 });
                                */
                                var audio = new Audio('{{ url("sound/error.mp3") }}');
                                audio.play();
                            }
                        }
                        else {
                            $('#barcode').val("");
                            $('#barcode').focus();
                            openErrorModal("Completion Gagal!", "{{ url('images/image-stop.png') }}", "Tidak terhubung ke server");
                            /*
                            jQuery.gritter.add({
                                title: 'Completion Gagal Dilakukan!',
                                text: "Tidak terhubung ke server",
                                class_name: 'growl-danger',
                                image: 'images/image-stop.png',
                                sticky: true,
                                time: ''
                             });
                            */
                            var audio = new Audio('{{ url("sound/error.mp3") }}');
                            audio.play();
                        }
                    });
                }
            }

        </script>
    </body>
</html>
