<!doctype html>
<html>
	<head>
		
	</head>
		<body>
			@if (isset($histories) && count($histories) > 0)
				<table class="table mb30" width="100%" class="bordered">
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
                                <td>{{ $index++; }}</td>
                                <td>{{ $history->completion_barcode_number }}</td>
                                <td>{{ $history->completion_location }}</td>
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
						@endforeach
					</tbody>
				</table>
			@endif
		<br>
	</body>
</html>