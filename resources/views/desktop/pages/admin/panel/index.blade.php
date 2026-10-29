@extends('desktop.layouts.app')
@section('title', 'Master Kode Absensi')

@section('css')
<!-- page css -->
<link rel="stylesheet" href="{{ asset('/desktop/js/plugins/datatables/dataTables.bootstrap4.css') }}">
<style type="text/css">
	tfoot {
		display: table-header-group;
	}
	.tahunBulan, .pathMDB, .dateRangePicker, .datePicker {
		display: none;
	}

	.btn-exec {
		height: 75px;
		white-space: normal;
	}
</style>
@endsection

@section('content')

<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title"><i class="fa fa-gears fa-fw"></i> Panel Administrator</strong></h3>
        <div class="block-options">            
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle" title="Jadikan layar penuh"></button>
        </div>
    </div>
    <div class="block-content">
        <div class="block-content block-content-full">
		    <div class="row">
                <div class="col-lg-12">
                    <div class="row items-push">
                        <!-- <div class="col-md-3">
                            <button type="button" class="btn btn-block btn-alt-primary btn-exec" id="generate_full_day_absensi">Generate Full Days Absensi</button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-block btn-alt-primary btn-exec" id="generate_telat_cepat_pulang">Generate Telat/Cepat Pulang</button>
                        </div> -->
                        <div class="col-md-3">
                            <button type="button" class="btn btn-block btn-alt-primary btn-exec" id="sync_att_mobile"><i class="fa fa-exchange"></i> Sinkronisasi Absensi Mobile</button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-block btn-alt-primary btn-exec" id="generate_summary_absensi_harian">Generate Summary Absensi Harian</button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-block btn-alt-primary btn-exec" id="generate_hari_minggu">Generate Hari Minggu</button>
                        </div>
                        <!-- <div class="col-md-3">
                            <button type="button" class="btn btn-block btn-alt-primary btn-exec" id="generate_kelompok_kerja_harian">Generate Kel. Kerja Harian</button>
                        </div> -->

                        <div class="col-md-3">
                            <button type="button" class="btn btn-block btn-alt-primary btn-exec" id="generate_kuota_cuti_tahunan">Generate Kuota Cuti Tahun</button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-block btn-alt-primary btn-exec" id="generate_kuota_cuti_panjang">Generate Kuota Cuti Panjang</button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-block btn-alt-primary btn-exec" id="run_db_backup">Backup Database</button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-block btn-alt-primary btn-exec" id="config_path_mdb"><i class="fa fa-database"></i> Config path MDB</button>
                        </div>
                        <!-- generate_summary_absensi_harian sudah tidak digunakan lagi
                        <div class="col-md-3">
                            <button type="button" class="btn btn-block btn-alt-primary btn-exec" id="generate_summary_absensi_harian"><i class="fa fa-500px"></i> Generate Absensi Harian</button>
                        </div>
                         -->
                    </div>
                </div>
            </div>
	    </div>
    </div>
</div>
<!-- Fade In Modal -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal-fadein" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title modal-really-title">Terms &amp; Conditions</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content modal-really-content">
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-alt-secondary btn-cancel" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-alt-success btn-ok" data-dismiss="modal">
                    <i class="fa fa-check"></i> Perfect
                </button>
            </div>
        </div>
    </div>
</div>
<div class="datePicker">
	<form>
		<div class="form-group row">
			<label class="col-lg-3 col-form-label" for="tgl">Tanggal</label>
			<div class="col-lg-7">
				<input type="text" class="form-control" name="tgl" placeholder="Tanggal" autocomplete="off" readonly="" required="">
			</div>
		</div>
	</form>
</div>
<div class="tahunBulan">
	<form>
		<div class="form-group row">
			<label class="col-lg-3 col-form-label" for="tahun">Tahun</label>
			<div class="col-lg-7">
				<input type="text" class="form-control" name="tahun" placeholder="Tahun" autocomplete="off" readonly="" required="">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-lg-3 col-form-label" for="bulan">Bulan</label>
			<div class="col-lg-7">
				<input type="text" class="form-control" name="bulan" placeholder="Bulan" autocomplete="off" readonly="" required="">
			</div>
		</div>
	</form>
