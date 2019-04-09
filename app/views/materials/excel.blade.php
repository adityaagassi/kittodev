<!doctype html>
<html>
	<head>
		
	</head>
		<body>
			@if (isset($materials) && count($materials) > 0)
				<table class="table mb30" width="100%" class="bordered">
					<thead>
						<tr>
							<th>Material Number</th>
							<th>Description</th>
							<th>Location</th>
							<th>Lead Time</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($materials as $material)
							<tr>
								<td>{{ $material->material_number }}</td>
								<td>{{ $material->description }}</td>
								<td>{{ $material->location }}</td>
								<td>{{ $material->lead_time }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@endif
		<br>
	</body>
</html>