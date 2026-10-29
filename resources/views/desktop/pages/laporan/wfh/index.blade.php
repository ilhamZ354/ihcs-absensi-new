@extends('desktop.layouts.app')
@section('title', 'Laporan Working From Home')

@section('css')
<!-- page css -->
<link href="https://fonts.googleapis.com/css?family=Abel" rel="stylesheet"> 
<style>
/*.table th, .table td {
	padding: 0px;
}*/
/*#tbl-thin {
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
}*/
.name_space tr th, .name_space tr td {
	width: 300px;
	text-align: left!important; 
	padding-left: 3px;
}
#tbl-thin {
	table-layout: fixed; 
	width: 100%;
	*margin-left: -100px;/*ie7*/
	font-family: 'Abel', sans-serif;
}

#tbl-thin td {
	padding: 5px;
}

td, th {
	vertical-align: top;
	border-top: 1px solid #ccc;
	padding:0px;
	width:100px;
	border: none;
}
.date-column-header {
	width: 100px;
	text-align: center;
}
.date-column {
	width: 50px;
	text-align: center;
}
.sub-total {
	width: 50px;
	text-align: center;
}

th {
/*  position:absolute;
*position: relative; /*ie7*/
/*  left:0; */
border: none;
width:100px;
}
.hard_left {
	position:absolute;
	*position: relative; /*ie7*/
	left:0; 
	width:30px;
}
.next_left_name {
	position:absolute;
	*position: relative; /*ie7*/
	left:29px; 
	width:300px;
}

.outer {position:relative}
.inner {
	overflow-x:scroll;
	overflow-y:visible;
	/*width:50%; */
	margin-left:328px;
	cursor: grab;
}
.table-bordered th, .table-bordered td {
	border: none;
	border-top : 1px solid #e4e7ed;
}

</style>
@endsection

@section('content')

