@extends('desktop.layouts.app')
@section('title', 'Daftar file absensi upload')

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
        <h3 class="block-title">Daftar file absensi yang telah diupload dalam sistem</h3>
        <div class="block-options">
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle" title="Jadikan layar penuh"></button>
        </div>
    </div>
    <div class="block-content">
        <div class="block-content block-content-full">
	        <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/be_tables_datatables.js -->
		    <div class="table-responsive">
		        <table class="table table-bordered table-striped table-vcenter" id="datatable_ajax">
		            <thead>
		                <tr>
		                	<th>No</th>
		                	<th>Tahun</th>
		                	<th>Bulan</th>
		                	<th>Jenis</th>
		                	<th>Uraian</th>
		                	<th>File</th>
		                	<!-- <th style="width: 80px;">Action</th> -->
		                </tr>
		            </thead>
		            <tfoot>
		                <tr>
		                	<th width="50px"></th>
		                	<th width="100px">Tahun</th>
		                	<th width="100px">Bulan</th>
		                	<th width="100px">Jenis</th>
		                	<th>Uraian</th>
		                	<th width="50px"></th>
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
	var table = Datatable.init({
		processing: true,
		serverSide: true,
		dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 text-right">><"row"<"col-sm-12"tr>><"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
		language: {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Mohon menunggu...</span> '},
		ajax: {
			url: '{!! route($route . 'table-uploaded-files') !!}',
			method: 'POST'
		},
		columns: [
		{ data: '', render: function(data, type, row, meta) {  return meta.row + meta.settings._iDisplayStart + 1; }, searchable: false, class: 'text-center' },
		{ data: 'tahun', class: 'text-center' },
		{ data: 'bulan', class: 'text-center text-bulan' },
		{ data: 'jenis_data', class: 'text-uppercase text-center' },
		{ data: 'uraian'},
		{ data: 'file_absensi', },
		// { data: 'action', name: 'action', sortable: false, searchable: false },
		],
		order: [[1, 'desc'], [2, 'desc'], ],
	});

	// $('#datatable_ajax').on('init.dt', function () {
	// 	$('.download_absensi_file').on('click', function(){
	// 		var action = $(this).data('action');
	// 		console.log(action);
	// 		var form = $('#downloadForm');
	// 		form.empty();
	// 		form.append('<form method="POST" action="'+action+'"><input type="hidden" name="_token" value="'+$('meta[name="csrf-token"]').attr('content')+'"');
	// 		$('.block').append(form);
	// 		form.submit();
	// 	});
	// });
</script>
@endsection