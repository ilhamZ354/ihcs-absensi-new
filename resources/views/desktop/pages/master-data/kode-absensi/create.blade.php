@extends('desktop.layouts.app')
@section('title', 'Tambah Kode Absensi Baru')

@section('css')
<!-- page css -->
@endsection

@section('content')

<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Silahkan mengisi data pada form berikut</h3>
        <div class="block-options">
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle" title="Jadikan layar penuh"></button>
            <!-- <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button> -->
        </div>
    </div>
    <div class="block-content">
    	@if ($errors->any())
		    <div class="alert alert-danger">
		        <ul>
		            @foreach ($errors->all() as $error)
		                <li>{{ $error }}</li>
		            @endforeach
		        </ul>
		    </div>
		@endif

    	<form action="{{ route($route . 'store') }}" method="post">
    		{{ csrf_field() }}
    		<div class="form-group row">
    			<label class="offset-lg-1 col-lg-2 col-form-label" for="kode">KODE</label>
    			<div class="col-lg-2">
    				<input type="text" class="form-control" id="kode" name="kode" placeholder="Kode">
    			</div>
    		</div>
    		<div class="form-group row">
    			<label class="offset-lg-1 col-lg-2 col-form-label" for="nama">Nama</label>
    			<div class="col-lg-5">
    				<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" value="{{ old('nama') }}">
    			</div>
    		</div>
    		<div class="form-group row">
    			<label class="offset-lg-1 col-lg-2 col-form-label" for="status">Status</label>
    			<div class="col-lg-5">
    				<div class="custom-control custom-radio custom-control-inline mb-5">
    					<input class="custom-control-input" type="radio" name="status" id="status_aktif" value="1" checked>
    					<label class="custom-control-label" for="status_aktif">Aktif</label>
    				</div>
    				<div class="custom-control custom-radio custom-control-inline mb-5">
    					<input class="custom-control-input" type="radio" name="status" id="status_tidak_aktif" value="0">
    					<label class="custom-control-label" for="status_tidak_aktif">Tidak Aktif</label>
    				</div>
    			</div>
    		</div>
    		<div class="form-group row">
    			<div class="col-lg-9 ml-auto">
    				<button type="submit" class="btn btn-alt-green min-width-125"><i class="fa fa-save fa-fw"></i> Simpan</button>
    				<a href="{{ route($route . 'index') }}" class="btn btn-outline-secondary"><i class="fa fa-arrow-left fa-fw"></i> Kembali</a>
    			</div>
    		</div>
    	</form>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">

</script>
@endsection