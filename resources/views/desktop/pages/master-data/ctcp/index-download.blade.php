@extends('desktop.layouts.app')
@section('title', 'Download Template CTCP')

@section('css')
<!-- page css -->
@endsection

@section('content')

<div class="block">
  <div class="block-header block-header-default">
    <h3 class="block-title">Silahkan download untuk mengubah Kuota Cuti Tahunan dan Cuti Panjang</h3>
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

  <div> 
    <div class="alert alert-info">
      <div class="row">
        <div class="col-lg-1 text-right" style="padding-top: 5px;">
          <i class="si si-info" style="font-size: 30px;"></i>
        </div>
        <div class="col-lg-11">
          Berdasarkan data login Anda, kami mendeteksi Unit Kerja Anda: <br>
          @if(!is_null(nama_bagian(Auth::user()->getUser()->kode_bagian)))
          <strong>{{ nama_bagian(Auth::user()->getUser()->kode_bagian) }}</strong> - 
          @endif
          <strong>{{ nama_unit(Auth::user()->getUser()->kode_unit) }}</strong> <br>
          Data yang akan didownload adalah data karyawan pada Unit/Bagian yang sesuai Unit/Bagian Kerja Anda.
          <br>Silahkan ubah Kuota Cuti Tahunan dan Cuti Panjang Karyawan sesuai dengan sebenarnya, ketentuan yang berlaku.
          <br><small style="color: red;">Jika Unit Kerja Anda tidak sesuai, mohon segera menghubungi kami di Bagian SDM-Kantor Direksi</small>
        </div>
      </div>
    </div>   		
            <div class="form-group row">
           <label class="offset-lg-2 col-lg-2 col-form-label"></label>
           <div class="col-lg-3">
            <a href="{{ route($route . 'download', [$id]) }}" class="btn btn-alt-green btn-lg btn-block"><i class="fa fa-download fa-fw"></i> Download</a>
            <small class="" style="text-align: center;">Tahun Berlaku : {{date('Y')-1}} sd {{date('Y')+1}}</small>
<p></p>
        </div>
    </div>
  </div>
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