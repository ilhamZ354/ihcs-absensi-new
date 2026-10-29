@extends('desktop.layouts.app')
@section('title', 'Daftar Formulir Absensi')

@section('css')
<!-- page css -->
<!-- <link rel="stylesheet" href="{{ asset('/desktop/js/plugins/datatables/dataTables.bootstrap4.css') }}"> -->

<style type="text/css">
	
	#datatable_ajax td {
		padding-left: 5px;
	}
	tfoot {
		display: table-header-group;
	}

	.form-control-plaintext {
		display: block;
		width: 100%;
		padding-top: 8px;
		padding-bottom: 8px;
		margin-bottom: 0;
		padding: 8px 0px 8px 8px;
		line-height: 16px;
		background-color: transparent;
		border: 1px solid #b7b7b7;
		/* border-width: 1px 0; */
		border-radius: 5px;
	}
	.action-column {
		width: 80px;
	}
</style>
@endsection

@section('content')
<div class="block block-themed">
	<div class="block-header bg-pulse-dark">
		<h3 class="block-title">Filter Data</h3>
		<div class="block-options">
			<button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
			<button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
		</div>
	</div>
	<form action="javascript::void(0);" method="post" name="frm-filter" id="frm-filter" onsubmit="return false;">
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
							@if(Auth::user()->getUser()->jenis_user == 'USER_BAGIAN')
							<option value="{{ Auth::user()->getUser()->kode_bagian }}" selected> {{ nama_bagian(Auth::user()->getUser()->kode_bagian) }} </option>
							@else
							@foreach($bagians as $bagian)
							<option value="{{ $bagian->kodebagian }}"> {{ $bagian->namabagian }} </option>
							@endforeach
							@endif
						</select>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12">
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
				<div class="col-lg-2 col-md-2 col-sm-12">
					<div class="form-group">
						<label for="bulan">Bulan-Tahun</label>
						<input type="text" class="form-control" id="bulantahun" data-toggle="bulantahunpicker" name="bulantahun" placeholder="Bulan"  value="" required="">
					</div>
				</div>
			</div>
		</div>
		<div class="block-content block-content-full block-content-sm bg-body-dark font-size-sm">
			<button type="submit" class="btn btn-alt-green col-lg-4 col-md-4 offset-md-4" onclick="filterData();" data-toggle="tooltip" title="Filter karyawan berdasarkan form diatas">Filter</button>
		</div>
	</form>
</div>
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Daftar Pengajuan Absensi</h3>
        <div class="block-options">
        	@allow('create', 'transaksi-absensi')
            <a href="{{ route('transaksi-data.formulir-absensi.index') }}" class="btn btn-sm btn-square btn-alt-green mr-5 mb-5" title="Sinkronkan data peserta dari Mesin"><i class="fa fa-refresh fa-fw"></i> Tambah Formulir</a>
            @endallow
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle" title="Jadikan layar penuh"></button>
            <!-- <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button> -->
        </div>
    </div>
    <div class="block-content">
        <div class="block-content block-content-full">
	        <div class="table-responsive">
	        	<table class="table table-bordered table-striped table-vcenter" id="datatable_ajax" style="width: 100% !important">
	        		<thead>
	        			<tr>
	        				<th class="text-center" style="width: 30px;">No</th>
	        				<th class="">NIK Simkar</th>
	        				<th class="">Nama Karyawan</th>
	        				<th class="">Unit</th>
	        				<th class="">Bagian</th>
	        				<th class="">Jenis Absen </th>
	        				<th class="">Tgl Mulai </th>
	        				<th class="">Tgl Selesai</th>
	        				<th class="">Jlh HK</th>
	        				<th class="text-center" style="width: 80px;">Aksi</th>
	        			</tr>
	        		</thead>
	        		<tfoot>
	        			<tr>
	        				<th></th>
	        				<th class="">NIK Simkar</th>
	        				<th class="">Nama Karyawan</th>
	        				<th class="">Unit</th>
	        				<th class="">Bagian</th>
	        				<th class="">Jenis Absen </th>
	        				<th class="" data-datepicker="true">Tgl Mulai </th>
	        				<th class="" data-datepicker="true">Tgl Selesai</th>
	        				<th class="">Jlh HK</th>
	        				<th></th>

	        			</tr>
	        		</tfoot>
	        	</table>
	        </div>
	    </div>
    </div>
</div>
<div id="detailContainer"></div>
@endsection

