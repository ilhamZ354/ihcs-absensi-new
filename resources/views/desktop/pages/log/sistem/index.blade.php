@extends('desktop.layouts.app')
@section('title', 'Log Absensi')

@section('css')
<!-- page css -->
<link rel="stylesheet" href="{{ asset('/desktop/js/plugins/datatables/dataTables.bootstrap4.css') }}">
<style type="text/css">
	tfoot {
		display: table-header-group;
	}
</style>
@endsection

@section('content')

<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Daftar Log Absensi</h3>
        <div class="block-options">
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle" title="Jadikan layar penuh"></button>
            <!-- <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button> -->
        </div>
    </div>
    <div class="block-content">
        <div class="block-content block-content-full">
        	<div class="table-responsive">
		        <table class="table table-bordered table-striped table-vcenter" style="width: 100% !important;" id="datatable_ajax">
		            <thead>
		                <tr>
		                    <th style="width: 20px;"class="text-center">No</th>
		                    <th>File log</th>
		                    <th class="text-center" style="width: 80px;">Aksi</th>
		                </tr>
		            </thead>
		        </table>
	    	</div>
	    </div>
    </div>
</div>
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal-normal" aria-hidden="true">
	<div class="modal-dialog" role="document" style="max-width: 90%; margin-top: 20px !important;">
		<div class="modal-content">
			<div class="block" id="content"></div>
		</div>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	var table = Datatable.init({
		processing: true,
		serverSide: true,
		ajax: {
			url: '{!! route($route . 'table') !!}',
			method: 'POST'
		},
		columns: [
		{ data: null, class: 'text-center', sortable: false, render: function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; }},
		{ data: 'file_log', class: '', searchable: false },
		{ data: 'action', name: 'action', sortable: false, searchable: false },
		],
		order: [1, 'desc'],
	});

	$.fn.dataTable.ext.errMode = function ( settings, helpPage, message ) { 
		console.log(message);
	};

	function showLog(e, route, app = this) {
		e.preventDefault();
		$.ajax({
			url: route,
			method: 'POST',
			success: function(res) {
				$('#content').html(res);
				$('#modal').modal('show');
			}
		});
	}

	function clearLog(e, route, app = this) {
		e.preventDefault();
		$.ajax({
			url: route,
			method: 'POST',
			success: function(res) {
				if(res.status)
					swal('Sukses!', 'Log berhasil dibersihkan', 'success');
				else
					swal('Gagal!', 'Gagal membersihkan log', 'danger');
			}
		});
	}

</script>
@endsection