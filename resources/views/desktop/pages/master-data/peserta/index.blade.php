@extends('desktop.layouts.app')
@section('title', 'Peserta Absensi')

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
        <h3 class="block-title">Daftar Peserta Absensi</h3>
        <div class="block-options">
        	@allow('create', 'peserta')
            <a href="{{ route($route . 'create') }}" class="btn btn-sm btn-square btn-alt-green mr-5 mb-5" title="Sinkronkan data peserta dari Mesin"><i class="fa fa-refresh fa-fw"></i> Sinkron Data</a>
        	@endallow
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle" title="Jadikan layar penuh"></button>
            <!-- <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button> -->
        </div>
    </div>
    <div class="block-content">
        <div class="block-content block-content-full">
	        <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/be_tables_datatables.js -->
	        <table class="table table-bordered table-striped table-vcenter" id="datatable_ajax" style="width: 100% !important">
	            <thead>
	                <tr>
	                    <th class="text-center">No</th>
	                    <th style="width: 50px;">PIN</th>
	                    <th style="width: 80px;">NIK Simkar</th>
	                    <th class="d-none d-sm-table-cell">Nama Mesin</th>
	                    <th style="width: 80px;">Tgl Bekerja</th>
	                    <th style="width: 80px;">Satpam</th>
	                    <th class="d-none d-sm-table-cell">Status</th>
	                </tr>
	            </thead>
	            <tfoot>
	                <tr>
	                    <th></th>
	                    <th>PIN</th>
	                    <th>NIK Simkar</th>
	                    <th>Nama Mesin</th>
	                    <th data-datepicker="true">Tgl Bekerja</th>
	                    <th>Satpam</th>
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
 
	var table = Datatable.init({
		processing: true,
		serverSide: true,
		ajax: {
			url: '{!! route($route . 'table') !!}',
			method: 'POST'
		},
		columns: [
		{ data: 'rownum', name: 'rownum', searchable: false, class: 'text-center' },
		{ data: 'pin', class: 'text-center' },
		{ data: 'nik_simkar', class: 'text-center'},
		{ data: 'nama_mesin', },
		{ data: 'tgl_bekerja', class: 'formatToDate text-center' },
		{ data: 'satpam', class: 'text-center'},
		{ data: 'status', class: 'text-center'},
		],
		order: [[0, 'asc']],
	});

</script>
@endsection