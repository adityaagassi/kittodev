<!doctype html>
<html>
	<head>
		
	</head>
		<body>
			@if (isset($histories) && count($histories) > 0)
				<table class="table mb30" width="100%" class="bordered">
					<thead>
						<tr>
							<th>ID</th>
							<th>Category</th>
							<th>Barcode Number</th>
							<th>Document Number</th>
							<th>Material Number</th>
							<th>Issue Location</th>
							<th>Issue Plant</th>
							<th>Receive Location</th>
							<th>Receive Plant</th>
							<th>Cost Center</th>
							<th>GL Account</th>
							<th>Transaction Code</th>
							<th>Movement Type</th>
							<th>Reason Code</th>
							<th>Qty</th>
							<th>User</th>
							<th>File</th>
							<th>Date</th>
							<th>Description</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($histories as $history)
							<tr>
								<td>{{ $history->id }}</td>
								<td>{{ $history->category }}</td>
                                <td>
                                    @if(isset($history->transfer_barcode_number) && strlen($history->transfer_barcode_number) > 0) 
                                        {{ $history->transfer_barcode_number }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if(isset($history->transfer_document_number)) 
                                        {{ $history->transfer_document_number }}
                                    @else
                                        8190
                                    @endif
                                </td>
								<td>{{ $history->material_number }}</td>
								<td>{{ $history->transfer_issue_location }}</td>
								<td>{{ $history->transfer_issue_plant }}</td>
								<td>{{ $history->transfer_receive_location }}</td>
								<td>{{ $history->transfer_receive_plant }}</td>
								<td>{{ $history->transfer_cost_center }}</td>
								<td>{{ $history->transfer_gl_account }}</td>
								<td>{{ $history->transfer_transaction_code }}</td>
								<td>{{ $history->transfer_movement_type }}</td>
								<td>{{ $history->transfer_reason_code }}</td>
								<td>{{ $history->lot }}</td>
								<td>
                                    @if(isset($history->name))
                                        {{ $history->name }}
                                    @else
                                        -
                                    @endif
								</td>
								<td>{{ $history->reference_file }}</td>
								<td>{{ $history->created_at }}</td>
								<td>{{ $history->description }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@endif
		<br>
	</body>
</html>