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
        <h3 class="block-title">Daftar Kode Absensi</h3>
        <div class="block-options">
            <a href="{{ route($route . 'create') }}" class="btn btn-sm btn-square btn-alt-green mr-5 mb-5" title="Tambah kode absen baru"><i class="fa fa-plus"></i> Tambah Normalisasi </a>
            <a href="{{ route($route . 'uploads') }}" class="btn btn-sm btn-square btn-outline-info mr-5 mb-5" title="Daftar Upload"><i class="fa fa-list"></i></a>
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle" title="Jadikan layar penuh"></button>
        </div>
    </div>
    <div class="block-content">
        <div class="block-content block-content-full">
        	<div class="table-responsive">
		        <table class="table table-bordered table-striped table-vcenter" style="width: 100% !important;" id="datatable_ajax">
		            <thead>
		                <tr>
		                    <th class="text-center" style="width: 20px;">No</th>
		                    <th class="text-center" style="width: 100px;">NIK</th>
		                    <th class="d-none d-sm-table-cell">Nama</th>
		                    <th class="d-none d-sm-table-cell" style="width: 100px;">Periode</th>
		                    <th class="d-none d-sm-table-cell" style="width: 150px;">Keterangan</th>
		                    <th class="text-center" style="width: 80px;">Aksi</th>
		                </tr>
		            </thead>
		            <tfoot>
		                <tr>
		                    <th></th>
		                    <th>NIK</th>
		                    <th>Nama</th>
		                    <th class="filter-custom">
		                    	<select class="form-control">
		                    		<option value="">Select</option>
		                    		@foreach($periods as $period)
		                    		<option>{{ $period }}</option>
		                    		@endforeach
		                    	</select>
		                    </th>
		                    <th></th>
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
	$('.select2').select2({ placeholder: 'Select'});
	var table = Datatable.init({
		processing: true,
		serverSide: true,
		ajax: {
			url: '{!! route($route . 'table') !!}',
			method: 'POST'
		},
		columns: [
			{ render: function(data, type, row, meta) {  return meta.row + meta.settings._iDisplayStart + 1; }, searchable: false, class: 'text-center' },
			{ data: 'nik', class: 'text-center' },
			{ data: 'nama', },
			{ data: 'periode', class: 'text-center'},
			{ data: 'keterangan', class: ''},
			{ data: 'action', name: 'action', sortable: false, searchable: false },
		],
		order: [[0, 'asc']],
	});

</script>
@endsection