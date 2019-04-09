<!doctype html>
<html>
	<head>
		
	</head>
		<body>
			@if (isset($inventories) && count($inventories) > 0)
				<table class="table mb30" width="100%" class="bordered">
					<thead>
						<tr>
							<th>Barcode</th>
							<th>Material Number</th>
							<th>Description</th>
							<th>Location</th>
							<th>No. Kanban</th>
							<th>Qty</th>
							<th>Last Update</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($inventories as $inventory)
							<tr>
								<td>{{ $inventory->barcode_number }}</td>
								<td>{{ $inventory->material_number }}</td>
								<td>
								@if(isset($inventory->description)) 
									{{ $inventory->description }}
								@else
									-
								@endif
								</td>
								<td>
								@if(isset($inventory->issue_location)) 
									{{ $inventory->issue_location }}
								@else
									-
								@endif
								</td>
								<td>{{ substr($inventory->barcode_number, 11, strlen($inventory->barcode_number) - 1) }}</td>
								<td>{{ $inventory->lot }}</td>
								<td>{{ date_format($inventory->updated_at,"d M Y H:i:s") }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@endif
		<br>
	</body>
</html>