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
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle" title="Jadikan layar penuh"></button>
            <!-- <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button> -->
        </div>
    </div>
    <div class="block-content">
        <div class="block-content block-content-full">
	        <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/be_tables_datatables.js -->
		    <div class="table-responsive">
		        <table class="table table-bordered table-striped table-vcenter" id="datatable_ajax">
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
<script type="text/javascript">
	var table = Datatable.init({
		processing: true,
		serverSide: true,
		dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 text-right">><"row"<"col-sm-12"tr>><"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
		language: {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Mohon menunggu...</span> '},
		ajax: {
			url: '#',
			method: 'POST'
		},
		columns: [
		{ data: '', render: function(data, type, row, meta) {  return meta.row + meta.settings._iDisplayStart + 1; }, searchable: false, class: 'text-center' },
		{ data: 'pin', class: 'text-center' },
		{ data: 'nik_simkar', name: 'tr_absensi_details.nik_simkar', class: 'text-center' },
		{ data: 'nama_mesin', name: 'p.nama_mesin'},
		{ data: 'tgl', name: 'tr_absensi_details.tgl'},
		{ data: 'checkin_time', name: 'tr_absensi_details.checkin_time', class: 'text-center formatToTime' },
		{ data: 'checkout_time', name: 'tr_absensi_details.checkout_time', class: 'text-center formatToTime' },
		{ data: 'telat_datang', name: 'tr_absensi_details.telat_datang', class: 'text-center formatToTime'},
		{ data: 'cepat_pulang', name: 'tr_absensi_details.cepat_pulang', class: 'text-center formatToTime'},
		// { data: 'action', name: 'action', sortable: false, searchable: false },
		],
		order: [[3, 'asc'], [4, 'asc'], ],
	});
</script>
@endsection