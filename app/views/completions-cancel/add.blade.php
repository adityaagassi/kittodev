                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
			        <link href="{{ url("css/jquery.gritter.css") }}" rel="stylesheet">
				@stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-barcode"></i> Cancel Completions </h2>
                </div>
                <div class="contentpanel">
                    <div class="row">
                    	<div class="panel panel-default">
        					<div class="panel-body panel-body-nopadding">
        						<!-- <form id ="basicForm" class="form-horizontal form-bordered" method="post" action="{{ url('/completions/cancel') }}"> -->
        						<div id ="basicForm" class="form-horizontal form-bordered">
									<div class="form-group">
										<label class="col-sm-3 control-label">Barcode Number</label>
										<div class="col-sm-6 ">
											<!-- <input id="barcode" type="text" placeholder="Barcode Number" class="form-control input-lg" name="barcode_number" /> -->
											<input id="barcode" type="text" placeholder="Barcode Number" class="form-control input-lg" name="barcode_number" disabled />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label"></label>
										<div class="col-sm-6 ">
											<!-- <button id="submit" class="btn btn-lg btn-success btn-block">&#9655;&nbsp;&nbsp;&nbsp;Mulai</button> -->
									        <button id="submit" class="btn btn-lg btn-success btn-block">&#9655;&nbsp;&nbsp;&nbsp;Mulai</button>
									        <button id="finish" class="btn btn-lg btn-danger btn-block">&#9723;&nbsp;&nbsp;&nbsp;Selesai</button>
										</div>
									</div>
								</div>
        					</div><!-- panel-body -->
      					</div><!-- panel -->
                    </div>
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
                                        <img id="iconModal" src="{{ url('images/image-unregistered.png') }}" />
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
                                                        <th>Description</th>
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
                @stop

                @section('scripts')
				<script src="{{ url("js/jquery.gritter.min.js") }}"></script>
				<script>

					var index = 0;
					var userID = "{{ $user_id }}";
					var enabled = false;
					var histories = [];

				    jQuery(document).ready(function() {

				    	// Scan barcode

						$("#finish").hide();
						$("#submit").show();
						$("#histories_table").empty();

				        // Chosen
				        // $(".mainpanel").css("height", "");
						jQuery(".chosen-select").chosen({'width':'100%','white-space':'nowrap'});

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

						$('#barcode').keydown(function(event) {
						    if (event.keyCode == 13) {
						        cancelBarcode()
						        return false;
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

					function cancelBarcode() {
					    var barcode = $("#barcode").val();
					    if (barcode.length > 0) {
					        var data = {
					            barcode_number: barcode,
					            user_id: userID
					        };
					        console.log(data);
					        $("#barcode").prop('disabled', true);
					        $.post('{{ url("/completions/cancel") }}', data, function(result, status, xhr) {
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
					                        title: 'Cancel Completion Berhasil Dilakukan!',
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
					                    openErrorModal("Cancel Completion Gagal!", image, "Barcode number " + barcode + " <br /> " + result.message);
					                    var audio = new Audio('{{ url("sound/error.mp3") }}');
					                    audio.play();
					                }
					            }
					            else {
					                $('#barcode').val("");
					                $('#barcode').focus();
					                openErrorModal("Cancel Completion Gagal!", "{{ url('images/image-stop.png') }}", "Tidak terhubung ke server");
					                var audio = new Audio('{{ url("sound/error.mp3") }}');
					                audio.play();
					            }
					        });
					    }
					}

				</script>
                @stop