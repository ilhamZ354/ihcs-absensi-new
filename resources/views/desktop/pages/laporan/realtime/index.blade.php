@extends('desktop.layouts.app')
@section('title', 'Laporan Finger Realtime')

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
		<h3 class="block-title">Laporan Finger Realtime</h3>
		<div class="block-options">            
			<button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle" title="Jadikan layar penuh"></button>
		</div>
	</div>
	<div class="block-content">

		<div class="row">
			<div class="block-content block-content-full">
				<div class="form-group row">
					<label class="offset-md-2 col-md-2 col-form-label" for="nama_karyawan">Nama Karyawan</label>
					<div class="col-lg-6">
						<select class="form-control" id="nama_karyawan" name="nama_karyawan" placeholder="Nama Unit" style="width: 100% !important" data-toggle="tooltip" title="Pilih Unit" onchange="getFingerData()">
							<option value="">-Pilih-</option>
							@foreach($karyawans as $karyawan)
							<option value="{{ $karyawan->nik }}" data-namaunitbagian="{{ $karyawan->nama }}"> {{ $karyawan->nama }} </option>
							@endforeach
						</select>
					</div>
				</div>	
			</div>
		</div>
	</div>
	<div class="block-content">
		<div class="table-responsive">			
			<table class="table table-hover table-bordered table-vcenter" id="tbl-thin">
		        <thead class="table-primary">
		            <tr>
		                <th>NIK SIMKAR</th>
		                <th>Nama</th>
		                <th>Tanggal</th>
		                <th>Absen Masuk</th>
		                <th>Absen Keluar</th>
		            </tr>
		        </thead>
		        <tbody id="dataRow">
		        	
		        </tbody>
		    </table>
		</div>		
	</div>
</div>
@endsection

@section('js')
<script>
	$(document).ready(function() {
		$('#nama_karyawan, #bagian, #golongan').select2({
			placeholder: '-PILIH-'
		});
	});

	function getFingerData()
	{
		$theValue = $('#nama_karyawan').val();
		$('#dataRow').empty();
		$.ajax({
			type        : 'POST',
			url         : '{{route('laporan.realtime.table')}}',
			data        : {
				'nik' : $theValue
			},
			dataType    : 'json',
			beforeSend: function() {
				Codebase.layout('header_loader_on');
				},
			success(data) {
				Codebase.layout('header_loader_off');
				if (data.length > 0) {
				$.each(data, function(key, val) {
					if(moment(val.tgl).format('dddd') !=='Sabtu' && moment(val.tgl).format('dddd') !=='Minggu') {
					$('#dataRow').append('<tr>\
		                <td>'+val.nik_simkar+'</td>\
		                <td>'+val.nama+'</td>\
		                <td>'+moment(val.tgl).format('dddd, DD-MM-YYYY')+'</td>\
		                <td>'+formatCheckTime(val.check_in)+'</td>\
		                <td>'+formatCheckTime(val.check_out)+'</td>\
		            	</tr>');
					}
				});
				}
				else {
					$('#dataRow').append('<tr><td colspan="5">Data Kosong</td></tr>');
				}
			}
		});
	}

	function formatCheckTime(dateValue)
	{
		if (dateValue == null) {
			return 'Kosong';
		}
		else {
			return moment(dateValue).format('HH:mm')+' WIB';
		}
	} 

</script>
@endsection