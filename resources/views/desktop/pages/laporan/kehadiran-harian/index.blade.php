@extends('desktop.layouts.app')
@section('title', 'Laporan Kehadiran Harian')

@section('css')
<!-- page css -->
@endsection

@section('content')

<div class="block">
	<div class="block-header block-header-default">
		<h3 class="block-title">Laporan Kehadiran Harian</h3>
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
		                    <th class="text-center">NAMA</th>
		                    <th style="">Tahun</th>
		                    <th style="">Bulan</th>
		                	@for($i=1; $i<=31; $i++)
		                    <th style="">{{ $i }}</th>
		                	@endfor
		                </tr>
		            </thead>
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
		{ data: 'nama', class: 'text-center', sortable: false, searchable: false  },
		{ data: 'tahun', class: 'text-center', sortable: false, searchable: false  },
		{ data: 'bulan', class: 'text-center text-bulan', sortable: false, searchable: false  },
		@for($i=0; $i<31; $i++)
		{ data: '{{ $i }}', class: 'text-center', sortable: false, searchable: false  },
		@endfor
		],
		order: [[1, 'desc'],[2, 'desc']],
	});

</script>
@endsection