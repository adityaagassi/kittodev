                @extends('master')

                @section('stylesheets')
                    <link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
                @stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-bars"></i> Completions Temporary </h2>
                </div>
                <div class="contentpanel">
                    <div class="row">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                @if(isset($temporaries) && count($temporaries) > 0)
                                <div class="table-responsive">
                                    <div class="table-responsive">
                                        <table class="table table-striped mb30">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Location</th>
                                                    <th>Material</th>
                                                    <th>Description</th>
                                                    <th>Plant</th>
                                                    <th>Qty</th>
                                                    <th>Category</th>
                                                    <th>Date</th>
                                                    @if(Session::has('level_id'))
                                                        @if(Session::get('level_id') == 1 || Session::get('level_id') == 2)
                                                            <th>Configuration</th>
                                                        @endif
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php $index = 1; ?>
                                            @foreach ($temporaries as $temporary)
                                                <tr>
                                                    <td>{{ $index }}</td>
                                                    <td>{{ $temporary->completion_location }}</td>
                                                    <td>{{ $temporary->material_number }}</td>
                                                    <td>{{ $temporary->completion_description }}</td>
                                                    <td>{{ $temporary->completion_issue_plant }}</td>
                                                    <td>{{ $temporary->lot }}</td>
                                                    <td>{{ $temporary->category }}</td>
                                                    <td>{{ $temporary->created_at }}</td>
                                                    @if(Session::has('level_id'))
                                                        @if(Session::get('level_id') == 1 || Session::get('level_id') == 2)
                                                            <td class="center">
                                                                <center>
                                                                    <a href="javascript:void(0)"  data-toggle="modal" data-target="#myModal" onclick="deleteConfirmation('{{ url("completions/temporary/$temporary->completion_material_id/delete") }}', '{{ $temporary->material_number }}');">
                                                                        <span class="fa fa-times"></span>
                                                                    </a>
                                                                </center>
                                                            </td>
                                                        @endif
                                                    @endif
                                                </tr>
                                            <?php $index += 1; ?>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div><!-- table-responsive -->
                                @else
                                    <center>Completion temporary data is empty</center>
                                @endif
                            </div><!-- panel-body -->
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
                @stop

                @section('scripts')
                <script type="text/javascript">
                    jQuery(document).ready(function() {
                        $(".mainpanel").css("height", "");
                    });
                    function deleteConfirmation(url, name) {
                        jQuery('.modal-body').text("Are you sure want to delete completion temporary with barcode number '" + name + "'?");
                        jQuery('#modalDeleteButton').attr("href", url);
                    }
                </script>
                @stop