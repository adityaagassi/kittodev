<!doctype html>
<html>
	<head>
		
	</head>
		<body>
			@if (isset($completions) && count($completions) > 0)
				<table class="table mb30" width="100%" class="bordered">
					<thead>
						<tr>
							<th>Barcode Number</th>
							<th>Material Number</th>
							<th>Location</th>
							<th>Plant</th>
							<th>Lot</th>
							<th>Limit</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($completions as $completion)
							<tr>
								<td>{{ $completion->barcode_number }}</td>
								<td>{{ $completion->material_number }}</td>
								<td>{{ $completion->location }}</td>
								<td>{{ $completion->issue_plant }}</td>
								<td>{{ $completion->lot_completion }}</td>
								<td>{{ $completion->limit_used }}</td>
								<td>
									@if($completion->active == 1)
										active
									@else
										non active
									@endif
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@endif
		<br>
	</body>
</html>