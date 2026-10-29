<!DOCTYPE html>
<html>
<head>
	<title>Laporan BKM</title>
</head>
<body>
	<table border="1">
		<thead>			
			<tr>
				@foreach($headers as $header)
				<th>{{ $header }}</th>
				@endforeach
			</tr>
		</thead>
		<tbody>
			@foreach($rows as $row)
			<tr>
				@foreach($headers as $header => $_header)
				@if(property_exists($row, strtolower($header)))
					@if(in_array($header, ["Tanggal", "Clock_In_Date", "Clock_Out_Date"]))
					<td>{{ date('d.m.Y', strtotime($row->{strtolower($header)})) }}</td>
					@else
					<td>{{ $row->{strtolower($header)} }}</td>
					@endif
				@else
				<td>&nbsp;</td>
				@endif
				@endforeach
			</tr>
			@endforeach
		</tbody>
	</table>
</body>
</html>