@section('js')
<script src="{{ asset('desktop/js/plugins/datatables/buttons/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('desktop/js/plugins/datatables/buttons/buttons.flash.min.js') }}"></script>
<script src="{{ asset('desktop/js/plugins/datatables/buttons/buttons.html5.min.js') }}"></script>
<script src="{{ asset('desktop/js/plugins/datatables/buttons/buttons.print.min.js') }}"></script>
<script src="{{ asset('desktop/js/plugins/datatables/buttons/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('desktop/js/plugins/datatables/buttons/jszip.min.js') }}"></script>
<script src="{{asset('desktop/js/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('desktop/js/plugins/jquery-validation/additional-methods.js')}}"></script>
<script src="{{asset('desktop/js/plugins/jquery-dateformat/dateformat.min.js')}}"></script>
<script type="text/javascript">
	$('#bulan').on('keypress', function(e) { e.preventDefault(); return false; });
	$('#bulan').on('copy paste cut', function(e) { e.preventDefault(); return false; });

	@if(in_array(Auth::user()->getUser()->jenis_user, ['USER_UNIT', 'USER_BAGIAN']))
	$('#unit').attr('disabled', true);
	$('#bagian').removeAttr('disabled');
	@endif
	@if(Auth::user()->getUser()->jenis_user == 'USER_BAGIAN')
	$('#bagian').attr('disabled', true);
	@endif

	$(document).ready(function() {
		$('#unit, #bagian').select2({
			placeholder: '-PILIH-'
		});
		$('#golongan').select2({
			placeholder: '-SEMUA-'
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
		if(this.value !== undefined) {
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
    });


	// $('#datatable_ajax tfoot th').each( function () {
 //        var title = $(this).text();
 //        if(title !== '') {
 //        	$(this).html('<input type="text" placeholder="Cari " style="padding-left: 5px; width: 100%">');
 //        }
 //    });
 function filterData() {

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
 			else if(e.attr('name') == 'bulantahun') {
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
          		required:true
          	},
          	'bulantahun':{
          		required:true
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
          	}
          },
          messages: {
          	'unit': {
          		required: 'Pilih Unit'
          	},
          	'bulantahun': {
          		required: 'Tentukan Bulan-Tahun'
          	}, 
          	'bagian': {
          		required: 'Pilih Bagian'
          	},
          	
          }
      });

 	if ($("#frm-filter").valid())
 	{
 		if ($.fn.DataTable.isDataTable("#datatable_ajax")) {
 			$('#datatable_ajax').DataTable().clear().destroy();
 		}
 		
 		var table = Datatable.init({
 			buttons: [
 			{
 				extend: 'excelHtml5',
 				text: 'Export Excel',
 				className: 'btn btn-outline-secondary',
 				exportOptions: {
 					columns: [0,1,2,3,4,5,6,7,8]
 				},
 				title: ''
 			}
 			],
 			processing: true,
 			serverSide: false,

 			ajax: {
 				url: '{!! route($route . 'table') !!}',
				data: {
					'unit' : $('#unit').val(),
					'bagian' : $('#bagian').val(),
					'golongan': $('#golongan').val(),
					'bulantahun':$('#bulantahun').val()
				},
 				method: 'POST'
 			},
 			columns: [
		// { data: 'rownum', name: 'rownum', searchable: false, class: 'text-center search-field' },
		// { data: null, searchable: false, class: 'text-center search-field' },
		{ data: '', render: function(data, type, row, meta) {  return meta.row + meta.settings._iDisplayStart + 1; }, searchable: false, orderable: false, class: 'text-center' },
		{ data: 'nik_simkar', class: 'search-field' },
		{ data: 'nama', class: 'search-field' },
		{ data: 'namaunitbagian', class: 'search-field' },
		{ data: 'namabagian', class: 'search-field' },
		{ data: 'nama_jenis_absen', class: 'search-field'},
		{ data: 'tgl_mulai', class: 'formatToDate search-field'},
		{ data: 'tgl_selesai',class: 'formatToDate search-field' },
		{ data: 'jml_hk', class: 'text-center search-field action-column'},
		{ data: 'action', name: 'action', class: 'text-center action-column', sortable: false, searchable: false },
		],
		order: [[6, 'desc']],
	});
 	}
 }

	function showDetail(theValue){
		$('#detailContainer').empty();
		$.ajax({
			type        : 'POST',
			url         : '{{route('transaksi-data.transaksi-absensi.detail')}}',
			data        : {
				'rowNum' : theValue
			},
			dataType    : 'json',
			beforeSend: function() {
				$("#ajaxPreloader").show();
				$("#ajaxStatus").show();
			},
			success(data) {
					$('#detailContainer').append('<div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="modal-detail" aria-hidden="true">\
					<div class="modal-dialog modal-dialog-popout modal-lg" role="document"> \
					<div class="modal-content"> \
					<div class="block block-themed block-transparent mb-0">\
					<div class="block-header bg-primary-dark">\
					<h3 class="block-title">Detail Formulir Absensi</h3>\
					<div class="block-options">\
					<button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">\
					<i class="si si-close"></i>\
					</button>\
					</div>\
					</div>\
					<div class="block-content block-content-full block-content-sm bg-body font-size-sm">\
					<span class="text-muted float-right"><em>Disimpan pada : '+data.tgl_simpan+'</em></span>\
					<h5 style="padding-left: 1rem;padding-top:1rem;margin-bottom: 0px;">Disimpan oleh : '+data.created_by+'</h5>\
					</div>\
					<div class="block-content">\
					<div id="DetailAccordion" role="tablist" aria-multiselectable="true" style="margin-bottom:20px;">\
					<div class="block block-bordered block-rounded mb-2">\
					<div class="block-header" role="tab" id="DetailAccordion_h1">\
					<a class="font-w600" data-toggle="collapse" data-parent="#DetailAccordion" href="#DetailAccordion1" aria-expanded="false" aria-controls="DetailAccordion1">\
					<i class="si si-eye"></i> Detail Informasi</a>\
					</div>\
					<div id="DetailAccordion1" class="collapse show" role="tabpanel" aria-labelledby="DetailAccordion_h1" data-parent="#DetailAccordion">\
					<div class="block-content">\
					<div class="form-group row">\
					<div class="col-md-6">\
					<label class="col-12 item-label">NIK SIMKAR:</label>\
					<div class="col-md-12">\
					<div class="form-control-plaintext item-data">'+data.nik_simkar+'</div>\
					</div>\
					</div>\
					<div class="col-md-6">\
					<label class="col-12 item-label">Nama Karyawan:</label>\
					<div class="col-md-12">\
					<div class="form-control-plaintext item-data">'+data.nama+'</div>\
					</div>\
					</div>\
					</div>\
					<div class="form-group row">\
					<div class="col-md-12">\
					<label class="col-12 item-label">Nama Unit :</label>\
					<div class="col-md-12">\
					<div class="form-control-plaintext item-data">'+data.nama_unit+'</div>\
					</div>\
					</div>\
					</div>\
					<div class="form-group row">\
					<div class="col-md-12">\
					<label class="col-12 item-label">Nama Bagian:</label>\
					<div class="col-md-12">\
					<div class="form-control-plaintext item-data">'+data.nama_bagian+'</div>\
					</div>\
					</div>\
					</div>\
					<div class="form-group row">\
					<div class="col-md-12">\
					<label class="col-12 item-label">Jenis Absen :</label>\
					<div class="col-md-12">\
					<div class="form-control-plaintext item-data">'+data.nama_jenis_absen+'</div>\
					</div>\
					</div>\
					</div>\
					<div id="detailSakit" style="display:none">\
					<div class="form-group row">\
					<div class="col-md-12">\
					<label class="col-12 item-label">Nama Jenis Penyakit:</label>\
					<div class="col-md-12">\
					<div class="form-control-plaintext item-data">'+data.jenis_penyakit+'</div>\
					</div>\
					</div>\
					</div>\
					<div class="form-group row">\
					<div class="col-md-12">\
					<label class="col-12 item-label">Nama Rumah Sakit:</label>\
					<div class="col-md-12">\
					<div class="form-control-plaintext item-data">'+data.nama_rumah_sakit+'</div>\
					</div>\
					</div>\
					</div>\
					</div>\
					<div class="form-group row">\
					<div class="col-md-12">\
					<label class="col-12 item-label">Alasan:</label>\
					<div class="col-md-12">\
					<div class="form-control-plaintext item-data">'+data.alasan+'</div>\
					</div>\
					</div>\
					</div>\
					<div class="form-group row">\
					<div class="col-md-6">\
					<label class="col-12 item-label">Tanggal Mulai :</label>\
					<div class="col-md-12">\
					<div class="form-control-plaintext item-data">'+data.tgl_mulai+'</div>\
					</div>\
					</div>\
					<div class="col-md-6">\
					<label class="col-12 item-label">Tanggal Selesai:</label>\
					<div class="col-md-12">\
					<div class="form-control-plaintext item-data">'+data.tgl_selesai+'</div>\
					</div>\
					</div>\
					</div>\
					<div class="form-group row">\
					<div class="col-md-12">\
					<label class="col-12 item-label">Nama Karyawan Pengganti:</label>\
					<div class="col-md-12">\
					<div class="form-control-plaintext item-data">'+data.nama_pengganti+'</div>\
					</div>\
					</div>\
					</div>\
					</div>\
					</div>\
					</div>\
					</div>\
					</div>\
					</div>');
				if (data.kode_jenis_absen =='S' && data.kode_jenis_absen =='S1') {
					$("#detailSakit").removeAttr("style");
				}
				$('#modal-detail').modal('show');
				$("#ajaxPreloader").hide();
				$("#ajaxStatus").hide();
				$('[data-toggle="popover"]').popover();

        		// $('.bg-image').css('background-image', 'url('+urlku+')');
        	}
        });
	//end Of ShowDetail
}

</script>
@endsection