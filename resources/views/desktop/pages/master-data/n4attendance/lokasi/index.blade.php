@extends('desktop.layouts.app')
@section('title', 'Daftar Lokasi Absensi')

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
		<h3 class="block-title">Daftar Lokasi Absensi N4-Attendance</h3>
		<div class="block-options">
			@allow('create', 'kode-absensi')
			<a href="{{ route($route . 'create') }}" class="btn btn-sm btn-square btn-alt-green mr-5 mb-5" title="Tambah kode absen baru"><i class="fa fa-plus"></i> Tambah Data </a>
			@endallow
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
							<th style="width: 30px;" class="text-center">No</th>
							<th style="width: 80px;">Unit</th>
							<th style="width: 150px;" class="d-none d-sm-table-cell">Nama</th>
							<th class="d-none d-sm-table-cell">Koordinat</th>
							<th class="text-center" style="width: 120px;">Aksi</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th></th>
							<th>Unit</th>
							<th>Nama</th>
							<th>Koordinat</th>
							<th></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="theModal" class="modal fade text-center">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Peta Lokasi</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="outline: none;">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
			</div>
		</div>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	$('.kode-absensi-table tfoot th').each( function () {
		var title = $(this).text();
		if(title !== '')
			$(this).html('<input type="text" placeholder="Cari '+title+'" style="padding-left: 5px; width: 100%" />');
	}); 
	var table = Datatable.init({
		processing: true,
		serverSide: true,
		ajax: {
			url: '{!! route($route . 'table') !!}',
			method: 'POST'
		},
		columns: [
		{ data: 'id', render: function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; }, searchable: false, class: 'text-center' },
		{ data: 'unit', class: 'text-center' },
		{ data: 'name', },
		{ data: 'coordinates', },
		{ data: 'action', name: 'action', sortable: false, searchable: false },
		],
		order: [[0, 'asc']],
	});

	function showMap(e, url) {
		e.preventDefault();

		if(url){
			$('#theModal').modal('show').find('.modal-body').load(url);
		}
	}

</script>
@endsection