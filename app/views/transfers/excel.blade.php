<!doctype html>
<html>
	<head>
		
	</head>
		<body>
			@if (isset($transfers) && count($transfers) > 0)
				<table class="table mb30" width="100%" class="bordered">
					<thead>
						<tr>
							<th>Barcode Number</th>
							<th>Material Number</th>
							<th>Description</th>
							<th>Issue Location</th>
							<th>Issue Plant</th>
							<th>Receive Location</th>
							<th>Receive Plant</th>
							<th>Transaction Code</th>
							<th>Movement Type</th>
							<th>Reason Code</th>
							<th>Lot</th>
							<th>Completion Barcode Number</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($transfers as $transfer)
							<tr>
								<td>{{ $transfer->barcode_number_transfer }}</td>
								<td>{{ $transfer->material_number }}</td>
								<td>{{ $transfer->description }}</td>
								<td>{{ $transfer->issue_location }}</td>
								<td>{{ $transfer->issue_plant }}</td>
								<td>{{ $transfer->receive_location }}</td>
								<td>{{ $transfer->receive_plant }}</td>
								<td>{{ $transfer->transaction_code }}</td>
								<td>{{ $transfer->movement_type }}</td>
								<td>{{ $transfer->reason_code }}</td>
								<td>{{ $transfer->lot_transfer }}</td>
								<td>{{ $transfer->barcode_number }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@endif
		<br>
	</body>
</html>