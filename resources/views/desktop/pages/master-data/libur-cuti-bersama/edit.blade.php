@extends('desktop.layouts.app')
@section('title', 'Edit Jadwal Libur & Cuti Bersama')

@section('css')
<!-- page css -->
@endsection

@section('content')

<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Silahkan menyesuaikan data pada form berikut</h3>
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

    	<form action="{{ route($route . 'update', encrypt($row->id)) }}" method="post">
            {{ csrf_field() }}
    		{{ method_field('PUT') }}
            <div class="form-group row">
                <label class="offset-lg-1 col-lg-2 col-form-label" for="tanggal">Jenis Libur/Cuti</label>
                <div class="col-lg-3">
                    <select class="form-control" id="jenis" name="jenis" style="width: 100% !important" required data-toggle="tooltip" title="Pilih Jenis Libur">
                    <option value="">Pilih</option>
                    <option value="0" @if ($row->is_cb =="0") {{ 'selected' }} @endif>Libur Nasional</option>
                    <option value="1" @if ($row->is_cb =="1") {{ 'selected' }} @endif>Cuti Bersama</option>
                </select>
                </div>
            </div>       
    		<div class="form-group row">
                <label class="offset-lg-1 col-lg-2 col-form-label" for="tanggal">Tanggal</label>
                <div class="col-lg-3">
                    <input type="text" class="form-control" id="tanggal" name="tanggal" placeholder="Tanggal" required="" value="{{ $row->tgl }}">
                </div>
            </div>
            <div class="form-group row">
                <label class="offset-lg-1 col-lg-2 col-form-label" for="uraian">Uraian</label>
                <div class="col-lg-6">
                    <input type="text" class="form-control" id="uraian" name="uraian" placeholder="Uraian" required="" value="{{ $row->uraian }}">
                </div>
            </div>
    		<div class="form-group row">
    			<label class="offset-lg-1 col-lg-2 col-form-label" for="status">Status</label>
    			<div class="col-lg-5">
    				<div class="custom-control custom-radio custom-control-inline mb-5">
    					<input class="custom-control-input" type="radio" name="status" id="status_aktif" value="1" @if($row->status == 1) checked @endif>
    					<label class="custom-control-label" for="status_aktif">Aktif</label>
    				</div>
    				<div class="custom-control custom-radio custom-control-inline mb-5">
    					<input class="custom-control-input" type="radio" name="status" id="status_tidak_aktif" value="0" @if($row->status == 0) checked @endif>
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
</script>
@endsection