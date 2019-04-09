                @extends('master')

                @section('stylesheets')
                    <link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
                @stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-bars"></i> Detail Transfers </h2>
                </div>
                <div class="contentpanel">
                    @if(isset($transfer))
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-dark panel-alt">
                                <div class="panel-heading">
                                    <div class="panel-btns">
                                        <a href="{{ url("transfers/$transfer->id/edit") }}"><span class="fa fa-pencil"></span></a>
                                        <a href="javascript:void(0)"  data-toggle="modal" data-target="#myModal" onclick="deleteConfirmation('{{ url("transfers/$transfer->id/delete") }}', '{{ $transfer->barcode_number }}');">
                                            <span class="fa fa-times"></span>
                                        </a>
                                    </div>
                                    <h3 class="panel-title">#{{ $transfer->barcode_number_transfer }}</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="form-horizontal form-bordered">
                                        <div class="form-group">
                                            <label class="col-sm-3">Barcode Number</label>
                                            <div class="col-sm-6">
                                                #{{ $transfer->barcode_number_transfer }}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3">Material Number</label>
                                            <div class="col-sm-6">
                                                {{ $transfer->completion->material->material_number }}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3">Issue Location</label>
                                            <div class="col-sm-6">
                                                {{ $transfer->issue_location }}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3">Issue Plant</label>
                                            <div class="col-sm-6">
                                                {{ $transfer->issue_plant }}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3">Receive Location</label>
                                            <div class="col-sm-6">
                                                {{ $transfer->receive_location }}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3">Receive Plant</label>
                                            <div class="col-sm-6">
                                                {{ $transfer->receive_plant }}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3">Transaction Code</label>
                                            <div class="col-sm-6">
                                                {{ $transfer->transaction_code }}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3">Movement Type</label>
                                            <div class="col-sm-6">
                                                {{ $transfer->movement_type }}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3">Lot Transfer</label>
                                            <div class="col-sm-6">
                                                {{ number_format($transfer->lot_transfer, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3">Reason Code</label>
                                            <div class="col-sm-6">
                                                @if(strlen($transfer->reason_code) > 0)
                                                    {{ $transfer->reason_code }}
                                                @else
                                                    -
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3">Lot Transfer</label>
                                            <div class="col-sm-6">
                                                {{ $transfer->lot_transfer }} item(s)
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3">Completion Barcode Number</label>
                                            <div class="col-sm-6">
                                                {{ $transfer->completion->barcode_number }}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3">Last Update By</label>
                                            <div class="col-sm-6">
                                                {{ $transfer->user->name }}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3">Updatead At</label>
                                            <div class="col-sm-6">
                                                {{ $transfer->updated_at }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- panel -->
                        </div>
                    </div>
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
                                </div>
                                <div class="modal-body">
                                    Are you sure delete?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                    <a id="modalDeleteButton" href="#" type="button" class="btn btn-danger">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @stop

                @section('scripts')
                <script>
                    jQuery(document).ready(function() {
                        // Spinner
                        var quantity = jQuery('#quantity').spinner();
                        quantity.spinner('value', 0);
                        // Chosen
                        jQuery(".chosen-select").chosen({'width':'100%','white-space':'nowrap'});
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
                    function deleteConfirmation(url, name) {
                        jQuery('.modal-body').text("Are you sure want to delete transfer with barcode number '" + name + "'?");
                        jQuery('#modalDeleteButton').attr("href", url);
                    }
                </script>
                @stop