                @extends('master')

                @section('stylesheets')
                    <link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
                @stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-bars"></i> Transfers History </h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li>
                                <a class="btn btn-success" href="{{ url("/transfers/history/export?start_date=" . $data['start_date'] . "&start_time=" . $data['start_time'] . "&end_date=" . $data['end_date'] . "&end_time=" . $data['end_time'] . "&barcode_number=" . $data['barcode_number'] . "&material_number=" . $data['material_number'] . "&issue_location=" . $data['issue_location'] . "&issue_plant=" . $data['issue_plant'] . "&receive_location=" . $data['receive_location'] . "&receive_plant=" . $data['receive_plant'] . "&category=" . $data['category'] . "&export=1")}}">Export</a>
                                <!-- <a class="btn btn-success" href="{{ url("/transfers/history/export?start_date=" . $data['start_date'] . "&start_time=" . $data['start_time'] . "&end_date=" . $data['end_date'] . "&end_time=" . $data['end_time'] . "&barcode_number=" . $data['barcode_number'] . "&material_number=" . $data['material_number'] . "&issue_location=" . $data['issue_location'] . "&receive_location=" . $data['receive_location'] . "&export=1")}}">Export</a> -->
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
                                                    <th>Barcode</th>
                                                    <!-- <th>No Document</th> -->
                                                    <th>Material</th>
                                                    <th>Description</th>
                                                    <th>Issue<br />Location</th>
                                                    <!-- <th>Issue<br />Plant</th> -->
                                                    <th>Receive<br />Location</th>
                                                    <!-- <th>Receive<br />Plant</th> -->
                                                    <!-- <th>Transaction<br/ >Code</th> -->
                                                    <th>Movement<br/ >Type</th>
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
                                                    <td>
                                                        @if(isset($history->transfer_barcode_number) && strlen($history->transfer_barcode_number) > 0) 
                                                            {{ $history->transfer_barcode_number }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                   <!--  <td>
                                                        @if(isset($history->transfer_document_number)) 
                                                            {{ $history->transfer_document_number }}
                                                        @else
                                                            8190
                                                        @endif
                                                    </td> -->
                                                    <td>{{ $history->material_number }}</td>
                                                    <td>{{ $history->description }}</td>
                                                    <td>{{ $history->transfer_issue_location }}</td>
                                                    <!-- <td>{{ $history->transfer_issue_plant }}</td> -->
                                                    <td>{{ $history->transfer_receive_location }}</td>
                                                    <!-- <td>{{ $history->transfer_receive_plant }}</td> -->
                                                    <!-- <td>{{ $history->transfer_transaction_code }}</td> -->
                                                    <td>{{ $history->transfer_movement_type }}</td>
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
                                    <center>Transfer data is empty</center>
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