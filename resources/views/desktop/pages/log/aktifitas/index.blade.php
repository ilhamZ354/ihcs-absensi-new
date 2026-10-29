@extends('desktop.layouts.app')
@section('title', 'Log Aktifitas')

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
        <h3 class="block-title">Daftar log aktifitas yang tercatat dalam sistem</h3>
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
		                	<th style="width: 10px;">No.</th>
		                	<th style="width: 30px;">Tanggal</th>
		                	<th style="width: 50px;">User Aktif</th>
		                	<th style="width: 200px;">Aktifitas</th>
		                	<th style="width: 30px;">Pencatat</th>
		                </tr>
		            </thead>
		            <tfoot>
		                <tr>
		                	<th></th>
		                	<th>Tanggal</th>
		                	<th>User Aktif</th>
		                	<th>Aktifitas</th>
		                	<th>Pencatat</th>
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
	var table = Datatable.init({
		processing: true,
		serverSide: true,
		dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 text-right">><"row"<"col-sm-12"tr>><"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
		language: {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Mohon menunggu...</span> '},
		ajax: {
			url: '{!! route($route . 'table') !!}',
			method: 'POST'
		},
		columns: [
		{ data: '', render: function(data, type, row, meta) {  return meta.row + meta.settings._iDisplayStart + 1; }, searchable: false, class: 'text-center' },
		{ data: 'created_at', nama: 'tp_log_aktifitas.created_at', class: 'text-center' },
		{ data: 'nama', name: 'nama', class: 'text-center' },
		{ data: 'aktifitas', },
		{ data: 'created_by', nama: 'tp_log_aktifitas.created_by', class: 'text-center' },
		],
		order: [[1, 'desc']],
	});
</script>
@endsection