</div>
<div class="dateRangePicker">
	<form>
		<div class="form-group row" id="date-container">
			<label class="col-lg-3 col-form-label" for="tgl_mulai">Pilih Tanggal</label>
			<div class="input-daterange input-group col-lg-9" data-date-format="d-m-yyyy" data-week-start="1" data-autoclose="true" data-today-highlight="true">
				<input type="text" class="form-control" id="tgl_mulai" name="tgl_mulai" placeholder="Mulai" data-week-start="1" data-autoclose="true" data-today-highlight="true" readonly="">
				<div class="input-group-prepend input-group-append">
					<span class="input-group-text font-w600">sd</span>
				</div>
				<input type="text" class="form-control" id="tgl_selesai" name="tgl_selesai" placeholder="Selesai" data-week-start="1" data-autoclose="true" data-today-highlight="true" readonly="">
			</div>
		</div>
	</form>
</div>
<div class="pathMDB">
	<form>
		<div class="form-group row">
			<label class="col-lg-3 col-form-label" for="tahun">Path MDB</label>
			<div class="col-lg-9">
				<input type="text" class="form-control" name="path_mdb" placeholder="Path MDB" autocomplete="off" required="" autofocus>
			</div>
		</div>
	</form>
</div>
<!-- END Fade In Modal -->
@endsection

