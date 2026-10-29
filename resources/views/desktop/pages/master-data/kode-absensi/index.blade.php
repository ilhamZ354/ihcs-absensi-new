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
		                    <th class="text-center">No</th>
		                    <th style="width: 80px;">KODE</th>
		                    <th class="d-none d-sm-table-cell">Nama</th>
		                    <th class="d-none d-sm-table-cell">Status</th>
		                    <th class="text-center" style="width: 80px;">Aksi</th>
		                </tr>
		            </thead>
		            <tfoot>
		                <tr>
		                    <th></th>
		                    <th>Kode</th>
		                    <th>Nama</th>
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
		{ data: 'rownum', name: 'rownum', searchable: false, class: 'text-center' },
		{ data: 'kode', class: 'text-center' },
		{ data: 'nama', },
		{ data: 'status', class: 'text-center'},
		{ data: 'action', name: 'action', sortable: false, searchable: false },
		],
		order: [[0, 'asc']],
	});

</script>
@endsection