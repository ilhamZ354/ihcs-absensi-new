@extends('desktop.layouts.app')
@section('title', 'Tambah Akses Backdate Baru')

@section('css')
<!-- page css -->
<link rel="stylesheet" href="{{asset('desktop/js/plugins/jquery-datetimepicker/jquery.datetimepicker.min.css')}}">
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
                <div class="col-lg-3">
                    <select class="form-control" id="unit_kerja" name="unit_kerja" required="">
                        <option value="">-Pilih-</option>
                        @foreach($units as $unit)
                        <option value="{{ $unit->kodeunitbagian }}" {{ old('unit_kerja') == $unit->kodeunitbagian ? 'selected' : ''}}>{{ $unit->namaunitbagian }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="offset-lg-1 col-lg-2 col-form-label" for="bagian">Bagian</label>
                <div class="col-lg-4">
                    <select class="form-control" id="bagian" name="bagian" disabled="">
                        <option value="">-Pilih-</option>
                        @foreach($bagians as $bagian)
                        <option value="{{ $bagian->kodebagian }}" {{ old('bagian') == $bagian->kodebagian ? 'selected' : ''}}>{{ $bagian->namabagian }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="offset-lg-1 col-lg-2 col-form-label" for="tgl_ubah_mulai">Tgl Ubah</label>
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-lg-6">
                            <input type="text" class="form-control" id="tgl_ubah_mulai" name="tgl_ubah_mulai" placeholder="Mulai" required="" value="{{ old('tgl_ubah_mulai') }}">
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" id="tgl_ubah_selesai" name="tgl_ubah_selesai" placeholder="Selesai" required="" value="{{ old('tgl_ubah_selesai') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="offset-lg-1 col-lg-2 col-form-label" for="dibuka_dari">Dibuka</label>
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-lg-6">
                            <input type="text" class="form-control" id="dibuka_dari" name="dibuka_dari" placeholder="Dari" required="" value="{{ old('dibuka_dari') }}">
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" id="dibuka_sampai" name="dibuka_sampai" placeholder="Sampai" required="" value="{{ old('dibuka_sampai') }}">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group row">
                <label class="offset-lg-1 col-lg-2 col-form-label" for="status">Status</label>
                <div class="col-lg-5">
                    <div class="custom-control custom-radio custom-control-inline mb-5">
                        <input class="custom-control-input" type="radio" name="status" id="status_aktif" value="1" {{ old('status') == "1" || is_null(old('status')) ? 'checked' : '' }} required="">
                        <label class="custom-control-label" for="status_aktif">Aktif</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline mb-5">
                        <input class="custom-control-input" type="radio" name="status" id="status_tidak_aktif" value="0" {{ old('status') == "0"  ? 'checked' : '' }} >
                        <label class="custom-control-label" for="status_tidak_aktif">Tidak Aktif</label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="offset-lg-1 col-lg-2 col-form-label" for="keterangan">Keterangan</label>
                <div class="col-lg-4">
                    <textarea class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan" rows="3">{{ old('keterangan') }}</textarea>
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
<script src="{{asset('desktop/js/plugins/jquery-datetimepicker/jquery.datetimepicker.full.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        jQuery('#tgl_ubah_mulai').datetimepicker({
            format:'Y-m-d',
            onShow:function( ct ){
                this.setOptions({
                    maxDate:jQuery('#tgl_ubah_selesai').val()?jQuery('#tgl_ubah_selesai').val():false
                })
            },
            timepicker:false
        });
        jQuery('#tgl_ubah_selesai').datetimepicker({
            format:'Y-m-d',
            onShow:function( ct ){
                this.setOptions({
                    minDate:jQuery('#tgl_ubah_mulai').val()?jQuery('#tgl_ubah_mulai').val():false
                })
            },
            timepicker:false
        });

        jQuery('#dibuka_dari').datetimepicker({
            format:'Y-m-d H:i',
            onShow:function( ct ){
                this.setOptions({
                    maxDate:jQuery('#dibuka_sampai').val()?jQuery('#dibuka_sampai').val():false
                })
            }
        });
        jQuery('#dibuka_sampai').datetimepicker({
            format:'Y-m-d H:i',
            onShow:function( ct ){
                this.setOptions({
                    minDate:jQuery('#dibuka_dari').val()?jQuery('#dibuka_dari').val():false
                })
            }
        });

        $('select#unit_kerja').on('change', function(e) {
            e.preventDefault();
            var that = $(this);
            var bagian = $("select#bagian");
            if(that.val() == '00') {
                bagian.removeAttr('disabled');
            } else {
                bagian.val('').attr('disabled', '');            
            }
        });
    });
</script>
@endsection