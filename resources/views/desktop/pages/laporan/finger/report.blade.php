@extends('desktop.layouts.app')
@section('title', 'Laporan Absensi Finger')

@section('css')
<!-- page css -->
<link href="https://fonts.googleapis.com/css?family=Abel" rel="stylesheet"> 
<style>
	.table th, .table td {
		padding: 0px;
	}
	#tbl-thin {
		font-family: 'Abel', sans-serif;
	}
	#tbl-thin tr th{
		vertical-align: middle;
		text-align: center;
	}
	#tbl-thin tr td{
		vertical-align: middle;
		padding: 2px 5px;
		text-align: center;
	}
	.name_space {
		width: 200px;
	}
</style>
@endsection

@section('content')

<div class="block">
	<div class="block-header block-header-default">
		<h3 class="block-title">Laporan Abensi Finger</h3>
		<div class="block-options">            
			<button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle" title="Jadikan layar penuh"></button>
		</div>
	</div>
	<div class="block-content">
		@if(!is_null($rows))
		<div class="row block-export">
			<div class="col-lg-6 text-left">
				<a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
				<p></p>
			</div>
			<div class="col-lg-6 text-right">
				<button id="export" type="button" class="btn btn-alt-green btn-sm"><i class="fa fa-file-excel-o"></i> Export ke Excel</button>
				<p></p>
			</div>
		</div>
		<div class="table-responsive">			
		    <table class="table table-hover table-bordered table-vcenter" id="tbl-thin">
		        <thead class="table-primary">
		            <tr>
		                <th>NIK SIMKAR</th>
		                <th>NIK SAP</th>
		                <th class="name_space" style="width: 200px;">Nama</th>
		                <th>Nama Bagian</th>
		                <th>Nama Unit</th>
		                <th>Date</th>
		                <th>Status</th>
		                <th>On Duty</th>
		                <th>Off Duty</th>
		                <th>Clock In</th>
		                <th>Clock Out</th>
		                <th>TD</th>
		                <th>CP</th>
		            </tr>
		        </thead>
		        <tbody>
		        	@if(!is_null($rows))	
		        	@foreach($rows as $row)
		            <tr>
		                <td>{{ $row->nik_simkar }}</td>
		                <td>{{ $row->nik_sap }}</td>
		                <td>{{ $row->nama }}</td>
		                <td>{{ $row->namabagian }}</td>
		                <td>{{ $row->namaunitbagian }}</td>
		                <td>{{ $row->tgl }}</td>
		                <td>{{ $row->status }}</td>
		                <td>{{ is_null($row->on_duty) ? '' : date('H:i', strtotime($row->on_duty)) }}</td>
		                <td>{{ is_null($row->off_duty) ? '' : date('H:i', strtotime($row->off_duty)) }}</td>
		                <td>{{ is_null($row->checkin) ? '' : date('H:i', strtotime($row->checkin)) }}</td>
		                <td>{{ is_null($row->checkout) ? '' : date('H:i', strtotime($row->checkout)) }}</td>
		                <td>{{ is_null($row->late) ? '' : $row->late }}</td>
		                <td>{{ is_null($row->early) ? '' : $row->early }}</td>
		            </tr>
		            @endforeach
		            @else
		            <tr>
		            	<td class="text-center">Tidak ada data</td>
		            </tr>
		            @endif
		        </tbody>
		    </table>
		</div>
		@endif
	</div>
</div>
@endsection

@section('js')
<script>
	$('#export').on('click', function(e) {
    	var query = window.location.search;
    	var container = $('.block-export');

    	container.find('a').remove();
    	container.append('<a href="{{ route($route . 'export', $exportparams) }}" class="export-link" style="display: none;">Export</a>');
    	container.find('a')[0].click();
    });


</script>
@endsection