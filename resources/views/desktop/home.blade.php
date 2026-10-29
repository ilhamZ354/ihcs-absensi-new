@extends('desktop.layouts.app')
@section('title', 'Home')
@section('content')

<div class="block">    
    <div class="block-header block-header-default">
        <h3 class="block-title">Beranda</h3>
        <div class="block-options">
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="pinned_toggle">
                <i class="si si-pin"></i>
            </button>
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                <i class="si si-refresh"></i>
            </button>
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
        </div>
    </div>
    <div class="block-content">
        

        <p>Selamat Datang di Aplikasi Absensi PTPN IV</p>
    </div>
</div>
@endsection
