@extends('desktop.layouts.app')
@section('title', 'Tambah Timetable Kehadiran')

@section('css')
<!-- page css -->
<style>
    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-left: 12px;
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

    	<form action="{{ route($route . 'store') }}" method="post">
    		{{ csrf_field() }}
    		<div class="form-group row">
                <label class="offset-lg-1 col-lg-2 col-form-label" for="unit_kerja">Unit Kerja</label>
                <div class="col-lg-6">
                    <select class="form-control" id="unit_kerja" name="unit_kerja" style="width: 100% !important" required data-toggle="tooltip" title="Pilih Unit Kerja">
                        <option value="">Pilih</option>
                        @foreach($units as $unit)
                        <option value="{{ $unit->kodeunitbagian }}">{{ $unit->namaunitbagian }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
    			<label class="offset-lg-1 col-lg-2 col-form-label" for="nama_hari">Nama Hari <i>(English)</i></label>
    			<div class="col-lg-3">
    				<select class="form-control" id="nama_hari" name="nama_hari" style="width: 100% !important" required data-toggle="tooltip" title="Choose a day name">
                        <option value="">Pilih</option>
                        @foreach($days as $eng => $day)
                        <option value="{{ $eng }}">{{ $day }}</option>
                        @endforeach
                    </select>
    			</div>
    		</div>            
    		
            <div class="form-group row">
                <label class="offset-lg-1 col-lg-2 col-form-label" for="jam_masuk">Jam Masuk</label>
                <div class="col-lg-3">
                    <input type="text" class="form-control" id="jam_masuk" name="jam_masuk" placeholder="Jam Masuk" required="" value="{{ old('jam_masuk') }}" data-toggle="tooltip" title="Jam Masuk">
                </div>
            </div>
            <div class="form-group row">
                <label class="offset-lg-1 col-lg-2 col-form-label" for="jam_masuk_min">Jam Masuk Min</label>
                <div class="col-lg-3">
                    <input type="text" class="form-control" id="jam_masuk_min" name="jam_masuk_min" placeholder="Jam Masuk Min" required="" value="{{ old('jam_masuk_min') }}" data-toggle="tooltip" title="Jam Masuk Minimal">
                </div>
            </div>
            <div class="form-group row">
                <label class="offset-lg-1 col-lg-2 col-form-label" for="jam_masuk_max">Jam Masuk Max</label>
                <div class="col-lg-3">
                    <input type="text" class="form-control" id="jam_masuk_max" name="jam_masuk_max" placeholder="Jam Masuk Max" required="" value="{{ old('jam_masuk_max') }}" data-toggle="tooltip" title="Jam Masuk Maksimal">
                </div>
            </div>

            <div class="form-group row">
                <label class="offset-lg-1 col-lg-2 col-form-label" for="jam_pulang">Jam Pulang</label>
                <div class="col-lg-3">
                    <input type="text" class="form-control" id="jam_pulang" name="jam_pulang" placeholder="Jam Pulang" required="" value="{{ old('jam_pulang') }}" data-toggle="tooltip" title="Jam Pulang">
                </div>
            </div>
            <div class="form-group row">
                <label class="offset-lg-1 col-lg-2 col-form-label" for="jam_pulang_min">Jam Pulang Min</label>
                <div class="col-lg-3">
                    <input type="text" class="form-control" id="jam_pulang_min" name="jam_pulang_min" placeholder="Jam Pulang Min" required="" value="{{ old('jam_pulang_min') }}" data-toggle="tooltip" title="Jam Pulang Minimal">
                </div>
            </div>
            <div class="form-group row">
                <label class="offset-lg-1 col-lg-2 col-form-label" for="jam_pulang_max">Jam Pulang Max</label>
                <div class="col-lg-3">
                    <input type="text" class="form-control" id="jam_pulang_max" name="jam_pulang_max" placeholder="Jam Pulang Max" required="" value="{{ old('jam_pulang_max') }}" data-toggle="tooltip" title="Jam Pulang Maksimal">
                </div>
            </div>

    		<div class="form-group row">
                <label class="offset-lg-1 col-lg-2 col-form-label" for="satpam">Satpam</label>
                <div class="col-lg-5" data-toggle="tooltip" title="Tentukan status satpam">
                    <div class="custom-control custom-radio custom-control-inline mb-5">
                        <input class="custom-control-input" type="radio" name="satpam" id="status_satpam" value="1" required="">
                        <label class="custom-control-label" for="status_satpam">Ya</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline mb-5">
                        <input class="custom-control-input" type="radio" name="satpam" id="status_bukan_satpam" value="0" checked="">
                        <label class="custom-control-label" for="status_satpam">Bukan</label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
    			<label class="offset-lg-1 col-lg-2 col-form-label" for="status">Status</label>
    			<div class="col-lg-5" data-toggle="tooltip" title="Tentukan status">
    				<div class="custom-control custom-radio custom-control-inline mb-5">
    					<input class="custom-control-input" type="radio" name="status" id="status_aktif" value="1" checked required="">
    					<label class="custom-control-label" for="status_aktif">Aktif</label>
    				</div>
    				<div class="custom-control custom-radio custom-control-inline mb-5">
    					<input class="custom-control-input" type="radio" name="status" id="status_tidak_aktif" value="0" >
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
    $('#tanggal').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true
    });

    $('#unit_kerja, #nama_hari').select2();


</script>
@endsection