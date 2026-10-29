@extends('desktop.layouts.app')
@section('title', 'Daftar Peserta N4-Attendance')

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
		<h3 class="block-title">Data Peserta Aplikasi Mobile <strong>N4 Attendance</strong></h3>
		<div class="block-options">

			<button type="button" class="btn btn-sm btn-square btn-alt-green mr-5 mb-5" data-toggle="modal" data-target="#n4attendanceBaru" data-backdrop="static">Peserta Baru</button>
		</div>
		<button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle" title="Jadikan layar penuh"></button>
	</div>
	<div class="block-content">
		<div class="block-content block-content-full">
			<!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/be_tables_datatables.js -->
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-vcenter" id="datatable_ajax" style="width: 100%!important;">
					<thead>
						<tr>
							<th style="width: 10px;">No</th>
							<th style="width: 20px;">NIK</th>
							<th style="width: 100px;">Nama</th>
							<th style="width: 10px;">Panggilan</th>
							<th style="width: 20px;">Ponsel</th>
							<th style="width: 20px;">Group</th>
							<th style="width: 20px;">Unit</th>
							<th style="width: 20px;">Bagian</th>
							<th style="width: 20px;">IMEI/ID</th>
							<th style="width: 10px;">Action</th>
							<!-- <th style="width: 80px;">Action</th> -->
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th></th>
							<th>NIK</th>
							<th>Nama</th>
							<th>Panggilan</th>
							<th>Ponsel</th>
							<th>Group</th>
							<th>Unit</th>
							<th>Bagian</th>
							<th></th>
							<th></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
@include('desktop.pages.master-data.n4attendance.peserta.modal')
@endsection

@section('js')
<script src="{{ asset('/desktop/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/desktop/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('/desktop/js/absensi.datatable.js') }}"></script>
<script type="text/javascript">
	@if(Session::has('error'))
	swal('error', 'Cek error');
	@endif
	var table = Datatable.init({
		processing: true,
		serverSide: true,		
		ajax: {
			url: '{!! route($route . 'table') !!}',
			method: 'POST'
		},
		columns: [
		{ data: '', render: function(data, type, row, meta) {  return meta.row + meta.settings._iDisplayStart + 1; }, searchable: false,  sortable: false, class: 'text-center' },
		{ data: 'nik', name: 'nik', searchable: true },
		{ data: 'name', name: 'name', searchable: true},
		{ data: 'nick_name', name: 'nick_name', searchable: true},
		{ data: 'username', name: 'username', searchable: true},
		{ data: 'group', name: 'employeeGroup.name', searchable: true},
		{ data: 'unit', name: 'unit', searchable: true, sortable: false},
		{ data: 'bagian', name: 'bagian', searchable: true, sortable: false},
		{ data: 'imei', name: 'imei', searchable: true, sortable: false},
		{ data: 'action', name: 'action', sortable : false},
		// { data: 'action', name: 'action', sortable: false, searchable: false },
		],
		order: [[2, 'asc'], [3, 'asc'], ],
	});

	function edit(e,$id) {
		$.ajax({
			url: '{!! route($route . 'index') !!}/' + $id+'/edit',
			method: 'get',
			beforeSend: function() {
				Codebase.layout('header_loader_on');
			},
			success: function (data) {
				let txtA = $('#detail_edit');
				var updateURL = $('#frmEditPeserta').attr('action');
				$('#frmEditPeserta').attr('action', updateURL + '/' + $id);

				Codebase.layout('header_loader_off');
				$("#name_edit").val(data.name);
				$("#detail_edit").val('UNIT : '+data.nama_unit);
				$("#detail_edit").val(txtA.val()+'\nBAGIAN : '+data.nama_bagian);
				$("#detail_edit").val(txtA.val()+'\nJABATAN : '+data.nama_jabatan);
				$("#nick_name_edit").val(data.nick_name);
				  	// $("#employee_group_id_edit").val(data.employee_group_id);
				  	$("#employee_group_id_edit").val(data.employee_group_id).change();
				  	$("#username_edit").val(data.username);
				  	$("#imei_edit").val(data.imei);



				  	$("#data_id").val($id);
				  	$('#n4attendanceEdit').modal({
				  		backdrop: 'static',
				  	}, 'show');
				  	// $('#n4attendanceEdit').modal(options.backdrop='static');
				  	// $('#n4attendanceEdit').data('bs.modal').options.backdrop = 'static';
				  }
				});
	}

</script>

@endsection