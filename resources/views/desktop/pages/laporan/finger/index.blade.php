@extends('desktop.layouts.app')
@section('title', 'Laporan Finger')

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
		<h3 class="block-title">Laporan Finger</h3>
		<div class="block-options">            
			<button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle" title="Jadikan layar penuh"></button>
		</div>
	</div>
	<div class="block-content">
		<div class="row">
			<div class="block-content block-content-full">
				<div id="accordion" role="tablist" aria-multiselectable="true">
					<div class="block block-bordered block-rounded mb-2">
						<div class="block-header bg-info-light" role="tab" id="accordion_filter">
							<a class="font-w600" data-toggle="collapse" data-parent="#accordion" href="#accordion_q1" aria-expanded="true" aria-controls="accordion_q1" style="width: 100%; height: 100%;"><i class="fa fa-search fa-fw"></i> FILTER DATA PESERTA</a>
						</div>
						<div id="accordion_q1" class="collapse show" role="tabpanel" aria-labelledby="accordion_filter" data-parent="#accordion">
							<div class="block-content">
								<form action="{{ route($route . 'index') }}" method="get">
									<div class="form-group row">
										<label class="offset-lg-1 col-lg-2 col-form-label" for="tahun">Bulan/Tahun</label>
										<div class="col-lg-7">
											<div class="row">
												<div class="col-lg-3">
												<input type="text" class="form-control" id="tahun" data-toggle="tahunpicker" name="tahun" placeholder="Tahun" readonly="" value="{{ property_exists($filter, 'tahun') ? $filter->tahun : '' }}" required="">
												</div>
												<div class="col-lg-4">
												<input type="text" class="form-control" id="bulan" data-toggle="bulanpicker" name="bulan" placeholder="Bulan" readonly=""value="{{ property_exists($filter, 'bulan') ? $filter->bulan : '' }}" required="">
												</div>
												<div class="col-lg-3" id="btn-export-all-container"></div>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="offset-lg-1 col-lg-2 col-form-label" for="nama_unit">Nama Unit</label>
										<div class="col-lg-7">
											<select class="form-control" id="nama_unit" name="nama_unit" placeholder="Nama Unit" style="width: 100% !important" data-toggle="tooltip" title="Pilih Unit">
												<option value="">-Pilih-</option>
												@foreach($units as $unit)
												<option value="{{ $unit->kodeunitbagian }}" data-namaunitbagian="{{ $unit->namaunitbagian }}"> {{ $unit->namaunitbagian }} </option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label class="offset-lg-1 col-lg-2 col-form-label" for="bagian">Bagian</label>
										<div class="col-lg-7">
											<select class="form-control" id="bagian" name="bagian" placeholder="Bagian" style="width: 100% !important" data-toggle="tooltip" title="Pilih Bagian" disabled="">
												<option value="">-Pilih-</option>
											</select>
										</div>
									</div>
									@if(true)						
									<div class="form-group row">
										<label class="offset-lg-1 col-lg-2 col-form-label" for="golongan">Golongan</label>
										<div class="col-lg-3">
											<select class="form-control" id="golongan" name="golongan" style="width: 100% !important" data-toggle="tooltip" title="Pilih golongan karyawan" required="">
												<option value="">-Pilih-</option>
												@foreach ($gols as $key => $value)
												<option value="{{ $key }}" {{ property_exists($filter, 'golongan') ? ($filter->golongan == $key ? 'selected' : '') : '' }}> {{ $value }} </option>
												@endforeach
											</select>
										</div>
									</div>
									@endif
									<div class="form-group row">
										<label class="offset-lg-1 col-lg-2 col-form-label" for="nama_karyawan">Nama Karyawan</label>
										<div class="col-lg-7">
											<input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan" placeholder="Nama karyawan" value="{{ property_exists($filter, 'nama_karyawan') ? $filter->nama_karyawan : '' }}">
										</div>
									</div>
									<div class="form-group row">
										<div class="offset-lg-3 col-lg-8">
											<button type="submit" class="btn btn-alt-primary"><i class="fa fa-search"></i> Filter</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>        	
			</div>
		</div>
	</div>
	<div class="block-content">
		<div class="row block-export">
			<div class="col-lg-12 text-right">
				<button id="export" type="button" @if (count($rows) <= 0) disabled @endif class="btn btn-alt-green btn-sm"><i class="fa fa-file-excel-o"></i> Export ke Excel</button>
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
		                <td style="text-align: left!important;">{{ $row->nama }}</td>
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
	</div>
</div>
@endsection

@section('js')
<script>
	$(document).ready(function() {
		$('#nama_unit, #bagian, #golongan').select2({
			placeholder: '-PILIH-'
		});
		if($('#golongan').val() == '') {
			$('#golongan').val('ALL').trigger('change');
		}		
	});

	$('#nama_unit').on('change.select2', function(e)  {
		if(this.value !== undefined) {
			// var data_ = $(this).find(':selected').data('namaunitbagian');
			// $(this).parent().append('<input type="hidden" name="namaunitbagian" value="'+data_+'">');
            //search bagian based selected unit
            var unit = this.value;

            getListBagian(unit, function(res) {
            	$('#bagian').empty();
            	$('#bagian').append('<option value="">-Pilih-</option>');
            	$.each(res, function(i, d) {
            		@if(Auth::user()->getUser()->jenis_user == "USER_BAGIAN")
            		if(d.kodebagian == '{{ Auth::user()->getUser()->kode_bagian}}') {
            			$('#bagian').append('<option data-val="'+d.kodebagian+'" value="' + d.kodebagian + '">' + d.namabagian + '</option>');            			
            		}
            		@else
            		$('#bagian').append('<option data-val="'+d.kodebagian+'" value="' + d.kodebagian + '">' + d.namabagian + '</option>');
            		@endif
            	});
            })
            //enable bagian
            $('#bagian').removeAttr('disabled');

            @if(property_exists($filter, 'nama_unit') && property_exists($filter, 'bagian') && !is_null($filter->bagian))
            setTimeout( function(){ 
            	var bgn = '{{ $filter->bagian }}';
            	$('#bagian').val(bgn).trigger('change');
			}, 1000 );
            @endif
        }
    });

    @if(property_exists($filter, 'nama_unit') && !is_null($filter->nama_unit))
    	$('#nama_unit').val('{{ $filter->nama_unit }}').trigger('change.select2');
    @endif

    $('#export').on('click', function(e) {
    	var query = window.location.search;
    	var container = $('.block-export');

    	container.find('a').remove();
    	container.append('<a href="{{ route($route . 'export-custom', ['token' => encrypt($filter)]) }}" class="export-link" style="display: none;">Export</a>');
    	container.find('a')[0].click();
    });
</script>
@endsection