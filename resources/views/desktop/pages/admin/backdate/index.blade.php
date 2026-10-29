@extends('desktop.layouts.app')
@section('title', 'Akses Backdate')

@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Daftar Akses Backdate</h3>
        <a href="{{ route($route . 'create') }}" class="btn btn-sm btn-square btn-alt-green mr-5 mb-5" title="Tambah Akses Backdate"><i class="fa fa-plus"></i> Tambah Akses Backdate</a>
    </div>
    <div class="block-content block-content-full">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-vcenter" id="datatable_ajax_backdate" style="width: 100%;">
                <thead>
                    <tr>
                        <th class="text-center"></th>
                        <th>Nama Unit/Bagian</th>
                        <th class="d-none d-sm-table-cell text-center" style="width: 5%;">Status</th>
                        <th class="d-none d-sm-table-cell">Ubah Mulai</th>
                        <th class="d-none d-sm-table-cell">Ubah Selesai</th>
                        <th class="d-none d-sm-table-cell">Dibuka Dari</th>
                        <th class="d-none d-sm-table-cell">Dibuka Sampai</th>
                        <th class="d-none d-sm-table-cell">Dibuka Oleh</th>
                        <th class="text-center" style="width: 5%;">Aksi</th>
                    </tr>
                </thead>

            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('desktop/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('desktop/js/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function(e) {
       var t =  jQuery('#datatable_ajax_backdate').DataTable({
            processing: true,
            serverSide: false,

            ajax: {
                url: '{!! route($route . 'table') !!}',
                method: 'POST'
            },
            columns: [
            { data: '', render: function(data, type, row, meta) {  return meta.row + meta.settings._iDisplayStart + 1; }, searchable: false, orderable: false, class: 'text-center' },
            { data: 'nama', sortable: true, searchable: true},
            { data: 'status', name: 'status', class: 'text-center', searchable: false },
            { data: 'tgl_ubah_mulai', class: 'd-none d-sm-table-cell', searchable: false },
            { data: 'tgl_ubah_selesai', class: 'd-none d-sm-table-cell', searchable: false },
            { data: 'dibuka_dari', class: 'd-none d-sm-table-cell', searchable: false },
            { data: 'dibuka_sampai', class: 'd-none d-sm-table-cell', searchable: false },
            { data: 'created_by', class: 'd-none d-sm-table-cell', sortable: false },
            { data: 'action', name: 'action', class: 'text-center', sortable: false, searchable: false },
            ],
            order: [[6, 'desc']],
        });

       t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
    });

    function deactivate (e, route) {
        e.preventDefault();
        swal({
            title: "Yakin ingin menonaktifkan?",
            text: "Data tidak aktif lagi",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "Ya, saya yakin!",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: route,
                    method: 'POST',
                    data: { _method: 'PUT', _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if(response != 1) {
                            swal('Gagal!', response.message, 'error');
                        } else {
                            swal('Sukses!', 'Data berhasil dinonaktifkan', 'success');
                            if ($.fn.DataTable.isDataTable("#datatable_ajax_backdate")) {
                                $('#datatable_ajax_backdate').DataTable().ajax.reload();
                            }
                        }
                    }
                });
            }
        });
    }
</script>
@endsection
