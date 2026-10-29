@extends('desktop.layouts.app')
@section('title', 'Master Kode Absensi')

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
        <h3 class="block-title">Data Kehadiran dari Aplikasi <strong>Attendance</strong></h3>
        <div class="block-options">
        	@allow('create', 'data-kehadiran')
            <a href="{{ route($route . 'upload') }}" class="btn btn-sm btn-square btn-outline-info mr-5 mb-5" title="Upload data kehadiran (dari aplikasi Attendance)"><i class="fa fa-upload"></i> Upload </a>
            @endallow
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle" title="Jadikan layar penuh"></button>
            <!-- <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button> -->
        </div>
    </div>
    <div class="block-content">
        <div class="block-content block-content-full">
	        <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/be_tables_datatables.js -->
		    <div class="table-responsive">
		        <table class="table table-bordered table-striped table-vcenter" id="datatable_ajax" style="width: 100%!important;">
		            <thead>
		                <tr>
		                	<th style="width: 30px;">No</th>
		                	<th style="width: 30px;">PIN</th>
		                	<th style="width: 30px;">NIK</th>
		                	<th style="width: 200px;">Nama</th>
		                	<th style="width: 30px;">Tanggal</th>
		                	<th style="width: 30px;">C. In</th>
		                	<th style="width: 30px;">C.Out</th>
		                	<th style="width: 30px;">TD</th>
		                	<th style="width: 30px;">CP</th>
		                	<!-- <th style="width: 80px;">Action</th> -->
		                </tr>
		            </thead>
		            <tfoot>
		                <tr>
		                	<th></th>
		                	<th></th>
		                	<th>NIK</th>
		                	<th>Nama</th>
		                	<th data-datepicker="true">Tanggal</th>
		                	<th>C. In</th>
		                	<th>C.Out</th>
		                	<th></th>
		                	<th></th>
		                	<!-- <th></th> -->
		                </tr>
		            </tfoot>
		        </table>
		    </div>
	    </div>
    </div>
</div>
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
		{ data: '', render: function(data, type, row, meta) {  return meta.row + meta.settings._iDisplayStart + 1; }, searchable: false, class: 'text-center' },
		{ data: 'pin', class: 'text-center' },
		{ data: 'nik_simkar', name: 'tbl.nik_simkar', class: 'text-center' },
		{ data: 'nama_mesin', name: 'p.nama_mesin'},
		{ data: 'tgl', name: 'tbl.tgl', class: 'formatToDate'},
		{ data: 'checkin_time', name: 'tbl.checkin_time', class: 'text-center formatToTime' },
		{ data: 'checkout_time', name: 'tbl.checkout_time', class: 'text-center formatToTime' },
		{ data: 'telat_datang', name: 'tbl.telat_datang', class: 'text-center formatToTime'},
		{ data: 'cepat_pulang', name: 'tbl.cepat_pulang', class: 'text-center formatToTime'},
		// { data: 'action', name: 'action', sortable: false, searchable: false },
		],
		order: [[3, 'asc'], [4, 'asc'], ],
	});
</script>
@endsection