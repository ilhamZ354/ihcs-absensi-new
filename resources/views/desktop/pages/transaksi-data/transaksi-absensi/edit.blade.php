@extends('desktop.layouts.app')
@section('content')
@section('css')
<!-- page css -->
<link rel="stylesheet" href="{{ asset('desktop/js/plugins/select2/css/select2.css') }}">
<link rel="stylesheet" href="{{asset('desktop/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
<link rel="stylesheet" href="{{ asset('desktop/js/plugins/jquery-auto-complete/jquery.auto-complete.min.css') }}">
<style type="text/css">
	#container_unit,
	#container_bagian,
	#container_personal {
		display: none;
	}

	.table th,
	.table td {
		padding: 5px;
		border: 1px solid #dedede;
	}

	.table thead th {
		background-color: #dedede;
		border: 1px solid #cecece;
		vertical-align: middle;
	}

	.form-control:disabled,
	.form-control[readonly] {
		background-color: #717a84 !important;
		color: #f2f2f2;
	}

	.twitter-typeahead {
		min-width: 100% !important;
	}

	.tt-menu {
		-webkit-box-shadow: 4px 4px 15px 1px rgba(173, 173, 173, 1);
		-moz-box-shadow: 4px 4px 15px 1px rgba(173, 173, 173, 1);
		box-shadow: 4px 4px 15px 1px rgba(173, 173, 173, 1);
		/*padding: 10px;*/
		background: #f0f0f0;
		min-width: 100%;
		cursor: pointer;
		z-index: 999;
		position: absolute !important;
	}

	.tt-suggestion {
		padding: 5px;
		background: #fff;
		margin-bottom: 2px;
	}

	.tt-suggestion strong {
		font-size: 1.1em;
		color: #333;
	}
