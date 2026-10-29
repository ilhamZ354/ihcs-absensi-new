@extends('desktop.layouts.app')
@section('title', 'Edit Lokasi Absensi')

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

        <form action="{{ route($route . 'update', encrypt($location->id)) }}" method="post">
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            <div class="form-group row">
                <label class="offset-lg-1 col-lg-2 col-form-label" for="unit">Unit</label>
                <div class="col-lg-3">
                    <select class="form-control select2" id="unit" name="unit">
                        <option value="">-Select-</option>
                        @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ !empty(old('unit')) ? (old('unit') == $unit->id ? 'selected' : '' ) : ($location->unit == $unit->id ? 'selected' : '' ) }}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="offset-lg-1 col-lg-2 col-form-label" for="name">Name</label>
                <div class="col-lg-5">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Location Name" value="{{ !empty(old('name')) ? old('name') : $location->name }}">
                </div>
            </div>
            
            <div class="form-group row">
                <label class="offset-lg-1 col-lg-2 col-form-label" for="coordinates">Coordinates</label>
                <div class="col-lg-5">
                    <textarea type="text" class="form-control" id="coordinates" name="coordinates" placeholder="Location Coordinates (Json)" style="min-height: 300px; max-height: 500px;">{{ !empty(old('coordinates')) ? old('coordinates') : $location->coordinates }}</textarea>
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
    $('#unit').select2({
        placeholder: '-PILIH-'
    });


    var koordinat = $('#coordinates').val();
    var str = JSON.stringify(JSON.parse(koordinat), undefined, 4);
    
    // display pretty printed object in text area:
    document.getElementById('coordinates').innerHTML = str;
</script>
@endsection