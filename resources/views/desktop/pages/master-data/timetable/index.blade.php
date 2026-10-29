@extends('desktop.layouts.app')
@section('title', 'Timetable (Jadwal absensi)')

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
        <h3 class="block-title">Timetable (Jadwal absensi)</h3>
        <div class="block-options">
        	<!-- allow('create', 'timetable') -->
            <a href="{{ route($route . 'create') }}" class="btn btn-sm btn-square btn-alt-green mr-5 mb-5" title="Tambah Timetable"><i class="fa fa-plus"></i> Tambah Data</a>
            <!-- endallow -->
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
	        				<th class="text-center">No</th>
	        				<th style="width: 150px;">Unit Kerja</th>
	        				<th class="d-none d-sm-table-cell">Day Name</th>
	        				<th class="d-none d-sm-table-cell">Jam Masuk</th>
	        				<th class="d-none d-sm-table-cell">Masuk Min</th>
	        				<th class="d-none d-sm-table-cell">Masuk Max</th>
	        				<th class="d-none d-sm-table-cell">Jam Pulang</th>
	        				<th class="d-none d-sm-table-cell">Pulang Min</th>
	        				<th class="d-none d-sm-table-cell">Pulang Max</th>
	        				<th class="d-none d-sm-table-cell">Satpam</th>
	        				<th class="d-none d-sm-table-cell">Status</th>
	        				<th class="text-center" style="width: 80px;">Aksi</th>
	        			</tr>
	        		</thead>
	        		<tfoot>
	        			<tr>
	        				<th></th>
	        				<th>Unit Kerja</th>
	        				<th>Day Name</th>
	        				<th></th>
	        				<th></th>
	        				<th></th>
	        				<th></th>
	        				<th></th>
	        				<th></th>
	        				<th></th>
	        				<th>Status</th>
	        				<th></th>
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
	$('.libur-cuti-bersama-table tfoot th').each( function () {
        var title = $(this).text();
        if(title !== '')
        	$(this).html('<input type="text" placeholder="Cari '+title+'" style="padding-left: 5px; width: 100%" />');
    } );
 
	// var table = jQuery('.libur-cuti-bersama-table').DataTable({
	var table = Datatable.init({
		processing: true,
		serverSide: true,
		ajax: {
			url: '{!! route($route . 'table') !!}',
			method: 'POST'
		},
		columns: [
		{ data: '', render: function(data, type, row, meta) {  return meta.row + meta.settings._iDisplayStart + 1; }, searchable: false, class: 'text-center' },
		{ data: 'namaunitbagian' },
		{ data: 'dayname' },
		{ data: 'jam_masuk' },
		{ data: 'jam_masuk_min' },
		{ data: 'jam_masuk_max' },
		{ data: 'jam_pulang' },
		{ data: 'jam_pulang_min' },
		{ data: 'jam_pulang_max' },
		{ data: 'satpam', },
		{ data: 'status', class: 'text-center'},
		{ data: 'action', name: 'action', sortable: false, searchable: false },
		],
		order: [[1, 'desc']],
	});

</script>
@endsection