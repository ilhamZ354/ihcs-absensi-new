@extends('desktop.layouts.app')
@section('title', 'Master Kode Absensi')

@section('css')
<!-- page css -->
<link rel="stylesheet" href="{{ asset('/desktop/js/plugins/datatables/dataTables.bootstrap4.css') }}">
<style type="text/css">
	tfoot {
		display: table-header-group;
	}
	.tahunBulan, .pathMDB {
		display: none;
	}

	.btn-exec {
		height: 75px;
	}
</style>
@endsection

@section('content')

<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title"><i class="fa fa-lock fa-fw"></i> Group Permission</strong></h3>
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
		                    <th style="width: 50px;">No</th>
		                    <th class="text-center">Kode Group</th>
		                    <th class="text-center" style="width: 80px;">Aksi</th>
		                </tr>
		            </thead>
		            <tfoot>
		                <tr>
		                    <th></th>
		                    <th>Kode Group</th>
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
		{ data: 'kode_group' },
		{ data: 'action', name: 'action', sortable: false, searchable: false },
		],
		order: [[1, 'asc']],
	});
</script>
@endsection