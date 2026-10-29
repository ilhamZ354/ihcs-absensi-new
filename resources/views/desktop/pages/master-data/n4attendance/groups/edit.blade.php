@extends('desktop.layouts.app')
@section('title', 'Edit Group')

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

        <form action="{{ route($route . 'update', encrypt($employeeGroup->id)) }}" method="post">
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            <div class="form-group row">
                <label class="offset-lg-1 col-lg-2 col-form-label" for="location">Location</label>
                <div class="col-lg-2">
                    <select class="form-control" id="location" name="location">
                        <option value="">-Select-</option>
                        @foreach($locations as $location)
                        <option value="{{ $location->id }}" {{ $employeeGroup->location_id == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="offset-lg-1 col-lg-2 col-form-label" for="name">Name</label>
                <div class="col-lg-5">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Group Name" value="{{ !empty(old('name')) ? old('name') : $employeeGroup->name }}">
                </div>
            </div>
            <div class="form-group row">
                <label class="offset-lg-1 col-lg-2 col-form-label" for="is_security">Is Security</label>
                <div class="col-lg-5">
                    <div class="custom-control custom-radio custom-control-inline mb-5">
                        <input class="custom-control-input" type="radio" name="is_security" id="is_security_aktif" value="1" {{ $employeeGroup->is_security ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_security_aktif">Ya, Security</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline mb-5">
                        <input class="custom-control-input" type="radio" name="is_security" id="is_security_tidak_aktif" value="0" {{ !$employeeGroup->is_security ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_security_tidak_aktif">Bukan</label>
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