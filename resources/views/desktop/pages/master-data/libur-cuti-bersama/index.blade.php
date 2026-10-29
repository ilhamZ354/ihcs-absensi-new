@extends('desktop.layouts.app')
@section('title', 'Libur & Cuti Bersama')

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
        <h3 class="block-title">Daftar Libur & Cuti Bersama</h3>
        <div class="block-options">
        	@allow('create', 'libur-cuti-bersama')
            <a href="{{ route($route . 'create') }}" class="btn btn-sm btn-square btn-alt-green mr-5 mb-5" title="Tambah Jadwal Libur & Cuti Bersama"><i class="fa fa-plus"></i> Tambah Data</a>
            @endallow
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle" title="Jadikan layar penuh"></button>
            <!-- <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button> -->
        </div>
    </div>
    <div class="block-content">
        <div class="block-content block-content-full">
	        <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/be_tables_datatables.js -->
	        <table class="table table-bordered table-striped table-vcenter" id="datatable_ajax">
	            <thead>
	                <tr>
	                    <th class="text-center">No</th>
	                    <th style="width: 80px;">Tanggal</th>
	                    <th class="d-none d-sm-table-cell">Uraian</th>
	                    <th class="d-none d-sm-table-cell">Jenis Libur</th>
	                    <th class="d-none d-sm-table-cell">Status</th>
	                    <th class="text-center" style="width: 80px;">Aksi</th>
	                </tr>
	            </thead>
	            <tfoot>
	                <tr>
	                    <th></th>
	                    <th data-datepicker="true">Tanggal</th>
	                    <th>Uraian</th>
	                    <th></th>
	                    <th></th>
	                    <th></th>
	                </tr>
	            </tfoot>
	        </table>
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
		{ data: 'rownum', name: 'rownum', searchable: false, class: 'text-center' },
		{ data: 'tgl', class: 'text-center formatToDate' },
		{ data: 'uraian', },
		{ data: 'jenis_libur', },
		{ data: 'status', class: 'text-center'},
		{ data: 'action', name: 'action', sortable: false, searchable: false },
		],
		order: [[1, 'desc']],
	});

</script>
@endsection