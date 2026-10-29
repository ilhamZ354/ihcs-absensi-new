@extends('desktop.layouts.app')
@section('title', 'Laporan Hitung Insentif')

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
		<h3 class="block-title">Laporan Hitung Insentif</h3>
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
													<input type="text" class="form-control" id="tahun" data-toggle="tahunpicker" name="tahun" placeholder="Tahun" value="{{ property_exists($filter, 'tahun') ? $filter->tahun : '' }}" required="">
												</div>
												<div class="col-lg-4">
													<input type="text" class="form-control" id="bulan" data-toggle="bulanpicker" name="bulan" placeholder="Bulan"  value="{{ property_exists($filter, 'bulan') ? $filter->bulan : '' }}" required="">
												</div>
												<div class="col-lg-3" id="btn-export-all-container"></div>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="offset-lg-1 col-lg-2 col-form-label" for="nama_unit">Nama Unit</label>
										<div class="col-lg-7">
											<select class="form-control" id="nama_unit" name="nama_unit" placeholder="Nama Unit" style="width: 100% !important" data-toggle="tooltip" title="Pilih Unit" required="">
												<option value="">-Pilih-</option>
												@foreach($units as $unit)
												@if(in_array(Auth::user()->getUser()->jenis_user, ['USER_UNIT', 'USER_BAGIAN']))
												<option value="{{ $unit->kodeunitbagian }}" {{ $unit->kodeunitbagian == Auth::user()->getUser()->kode_unit ? 'selected' : '' }}> {{ $unit->namaunitbagian }} </option>
												@else
												<option value="{{ $unit->kodeunitbagian }}"> {{ $unit->namaunitbagian }} </option>
												@endif
												@endforeach
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label class="offset-lg-1 col-lg-2 col-form-label" for="bagian">Bagian</label>
										<div class="col-lg-7">
											<select class="form-control" id="bagian" name="bagian" placeholder="Bagian" style="width: 100% !important" data-toggle="tooltip" title="Pilih Bagian">
												<option value=""></option>
												@foreach($bagians as $bagian)
												@if(in_array(Auth::user()->getUser()->jenis_user, ['USER_UNIT', 'USER_BAGIAN']))
												<option value="{{ $bagian->namabagian }}" {{ property_exists($filter, 'bagian') ? ($filter->bagian == $bagian->namabagian ? 'selected' : '') : $bagian->kodebagian == Auth::user()->getUser()->kode_bagian ? 'selected' : ''}}> {{ $bagian->namabagian }} </option>
												@else
												<option value="{{ $bagian->kodebagian }}"> {{ $bagian->namabagian }} </option>
												@endif
												@endforeach
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
										<label class="offset-lg-1 col-lg-2 col-form-label" for="nama_karyawan">Cari Berdasarkan</label>
										<div class="col-lg-7">
											<div class="row">
												<div class="col-lg-2">
													<select name="searchBy" id="searchBy" class="form-control" style="width: 100% !important">
														<option value="nama" {{ property_exists($filter, 'searchBy') ? ($filter->searchBy == "nama" ? 'selected' : '') : '' }}>NAMA</option>
														<option value="nik" {{ property_exists($filter, 'searchBy') ? ($filter->searchBy == "nik" ? 'selected' : '') : '' }}>NIK</option>
														<option value="nik_sap" {{ property_exists($filter, 'searchBy') ? ($filter->searchBy == "nik_sap" ? 'selected' : '') : '' }}>NIK SAP</option>
													</select>
												</div>
												<div class="col-lg-10">
													<input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan" placeholder="Masukkan Nama karyawan" value="{{ property_exists($filter, 'nama_karyawan') ? $filter->nama_karyawan : '' }}">
												</div>
											</div>
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
		@if(!empty($insentif))
		<div class="row block-export">
			<div class="col-lg-12 text-right">
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
		                <th class="name_space" style="width: 200px;">NAMA</th>
		                <th class="name_space" style="width: 250px;">BAGIAN</th>
		                <th class="name_space" style="width: 250px;">JABATAN</th>
		                <th>MKG</th>
		                <th>NAMA UNIT</th>
		                <th>TD</th>
		                <th>CP</th>
		                <th>TP+CP</th>
		                <th>MENIT</th>
		                <th>HK</th>
		                <th>Level 1</th>
		                <th>Level 2</th>
		                <th>Level 3</th>
		                <th>Level 4</th>
		                <th>Level 5</th>
		                <th>Level Final</th>
		            </tr>
		        </thead>
		        <tbody>
		        	@if(!empty($insentif))
		        	@foreach($insentif as $row)
		            <tr>
		                <td>{{ $row->nik_simkar }}</td>
		                <td>{{ $row->nik_sap }}</td>
		                <td class="text-left">{{ $row->nama }}</td>
		                <td class="text-left">{{ $row->namabagian }}</td>
		                <td class="text-left">{{ $row->namajabatan }}</td>
		                <td>{{ $row->mkg }}</td>
		                <td>{{ $row->namaunitbagian }}</td>
		                <td>{{ $row->td }}</td>
		                <td>{{ $row->cp }}</td>
		                <td>{{ $row->akm_hhmm }}</td>
		                <td>{{ $row->akm_menit }}</td>
		                <td>{{ $row->hk }}</td>
		                <td>{{ $row->level_1 }}</td>
		                <td>{{ $row->level_2 }}</td>
		                <td>{{ $row->level_3 }}</td>
		                <td>{{ $row->level_4 }}</td>
		                <td>{{ $row->level_5 }}</td>
		                <td>{{ $row->level_final }}</td>
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
	$('#tahun, #bulan').on('keypress', function(e) { e.preventDefault(); return false; });
	$('#tahun, #bulan').on('copy paste cut', function(e) { e.preventDefault(); return false; });
	$(document).ready(function() {
		$('#bagian').empty();
		$('#nama_unit, #bagian, #golongan, #searchBy').select2({
			placeholder: '-PILIH-'
		});

		if($('#golongan').val() == '') {
			$('#golongan').val('ALL').trigger('change');
		}
		@if(property_exists($filter, 'nama_unit') && property_exists($filter, 'bagian') && !is_null($filter->bagian))
		setTimeout( function(){ 
			var bgn = $.trim('{{ $filter->bagian }}');
			$('#bagian').val(bgn).trigger('change');
		}, 1000 );
		@endif

		@if(Auth::user()->getUser()->jenis_user == 'USER_BAGIAN')
			refreshListBagian('{{Auth::user()->getUser()->kode_unit}}');
		@endif


		$('#searchBy').on('change', function(e){
			let kode = this.value;
			switch(kode) {
				case 'nama':
					$('#nama_karyawan').prop('placeholder', 'Masukkan Nama Karyawan');
				break;
				case 'nik':
					$('#nama_karyawan').prop('placeholder', 'Masukkan NIK SIMKAR Karyawan');
				break;
				case 'nik_sap':
					$('#nama_karyawan').prop('placeholder', 'Masukkan NIK SAP Karyawan');
				break;
			}
		});

	});

	$('#nama_unit').on('change.select2', function(e)  {
		if(this.value !== undefined) {
			// var data_ = $(this).find(':selected').data('namaunitbagian');
			// $(this).parent().append('<input type="hidden" name="namaunitbagian" value="'+data_+'">');
            //search bagian based selected unit
            var unit = this.value;
            if(unit != '00') {
            	$('#bagian').empty();
            	$('#bagian').attr('disabled', true);
            } else {
	            getListBagian(unit, function(res) {
	            	$('#bagian').empty();

	            	@if(in_array(Auth::user()->getUser()->jenis_user, ['USER_BAGIAN']))
		            	$('#bagian').append('<option value="">-Pilih-</option>');
		            	$.each(res, function(i, d) {
		            		if(d.kodebagian == '{{Auth::user()->getUser()->kode_bagian}}') {
		            			$('#bagian').append('<option data-val="'+d.kodebagian+'" value="' + $.trim(d.kodebagian) + '">' + d.namabagian + '</option>');
		            		}
		            	});
	            	@else
	            		$('#bagian').append('<option value="">-Pilih-</option>');
		            	$.each(res, function(i, d) {	            		
	            			$('#bagian').append('<option data-val="'+d.kodebagian+'" value="' + $.trim(d.kodebagian) + '">' + d.namabagian + '</option>');
		            	});
	            	@endif
	            })
	            //enable bagian
	            $('#bagian').removeAttr('disabled');
	        }
        }
    });

	@if(property_exists($filter, 'nama_unit') && !is_null($filter->nama_unit))
	$('#nama_unit').val('{{ $filter->nama_unit }}').trigger('change.select2');
	@endif

	$('#export').on('click', function(e) {
    	var query = window.location.search;
    	var container = $('.block-export');

    	container.find('a').remove();
    	container.append('<a href="{{ route($route . 'export', $exportparams) }}" class="export-link" style="display: none;">Export</a>');
    	container.find('a')[0].click();
    });


</script>
@endsection