<!-- New Modal -->
<style>
  .autocomplete-suggestions {
    z-index: 1051;
    position: absolute;
    background-color: rgb(242, 242, 242);
    border: 1px solid #aaa;
    cursor: pointer;
  }
  .help-error {
    display: none;
  }
  .is-invalid span.help-error, .is-invalid .error{
    color: #ff3b00;
    font-size: .9rem;
    /*display: block !important;*/
  }
</style>
<div class="modal fade" id="n4attendanceBaru" tabindex="-1" role="dialog" aria-labelledby="n4attendanceBaruLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Peserta Baru</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="outline: none;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <form action="{{ route($route . 'store') }}" method="post" id="frmAddPeserta">
        {{ csrf_field() }}
        <div class="form-group">
          <label for="name" class="col-form-label error">Cari Nama/NIK:</label>
          <input type="hidden" id="nik" name="nik">
          <input type="text" class="form-control" id="name" name="name" placeholder="Ketik NIK/Nama Lalu Pilih pada daftar" value="{{old('name')}}" required="">
          <span class="help-error">Nama harus diisi</span>
        </div>
        <div class="form-group">
          <label for="nik_check" class="col-form-label error">NIK:</label>
          <input type="text" class="form-control" id="nik_check" name="nik_check" value="{{old('nik_check')}}" required="" readonly="true">
        </div>
        <div class="form-group">
          <label for="peserta-detail" class="col-form-label error">Detail:</label>
          <textarea class="form-control" id="peserta-detail" disabled="" rows="4">
          </textarea>
        </div>
        <div class="form-group">
          <label for="nick_name" class="col-form-label error">Panggilan:</label>
          <input type="text" id="nick_name" name="nick_name"  class="form-control" required placeholder="Panggilan Akrab">
        </div>
        <div class="form-group">
          <label for="employee_group_id" class="col-form-label error">Grup Karyawan:</label>
          <select class="form-control" id="employee_group_id" name="employee_group_id" title="Tentukan grup/lokasi kerja karyawan">
            <option value="">Pilih</option>
            @foreach($employeeGroups as $group)
            <option value="{{ $group->id }}">{{ $group->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label for="username" class="col-form-label error">Ponsel:</label>
          <input type="text" id="username" name="username"  class="form-control" required placeholder="Nomor Ponsel">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
</div>
<!-- Edit Modal -->
<div class="modal fade" id="n4attendanceEdit" tabindex="-1" role="dialog" aria-labelledby="n4attendanceEditLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="n4attendanceEditLabel">Ubah Data Peserta</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="outline: none;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <form action="{{ route($route . 'store') }}" method="post" id="frmEditPeserta">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <div class="form-group">
          <label for="name_edit" class="col-form-label error">Nama Karyawan:</label>
          <input type="hidden" id="data_id" name="id">
          <input type="text" class="form-control" id="name_edit" disabled="">
        </div>
        <div class="form-group">
          <label for="detail_edit" class="col-form-label error">Detail:</label>
          <textarea class="form-control" id="detail_edit" disabled="" rows="4">
          </textarea>
        </div>
        <div class="form-group">
          <label for="nick_name_edit" class="col-form-label error">Panggilan:</label>
          <input type="text" id="nick_name_edit" name="nick_name"  class="form-control" required placeholder="Panggilan Akrab">
        </div>
        <div class="form-group">
          <label for="employee_group_id_edit" class="col-form-label error">Grup Karyawan:</label>
          <select class="form-control" id="employee_group_id_edit" name="employee_group_id" title="Tentukan grup/lokasi kerja karyawan">
            <option value="">Pilih</option>
            @foreach($employeeGroups as $group)
            <option value="{{ $group->id }}">{{ $group->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label for="username_edit" class="col-form-label error">Ponsel:</label>
          <input type="text" id="username_edit" name="username"  class="form-control" required placeholder="Nomor Ponsel">
        </div>
        <div class="form-group">
          <label for="imei_edit" class="col-form-label error">IMEI/ID Smartphone:</label>
          <input type="text" id="imei_edit" name="imei"  class="form-control" placeholder="IMEI/ID">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
</div>

@section('subjs')
<script src="{{asset('desktop/js/plugins/jquery-auto-complete/jquery.auto-complete.min.js') }}"></script>
<script src="{{asset('desktop/js/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('desktop/js/plugins/jquery-validation/additional-methods.js')}}"></script>

<script type="text/javascript">
  jQuery('#name').autoComplete({
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
      return '<div class="autocomplete-suggestion" style="padding: 5px;" data-nik="'+item.nik+'" data-namaunit="'+item.namaunitbagian+'" data-nama="'+item.nama+'" data-namabagian="'+item.namabagian+'" data-namajabatan="'+item.namajabatan+'">['+item.nik+'] - '+item.nama+'</div>';
    },
    onSelect: function(e, term, item){
      $('#peserta-detail').val('NIK : '+item.data('nik')+'\nUNIT : '+item.data('namaunit')+'\nBAGIAN : '+item.data('namabagian')+'\n JABATAN : '+item.data('namajabatan'));
      $('#name').val(item.data('nama'));
      $('#nik').val(item.data('nik'));
      $('#nik_check').val(item.data('nik'));
    }
  });

  $(document).ready(function () {
     
     var myTimer = 0;

 $("#username").on('keydown', function() {
  // cancel any previously-set timer
    if (myTimer) {
       clearTimeout(myTimer);
    }

     myTimer = setTimeout(function() {
       
     }, 400); //delay time in milliseconds
  });

    $('#frmAddPeserta').validate({
      ignore: [],
      errorClass: 'invalid-feedback animated fadeInDown',
      errorElement: 'div',
      errorPlacement: function(error, e) {
        // jQuery(e).parents('.form-group > div').append(error);
        error.insertAfter(e);
      },

      highlight: function(e) {
        jQuery(e).closest('.form-group').addClass('is-invalid');
      },
      unhighlight : function(e) {
        jQuery(e).closest('.form-group').removeClass('is-invalid')
      },
      // success: function(e) {
      //   jQuery(e).closest('.form-group').removeClass('is-invalid');
      //   jQuery(e).remove();
      //   },

        rules: {
          'name':{
            required:true
          },
          'nik_check':{
            required:true,
            remote: {
              url: '{!! route($route . 'ceknik') !!}',
              type: "post"
            }
          },
          'nick_name':{
            required:true
          },
          'employee_group_id': {
            required: true
          },
          'username': {
            required: true,
            pattern: /^08\d{9,12}$/,
            remote: {
             url: '{!! route($route . 'cekponsel') !!}',
              type: "post"
            }
          },
         
        },
        messages: {
          'name': {
            required: 'Isi nama karyawan',
          }, 
          'nik_check': {
            required: 'NIK harus diisi',
            remote: 'NIK sudah terdaftar'
          }, 
          'nick_name': {
            required: 'Isi nama panggilan',
          }, 
          'employee_group_id': {
            required: 'Pilih Grup Karyawan',
          }, 
          'username': {
            required: 'Nomor Ponsel harus diisi',
            pattern: 'Format ponsel salah',
            remote: 'Ponsel telah terdaftar'
          }, 
        }
      });
  });

</script>
@endsection