@section('js')
<script type="text/javascript">
	var modal = jQuery('#modal');
	var btnCancel = jQuery('button.btn-cancel');
	var btnOk = jQuery('button.btn-ok');
	var content = jQuery('.modal-really-content');
	var tahunBulan = jQuery('.tahunBulan');
	var datePicker = jQuery('.datePicker');
	var dateRangePicker = jQuery('.dateRangePicker');
	var pathMDB = jQuery('.pathMDB');

	/*$('#generate_full_day_absensi').on('click', function(e) {
		var that = $(this);
		var title = that.text();

		content.children().remove();
		tahunBulan.children().clone().appendTo(content);
		content.children().prop('id', 'generate_full_day_absensi_form');

		$('.block-title.modal-really-title').text(title);
		btnCancel.text('Batal');
		btnOk.text('Eksekusi');
		modal.modal('show');
		_addDatePicker();
	});
	
	$('#generate_telat_cepat_pulang').on('click', function(e) {
		var that = $(this);
		var title = that.text();

		content.children().remove();
		tahunBulan.children().clone().appendTo(content);
		content.children().prop('id', 'generate_telat_cepat_pulang_form');

		$('.block-title.modal-really-title').text(title);
		btnCancel.text('Batal');
		btnOk.text('Eksekusi');
		modal.modal('show');
		_addDatePicker();
	});*/
	
	@if(is_periode_generate_summary())
	$('#generate_summary_absensi_harian').on('click', function(e) {
		var that = $(this);
		var title = that.text();

		content.children().remove();
		datePicker.children().clone().appendTo(content);
		content.children().prop('id', 'generate_summary_absensi_harian_form');

		$('.block-title.modal-really-title').text(title);
		btnCancel.text('Batal');
		btnOk.text('Eksekusi');
		modal.modal('show');
		_addDatePicker();
	});
	@endif

	$('#generate_hari_minggu').on('click', function(e) {
		var that = $(this);
		var title = that.text();

		content.children().remove();
		tahunBulan.children().clone().appendTo(content);
		content.children().prop('id', 'generate_hari_minggu_form');
		content.find('input[name=bulan]').parent().parent().remove();
		$('.block-title.modal-really-title').text(title);
		btnCancel.text('Batal');
		btnOk.text('Eksekusi');
		modal.modal('show');
		_addDatePicker();
	});

	$('#generate_kuota_cuti_panjang').on('click', function(e) {
		var that = $(this);
		var title = that.text();

		/*content.children().remove();
		tahunBulan.children().clone().appendTo(content);
		content.children().prop('id', 'generate_kuota_cuti_panjang_form');

		$('.block-title.modal-really-title').text(title);
		btnCancel.text('Batal');
		btnOk.text('Eksekusi');
		modal.modal('show');
		_addDatePicker();*/

		jQuery.ajax({
			url: '{{ route('api.absensi.cuti-panjang') }}',
			method: 'POST',
			success: function(response) {
				swal('Success', response['message'], (response['success'] ? 'success' : 'error'));
			},
			error: function(err) {
				swal('Gagal','Backup database gagal, periksa error log', 'error');
			}
		});
	});

	$('#generate_kuota_cuti_tahunan').on('click', function(e) {
		var that = $(this);
		var title = that.text();

		/*jQuery.ajax({
			url: '{{ route('api.absensi.cuti-tahunan') }}',
			method: 'POST',
			success: function(response) {
				swal('Success', response['message'], (response['success'] ? 'success' : 'error'));
			},
			error: function(err) {
				console.log(err);
				swal('Gagal','Backup database gagal, periksa error log', 'error');
			}
		});*/

		content.children().remove();
		tahunBulan.children().clone().appendTo(content);
		content.children().prop('id', 'generate_kuota_cuti_tahunan_form');
		content.find('input[name=bulan]').parent().parent().remove();
		$('.block-title.modal-really-title').text(title);
		btnCancel.text('Batal');
		btnOk.text('Eksekusi');
		modal.modal('show');
		_addDatePicker();
	});

	$('#sync_att_mobile').on('click', function(e) {
		var that = $(this);
		var title = that.text();

		content.children().remove();
		dateRangePicker.children().clone().appendTo(content);
		content.children().prop('id', 'sync_att_mobile_form');
		$('.block-title.modal-really-title').text(title);
		btnCancel.text('Batal');
		btnOk.text('Eksekusi');
		modal.modal('show');
		_addDateRangePicker();
	});
	

	$('#run_db_backup').on('click', function(e) {
		var that = $(this);
		var title = that.text();

		jQuery.ajax({
			url: '{{ route('api.absensi.backup-db') }}',
			method: 'POST',
			success: function(response) {
				swal('Success', response['message'], (response['success'] ? 'success' : 'error'));
			},
			error: function(err) {
				swal('Gagal','Backup database gagal, periksa error log', 'error');
			}
		});
	});

	$('#config_path_mdb').on('click', function(e) {
		var that = $(this);
		var title = 'Konfigurasi Path MDB';

		content.children().remove();
		pathMDB.children().clone().appendTo(content);
		content.children().prop('id', 'config_path_mdb_form');
		$('.block-title.modal-really-title').text(title);

		getCurrentMDBPath(function(path) {
			$('#config_path_mdb_form').find('input[name=path_mdb]').val(path);
		});
		
		btnCancel.text('Batal');
		btnOk.text('Eksekusi');
		modal.modal('show');
	});

	/*$('#generate_summary_absensi_harian').on('click', function(e) {
		var that = $(this);
		
		swal({
            title: "Apakah Anda yakin?",
            text: "Data kehadiran akan ditarik dari seluruh mesin absensi.",
            type: 'warning', //width: '450px',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "Ya, saya yakin!",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: 'route('api.absensi.generate_summary_absensi_harian')',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if(response != 1) {
                            swal(
                                'Gagal!',
                                response.message,
                                'error'
                                );  
                        } else {
                            swal(
                                'Sukses!',
                                response.message,
                                'success'
                                );
                        }
                    }
                });
            }
        });
	});*/

	btnOk.on('click', function(e) {
		var frm = content.find('form').serializeArray();
		var formId = btnOk.parent().parent().find('form').attr('id');
		switch(formId) {
			/*case 'generate_full_day_absensi_form':
				jQuery.ajax({
					url: 'route('api.absensi.full_day_absensi')',
					method: 'POST',
					data: {
						tahun: frm[0]['value'],
						bulan: frm[1]['value'],
					},
					success: function(response) {
						swal('Success', response['message'], (response['success'] ? 'success' : 'error'));
						
					},
					error: function(err) {
						swal('Gagal','Eksekusi generate full days absensi gagal', 'error');
					}
				});
			break;

			case 'generate_telat_cepat_pulang_form':
				jQuery.ajax({
					url: 'route('api.absensi.telat_cepat_pulang')',
					method: 'POST',
					data: {
						tahun: frm[0]['value'],
						bulan: frm[1]['value'],
					},
					success: function(response) {
						swal('Success', response['message'], (response['success'] ? 'success' : 'error'));
					},
					error: function(err) {
						swal('Gagal','Eksekusi generate telat/cepat pulang gagal', 'error');
					}
				});
			break;*/

			@if(is_periode_generate_summary())
			case 'generate_summary_absensi_harian_form':
				jQuery.ajax({
					url: '{{ route('api.absensi.generate_summary_absensi_harian') }}',
					method: 'POST',
					data: {
						tgl: frm[0]['value'],
					},
					success: function(response) {
						swal('Success', response['message'], (response['success'] ? 'success' : 'error'));
					},
					error: function(err) {
						swal('Gagal','Eksekusi generate summary absensi bulanan gagal', 'error');
					}
				});
			break;
			@endif

			case 'generate_hari_minggu_form':
				jQuery.ajax({
					url: '{{ route('api.absensi.hari_minggu') }}',
					method: 'POST',
					data: {
						tahun: frm[0]['value'],
						// bulan: frm[1]['value'],
					},
					success: function(response) {
						swal('Success', response['message'], (response['success'] ? 'success' : 'error'));
					},
					error: function(err) {
						swal('Gagal','Eksekusi generate hari minggu gagal', 'error');
					}
				});
			break;

			case 'generate_kuota_cuti_tahunan_form':
				jQuery.ajax({
					url: '{{ route('api.absensi.cuti-tahunan') }}',
					method: 'POST',
					data: {
						tahun: frm[0]['value'],
						// bulan: frm[1]['value'],
					},
					success: function(response) {
						swal('Success', response['message'], (response['success'] ? 'success' : 'error'));
					},
					error: function(err) {
						swal('Gagal','Eksekusi generate cuti tahunan gagal', 'error');
					}
				});
			break;

			case 'sync_att_mobile_form':
				jQuery.ajax({
					url: '{{ route('api.absensi.sync-att-mobile') }}',
					method: 'POST',
					data: {
						mulai: frm[0]['value'],
						selesai: frm[1]['value'],
					},
					success: function(response) {
						swal('Success', response['message'], (response['success'] ? 'success' : 'error'));
					},
					error: function(err) {
						swal('Gagal','Sinkronisasi gagal, hubungi Administrator Aplikasi.', 'error');
					}
				});
			break;

			case 'config_path_mdb_form':
				jQuery.ajax({
					url: '{{ route('api.absensi.config-path-mdb') }}',
					method: 'POST',
					data: {
						path: frm[0]['value']
					},
					success: function(response) {
						swal('Success', response['message'], (response['success'] ? 'success' : 'error'));
					},
					error: function(err) {
						swal('Gagal','Gagal menyimpan konfig path MDB', 'error');
					}
				});
			break;

			default:
				//else, nothing to do...
			break;
		}
	});

	$('#modal').on('hidden.bs.modal', function() {
		content.find('form')[0].reset();
	});

	function _addDatePicker() {
		$("input[name=tahun]").datepicker({
	        format: "yyyy",
	        viewMode: "years", 
	        minViewMode: "years",
	        autoclose: true,
	        startDate: "-1y",
	    });
	    $("input[name=bulan]").datepicker( {
	        autoclose: true,
	        format: "MM",
	        viewMode: "months", 
	        minViewMode: "months",
	        language: 'ID'
	    });
	    $("input[name=tgl]").datepicker( {
	       	format: "dd-mm-yyyy",
	        endDate: "+0d",
	        todayHighlight: true,
	        language: "ID"
	    });
	}

	function _addDateRangePicker() {
		$('#date-container .input-daterange').datepicker({
			format: "yyyy-mm-dd",
			maxViewMode: 2,
			todayBtn: "linked",
			language: "id",
			orientation: "bottom auto",
			autoclose: true,
			todayHighlight: true,
			endDate: "+0d",
		});
	}

	function getCurrentMDBPath(callback) {
		jQuery.ajax({
			url: '{{ route('api.absensi.get-path-mdb') }}',
			method: 'POST',
			success: function(response) {
				callback(response);
			},
			error: function(err) {
				callback('');
			}
		});
	}

	$(document).ready(function() {
		_addDatePicker();		
    });
</script>
@endsection