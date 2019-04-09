<!doctype html>
<html>
	<head>
		
	</head>
		<body>
			@if (isset($turnovers) && count($turnovers) > 0)
				<table class="table mb30" width="100%" class="bordered">
					<thead>
						<tr>
							<th>Location</th>
							<th>Material Number</th>
							<th>Description</th>
							<th>Barcode Number</th>
							<th>Completion Qty</th>
							<th>Frequency of Completion</th>
							<th>Transfer Qty</th>
							<th>Frequency of Transfer</th>
							<th>Cycle</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($turnovers as $turnover)
							<tr>
								<td>{{ $turnover->location }}</td>
								<td>{{ $turnover->material_number }}</td>
								<td>
								@if(isset($turnover->description)) 
									{{ $turnover->description }}
								@else
									-
								@endif
								</td>
								<td>
								@if(isset($turnover->barcode_number)) 
									{{ $turnover->barcode_number }}
								@else
									-
								@endif
								</td>
								<td>{{ $turnover->completion_lot }}</td>
								<td>{{ $turnover->completion }}</td>
								<td>{{ $turnover->transfer_lot }}</td>
								<td>{{ $turnover->transfer }}</td>
								<td>{{ $turnover->cycle }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@endif
		<br>
	</body>
</html>