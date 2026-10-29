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
        {{-- <div class="block-options">
            <a href="{{ route($route . 'create') }}" class="btn btn-sm btn-square btn-alt-green mr-5 mb-5" title="Tambah kode absen baru"><i class="fa fa-plus"></i> Tambah Normalisasi </a>
            <a href="{{ route($route . 'uploads') }}" class="btn btn-sm btn-square btn-outline-info mr-5 mb-5" title="Daftar Upload"><i class="fa fa-list"></i></a>
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle" title="Jadikan layar penuh"></button>
        </div> --}}
    </div>
    <div class="block-content">
        <div class="block-content block-content-full">
        	<div class="table-responsive">
		        <table class="table table-bordered table-striped table-vcenter" style="width: 100% !important;" id="datatable_ajax">
		            <thead>
		                <tr>
		                    <th class="text-center" style="width: 20px;">No</th>
		                    <th class="text-center">Periode</th>
		                    <th class="text-center" style="width: 120px;">Aksi</th>
		                </tr>
		            </thead>
		            <tbody>
		            	@if(count($periods) > 0)
		            	@foreach($periods as $period)
		            	<tr>
		            		<td>{{ $loop->index + 1 }}</td>
		            		<td>{{ $period }}</td>
		            		<td>
		            			<a onclick="destroy(event, '{{ route($route . 'uploads.delete', [$period]) }}', app = this, function() { window.location.href = '{{ route($route . 'index') }}'})" class="btn btn-danger btn-sm" href="{{ route($route . 'uploads.delete', [$period]) }}"><i class="fa fa-trash"></i> Hapus</a>
		            		</td>
		            	</tr>
		            	@endforeach
		            	@else
		            	<tr>
		            		<td colspan="3" class="text-center"> Data tidak ditemukan.</td>
		            	</tr>
		            	@endif
		            </tbody>
		        </table>
	    	</div>
	    </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
</script>
@endsection