<div class="block">
	<div class="block-header block-header-default">
		<h3 class="block-title">Laporan Working From Home</h3>
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
								<form action="{{ route($route . 'index') }}" method="get" id="frm_wfh">
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
												<option value="00">KANTOR DIREKSI</option>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label class="offset-lg-1 col-lg-2 col-form-label" for="bagian">Bagian</label>
										<div class="col-lg-7">
											<select class="form-control" id="bagian" name="bagian" placeholder="Bagian" style="width: 100% !important" data-toggle="tooltip" title="Pilih Bagian">
												<option value="ALL">SEMUA BAGIAN</option>
												@foreach($bagians as $bagian)
												@if(in_array(Auth::user()->getUser()->jenis_user, ['USER_BAGIAN']))
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
										<label class="offset-lg-1 col-lg-2 col-form-label" for="nama_karyawan">Jenis Laporan</label>
										<div class="col-lg-3">
											<select class="form-control" id="jenis_laporan" name="jenis_laporan" style="width: 100% !important" data-toggle="tooltip" title="Pilih Jenis Laporan" required="">
												<option value="">-Pilih-</option>
												<option value="ANGKA" {{ property_exists($filter, 'jenis_laporan') ? ($filter->jenis_laporan == 'ANGKA' ? 'selected' : '') : '' }}>DATA ANGKA</option>
												<option value="PERSEN" {{ property_exists($filter, 'jenis_laporan') ? ($filter->jenis_laporan == 'PERSEN' ? 'selected' : '') : '' }}>DATA PERSENTASE</option>
											</select>
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
		@if(!empty($rows->toArray()))
		<div class="row block-export">
			<div class="col-lg-8" style="padding-left: 2rem;">
				<h6 style="font-size: 1.3rem;margin-bottom: 0px;"> LAPORAN REKAPITULASI WORKING FROM HOME VS KERJA BULAN {{strtoupper($filter->bulan).' '.$filter->tahun}} DALAM <strong> {{$filter->jenis_laporan}} </strong></h6>
			</div>
			<div class="col-lg-4 text-right">
				<button id="export" type="button" @if (count($rows) <= 0) disabled @endif class="btn btn-alt-green btn-sm"><i class="fa fa-file-excel-o"></i> Export ke Excel</button>
				<p></p>
			</div>
		</div>
		<div class="outer">
			<div class="inner">		
				<table class="table table-hover table-bordered table-vcenter" id="tbl-thin">
					<thead class="table-primary" style="border: 1px solid #fff;">
						<tr>
							<th rowspan="2" class="text-center hard_left" style="background-color: #c8e2f8;">#</th>
							<th rowspan="2" class="name_space next_left_name" style="background-color: #c8e2f8;">NAMA BAGIAN</th>
							@for($i=1; $i <= $max_date; $i++)
							<th colspan="2" class="date-column-header">{{ $i }}</th>
							@endfor							
						</tr>
						<tr>
							<th rowspan="2" class="text-center hard_left" style="background-color: #c8e2f8;">&nbsp;</th>
							<th rowspan="2" class="name_space next_left_name" style="background-color: #c8e2f8;">&nbsp;</th>
							@for($i=1; $i <= (2 * $max_date); $i++)
								@if($i % 2 > 0)
								<th class="date-column">WFH</th>
								@else
								<th class="date-column">K</th>
								@endif
							@endfor							
						</tr>
					</thead>
					<tbody>
						@foreach($rows as $row)
						<tr>
							<td class="text-center hard_left" scope="row">{{ $loop->index + 1 }}</td>
							
							<td class="name_space next_left_name">{{ $row->namabagian }}</td>
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
									<td style="background-color: #f06f6f; color: #fff;" class="date-column">{{ $row->{$i}[0] }}</td>
									<td style="background-color: #f06f6f; color: #fff;" class="date-column">{{ $row->{$i}[1] }}</td>
								@else
									<td style="background-color: #756ffb;color: #fff" class="date-column">{{ $row->{$i}[0] }}</td>
									<td style="background-color: #2ac38f;color: #fff" class="date-column">{{ $row->{$i}[1] }}</td>
								@endif
							@endfor
							
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>		
		@elseif(empty($rows) && empty((array) $filter))
		@else 
		<div class="alert alert-danger text-center">Data tidak ditemukan</div>
		@endif
	</div>
</div>
@endsection

@section('js')
<script src="{{asset('desktop/js/plugins/scroll-booster/jquery.dragscroll.js')}}"></script>
<script>
	$('#tahun, #bulan').on('keypress', function(e) { e.preventDefault(); return false; });
	$('#tahun, #bulan').on('copy paste cut', function(e) { e.preventDefault(); return false; });

	@if(in_array(Auth::user()->getUser()->jenis_user, ['USER_UNIT']))
	$('#nama_unit').attr('readonly', true);
	@endif
	/*if(Auth::user()->getUser()->jenis_user == 'USER_BAGIAN')
	$('#bagian').attr('disabled', true);
	endif*/
	$(document).ready(function() {
		$('#bagian').empty();
		$('#nama_unit, #bagian, #golongan').select2({
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

		$('.inner').dragScroll({});

	});

	$('#nama_unit').on('change.select2', function(e)  {
		console.log('unit change');

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
	            		$('#bagian').append('<option value="ALL">-SEMUA BAGIAN-</option>');
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
		container.append('<a href="{{ route($route . 'export') }}'+query+'" class="export-link" style="display: none;">Export</a>');
		container.find('a')[0].click();
	});


    // $('#tahun, #bulan').on('change', function(e) {
    // 	var tahun = $('#tahun').val();
    // 	var bulan = $('#bulan').val();    	
    // 	var btnContainer = $('#btn-export-all-container');    	
    // 	if(tahun != '' && bulan != '') {
    // 		btnContainer.empty();
    // 		btnContainer.hide();
    // 		btnContainer.append('<button type="button" id="btn-export-all" class="btn btn-alt-green"><i class="fa fa fa-file-excel-o"></i> Export All').fadeIn();
    // 	}
    // });



</script>
@endsection