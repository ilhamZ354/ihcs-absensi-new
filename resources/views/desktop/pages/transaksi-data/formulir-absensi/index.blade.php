@extends('desktop.layouts.app')
@section('title', 'Formulir Absensi')
@section('content')
@section('css')
<!-- page css -->
<style type="text/css">
#container_unit, #container_bagian, #container_personal {
	display: none;
}
.table th, .table td {
	padding: 5px;
	border: 1px solid #dedede;
}
.table thead th {
	background-color: #dedede;
	border: 1px solid #cecece;
	vertical-align: middle;
}
</style>
@endsection
<div class="block block-themed">
	<div class="block-header bg-pulse-dark">
		<h3 class="block-title">Filter Data</h3>
		<div class="block-options">
			<button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
			<button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
		</div>
	</div>
	<div class="block-content">
		@if ($errors->any())
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
		@endif
		<form action="javascript::void(0);" method="post" name="frm-filter" id="frm-filter" onsubmit="return false;">
			{{ csrf_field() }}
			<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-12">
					<div class="form-group">
						<label for="unit">Nama Unit</label>
						<select class="form-control" id="unit" name="unit" placeholder="Unit" style="width: 100% !important" data-toggle="tooltip" title="Pilih unit">
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
				<div class="col-lg-4 col-md-4 col-sm-12">
					<div class="form-group">
						<label for="bagian">Bagian</label>
						<select class="form-control" id="bagian" name="bagian" placeholder="Bagian" style="width: 100% !important" disabled="" data-toggle="tooltip" title="Pilih bagian kerja">
							<option value="">-Pilih-</option>
							@if(Auth::user()->getUser()->jenis_user == 'USER_BAGIAN' || Auth::user()->getUser()->kode_unit =='62')
							<option value="{{ Auth::user()->getUser()->kode_bagian }}" selected> {{ nama_bagian(Auth::user()->getUser()->kode_bagian) }} </option>
							@else
							@foreach($bagians as $bagian)
							<option value="{{ $bagian->kodebagian }}"> {{ $bagian->namabagian }} </option>
							@endforeach
							@endif
						</select>
					</div>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-12">
					<div class="form-group">
						<label for="golongan">Golongan</label>
						<select class="form-control" id="golongan" name="golongan" style="width: 100% !important" data-toggle="tooltip" title="Pilih golongan karyawan">
							<option value="">-Pilih-</option>
							@foreach ($gols as $key => $value)
							<option value="{{ $key }}"> {{ $value }} </option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12">
					<div class="form-group">
						<label for="nikNama">Nama Karyawan</label>
						<input type="text" class="form-control" id="nikNama" name="nikNama" placeholder="Nama..." data-toggle="tooltip" title="Ketik nama minimal 3 karakter">
					</div>
				</div>
			</div>
		</div>
		<div class="block-content block-content-full block-content-sm bg-body-dark font-size-sm">
			<button type="submit" class="btn btn-alt-green col-lg-4 col-md-4 offset-md-4" onclick="cariKaryawan();" data-toggle="tooltip" title="Filter karyawan berdasarkan form diatas">Filter</button>
		</div>
	</form>
</div>
<div class="block block-themed block-mode-hidden" id="table-block">
	<div class="block-header bg-pulse-dark">
		<h3 class="block-title">Daftar Formulir Absensi</h3>
		<div class="block-options">
			<button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
			<button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
		</div>
	</div>
	<div class="block-content">
		<div class="table-responsive">
			<table class="table table-striped table-vcenter">
				<thead>
					<tr>
						<th rowspan="2" class="text-center" style="width: 30px;"><i class="si si-user"></i></th>
						<th rowspan="2" class="text-center d-none d-md-table-cell">NIK SAP</th>
						<th rowspan="2" class="text-center d-none d-md-table-cell">NIK SIMKAR</th>
						<th rowspan="2" class="text-center">Nama</th>
						<th rowspan="2" class="text-center d-none d-md-table-cell">TGL BEKERJA</th>
						<th rowspan="2" class="text-center d-none d-md-table-cell">UNIT</th>
						<th rowspan="2" class="text-center d-none d-md-table-cell">BAGIAN</th>
						<th rowspan="2" class="text-center d-none d-md-table-cell">JABATAN</th>
						<th rowspan="2" class="text-center d-none d-md-table-cell">STATUS</th>
						<th colspan="2" class="text-center d-none d-md-table-cell">CUTI</th>
						<th rowspan="2" class="text-center" style="width: 100px;">Actions</th>
					</tr>
					<tr>
						<th class="text-center d-none d-md-table-cell">TAHUNAN</th>
						<th class="text-center d-none d-md-table-cell">PANJANG</th>
					</tr>
				</thead>
				<tbody id="t-body">
					<tr><td colspan="12" class="text-center">Data Kosong</td></tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection
