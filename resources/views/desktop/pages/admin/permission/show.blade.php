@extends('desktop.layouts.app')
@section('title', 'Master Kode Absensi')

@section('css')
<!-- page css -->
<link rel="stylesheet" href="{{ asset('/desktop/js/plugins/datatables/dataTables.bootstrap4.css') }}">
<style type="text/css">
	tfoot {
		display: table-header-group;
	}
	.tahunBulan, .pathMDB {
		display: none;
	}

	.btn-exec {
		height: 75px;
	}
</style>
@endsection

@section('content')

<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title"><i class="fa fa-lock fa-fw"></i> <b>{{ $title }}'s</b> Permission</strong></h3>
        <div class="block-options">
	        @allow('create', 'permission')
            <a href="{{ route($route . 'create', ['token' => encrypt($kode_group)]) }}" class="btn btn-sm btn-square btn-alt-green mr-5 mb-5" title="Tambah kode absen baru"><i class="fa fa-plus"></i> Tambah</a>
            @endallow
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle" title="Jadikan layar penuh"></button>
        </div>
    </div>
    <div class="block-content">
        <div class="block-content block-content-full">
    		<form method="post" action="{{ route($route . 'update', [encrypt($kode_group)]) }}">
    			{{ csrf_field() }}
    			{{ method_field('PUT') }}
        		<div class="table-responsive">
	        		<table class="table table-hover table-vcenter table-striped">
	        			<thead>
	        				<tr>
	        					<th class="text-center" style="width: 50px;">No.</th>
	        					<th>Slug</th>
	        					@foreach($permissions as $permission)
	        					<th class="text-center" style="width: 10%;">{{$permission}}</th>
	        					@endforeach
	        					<!-- <th class="text-center" style="width: 15%;">Create</th>
	        					<th class="text-center" style="width: 15%;">Edit</th>
	        					<th class="text-center" style="width: 15%;">Delete</th> -->
	        				</tr>
	        			</thead>
	        			<tbody>
	        				@if(!empty($rows))
	        				@foreach($rows as $row)
	        				<tr>
	        					<td class="text-center" scope="row">{{ $loop->index + 1}}.</td>
	        					<td>{{ strtoupper(str_replace('-', ' ', $row->slug)) }}</td>
	        					@foreach($permissions as $permission)
	        					<td class="text-center">
	        						<label class="css-control css-control-primary css-checkbox">
	        							<input type="checkbox" class="css-control-input" name="{{ $row->slug }}[{{$permission}}]" {{ $row->{$permission} ? 'checked' : '' }}>
	        							<span class="css-control-indicator"></span>
	        						</label>
	        					</td>
	        					@endforeach
	        					<!-- <td class="text-center">
	        						<label class="css-control css-control-primary css-checkbox">
	        							<input type="checkbox" class="css-control-input" name="{{ $row->slug }}[create]" {{ $row->create ? 'checked' : '' }}>
	        							<span class="css-control-indicator"></span>
	        						</label>
	        					</td>
	        					<td class="text-center">
	        						<label class="css-control css-control-primary css-checkbox">
	        							<input type="checkbox" class="css-control-input" name="{{ $row->slug }}[edit]" {{ $row->edit ? 'checked' : '' }}>
	        							<span class="css-control-indicator"></span>
	        						</label>
	        					</td>
	        					<td class="text-center">
	        						<label class="css-control css-control-primary css-checkbox">
	        							<input type="checkbox" class="css-control-input" name="{{ $row->slug }}[delete]" {{ $row->delete ? 'checked' : '' }}>
	        							<span class="css-control-indicator"></span>
	        						</label>
	        					</td> -->
	        				</tr>
	        				@endforeach
	        				@endif
			            </tbody>
			        </table>
	    		</div>
	    		<div class="row">
	    			<div class="col-md-6">
	    				<a href="{{ route($route . 'index') }}" class="btn btn-secondary" type="button"><i class="fa fa-arrow-left"></i> Kembali</a>
	    			</div>
	    			<div class="col-md-6 text-right">
	    				<button class="btn btn-alt-green"><i class="fa fa-check"></i> Simpan</button>
	    			</div>
	    		</div>
		    </form>
	    </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
</script>
@endsection
