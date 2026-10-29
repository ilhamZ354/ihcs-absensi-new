@extends('desktop.layouts.app')
@section('title', 'Normalisasi Absensi')

@section('css')
<!-- page css -->
<style type="text/css">

</style>
@endsection

@section('content')

<div class="block">
	<div class="block-header block-header-default">
		<h3 class="block-title"><i class="fa fa-refresh fa-fw"></i> Normalisasi Absensi</h3>
		<div class="block-options">            
			<button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle" title="Jadikan layar penuh"></button>
		</div>
	</div>
		<div class="block-content">
			<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-12">
					<div class="form-group">
						<label for="unit">Nama Unit</label>
						<input class="form-control" id="unit" placeholder="Unit" disabled="" value="{{ nama_unit($request['unit']) }}">
					</div>

				</div>
				<div class="col-lg-4 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="bagian">Bagian</label>
						<input class="form-control" id="bagian" placeholder="Bagian" disabled="" value="{{ nama_bagian($request['bagian']) }}">
					</div>
				</div>
				<div class="col-lg-2 col-md-3 col-sm-12">
					<div class="form-group">
						<label for="golongan">Golongan</label>
						<input class="form-control" id="golongan" placeholder="Golongan" disabled="" value="{{ nama_golongan($request['golongan']) }}">
					</div>
				</div>
				<div class="col-lg-3 col-md-12 col-sm-12 date-container">
					<label for="tgl_mulai">Tanggal Absen</label>
					<div class="input-daterange input-group" data-date-format="d-m-yyyy" data-week-start="1" data-autoclose="true" data-today-highlight="true">
						<input type="text" class="form-control" id="tgl_mulai" name="tgl_mulai" placeholder="Mulai" data-week-start="1" data-autoclose="true" data-today-highlight="true" value="{{ $request['tgl_mulai'] }}" disabled="">
						<div class="input-group-prepend input-group-append">
							<span class="input-group-text font-w600">sd</span>
						</div>
						<input type="text" class="form-control" id="tgl_selesai" name="tgl_selesai" placeholder="Selesai" data-week-start="1" data-autoclose="true" data-today-highlight="true" value="{{ $request['tgl_selesai'] }}" disabled="">
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="form-group">
						<label for="alasan_normalisasi">Alasan Normalisasi</label>
						<textarea type="text" class="form-control" id="alasan_normalisasi" name="alasan_normalisasi" placeholder="Alasan normalisasi" disabled="">{{ $request['alasan_normalisasi'] }}</textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="block-content block-content-full block-content-sm bg-body-dark font-size-sm">
			<button type="button" class="btn btn-alt-green col-lg-4 col-md-4 offset-md-4 btn-trigger" data-toggle="tooltip" title="Lakukan Normalisasi" disabled="">Proses</button>
		</div>
</div>

<div class="block">
	<div class="block-header block-header-default">
		<h3 class="block-title"><i class="fa fa-users fa-fw"></i> Pilih peserta yang hendak dinormalisasi</h3>
		<div class="block-options">            
			<button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle" title="Jadikan layar penuh"></button>
		</div>
	</div>
	<form action="{{ route($route . 'create') }}" method="post" name="frm-create" id="frm-create">
	{{ csrf_field() }}
	<input type="hidden" name="_request" value="{{encrypt($request)}}">
	<div class="block-content">
		<div class="table-responsive">
			<table class="table table-bordered table-hover" style="max-width: 100%!important;">
				<thead>
					<tr>
						<th>No.</th>
						<th>NIK</th>
						<th>NIK SAP</th>
						<th>Nama Karyawan</th>
						<th>Unit Kerja</th>
						<th>Bagian</th>
						<th><label><input type="checkbox" id="chkAll"> Check All</label></th>
					</tr>
				</thead>
				<tbody>
					@foreach($users as $user)
					<tr>
						<td>{{ $loop->index + 1 }}.</td>
						<td>{{ $user->nik }}</td>
						<td>{{ $user->nik_sap }}</td>
						<td>{{ $user->nama }}</td>
						<td>{{ $user->namaunitbagian }}</td>
						<td>{{ $user->namabagian }}</td>
						<td><input type="checkbox" class="form-control" name="peserta[]" value="{{ $user->nik }}"></td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<button type="submit" id="btnSubmit" style="display: none;"></button>
</form>
</div>

@endsection

@section('js')
<script src="{{asset('desktop/js/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script type="text/javascript">
	var unit = $('#unit');
	var chkAll = $('#chkAll');

	$(() => {
		$(unit).on('change', function (e) {
			var that = $(this);

			getListBagian(that.val(), function(res) {
				$('#bagian').empty();
				$('#bagian').append('<option value="">-Pilih-</option>');
				$.each(res, function(i, d) {
					$('#bagian').append('<option value="' + d.kodebagian + '">' + d.namabagian + '</option>');
				});
			})
            //enable bagian
            $('#bagian').removeAttr('disabled');
        });

		$(chkAll).on('change', function (e) {
			var that = $(this);
			var status = that.is(':checked');
			var target = $('tbody').find('input[type="checkbox"]');
			$.each($(target), function(i, ele) {
				console.log(ele);
				// $(ele).attr('checked', status);
			});
			// $('.btn-trigger').attr('disabled', !status);
		});
		
		$('.btn-trigger').on('click', function(e) {
			$('#btnSubmit').trigger('click');
		});

		$('.date-container .input-daterange').datepicker({
			format: "d-m-yyyy",
			maxViewMode: 2,
			todayBtn: "linked",
			language: "id",
			orientation: "top auto",
			autoclose: true,
			todayHighlight: true
		});

		$('#frm-filter').on('submit', function(e) {
			var that = $(this);
			validate();		
		});
	});

	function validate() {
		$('#frm-filter').validate({
			ignore: ":hidden",
			errorClass: 'invalid-feedback animated fadeInDown',
			errorElement: 'div',
			errorPlacement: function(error, e) {
				if(e.attr('name') == 'unit') {
					jQuery(e).next('.select2').append(error);
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
			},
			rules: {
				'unit':{
					required:true
				},
				'tgl_mulai':{
					required:true,
					minlength:8,
					maxlength:10
				},
				'tgl_selesai':{
					required:true,
					minlength:8,
					maxlength:10
				},
				'alasan_normalisasi':{
					required:true,
					minlength:10,
					maxlength:500
				},
			},
			messages: {
				'unit':{
					required:'Tentukan unit kerja'
				},
				'tgl_mulai':{
					required:'Tentukan tanggal mulai',
					minlength:'Tanggal mulai salah',
					maxlength:'Tanggal mulai salah'
				},
				'tgl_selesai':{
					required:'Tentukan tanggal selesai',
					minlength:'Tanggal selesai salah',
					maxlength:'Tanggal selesai salah'
				},
				'alasan_normalisasi':{
					required:'Alasan normalisasi tidak boleh kosong',
					minlength:'Minimal 10 karakter',
					maxlength:'Maksimal 500 karakter'
				}
			}
		});
	}
</script>
@endsection