</style>
@endsection
<div class="block block-themed">
	<div class="block-header bg-pulse-dark">
		<h3 class="block-title">Ubah Pengajuan Absensi</h3>
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
		<div class="col-12">
			<form action="{{ route($route . 'update', $id) }}" method="post" name="frm-pengajuan" id="frm-pengajuan">
				{{ csrf_field() }}
				{{ method_field('PUT') }}
				<h4 class="beautify"><i class="fa fa-user"></i> Informasi Karyawan</h4>
				<div class="form-group row" style="margin-bottom:30px;">
					<div class="col-lg-4 col-md-4 col-sm-12">
						<label for="unit">NIK SIMKAR/NIK SAP</label>
						<input class="form-control font-w600" type="text" name="nik" id="nik" value="{{$formulir->nik.'/'.$formulir->nik_sap}}" readonly="">
					</div>
					<div class="col-lg-5 col-md-4 col-sm-12">
						<label for="unit">Nama</label>
						<input class="form-control font-w600" type="text" name="nama" id="nama" value="{{$formulir->nama}}" readonly="">
					</div>
					<div class="col-lg-3 col-md-4 col-sm-12">
						<label for="unit">Tanggal Bekerja</label>
						<input class="form-control font-w600" type="text" name="tgl_bekerja" id="tgl_bekerja" value="{{indoDateCustom($formulir->tgl_bekerja)}}" readonly="">
					</div>
				</div>
				<h4 class="beautify"><i class="fa fa-calendar"></i> Input Pengajuan</h4>
				<div class="form-group row">
					<div class="col-lg-6 col-md-6 col-sm-12" id="jenis-absen-container">
						<label for="unit">Jenis Absen</label>
						<select class="form-control" id="jenis_absen" name="jenis_absen" style="width: 100% !important" data-toggle="tooltip" title="Pilih jenis absen">
							<option value="">-Pilih-</option>
							@foreach($jenis_absens as $ja)
							<option value="{{$ja->kode}}" {{ $formulir->kode_jenis_absen == $ja->kode ? "selected":"" }}>{{ $ja->nama }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12" id="date-container">
						<label for="tgl_mulai">Tanggal Absen</label>
						<div class="input-daterange input-group" data-date-format="d-m-yyyy" data-week-start="1" data-autoclose="true" data-today-highlight="true">
							<input type="text" class="form-control" id="tgl_mulai" name="tgl_mulai" placeholder="Mulai" data-week-start="1" data-autoclose="true" data-today-highlight="true" onchange="getMyCuti()" value="{{indoDateShort($formulir->tgl_mulai)}}">
							<div class="input-group-prepend input-group-append">
								<span class="input-group-text font-w600">sd</span>
							</div>
							<input type="text" class="form-control" id="tgl_selesai" name="tgl_selesai" placeholder="Selesai" data-week-start="1" data-autoclose="true" data-today-highlight="true" onchange="getMyCuti()" value="{{indoDateShort($formulir->tgl_selesai)}}">
						</div>
					</div>
				</div>
				<div class="form-group row" style="display: none;" id="sakit-container">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<label for="jenis_penyakit">Jenis Penyakit</label>
						<input class="form-control font-w600 jenis_penyakit" type="text" name="jenis_penyakit" id="jenis_penyakit" autofocus="true" data-provide="typeahead" value="{{$formulir->jenis_penyakit}}">
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<label for="nama_rumah_sakit">Nama Rumah Sakit</label>
						<input class="form-control font-w600 nama_rumah_sakit" type="text" name="nama_rumah_sakit" id="nama_rumah_sakit" data-provide="typeahead" value="{{$formulir->nama_rumah_sakit}}">
					</div>
				</div>
				<div class="form-group row" id="cuti_tahunan-container" style="display: none;">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<label for="jlh_cuti_tahunan">Jumlah Cuti Tahunan (hari)</label>
						<input class="form-control font-w600" type="text" name="jlh_cuti_tahunan" id="jlh_cuti_tahunan" readonly="">
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<label for="jlh_cuti_tahunan_tersedia">Jumlah Cuti Tersedia (hari)</label>
						<input class="form-control font-w600" type="text" name="jlh_cuti_tahunan_tersedia" id="jlh_cuti_tahunan_tersedia" readonly="">
					</div>
				</div>
				<div class="form-group row" id="cuti_panjang-container" style="display: none;">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<label for="jlh_cuti_panjang">Jumlah Cuti Panjang (hari)</label>
						<input class="form-control font-w600" type="text" name="jlh_cuti_panjang" id="jlh_cuti_panjang" readonly="">
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<label for="jlh_cuti_panjang_tersedia">Jumlah Cuti Tersedia (hari)</label>
						<input class="form-control font-w600" type="text" name="jlh_cuti_panjang_tersedia" id="jlh_cuti_panjang_tersedia" readonly="">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<label for="unit">Alasan/Keterangan</label>
						<textarea class="form-control" rows="2" placeholder="Alasan/Keterangan" name="alasan" id="alasan" style="resize: none;">{{$formulir->alasan}}</textarea>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<label for="unit">Selama absen, pekerjaan diserahkan kepada</label>
						<input type="hidden" id="nik_pengganti" name="nik_pengganti">
						<input type="text" class="form-control" id="nama_pengganti" name="nama_pengganti" placeholder="Nama Pengganti" value="{{$formulir->nama_pengganti}}">
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12" id="errorContainer"></div>
		</div>
	</div>
	<div class="block-content block-content-full block-content-sm font-size-sm" style="background-color: #dedede !important">
		<div class="col-lg-6 col-md-6 col-sm-12 offset-md-6" style="text-align: right;">
			<button type="button" class="btn col-md-4 col-sm-12 btn-outline-secondary" onclick="resetForm();">Reset</button>&nbsp;&nbsp;
			<button type="submit" class="btn btn-alt-green col-md-6 col-sm-12">Simpan</button>
		</div>
	</div>
	</form>
</div>
@endsection
@section('js')
<script src="{{asset('desktop/js/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('desktop/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('desktop/js/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.id.min.js')}}"></script>
<script src="{{asset('desktop/js/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('desktop/js/plugins/jquery-validation/additional-methods.js')}}"></script>
<script src="{{asset('desktop/js/plugins/bootstrap-typeahead/typeahead.bundle.js')}}"></script>
<script src="{{ asset('desktop/js/plugins/jquery-auto-complete/jquery.auto-complete.min.js') }}"></script>

<script type="text/javascript">
	$(document).ready(function() {
		Codebase.helper('core-tooltip');

		setTimeout(function() {
			$("#jenis_absen").trigger("change");
		}, 500);

		$('#date-container .input-daterange').datepicker({
			format: "d-m-yyyy",
			maxViewMode: 2,
			todayBtn: "linked",
			language: "id",
			orientation: "top auto",
			autoclose: true,
			todayHighlight: true
		});

		$('#jenis_absen').select2({
			placeholder: '-PILIH-'
		});

		$(document).on("select2:opening", function(e) {
			var elem = $(e.target);
			if ($("#select2-id_" + elem.attr("id") + " ul").hasClass("is-invalid")) {
				//jquery checks if the class exists before adding.
				$(".select2-drop ul").addClass("is-invalid");
			} else {
				$(".select2-drop ul").removeClass("is-invalid");
			}
		});

		//validasi form
		$('#frm-pengajuan').validate({
			ignore: ":hidden",
			errorClass: 'invalid-feedback animated fadeInDown',
			errorElement: 'div',
			// showErrors: function (error,e) {
			//      		//$("#errorContainer").html("Please enter all required fields with an asterisk*.");
			//  		},
			errorPlacement: function(error, e) {
				if (e.attr('name') == 'jenis_absen') {
					jQuery(e).next('.select2').append(error);
				} else {
					jQuery(e).parents('.form-group > div').append(error);
				}
			},
			highlight: function(e) {
				jQuery(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
			},
			success: function(e) {
				jQuery(e).closest('.form-group').removeClass('is-invalid');
				jQuery(e).remove();
			},
			rules: {
				'jenis_absen': {
					required: true
				},
				'tgl_mulai': {
					required: true,
					minlength: 8,
					maxlength: 10
				},
				'tgl_selesai': {
					required: true,
					minlength: 8,
					maxlength: 10
				},
				'alasan': {
					required: true,
					minlength: 10,
					maxlength: 500
				},
				'nama_pengganti': {
					required: false,
					maxlength: 100
				},
				'jenis_penyakit': {
					required: true,
					minlength: 5,
					maxlength: 100
				},
				'nama_rumah_sakit': {
					required: true,
					minlength: 5,
					maxlength: 100
				},
				'jlh_cuti_tahunan_tersedia': {
					required: function() {
						if ($('#jenis_absen').val() == 'CT') {
							return true;
						} else {
							return false
						}
					},
					minlength: 1,
					maxlength: 2
				},
				'jlh_cuti_panjang_tersedia': {
					required: function() {
						if ($('#jenis_absen').val() == 'CP') {
							return true;
						} else {
							return false
						}
					},
					minlength: 1,
					maxlength: 2
				},
			},
			messages: {
				'jenis_absen': {
					required: 'Pilih jenis absen'
				},
				'tgl_mulai': {
					required: 'Tentukan tanggal mulai',
					minlength: 'Tanggal mulai salah',
					maxlength: 'Tanggal mulai salah'
				},
				'tgl_selesai': {
					required: 'Tentukan tanggal selesai',
					minlength: 'Tanggal selesai salah',
					maxlength: 'Tanggal selesai salah'
				},
				'jenis_penyakit': {
					required: 'Isi jenis penyakit',
					minlength: 'Minimal 5 karakter',
					maxlength: 'Maksimal 100 karakter'
				},
				'nama_rumah_sakit': {
					required: 'Isi nama Rumah Sakit',
					minlength: 'Minimal 10 karakter',
					maxlength: 'Maksimal 500 karakter'
				},
				'alasan': {
					required: 'Isi alasan/Keterangan absen',
					minlength: 'Minimal 10 karakter',
					maxlength: 'Maksimal 500 karakter'
				},
				'nama_pengganti': {
					maxlength: 'Maksimal 100 karakter'
				},
				'jlh_cuti_tahunan_tersedia': {
					required: 'Jumlah cuti tahunan tidak tersedia',
					minlength: 'Minimal 1 digit',
					maxlength: 'maksimal 2 digit'
				},
				'jlh_cuti_panjang_tersedia': {
					required: 'Jumlah cuti panjang tidak tersedia',
					minlength: 'Minimal 1 digit',
					maxlength: 'maksimal 2 digit'
				}
			}
		});

		var dataRS = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nama'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			prefetch: {
				url: "{{asset('/desktop/media/jsons/rumah_sakit.json')}}"
			}
		});

		var dataPenyakit = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nama'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			prefetch: {
				url: "{{asset('/desktop/media/jsons/penyakit.json')}}"
			}
		});

		(dataRS, dataPenyakit).initialize();
		// dataRS.initialize();
		// dataPenyakit.initialize();

		// Init autocomplete functionality
		jQuery('#nama_pengganti').autoComplete({
			minChars: 3,
			source: function(term, response) {
				var term = term.toLowerCase();
				var unit = $('#unit').val();
				var bagian = $('#bagian').val();

				getListKaryawan(term, unit, bagian, null, function(res) {
					response(res);
				});
			},
			renderItem: function(item, search) {
				search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
				var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
				return '<div class="autocomplete-suggestion" style="padding: 5px;" data-nik="' + item.nik + '" data-nik_sap="' + item.nik_sap + '" data-nama="' + item.nama + '" data-unit="' + item.unit + '" data-kodebagian="' + item.kodebagian + '">[' +
					item.nik + '] - ' + item.nama + '</div>';
			},
			onSelect: function(e, term, item) {
				// alert('Item "'+item.data('langname')+' ('+item.data('lang')+')" selected by '+(e.type == 'keydown' ? 'pressing enter' : 'mouse click')+'.');
				$('#nik_pengganti').val(item.data('nik'));
				$('#nama_pengganti').val(item.data('nama'));
			}
		});

		$('.nama_rumah_sakit').typeahead(null,{
			highlight 	: true,
			hint		: true,
			limit 		: 5,
			display 	: 'nama',
			source: dataRS.ttAdapter(),
			templates: {
				suggestion: Handlebars.compile('<div style="color:#333;"><strong><i class="fa fa-hospital-o"></i>&nbsp;@{{nama}}</strong>')
			}
		});

		$('.jenis_penyakit').typeahead(null,{
			highlight 	: true,
			hint		: true,
			limit 		: 5,
			display 	: 'nama',
			source: dataPenyakit.ttAdapter(),
			templates: {
				suggestion: Handlebars.compile('<div style="color:#333;"><strong><i class="fa fa-heartbeat"></i>&nbsp;@{{nama}}</strong>')
			}
		});

	}); //end of doc ready function

	$('#jenis_absen').change(function() {
		switch (this.value) {
			case 'S':
				$('#cuti_tahunan-container, #cuti_panjang-container').css('display', 'none');
				$('#sakit-container').css('display', 'flex');
				break;
			case 'S1':
				$('#cuti_tahunan-container, #cuti_panjang-container').css('display', 'none');
				$('#sakit-container').css('display', 'flex');
				break;
			case 'CT':
				$('#cuti_panjang-container, #sakit-container').css('display', 'none');
				$('#cuti_tahunan-container').css('display', 'flex');
				getMyCuti();
				break;
			case 'CP':
				$('#cuti_tahunan-container, #sakit-container').css('display', 'none');
				$('#cuti_panjang-container').css('display', 'flex');
				getMyCuti();
				break;
			default:
				$('#cuti_tahunan-container, #cuti_panjang-container, #sakit-container').css('display', 'none');

		}
	});

	function getMyCuti() {
		if ($('#jenis_absen').val() == 'CP' || $('#jenis_absen').val() == 'CT') {
			var jenis_absen = $('#jenis_absen').val();
			var nik = $('#nik').val();
			var tgl_mulai = $('#tgl_mulai').val();
			var tgl_selesai = $('#tgl_selesai').val();

			jsGetCuti(jenis_absen, nik, tgl_mulai, tgl_selesai, function(res) {

				if (res[0] !== null && res[0].kuota !== '' && res[0].kuota !== undefined) {
					$('#jlh_cuti_tahunan').val(res[0].kuota);
					$('#jlh_cuti_tahunan_tersedia').val(hitungTersedia(res[0].kuota, res[0].terpakai));
					$('#jlh_cuti_panjang').val(res[0].kuota);
					$('#jlh_cuti_panjang_tersedia').val(hitungTersedia(res[0].kuota, res[0].terpakai));
				} else {
					$('#jlh_cuti_tahunan').val(null);
					$('#jlh_cuti_tahunan_tersedia').val(null);
					$('#jlh_cuti_panjang').val(null);
					$('#jlh_cuti_panjang_tersedia').val(null);

					if ($('#jlh_cuti_tahunan_tersedia').val() == '' || $('#jlh_cuti_tahunan_tersedia').val() == '0' || $('#jlh_cuti_panjang_tersedia').val() == '0' || $('#jlh_cuti_tahunan_tersedia').val() == '') {
						swal(
							'Cuti Kosong!',
							'Kuota cuti ' + res[1] + ' untuk karyawan ini kosong.',
							'warning'
						);
					}
				}
			});
		}
	}

	function hitungTersedia(kuota, terpakai) {
		var hasil = kuota - terpakai;

		if (hasil >= 1) {
			return hasil;
		} else {
			return 0;
		}
	}

	function focusFunction(id) {
		setTimeout(function() {
			document.getElementById(id).focus();
		}, 200);
	}
</script>
@endsection
