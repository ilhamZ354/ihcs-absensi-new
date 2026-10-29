@extends('desktop.layouts.app')
@section('title', 'Normalisasi Absensi')
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

.input-daterange > .form-control[readonly] {
	background-color: #fff;
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
		<form action="javascript::void(0);" method="post" name="frm-normalisasi" id="frm-normalisasi" onsubmit="return false;" class="frm-normalisasi">
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
				<div class="col-lg-3 col-md-3 col-sm-12 date-container" id="date-container">
					<label for="tgl_mulai">Tanggal Normalisasi</label>
					<div class="input-daterange input-group" data-date-format="dd-mm-yyyy" data-week-start="1" data-autoclose="true" data-today-highlight="true">
						<input type="text" class="form-control input-textdate" id="tgl_mulai" name="tgl_mulai" placeholder="Mulai" data-week-start="1" data-autoclose="true" data-today-highlight="true" value="{{old('tgl_mulai')}}" readonly required>
						<div class="input-group-prepend input-group-append">
							<span class="input-group-text font-w600">sd</span>
						</div>
						<input type="text" class="form-control input-textdate" id="tgl_selesai" name="tgl_selesai" placeholder="Selesai" data-week-start="1" data-autoclose="true" data-today-highlight="true" value="{{old('tgl_selesai')}}" readonly required>
					</div>
				</div>
			</div>
		</div>
		<div class="block-content block-content-full block-content-sm bg-body-dark font-size-sm">
			<button type="submit" class="btn btn-alt-green col-lg-4 col-md-4 offset-md-4" data-toggle="tooltip" title="Filter karyawan berdasarkan form diatas" onclick="loadData();">Filter</button>
		</div>
	</form>
</div>
<div class="block block-themed block-mode-hidden" id="table-block">
	<div class="block-header bg-pulse-dark">
		<h3 class="block-title">Daftar Karyawan Normalisasi</h3>
		<div class="block-options">
			<button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
			<button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
		</div>
	</div>
	<div class="block-content">
		<div class="text-right">
			<button id="btnSubmit" class="btn btn-outline-primary btn-md mb-10" type="button" style="display: none;">Proses Data</button>
		</div>
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
						<th rowspan="2" class="text-center" style="width: 100px;"><label class="css-control css-control-sm css-control-primary css-switch">
							<input type="checkbox" class="css-control-input" id="checkAll" onchange="selectAll();">
							<span class="css-control-indicator"></span> All
						</label></th>
					</tr>
				</thead>
				<tbody id="t-body">
					<tr><td colspan="12" class="text-center">Data Kosong</td></tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- Top Modal -->
<div class="modal fade" id="confirmation-modal" tabindex="-1" role="dialog" aria-labelledby="modal-top" aria-hidden="true">
	<div class="modal-dialog modal-dialog-top" role="document">
		<div class="modal-content">
			<div class="block block-themed block-transparent mb-0">
				<div class="block-header bg-primary-dark">
					<h3 class="block-title">Konfirmasi Normalisasi Absensi.</h3>
					<div class="block-options">
						<button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
							<i class="si si-close"></i>
						</button>
					</div>
				</div>
				<div class="block-content">
					<form action="be_forms_elements_bootstrap.html" method="post" onsubmit="return false;">
						<div class="form-group row">
							<label class="col-lg-4 col-form-label" for="c_unit_kerja">Unit Kerja</label>
							<div class="col-lg-8">
								<input type="text" class="form-control" id="c_unit_kerja" name="c_unit_kerja" placeholder="Unit Kerja" readonly="">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-4 col-form-label" for="c_bagian">Bagian</label>
							<div class="col-lg-8">
								<input type="text" class="form-control" id="c_bagian" name="c_bagian" placeholder="Bagian" readonly="">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-4 col-form-label" for="c_golongan">Golongan</label>
							<div class="col-lg-8">
								<input type="text" class="form-control" id="c_golongan" name="c_golongan" placeholder="Golongan" readonly="">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-4 col-form-label" for="c_tgl_mulai">Tanggal</label>
							<div class="col-lg-8">
								<div class="input-daterange input-group" data-date-format="d-m-yyyy" data-week-start="1" data-autoclose="true" data-today-highlight="true">
									<input type="text" class="form-control input-textdate" id="c_tgl_mulai" name="c_tgl_mulai" placeholder="Mulai" data-week-start="1" data-autoclose="true" data-today-highlight="true" value="" readonly="" >
									<div class="input-group-prepend input-group-append">
										<span class="input-group-text font-w600">sd</span>
									</div>
									<input type="text" class="form-control input-textdate" id="c_tgl_selesai" name="c_tgl_selesai" placeholder="Selesai" data-week-start="1" data-autoclose="true" data-today-highlight="true" value="" readonly="">
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-4 col-form-label" for="c_jml_peserta">Jumlah Peserta</label>
							<div class="col-lg-8">
								<input type="text" class="form-control" id="c_jml_peserta" name="c_jml_peserta" placeholder="Jumlah Peserta" readonly="">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-4 col-form-label" for="c_alasan">Alasan Normalisasi</label>
							<div class="col-lg-8">
								<textarea class="form-control" id="c_alasan" name="c_alasan" placeholder="Alasan Normalisasi"></textarea>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-alt-success" onclick="makeRequest()">
					<i class="fa fa-check"></i> Ok, proses!
				</button>
				<button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>
<!-- END Top Modal -->

@endsection
@section('js')
<script src="{{asset('desktop/js/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('desktop/js/plugins/jquery-validation/additional-methods.js')}}"></script>
<script src="{{asset('desktop/js/plugins/jquery-dateformat/dateformat.min.js')}}"></script>

<script type="text/javascript">
	var request = {};

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

	jQuery(function(){
		$('#date-container .input-daterange').datepicker({
			format: "dd-mm-yyyy",
			maxViewMode: 2,
			todayBtn: "linked",
			language: "id",
			orientation: "top auto",
			autoclose: true,
			todayHighlight: true
		});

		$('#selectAll').on('change', function(){
			if($(this).is(':checked')) {
				$('.cek-val').bootstrapToggle('on');
			} else {
				$('.cek-val').bootstrapToggle('off');
			}
		});

		$('#unit, #bagian, #golongan').select2({
			placeholder: '-PILIH-'
		});

		$('#unit').on('change.select2', function(e)  {
			if(this.value !== undefined && this.value !=='62') {
            //search bagian based selected unit
            var unit = this.value;
            var namaunit = $('#unit option:selected').html();

            request.unit = {kode: unit, nama: namaunit};

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

        $('#bagian').on('change.select2', function(e) {
        	request.bagian = {kode: this.value, nama: $('#bagian option:selected').html()};
        });

        $('#golongan').on('change.select2', function(e) {
        	request.golongan = {kode: this.value, nama: $('#golongan option:selected').html()};
        });

        $('#tgl_mulai, #tgl_selesai').on('change', function(e) {
        	request.tgl = {
        		mulai: $('#tgl_mulai').val(), 
        		selesai: $('#tgl_selesai').val()
        	};
        });

        $('#c_alasan').on('change', function(e) {
        	request.alasan = $(this).val();
        });

    });

		addCheckBoxEventListener();
		

		Codebase.helper('core-tooltip');
	});

	function loadData (){
		var formNormalisasi = jQuery('.frm-normalisasi');

		var validatorAddData = formNormalisasi.validate({
			ignore: [],
			errorClass: 'invalid-feedback animated fadeInDown',
			errorElement: 'div',
			errorPlacement: function(error, e) {
				if (e.hasClass("input-textdate")) {
					jQuery(e).closest('.date-container').removeClass('is-invalid').addClass('is-invalid');
				}
				else {
					jQuery(e).parents('.form-group').removeClass('is-invalid').addClass('is-invalid');//.append(error);
				}
			},
			highlight: function (e, errorClass, validClass) {
                // var elem = $(e);
            //     if (elem.hasClass("select2-offscreen")) {
            //         $("#s2id_" + elem.attr("id") + " a").addClass(errorClass);
            //     } else {
            //         // elem.addClass(errorClass);
            jQuery(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
            //     }
        },
        unhighlight: function (e, errorClass, validClass) {
            //     var elem = $(element);
            //     if (elem.hasClass("select2-offscreen")) {
            //         $("#s2id_" + elem.attr("id") + " a").removeClass(errorClass);
            //     } else {
            //         elem.removeClass(errorClass);
            //     }
            jQuery(e).closest('.form-group').removeClass('is-invalid');
        },
            // highlight: function(e) {
            // },
            success: function(e) {
            	jQuery(e).closest('.form-group').removeClass('is-invalid');
            	jQuery(e).remove();
            },
            rules: {

            	'unit': {
            		required: true                },
            		'tgl_mulai': {
            			required: true
            		},
            		'tgl_selesai': {
            			required: true
            		},
            		'bagian': {
            			required: true
            		},
            		'golongan': {
            			required: true
            		}
            	},
            	messages: {
            		'nomor': {
            			required: 'Pilih Unit!'
            		},
            		'tgl_mulai': {
            			required: 'Tentukan tanggal Normalisasi'
            		},
            		'tgl_selesai': {
            			required: 'Tentukan Tanggal Normalisasi'
            		},
            		'bagian': {
            			required: 'Pilih Bagian!'
            		},
            		'golongan': {
            			required: 'Pilih Golongan'
            		}
            	}
            });

		if ($("#frm-normalisasi").valid())
		{
			var $data = $('#frm-normalisasi').serialize();
			$.ajax({
				url: '{{route('admin.normalisasi.absensi.peserta')}}',
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
							<td class="text-center">\
							<label class="css-control css-control-sm css-control-primary css-switch">\
							<input type="checkbox" class="css-control-input" name="pesertas[]" value="'+obj.nik+'">\
							<span class="css-control-indicator"></span>\
							</label>\
							</td>\
							</tr>');
						}); //end of eachdata parsing

					addCheckBoxEventListener();

					Codebase.blocks('#table-block', 'content_show');
					Codebase.helper('core-tooltip');
					Codebase.layout('header_loader_off');
					} //end of success
				}); //end of ajax
		}
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

		function selectAll()
		{
			var theValue = $('#checkAll').is(':checked'); 
			$($('table').find('input[type="checkbox"]')).prop('checked', theValue);

			addCheckBoxEventListener();
		}

		function isCheckboxChecked() {
			var result = false;

			var chkVals= [];
			$.each($('table').find('input[type="checkbox"]'), (i, ele) => {
				chkVals.push($(ele).is(':checked'));
			});

			if($.inArray(true, chkVals) !== -1) {
				result = true;
			}

			return result;
		}

		function addCheckBoxEventListener() {
			var bools = [];
			$('input[type="checkbox"]').on('change', (e) => {
				if(isCheckboxChecked()) {
				//Jika ada yang bernilai true, tampilkan tombol submit
				$('#btnSubmit').fadeIn('slow');
				$('#btnSubmit').removeProp('disabled');
			} else {
				$('#btnSubmit').fadeOut('slow');				
				$('#btnSubmit').prop('disabled', true);
			}
		});
		}

		function getJumlahPeserta() {
			request.pesertas = [];
			var niks = [];
			var countt = 0;
			$.each($('table > tbody').find('input[type="checkbox"]'), function(index, value) {
				if($(value).is(':checked')) {
					niks.push($(value).val());
					countt ++;
				}
			});
			
			request.pesertas = niks;
			return countt;
		}

		$('#btnSubmit').on('click', (e) => {

			$('#c_unit_kerja').val(request.unit.nama);
			$('#c_bagian').val(request.bagian.nama);
			$('#c_golongan').val(request.golongan.nama);
			$('#c_tgl_mulai').val(request.tgl.mulai);
			$('#c_tgl_selesai').val(request.tgl.selesai);
			$('#c_jml_peserta').val(getJumlahPeserta() + ' Orang');
			jQuery('#confirmation-modal').modal('show');
		});

		function makeRequest() {
			var data = {
				kodeunitbagian: request.unit.kode,
				kodebagian: request.bagian.kode,
				golongan: request.golongan.kode,
				tgl_mulai: request.tgl.mulai,
				tgl_selesai: request.tgl.selesai,
				alasan: request.alasan,
				pesertas: request.pesertas,
			};
			$.ajax({
				url: '{{ route('admin.normalisasi.absensi.process') }}',
				method: 'POST',
				data: data,
				success: function(res) {
					var data = JSON.parse(res);
					swal((data.status === 1 ? 'Sukses' : 'Gagal'), data.message, (data.status === 1 ? 'success' : 'error'));

					if(data.status === 1) {
						setTimeout(() => { location.reload(); }, 500);
					}
				},
				error: function(err) {
					$.each(err.responseJSON.errors, (key, val) => {
						if(key === 'alasan') {
							var parent = $('#c_alasan').parent(); 
							parent.find('textarea').addClass('is-invalid');
							parent.find('.invalid-feedback').remove();
							parent.append('<div class="invalid-feedback">'+val+'</div>');
						} else {
							$.notify({ message: val}, { type: 'danger', placement: { align: 'center' }});
						}
					});
				},
			});
		}
	</script>
	@endsection