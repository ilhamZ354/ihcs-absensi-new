@extends('desktop.layouts.app')
@section('title', 'Tambah Permission')

@section('css')
<!-- page css -->
@endsection

@section('content')

<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Silahkan mengisi data pada form berikut</h3>
        <div class="block-options">
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle" title="Jadikan layar penuh"></button>
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

    	<form action="{{ route($route . 'store', ['token' => $token]) }}" method="post">
    		{{ csrf_field() }}
    		<div class="form-group row">
    			<label class="offset-lg-1 col-lg-2 col-form-label" for="slug">Slug</label>
    			<div class="col-lg-3">
    				<input type="text" class="form-control" id="slug" name="slug" placeholder="Slug" required="">
    			</div>
    		</div>
    		<div class="form-group row">
    			<div class="col-lg-9 ml-auto">
    				<button type="submit" class="btn btn-alt-green min-width-125"><i class="fa fa-save fa-fw"></i> Simpan</button>
    				<a href="{{ route($route . 'show', [$token]) }}" class="btn btn-outline-secondary"><i class="fa fa-arrow-left fa-fw"></i> Kembali</a>
    			</div>
    		</div>
    	</form>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $('#tanggal').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true
    });
</script>
@endsection