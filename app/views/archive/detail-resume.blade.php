                @extends('master')

                @section('stylesheets')
                    <link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
                @stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-bars"></i> Detail Archived </h2>
                </div>
                <div class="contentpanel">
                    <div class="row">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table" id="table2">
                                        @if ($type == "completion")
                                        <thead>
                                            <tr>
                                                <th>Material Number</th>
                                                <th>Issue Plant</th>
                                                <th>Location</th>
                                                <th>Qty</th>
                                                <th>Reference Number</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $index = 1; ?>
                                            @foreach ($batchoutputs as $batchoutput)
                                                @if($index % 2 == 1)
                                                <tr class="odd">
                                                @else
                                                <tr class="even">
                                                @endif
                                                    <td>{{ $batchoutput->material_number }}</td>
                                                    <td>{{ $batchoutput->completion_issue_plant }}</td>
                                                    <td>{{ $batchoutput->completion_location }}</td>
                                                    <td>{{ $batchoutput->lot }}</td>
                                                    <td>
                                                        @if (isset($batchoutput->completion_reference_number) && strlen($batchoutput->completion_reference_number))
                                                            {{ $batchoutput->completion_reference_number }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        @else
                                        <thead>
                                            <tr>
                                                <th>Material Number</th>
                                                <th>Issue Plant</th>
                                                <th>Issue Location</th>
                                                <th>Receive Plant</th>
                                                <th>Receive Location</th>
                                                <th>Qty</th>
                                                <th>Cost Center</th>
                                                <th>GL Account</th>
                                                <th>Transaction Code</th>
                                                <th>Movement Type</th>
                                                <th>Reason Code</th>
                                                <th>Reference Number</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $index = 1; ?>
                                            @foreach ($batchoutputs as $batchoutput)
                                                @if($index % 2 == 1)
                                                <tr class="odd">
                                                @else
                                                <tr class="even">
                                                @endif
                                                    <td>{{ $batchoutput->material_number }}</td>
                                                    <td>{{ $batchoutput->transfer_issue_plant }}</td>
                                                    <td>{{ $batchoutput->transfer_issue_location }}</td>
                                                    <td>{{ $batchoutput->transfer_receive_plant }}</td>
                                                    <td>{{ $batchoutput->transfer_receive_location }}</td>
                                                    <td>{{ $batchoutput->lot }}</td>
                                                    <td>
                                                        @if (isset($batchoutput->transfer_cost_center) && strlen($batchoutput->transfer_cost_center))
                                                            {{ $batchoutput->transfer_cost_center }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (isset($batchoutput->transfer_gl_account) && strlen($batchoutput->transfer_gl_account))
                                                            {{ $batchoutput->transfer_gl_account }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>{{ $batchoutput->transfer_transaction_code }}</td>
                                                    <td>{{ $batchoutput->transfer_movement_type }}</td>
                                                    <td>
                                                        @if (isset($batchoutput->transfer_reason_code) && strlen($batchoutput->transfer_reason_code))
                                                            {{ $batchoutput->transfer_reason_code }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (isset($batchoutput->completion_reference_number) && strlen($batchoutput->completion_reference_number))
                                                            {{ $batchoutput->completion_reference_number }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @stop

                @section('scripts')
                <script>
                    jQuery(document).ready(function() {
                        $(".mainpanel").css("height", "");
                    });
                    jQuery('#table2').dataTable({
                        "sPaginationType": "full_numbers"
                    });
                    jQuery("select").chosen({
                        'min-width': '100px',
                        'white-space': 'nowrap',
                        disable_search_threshold: 10
                    });
                    function deleteConfirmation(url, time) {
                        jQuery('.modal-body').text("Are you sure want to delete batch output with time '" + time + "'?");
                        jQuery('#modalDeleteButton').attr("href", url);
                    }
                </script>
                @stop