@extends('desktop.layouts.app')
@section('title', 'Daftar Mock Location')

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
		<h3 class="block-title">Daftar Peserta yang mencoba mock location</h3>
		<div class="block-options">
			<button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle" title="Jadikan layar penuh"></button>
		</div>
	</div>
	<div class="block-content">
		<div class="block-content block-content-full">
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-vcenter" style="width: 100% !important;" id="datatable_ajax">
					<thead>
						<tr>
							<th style="width: 30px;" class="text-center">No</th>
							<th class="d-none d-sm-table-cell">Nama</th>
							<th >Waktu</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th></th>
							<th>Nama</th>
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
	var table = Datatable.init({
		processing: true,
		serverSide: true,
		ajax: {
			url: '{!! route($route . 'table') !!}',
			method: 'POST'
		},
		columns: [
		{ render: function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; }, searchable: false, class: 'text-center' },
		{ data: 'name', name: 'employees.name', class: 'text-left' },
		{ data: 'created_at', name : 'mock_locations.created_at'},
		],
		order: [[2, 'desc']],
	});

</script>
@endsection