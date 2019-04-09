                @extends('master')

                @section('stylesheets')
                    <link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
                @stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-bars"></i> Completions History </h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li>
                                <a class="btn btn-success" href="{{ url("/completions/history/export?start_date=" . $data['start_date'] . "&start_time=" . $data['start_time'] . "&end_date=" . $data['end_date'] . "&end_time=" . $data['end_time'] . "&barcode_number=" . $data['barcode_number'] . "&material_number=" . $data['material_number'] . "&location_completion=" . $data['location_completion']. "&category=" . $data['category'] . "&export=1")}}">Export</a>
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="contentpanel">
                    <div class="row">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                @if(isset($histories) && count($histories) > 0)
                                <div class="table-responsive">
                                    <div class="table-responsive">
                                        <table class="table table-striped mb30">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Barcode Number</th>
                                                    <th>Location</th>
                                                    <th>Material</th>
                                                    <th>No Kanban</th>
                                                    <th>Description</th>
                                                    <th>Qty</th>
                                                    <th>Category</th>
                                                    <th>User</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php $index = 1; ?>
                                            @foreach ($histories as $history)
                                                <tr>
                                                    <td>{{ $index }}</td>
                                                    <td>{{ $history->completion_barcode_number }}</td>
                                                    <td>{{ $history->location }}</td>
                                                    <td>{{ $history->material_number }}</td>
                                                    <td>
                                                        @if(strlen($history->completion_barcode_number) > 0)
                                                            {{ substr($history->completion_barcode_number, 11, strlen($history->completion_barcode_number) - 1) }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>{{ $history->description }}</td>
                                                    <td>{{ $history->lot }}</td>
                                                    <td>{{ $history->category }}</td>
                                                    <td>
                                                        @if(isset($history->name))
                                                            {{ $history->name }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>{{ $history->created_at }}</td>
                                                </tr>
                                            <?php $index += 1; ?>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div><!-- table-responsive -->
                                @else
                                    <center>Completion data is empty</center>
                                @endif
                            </div><!-- panel-body -->
                        </div><!-- panel -->
                    </div>
                </div>
                @stop

                @section('scripts')
                <script type="text/javascript">
                    jQuery(document).ready(function() {
                        $(".mainpanel").css("height", "");
                    });
                </script>
                @stop