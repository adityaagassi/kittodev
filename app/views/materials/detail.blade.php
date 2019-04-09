                @extends('master')

                @section('stylesheets')
                    <link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
                @stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-bars"></i> Detail Materials </h2>
                </div>
                <div class="contentpanel">
                    @if(isset($material))
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-dark panel-alt">
                                <div class="panel-heading">
                                    <div class="panel-btns">
                                        <a href="{{ url("materials/$material->id/edit") }}"><span class="fa fa-pencil"></span></a>
                                        <a href="javascript:void(0)"  data-toggle="modal" data-target="#myModal" onclick="deleteConfirmation('{{ url("materials/$material->id/delete") }}', '{{ $material->material_number }}');">
                                            <span class="fa fa-times"></span>
                                        </a>
                                    </div>
                                    <h3 class="panel-title">#{{ $material->material_number }}</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="form-horizontal form-bordered">
                                        <div class="form-group">
                                            <label class="col-sm-3">Material Number</label>
                                            <div class="col-sm-6">
                                                {{ $material->material_number }}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3">Description</label>
                                            <div class="col-sm-6">
                                                {{ $material->description }}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3">Lead Time</label>
                                            <div class="col-sm-6">
                                                {{ $material->lead_time }} minutes
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3">Last Update By</label>
                                            <div class="col-sm-6">
                                                {{ $material->user->name }}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3">Updatead At</label>
                                            <div class="col-sm-6">
                                                {{ $material->updated_at }}
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
                        jQuery('.modal-body').text("Are you sure want to delete material with barcode number '" + name + "'?");
                        jQuery('#modalDeleteButton').attr("href", url);
                    }
                </script>
                @stop