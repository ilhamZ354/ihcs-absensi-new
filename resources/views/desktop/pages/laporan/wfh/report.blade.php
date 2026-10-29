<!DOCTYPE html>
<html>
<head>
	<title>Report</title>
</head>
<style>
	table, th, td {
		border: 1px solid black;
		border-collapse: collapse;
	}
</style>
<body>
	<table>
		<thead>
			<tr>
				<th colspan="30">LAPORAN REKAPITULASI WORKING FROM HOME VS KERJA DALAM {{$filter->jenis_laporan}}</th>
			</tr>
			<tr>
				<th colspan="30">BULAN: {{ strtoupper($filter->bulan) }}</th>
			</tr>
			<tr>
				<th colspan="30">TAHUN: {{ $filter->tahun }}</th>
			</tr>
		</thead>
	</table>
	<table>
		<thead>
			<tr>
				<th rowspan="2">NO.</th>
				<th rowspan="2">NAMA BAGIAN</th>
				@for($i=1; $i <= $max_date; $i++)
				<th colspan="2">{{ $i }}</th>
				@endfor							
			</tr>
			<tr>
				@for($i=1; $i <= (2 * $max_date); $i++)
				@if($i % 2 > 0)
				<th>WFH</th>
				@else
				<th>K</th>
				@endif
				@endfor							
			</tr>
		</thead>
		<tbody>
			@foreach($rows as $row)
			<tr>
				<td>{{ $loop->index + 1 }}</td>
				<td>{{ $row->namabagian }}</td>
				<?php 
				$user = Auth::user()->getUser();
				if(in_array($user->kode_unit, ['00', '62'])) {
					$libur = ['Saturday', 'Sunday'];
				} else {
					$libur = ['Sunday'];
				}

				$kodeCuti=array("TD","CH","CL","CP","CT","H1","H2","H3","M","M1","M1","P1","P2","P3","P4","S","S1","S2");

				$bulan = (toNumberOfMonth($filter->bulan));
				$tahun = $filter->tahun;

				$bulan1 = $bulan == 1 ? 12 : ($bulan -1 );
				$tahun1 = $bulan == 1 ? ($tahun - 1) : $tahun;
				?>

				@for($i=1; $i <= $max_date; $i++)
				@if( in_array(date('l', strtotime($tahun .'-'.$bulan.'-'.$i)), $libur) || is_libur_cuti_bersama($tahun .'-'.$bulan.'-'.$i))

				<td>{{ $row->{$i}[0] }}</td>
				<td>{{ $row->{$i}[1] }}</td>
				@else
				<td>{{ $row->{$i}[0] }}</td>
				<td>{{ $row->{$i}[1] }}</td>
				@endif
				@endfor

			</tr>
			@endforeach
		</tbody>
	</table>

</body>
</html>