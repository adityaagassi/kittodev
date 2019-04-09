                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
			        <link href="{{ url("css/jquery.gritter.css") }}" rel="stylesheet">
					<link href="{{ url("css/bootstrap-timepicker.min.css") }}" rel="stylesheet" />
			        <style type="text/css">
			        	.bootstrap-timepicker-widget table td input {
			        		width: 104px;
			        	}
			        </style>
				@stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-barcode"></i> Add Adjustment Completions </h2>
                </div>
                <div class="contentpanel">
                	@if(isset($errors) && count($errors) > 0)
                	<div class="row">
                		<div class="col-sm-12">
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <strong>
								@if ($errors->has('lot_completion'))
								    Lot Completion is required<br>
								@endif
								</strong>
                            </div>
                		</div>
                	</div>
			    	@endif
                    <div class="row">
						@if (isset($level_id) && ($level_id == 2 || $level_id == 1))
						<ul class="nav nav-tabs nav-dark">
							<li class="active"><a href="#singleBarcode" data-toggle="tab"><strong>Barcode</strong></a></li>
							<li><a href="#multiBarcode" data-toggle="tab"><strong>Multi Barcode</strong></a></li>
							<li><a href="#manual" data-toggle="tab"><strong>Manual</strong></a></li>
						</ul>
						<!-- Tab panes -->
						<div class="tab-content mb30">
							<div class="tab-pane active" id="singleBarcode">
								<div class="panel panel-default">
									<div class="panel-body panel-body-nopadding">
										<div class="form-group">
											<label class="col-sm-3 control-label">Scan Barcode</label>
											<div class="col-sm-6 ">
												<input id="barcode" type="text" placeholder="Barcode Number" class="form-control input-lg mb30" name="barcode_number" disabled />
										        <button id="submit" class="btn btn-lg btn-success btn-block">&#9655;&nbsp;&nbsp;&nbsp;Mulai</button>
										        <button id="finish" class="btn btn-lg btn-danger btn-block">&#9723;&nbsp;&nbsp;&nbsp;Selesai</button>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="multiBarcode">
								<div class="panel panel-default">
									<div class="panel-body panel-body-nopadding">
										<form id="multipleForm" class="form-horizontal form-bordered" method="POST" action="{{ url('/completions/adjustment/excel') }}">
											<div id="excelForm">
												<div class="form-group">
													<label class="col-sm-3 control-label">Posting Date <span class="asterisk">*</span></label>
													<div class="col-sm-6">
														<input type="text" class="form-control datepicker" placeholder="mm/dd/yyyy" name="date" required />
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Posting Time <span class="asterisk">*</span></label>
													<div class="col-sm-6">
										                <div class="bootstrap-timepicker">
										                	<input type="text" placeholder="11:59" class="form-control timepicker" name="time" required />
										                </div>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Barcode Numbers from Excel</label>
													<div class="col-sm-6 ">
														<textarea id="barcodeArea" class="form-control" rows="5" placecholder="Paste barcode number from excel here"></textarea>
														<input type="hidden" name="user_id" value="{{ $user_id }}" />
													</div>
												</div>
											</div>
											<div id="insideForm">

											</div>
											<div class="form-group">
												<div class="col-sm-offset-3 col-sm-3">
													<button class="btn btn-success" id="multiplesubmit" disabled>Submit</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="manual">
								<div class="panel panel-default">
									<div class="panel-body panel-body-nopadding">
										<form id="basicForm" class="form-horizontal form-bordered" method="POST" action="{{ url('/completions/adjustment/manual') }}">
											<div class="form-group">
												<label class="col-sm-3 control-label">Plant <span class="asterisk">*</span></label>
												<div class="col-sm-6 ">
													<input type="text" placeholder="Plant" class="form-control" name="issue_plant" value="8190" maxlength="4" required />
													<input type="hidden" name="user_id" value="{{ $user_id }}" />
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Storage Location <span class="asterisk">*</span></label>
												<div class="col-sm-6 ">
													<input type="text" placeholder="Storage Location" class="form-control" name="location_completion" maxlength="4" required />
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Material Number <span class="asterisk">*</span></label>
												<div class="col-sm-6 ">
													<select class="form-control chosen-select" name="material_id" data-placeholder="Choose a Material Number...">
														<option value=""></option>
														@if(isset($materials) && count($materials) > 0)
														@foreach($materials as $material)
														<option value="{{ $material->id }}">{{ $material->material_number }}</option>
														@endforeach
														@endif
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Quantity <span class="asterisk">*</span></label>
												<div class="col-sm-6 ">
													<div class="input-group">
														<input type="number" placeholder="Quantity" class="form-control" name="lot_completion" value="0" required />
														<span class="input-group-addon">item(s)</span>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Reference Number</label>
												<div class="col-sm-6 ">
													<input type="text" placeholder="Reference Number" class="form-control" name="reference_number" />
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Date <span class="asterisk">*</span></label>
												<div class="col-sm-6">
													<input type="text" class="form-control datepicker" placeholder="mm/dd/yyyy" name="date" required />
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Time <span class="asterisk">*</span></label>
												<div class="col-sm-6">
									                <div class="bootstrap-timepicker">
									                	<input type="text" placeholder="11:59" class="form-control timepicker" name="time" required />
									                </div>
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-offset-3 col-sm-3">
													<button class="btn btn-success">Submit</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						@endif
						@if (isset($level_id) && $level_id == 3)
						<ul class="nav nav-tabs nav-dark">
							<li class="active"><a href="#singleBarcode" data-toggle="tab"><strong>Barcode</strong></a></li>
							<li><a href="#multiBarcode" data-toggle="tab"><strong>Multi Barcode</strong></a></li>
						</ul>
						<div class="tab-content mb30">
							<div class="tab-pane active" id="singleBarcode">
								<div class="panel panel-default">
									<div class="panel-body panel-body-nopadding">
										<div class="form-group">
											<label class="col-sm-3 control-label">Scan Barcode</label>
											<div class="col-sm-6 ">
												<input id="barcode" type="text" placeholder="Barcode Number" class="form-control input-lg mb30" name="barcode_number" disabled />
										        <button id="submit" class="btn btn-lg btn-success btn-block">&#9655;&nbsp;&nbsp;&nbsp;Mulai</button>
										        <button id="finish" class="btn btn-lg btn-danger btn-block">&#9723;&nbsp;&nbsp;&nbsp;Selesai</button>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="multiBarcode">
								<div class="panel panel-default">
									<div class="panel-body panel-body-nopadding">
										<form id="multipleForm" class="form-horizontal form-bordered" method="POST" action="{{ url('/completions/adjustment/excel') }}">
											<div id="excelForm">
												<div class="form-group">
													<label class="col-sm-3 control-label">Posting Date <span class="asterisk">*</span></label>
													<div class="col-sm-6">
														<input type="text" class="form-control datepicker" placeholder="mm/dd/yyyy" name="date" required />
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Posting Time <span class="asterisk">*</span></label>
													<div class="col-sm-6">
										                <div class="bootstrap-timepicker">
										                	<input type="text" placeholder="11:59" class="form-control timepicker" name="time" required />
										                </div>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Barcode Numbers from Excel</label>
													<div class="col-sm-6 ">
														<textarea id="barcodeArea" class="form-control" rows="5" placecholder="Paste barcode number from excel here"></textarea>
													</div>
												</div>
											</div>
											<div id="insideForm">

											</div>
											<div class="form-group">
												<div class="col-sm-offset-3 col-sm-3">
													<button class="btn btn-success" id="multiplesubmit" disabled>Submit</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
      					@endif
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
				<script src="{{ url("js/bootstrap-timepicker.min.js") }}"></script>
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
				        $(".mainpanel").css("height", "");
						jQuery(".chosen-select").chosen({'width':'100%','white-space':'nowrap'});
						jQuery('.datepicker').datepicker();
						jQuery('.timepicker').timepicker({showMeridian: false, minuteStep: 1});
						initKeyDown();

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
						        scanBarcode()
						        return false;
						     }
						});
				    });

				    function initKeyDown() {
						$('#barcodeArea').keydown(function(event) {
						    if (event.keyCode == 13) {
						        generateForm();
								$('#multipleForm').submit();
						        return false;
						     }
						});
					}

				    function generateForm() {
				    	var data = $('#barcodeArea').val();
				    	if (data.length > 0) {
							enabled = true;
							$("#multiplesubmit").prop('disabled', !enabled);
							var rows = data.split('\n');
							if (rows.length > 0) {
								for (var i = 0; i < rows.length; i++) {
									var barcode = rows[i].trim();
									if (barcode.length > 0) {
										addNewBarcode(i, barcode);
									}
								}
							}
							$('#excelForm').hide();
				    	}
				    	else {
				    		enabled = false;
							$("#multiplesubmit").prop('disabled', !enabled);
							$('#excelForm').show();
				    		$('#barcodeArea').val("");
				    		$('#insideForm').empty();
				    	}
				    }


				    function addNewBarcode(index, barcode) {
				    	$(".mainpanel").removeAttr("style");
				    	$(".btn-barcode").remove();
				    	var field = '<div class="form-group">';
				    	field += '<label class="col-sm-3 control-label">Barcode Number</label>';
				    	field += '<div class="col-sm-6">';
				    	field += '<input type="text" placeholder="Barcode Number" class="form-control barcode" id="barcode-' + index + '" name="barcode_number[]" value="' + barcode + '" required />';
				    	field += '</div>';
				    	field += '<div class="col-sm-3 btn-barcode">';
				    	field += '</div>';
				    	field += '</div>';
				    	$("#insideForm").append(field);
				    }

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
					            barcode_number: barcode,
					            user_id: userID
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
					                    var audio = new Audio('{{ url("sound/error.mp3") }}');
					                    audio.play();
					                }
					            }
					            else {
					                $('#barcode').val("");
					                $('#barcode').focus();
					                openErrorModal("Completion Gagal!", "{{ url('images/image-stop.png') }}", "Tidak terhubung ke server");
					                var audio = new Audio('{{ url("sound/error.mp3") }}');
					                audio.play();
					            }
					        });
					    }
					}

				</script>
                @stop