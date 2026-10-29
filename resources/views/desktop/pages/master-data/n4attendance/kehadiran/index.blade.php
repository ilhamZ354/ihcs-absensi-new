@extends('desktop.layouts.app')
@section('title', 'N4-Attendance Report')

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
		<h3 class="block-title">Kehadiran Karyawan Aplikasi N4-Attendance</h3>
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
							<th class="d-none d-sm-table-cell">NIK</th>
							<th class="d-none d-sm-table-cell">Nama</th>
							<th class="d-none d-sm-table-cell">Tgl</th>
							<th class="d-none d-sm-table-cell">Jam Masuk</th>
							<th class="d-none d-sm-table-cell">Koordinat Masuk</th>
							<th class="d-none d-sm-table-cell">Jam Pulang</th>
							<th class="d-none d-sm-table-cell">Koordinat Pulang</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th></th>
							<th>NIK</th>
							<th>Nama</th>
							<th data-datepicker="true">Tgl</th>
							<th></th>
							<th></th>
							<th></th>
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
	var table = Datatable.init({
		processing: true,
		serverSide: true,
		ajax: {
			url: '{!! route($route . 'table') !!}',
			method: 'POST'
		},
		columns: [
		{ render: function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; }, searchable: false, class: 'text-center' },
		{ data: 'nik', name: 'nik', class: 'text-center' },
		{ data: 'name', name: 'name', class: 'text-left'  },
		{ data: 'date', name: 'date', class: 'text-center'  },
		{ data: 'check_in', name: 'check_in', class: 'text-center'  },
		{ data: 'coordinate_in', name: 'coordinate_in', class: 'text-center'  },
		{ data: 'check_out', name: 'check_out', class: 'text-center'  },
		{ data: 'coordinate_out', name: 'coordinate_out', class: 'text-center'  },
		],
		order: [[3, 'desc'],[4, 'desc'],],
	});

	function showMap(e, url) {
		e.preventDefault();

		if(url){
			console.log(url);
			$('#theModal').modal('show').find('.modal-body').load(url);
		}
	}

</script>
@endsection