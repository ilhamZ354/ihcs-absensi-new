@extends('desktop.layouts.app')
@section('title', 'Laporan BKM')

@section('css')
<!-- page css -->
<link href="https://fonts.googleapis.com/css?family=Abel" rel="stylesheet"> 
<style>
	.table th, .table td {
		padding: 0px;
	}
	#tbl-thin {
		font-family: 'Abel', sans-serif;
	}
	#tbl-thin tr th{
		vertical-align: middle;
		text-align: center;
	}
	#tbl-thin tr td{
		vertical-align: middle;
		padding: 2px 5px;
		text-align: center;
	}
	.name_space {
		width: 200px;
	}
</style>
@endsection

@section('content')

<div class="block">
	<div class="block-header block-header-default">
		<h3 class="block-title">Generate Laporan Asistensi GPU</h3>
		<div class="block-options">            
			<button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle" title="Jadikan layar penuh"></button>
		</div>
	</div>
	<div class="block-content">
		<div class="row">
			<div class="block-content block-content-full">
				<div id="accordion" role="tablist" aria-multiselectable="true">
					<div class="block block-bordered block-rounded mb-2">
						<div class="block-header bg-info-light" role="tab" id="accordion_filter">
							<a class="font-w600" data-toggle="collapse" data-parent="#accordion" href="#accordion_q1" aria-expanded="true" aria-controls="accordion_q1" style="width: 100%; height: 100%;"><i class="fa fa-search fa-fw"></i> TENTUKAN PERIODE Laporan</a>
						</div>
						<div id="accordion_q1" class="collapse show" role="tabpanel" aria-labelledby="accordion_filter" data-parent="#accordion">
							<div class="block-content">
								<form action="{{ route($route . 'process') }}" method="post">
									{{ csrf_field() }}
									<div class="form-group row">
										<label class="offset-lg-1 col-lg-2 col-form-label" for="tahun">Tahun/Bulan</label>
										<div class="col-lg-7">
											<div class="row">
												<div class="col-lg-3">
													<input type="text" class="form-control" id="tahun" data-toggle="tahunpicker" name="tahun" placeholder="Tahun" readonly="" required="" value="">
												</div>
												<div class="col-lg-4">
													<input type="text" class="form-control" id="bulan" data-toggle="bulanpicker" name="bulan" placeholder="Bulan" readonly="" required="" value="">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<div class="offset-lg-3 col-lg-8">
											<button type="submit" class="btn btn-alt-primary"><i class="fa fa-download"></i> Download Laporan</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>        	
			</div>
		</div>
	</div>
</div>
@endsection

@section('js')
<script>
	//
</script>
@endsection