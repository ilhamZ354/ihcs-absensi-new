@extends('desktop.layouts.app')
@section('title', 'Tambah Peserta N4 Attendance')

@section('css')
<!-- page css -->
<link rel="stylesheet" href="{{ asset('desktop/js/plugins/jquery-auto-complete/jquery.auto-complete.min.css') }}">
<style>
  .select2-container .select2-selection--single .select2-selection__rendered {
    padding-left: 12px;
  }
</style>
@endsection

@section('content')

<div class="block">
  <div class="block-header block-header-default">
    <h3 class="block-title">Isilah Data Peserta Aplikasi N4-Attendance</h3>
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
    <div class="form-group row mb-50">
     <label class="offset-lg-1 col-lg-2 col-form-label" for="unit_kerja">Cari Karyawan</label>
     <div class="col-lg-6">
      <input type="hidden" id="nik_pengganti" name="nik_pengganti">
      <input type="text" class="form-control" id="nama_peserta" name="nama_peserta" placeholder="Ketik NIK/Nama Lalu Pilih" value="{{old('nama_peserta')}}" required="">
    </div>
  </div>
  <div class="form-group row">
    <label class="offset-lg-1 col-lg-2 col-form-label" for="unit_kerja" title="Ganti Unit Kerja jika berbeda dengan Data Simkar">Unit Kerja/Lokasi Kantor</label>
    <div class="col-lg-6">
      <select class="form-control" id="unit_kerja" name="unit_kerja" style="width: 100% !important" required data-toggle="tooltip" title="Ganti Unit Kerja jika berbeda dengan Data Simkar">
        <option value="">Pilih</option>
        @foreach($units as $unit)
        <option value="{{ $unit->kodeunitbagian }}">{{ $unit->namaunitbagian }}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="form-group row">
    <label class="offset-lg-1 col-lg-2 col-form-label" for="bagian">Bagian</label>
    <div class="col-lg-6">
      <input type="text" class="form-control" id="bagian" readonly="" name="bagian" required="" value="{{ old('bagian') }}" data-toggle="tooltip" style="background-color: #fff !important;">
    </div>
  </div>

  <div class="form-group row">
    <label class="offset-lg-1 col-lg-2 col-form-label" for="bagian">Jabatan</label>
    <div class="col-lg-6">
      <input type="text" class="form-control" id="jabatan" readonly="" name="jabatan" required="" value="{{ old('jabatan') }}" data-toggle="tooltip" style="background-color: #fff !important;">
    </div>
  </div>

  <div class="form-group row">
    <label class="offset-lg-1 col-lg-2 col-form-label" for="sapaan">Sapaan/Nick Name</label>
    <div class="col-lg-3">
      <input type="text" class="form-control" id="sapaan" name="sapaan" maxlength="15" placeholder="Sapaan" required="" value="{{ old('sapaan') }}" data-toggle="tooltip" title="sapaan">
    </div>
  </div>
  <div class="form-group row">
    <label class="offset-lg-1 col-lg-2 col-form-label" for="ponsel">Nomor Ponsel</label>
    <div class="col-lg-3">
      <input type="text" class="form-control" id="ponsel" name="ponsel" maxlength="15" placeholder="Ponsel" required="" value="{{ old('ponsel') }}" data-toggle="tooltip" title="ponsel">
    </div>
  </div>

  <div class="form-group row">
    <label class="offset-lg-1 col-lg-2 col-form-label" for="satpam">Satpam</label>
    <div class="col-lg-5" data-toggle="tooltip" title="Tentukan status satpam">
      <div class="custom-control custom-radio custom-control-inline mb-5">
        <input class="custom-control-input" type="radio" name="satpam" id="status_satpam" value="1">
        <label class="custom-control-label" for="status_satpam">Ya</label>
      </div>
      <div class="custom-control custom-radio custom-control-inline mb-5">
        <input class="custom-control-input" type="radio" name="satpam" id="status_bukan_satpam" value="0" checked="" required="">
        <label class="custom-control-label" for="status_bukan_satpam">Bukan</label>
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
<script src="{{ asset('desktop/js/plugins/jquery-auto-complete/jquery.auto-complete.min.js') }}"></script>
<script type="text/javascript">
  $('#tanggal').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true
  });

  $('#unit_kerja, #nama_hari').select2();

  jQuery('#nama_peserta').autoComplete({
    minChars: 3,
    source: function(term, response) {
      var term = term.toLowerCase();           
      var unit = $('#unit').val();
      var bagian = $('#bagian').val();

      getListKaryawan(term, unit, bagian, null, function(res) {
        response(res);
      });
    },
    renderItem: function (item, search){
      search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
      var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
      return '<div class="autocomplete-suggestion" style="padding: 5px;" data-nik="'+item.nik+'" data-nik_sap="'+item.nik_sap+'" data-nama="'+item.nama+'" data-unit="'+item.unit+'" data-kodebagian="'+item.kodebagian+'">['+item.nik+'] - '+item.nama+'</div>';
    },
    onSelect: function(e, term, item){
      $('#nik_pengganti').val(item.data('nik'));
      $('#nama_peserta').val(item.data('nama'));
    }
  });

</script>
@endsection