@extends('desktop.layouts.app')
@section('title', 'Tambah Normalisasi Insentif')

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
 
    	<form action="{{ route($route . 'store') }}" method="post" enctype="multipart/form-data">
    		{{ csrf_field() }}
            <div class="form-group row">
                <label class="offset-lg-1 col-lg-2 col-form-label" for="tahun">Bulan/Tahun</label>
                <div class="col-lg-7">
                    <div class="row">
                        <div class="col-lg-3">
                            <input type="text" class="form-control" id="tahun" data-toggle="tahunpicker" name="tahun" placeholder="Tahun" required="" readonly="" value="{{ old('tahun') }}">
                        </div>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" id="bulan" data-toggle="bulanpicker" name="bulan" placeholder="Bulan" required="" readonly="" value="{{ old('bulan') }}">
                        </div>
                        <div class="col-lg-3" id="btn-export-all-container"></div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="offset-lg-1 col-lg-2 col-sm-12 col-form-label" for="status">File</label>
                <div class="col-lg-5 col-sm-12">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="example-file-input-custom" name="file" data-toggle="custom-file-input" required="" accept=".xlsx">
                        <label class="custom-file-label" for="example-file-input-custom">Pilih file</label>
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