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
</style>
@endsection

@section('content')

<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Silahkan mengisi data pada form berikut</h3>
        <div class="block-options">
            <a href="{{ route($route . 'uploaded-files') }}" class="btn btn-sm btn-square btn-outline-info mr-5 mb-5" title="Daftar file yg telah diupload"><i class="fa fa-list"></i> Uploaded Files </a>
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

    	<form id="frmUpload" action="{{ route($route . 'saveupload') }}" method="post" enctype="multipart/form-data">
    		{{ csrf_field() }}
    		<div class="form-group row">
    			<label class="offset-lg-1 col-lg-2 col-form-label" for="tahun">Tahun</label>
    			<div class="col-lg-2">
                    <!-- <input type="text" class="form-control is-invalid" id="tahun" name="tahun" placeholder="Tahun" autocomplete="off" required=""> -->
    				<input type="text" class="form-control" id="tahun" name="tahun" placeholder="Tahun" autocomplete="off" required="" readonly="">
                    <!-- <div class="invalid-feedback">Invalid feedback</div> -->
    			</div>
    		</div>
    		<div class="form-group row">
    			<label class="offset-lg-1 col-lg-2 col-form-label" for="nama">Bulan</label>
    			<div class="col-lg-2">
    				<input type="text" class="form-control" id="bulan" name="bulan" placeholder="Bulan" required="" readonly="">
    			</div>
    		</div>
    		<div class="form-group row">
    			<label class="offset-lg-1 col-lg-2 col-sm-12 col-form-label" for="status">File Absensi</label>
    			<div class="col-lg-5 col-sm-12">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="example-file-input-custom" name="file_absensi" data-toggle="custom-file-input" required="" accept=".csv">
                        <label class="custom-file-label" for="example-file-input-custom">Pilih file</label>
                    </div>
                </div>
    		</div>
            <div class="form-group row">
                <label class="offset-lg-1 col-lg-2 col-form-label" for="jenis_data">Jenis Data</label>
                <div class="col-lg-5 col-sm-12">
                    <div class="custom-control custom-radio custom-control-inline mb-5">
                        <input class="custom-control-input" type="radio" name="jenis_data" id="jenis_data_personal" value="personal">
                        <label class="custom-control-label" for="jenis_data_personal">Personal</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline mb-5">
                        <input class="custom-control-input" type="radio" name="jenis_data" id="jenis_data_bagian" value="bagian">
                        <label class="custom-control-label" for="jenis_data_bagian">Bagian</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline mb-5">
                        <input class="custom-control-input" type="radio" name="jenis_data" id="jenis_data_unit" value="unit">
                        <label class="custom-control-label" for="jenis_data_unit">Unit</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline mb-5">
                        <input class="custom-control-input" type="radio" name="jenis_data" id="jenis_data_semua" value="semua">
                        <label class="custom-control-label" for="jenis_data_semua">Semua</label>
                    </div>                    
                </div>
            </div>
            <div class="form-group row" id="container_unit">
                <label class="offset-lg-1 col-lg-2 col-form-label" for="unit">Unit</label>
                <div class="col-lg-5">
                    <select class="form-control" id="unit" name="unit" placeholder="Unit" style="width: 100%">
                        <option value="">-Pilih-</option>
                        @foreach($units as $unit)
                        <option value="{{ $unit->kodeunitbagian }}"> {{ $unit->namaunitbagian }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row" id="container_bagian">
                <label class="offset-lg-1 col-lg-2 col-form-label" for="bagian">Bagian</label>
                <div class="col-lg-5">
                    <select class="form-control" id="bagian" name="bagian" placeholder="Bagian" style="width: 100%" disabled="">
                        <option value="">-Pilih-</option>                        
                    </select>
                </div>
            </div>
            <div class="form-group row" id="container_personal">
                <label class="offset-lg-1 col-lg-2 col-form-label" for="nama_peserta">Nama Peserta</label>
                <div class="col-lg-5">
                    <input type="hidden" id="nik_peserta" name="nik_peserta">
                    <input type="text" class="form-control" id="nama_peserta" name="nama_peserta" placeholder="Nama Peserta">
                </div>
            </div>
            @if(false)
            <div class="form-group row">
                <label class="offset-lg-1 col-lg-2 col-form-label"></label>
                <div class="col-lg-8">
                    <div class="alert alert-danger">Sistem mendeteksi data absensi yang sama telah ada dalam database. <br>Data lama harus ditimpa jika hendak melanjutkan proses upload.
                    </div>
                    <label class="css-control css-control-danger css-checkbox" for="val-terms" style="padding-top: 0 !important;">
                        <input type="checkbox" class="css-control-input" id="val-terms" name="timpa_data_absensi_lama" value="1" required="">
                        <span class="css-control-indicator"></span> <strong>Timpa data absensi lama</strong>
                    </label>
                </div>
            </div>
            @endif
    		<div class="form-group row">
    			<div class="col-lg-9 ml-auto">
    				<button type="button" id="btn-upload" class="btn btn-alt-green min-width-125"><i class="fa fa-upload fa-fw"></i> Upload & Proses</button>
    				<a href="{{ route($route . 'index') }}" class="btn btn-outline-secondary"><i class="fa fa-arrow-left fa-fw"></i> Kembali</a>
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


    $("#tahun").datepicker({
        format: "yyyy",
        viewMode: "years", 
        minViewMode: "years",
        autoclose: true,
        startDate: '-1y',
        // endDate: '+1y',
    });
    $("#bulan").datepicker( {
        autoclose: true,
        format: "MM",
        viewMode: "months", 
        minViewMode: "months",
        language: 'ID'
    });

    $('input[type=radio][name=jenis_data]').change(function() {
        switch(this.value) {
           case 'semua':
            //do nothing
            $('#container_unit, #container_bagian, #container_personal').css('display', 'none');
           break;

           case 'unit':
            //show unit only
            $('#container_bagian, #container_personal').css('display', 'none');
            $('#unit').attr('required', true);
            $('#bagian').removeAttr('required');
            $('#container_unit').css('display', 'flex');
           break;

           case 'bagian':
            //show unit and bagian
            $('#container_personal').css('display', 'none');
            $('#unit').attr('required', true);
            $('#bagian').attr('required', true);
            $('#container_unit, #container_bagian').css('display', 'flex');
           break;

           case 'personal':
            //show all
            $('#unit').removeAttr('required');
            $('#bagian').removeAttr('required');
            $('#nama_peserta').attr('required', true);
            $('#container_unit, #container_bagian, #container_personal').css('display', 'flex');
           break;
       }
    });

    $('#unit').on('change.select2', function(e)  {
        if(this.value !== undefined) {
            //search bagian based selected unit
            var unit = this.value;

            getListBagian(unit, function(res) {
                $('#bagian').empty();
                $('#bagian').append('<option value=""></option>');
                $.each(res, function(i, d) {
                    $('#bagian').append('<option value="' + d.kodebagian + '">' + d.namabagian + '</option>');
                });
            })
            //enable bagian
            $('#bagian').removeAttr('disabled');
        }
    });

    // Init autocomplete functionality
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
                // console.log(item, search);
                search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
                var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
                return '<div class="autocomplete-suggestion" style="padding: 5px;" data-nik="'+item.nik+'" data-nik_sap="'+item.nik_sap+'" data-nama="'+item.nama+'" data-unit="'+item.unit+'" data-kodebagian="'+item.kodebagian+'">['+item.nik+'] - '+item.nama+'</div>';
            },
            onSelect: function(e, term, item){
                // alert('Item "'+item.data('langname')+' ('+item.data('lang')+')" selected by '+(e.type == 'keydown' ? 'pressing enter' : 'mouse click')+'.');
                $('#nik_peserta').val(item.data('nik'));
                $('#nama_peserta').val(item.data('nama'));
            }
        });


        $('#tahun').change(function(e) {

        });

        function checkDuplicateData(tahun, bulan, jenis_data, unit = null, bagian = null, nik = null) {
            $.ajax({
                url: '{{ route($route  . 'check-duplicate') }}',
                method: 'POST',
                data: { 
                    tahun: tahun, 
                    bulan: bulan,
                    jenis_data: jenis_data, 
                    unit: unit, 
                    bagian: bagian,
                    nik: nik, 
                },
                success: function(res) {
                    if(res) {

                    }
                }
            });
        }

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