@section('js')
<script src="{{asset('desktop/js/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('desktop/js/plugins/jquery-validation/additional-methods.js')}}"></script>
<script src="{{asset('desktop/js/plugins/jquery-dateformat/dateformat.min.js')}}"></script>

<script type="text/javascript">
	@if(in_array(Auth::user()->getUser()->jenis_user, ['USER_UNIT', 'USER_BAGIAN']))
	$('#unit').attr('disabled', true);
	$('#bagian').removeAttr('disabled');
	@endif
	@if(Auth::user()->getUser()->jenis_user == 'USER_BAGIAN')
	$('#bagian').attr('disabled', true);
	@endif
	@if(Auth::user()->getUser()->kode_unit =='62')
	$('#bagian').attr('disabled', true);
	@endif
	$(document).ready(function() {
		$('#unit, #bagian, #golongan').select2({
			placeholder: '-PILIH-'
		});

		$(document).on("select2:opening", function (e) {
			var elem = $(e.target);
			if ($("#select2-id_" + elem.attr("id") + " ul").hasClass("is-invalid")) {
            //jquery checks if the class exists before adding.
            $(".select2-drop ul").addClass("is-invalid");
        } else {
        	$(".select2-drop ul").removeClass("is-invalid");
        }
    });
		Codebase.helper('core-tooltip');
	}); //end of doc ready function

	$('#unit').on('change.select2', function(e)  {
		if(this.value !== undefined && this.value !=='62') {
            //search bagian based selected unit
            var unit = this.value;

            getListBagian(unit, function(res) {
            	$('#bagian').empty();
            	$('#bagian').append('<option value="">-Pilih-</option>');
            	$.each(res, function(i, d) {
            		$('#bagian').append('<option value="' + d.kodebagian + '">' + d.namabagian + '</option>');
            	});
            })
            //enable bagian
            $('#bagian').removeAttr('disabled');
        }
        if (this.value =='62') {
        	$('#bagian').empty();
        	$('#bagian').attr('disabled', true);
        }
    });

	function cariKaryawan() {
		//fungsi pencarian karyawan berdasarkan input form
		//validasi form dulu
		$('#frm-filter').validate({
			ignore: [],
			errorClass: 'invalid-feedback animated fadeInDown',
			errorElement: 'div',
			errorPlacement: function(error, e) {
				if(e.attr('name') == 'unit') {
					jQuery(e).next('.select2').append(error);
				}
				else if(e.attr('name') == 'bagian') {
					jQuery(e).next('.select2').append(error);
				}
				else if(e.attr('name') == 'golongan') {
					jQuery(e).next('.select2').append(error);
				}
				else if(e.attr('name') == 'nikNama') {
					error.insertAfter(e);
				}
				else {
					jQuery(e).parents('.form-group > div').append(error);
				}
			},
			highlight: function(e) {
				jQuery(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
			},
			success: function(e) {
				jQuery(e).closest('.form-group').removeClass('is-invalid');
				jQuery(e).remove();
          	// $('#reg_form').submit();
          },
          rules: {
          	'unit':{
          		required: function (element) {
          			if($('#nikNama').val() == '') {
          				return true;                            
          			}
          			else
          			{
          				return false;
          			}
          		}
          	},
          	'nikNama':{
          		required: function (element) {
          			if($('#unit').val() == '') {
          				return true;                            
          			}
          			else
          			{
          				return false;
          			}
          		},
          		minlength:3,
          		maxlength:50
          	},
          	'bagian': {
          		required: function (element) {
          			if($('#unit').val() == '00') {
          				return true;                            
          			}
          			else
          			{
          				return false;
          			}
          		}
          	},
          	'golongan':{
          		required: function (element) {
          			if($('#nikNama').val() == '') {
          				return true;                            
          			}
          			else
          			{
          				return false;
          			}
          		}
          	}
          },
          messages: {
          	'unit': {
          		required: 'Pilih Unit'
          	}, 
          	'bagian': {
          		required: 'Pilih Bagian'
          	},
          	'golongan': {
          		required: 'Pilih Golongan'
          	},
          	'nikNama': {
          		required: 'Nama harus diisi',
          		minlength: 'Minimal 3 huruf',
          		maxlength: 'Maksimal 50 huruf'
          	}
          }
      });

		//jika form valid
		if ($("#frm-filter").valid())
		{
			//compile all form data
			var $data = $('#frm-filter').serialize();
			$.ajax({
				url: '{{route('transaksi-data.formulir-absensi.table')}}',
				method: 'POST',
				data: $data,
				beforeSend: function() {
					// $("#ajaxPreloader").show();
					// $("#ajaxStatus").show();
					Codebase.layout('header_loader_on');
				},
				// dataType: 'json',
				success: function (data) {
					$('#t-body').empty();
					if (data =='empty') {
						$('#t-body').append('<tr><td colspan="12" class="text-center">Data Kosong</td></tr>');
						Codebase.layout('header_loader_off');
						return;
					}
					$.each(data, function(i, obj){
						$('#t-body').append('\
							<tr>\
							<td class="text-right">'+(i+1)+'.</td>\
							<td class="d-none d-md-table-cell">'+notNull(obj.nik_sap)+'</td>\
							<td class="d-none d-md-table-cell">'+obj.nik+'</td>\
							<td class="font-w600">'+obj.nama+'</td>\
							<td class="d-none d-md-table-cell">'+obj.tanggal_bekerja+'</td>\
							<td class="d-none d-md-table-cell">'+obj.namaunit+'</td>\
							<td class="d-none d-md-table-cell">'+obj.namabagian+'</td>\
							<td class="d-none d-md-table-cell">'+obj.namajabatan+'</td>\
							<td class="d-none d-md-table-cell text-center"><span class="badge badge-info">Aktif</span></td>\
							<td class="d-none d-md-table-cell text-right"><span data-toggle="tooltip" data-placement="top" title="'+formatDate(obj.tgl_mulai_tahunan)+' s/d '+formatDate(obj.tgl_berakhir_tahunan)+'">'+notNull(obj.kuota_cuti_tahunan)+'</span></td>\
							<td class="d-none d-md-table-cell text-right"><span data-toggle="tooltip" data-placement="top" title="'+formatDate(obj.tgl_mulai_panjang)+' s/d '+formatDate(obj.tgl_berakhir_panjang)+'">'+notNull(obj.kuota_cuti_panjang)+'</span></td>\
							<td class="text-center">\
							<div class="btn-group">\
							@allow('create', 'formulir-absensi')\
							<a class="btn btn-sm btn-alt-green" href="javascript:void(0);" onclick="submitForm(\''+obj.nik+'\')" data-toggle="tooltip" data-placement="auto" title="Pengajuan Absen '+obj.nama+'">\
							<i class="fa fa-calendar-plus-o"></i>\
							</a>\
							<form method="POST" action="{{route("transaksi-data.formulir-absensi.create")}}" style="display:none;" id="frm_pengajuan_'+obj.nik+'">\
							{{ csrf_field() }}\
							<input type="hidden" name="nik['+obj.nik+']" id="nik['+obj.nik+']" value="'+obj.nik+'">\
							</form>\
							@else\
							{!! btn_disabled('calendar-plus-o') !!} \
							@endallow\
							</div>\
							</td>\
							</tr>');
						}); //end of eachdata parsing
					Codebase.blocks('#table-block', 'content_show');
					Codebase.helper('core-tooltip');
					Codebase.layout('header_loader_off');
					} //end of success
				}); //end of ajax
			} //end of form valid

		} //end of function cariKaryawan

		function submitForm(nik){
			$('#frm_pengajuan_'+nik).submit();
		}

		function formatDate(value) {
			if (value == undefined || value == '') {
				return '-';
			}
			else {
				var shortDateFormat = 'dd/MM/yyyy';
				var longDate = value + ' 00:00:00.00';
				return (DateFormat.format.date(longDate, shortDateFormat));
			}
			// console.log($.format.date("2019-12-12 10:54:40.22","test: dd/mm/yyyy"));
		}

	</script>
	@endsection