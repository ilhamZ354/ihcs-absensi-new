@extends('desktop.layouts.app')
@section('title', 'Tambah Kode Absensi Baru')

@section('css')
<!-- page css -->
<link rel="stylesheet" href="{{ asset('desktop/js/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('desktop/js/plugins/jquery-auto-complete/jquery.auto-complete.min.css') }}">
<style type="text/css">
    #container_unit, #container_bagian, #container_personal {
        display: none;
    }
    .alert-danger {
        border: 2px solid red;
    }
    .alert-info {
        border: 2px solid;
    }
</style>
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

    	<form id="frmUpload" action="{{ route($route . 'store') }}" method="post" enctype="multipart/form-data">
    		{{ csrf_field() }}
            <div class="form-group row">
                <div class="offset-lg-1 col-lg-10">
                    <div class="alert alert-info">
                        <strong>Perhatian:</strong><br>
                        Pastikan data Cuti Tahunan dan Cuti Panjang sudah benar. Kesalahan pengisian data akan menyebabkan <b>UPLOAD GAGAL</b>. 
                    </div>
                </div>
            </div>
    		<div class="form-group row">
    			<label class="offset-lg-1 col-lg-2 col-form-label" for="tahun">Tahun</label>
    			<div class="col-lg-2">
                    <!-- <input type="text" class="form-control is-invalid" id="tahun" name="tahun" placeholder="Tahun" autocomplete="off" required=""> -->
    				<input type="text" class="form-control" id="tahuns" name="tahun" placeholder="Tahun" autocomplete="off" required="" readonly="" value="{{date('Y')-1}} sd {{date('Y')+1}}">
                    <!-- <div class="invalid-feedback">Invalid feedback</div> -->
    			</div>
    		</div>
    		<div class="form-group row">
    			<label class="offset-lg-1 col-lg-2 col-sm-12 col-form-label" for="status">File CTCP (.xls)</label>
    			<div class="col-lg-5 col-sm-12">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="example-file-input-custom" name="file_ctcp" data-toggle="custom-file-input" required="" accept=".xls">
                        <label class="custom-file-label" for="example-file-input-custom">Pilih file</label>
                    </div>
                </div>
    		</div>
    		<div class="form-group row">
                <div class="col-lg-9 ml-auto">
                    <button type="button" id="btn-upload" class="btn btn-alt-green min-width-125"><i class="fa fa-upload fa-fw"></i> Upload</button>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary"><i class="fa fa-arrow-left fa-fw"></i> Kembali</a>
                </div>
            </div>            
    	</form>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('desktop/js/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{ asset('desktop/js/plugins/jquery-auto-complete/jquery.auto-complete.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#unit, #bagian').select2({
            placeholder: '-PILIH-'
        });
    });

    $('#btn-upload').on('click', function(e) {
        swal({
          title: 'Yakin ingin mengupload?',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText: 'Batal',
          confirmButtonText: 'Ya, saya yakin!'
      }).then((result) => {
          if (result.value) {
            $('#frmUpload').submit();
        }
    });
  });

</script>
@endsection