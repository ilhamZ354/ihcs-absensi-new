<!doctype html>

<!--[if lte IE 9]>     <html lang="en" class="no-focus lt-ie10 lt-ie10-msg"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="{{ app()->getLocale() }}"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

    <title>@yield('title') :: {{ config('app.name', 'E-Absensi') }}</title>

    <meta name="description" content="Aplikasi Absensi PT Perkebunan Nusantara IV">
    <meta name="author" content="harefa &amp; Yusren">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#137b67" />
    <meta name="msapplication-navbutton-color" content="#137b67" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="#137b67" />
    <!-- Open Graph Meta -->
    <meta property="og:title" content="N4-Absensi, Aplikasi Absensi PTPN IV">
    <meta property="og:site_name" content="N4 Absensi">
    <meta property="og:description" content="">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Icons -->
    <link rel="shortcut icon" href="{{asset('desktop/media/ptpn4-logo/favicon.png')}}">

    <!-- Stylesheets -->

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/desktop/js/plugins/datatables/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" id="css-main" href="{{asset('desktop/css/codebase.css')}}">
    <link rel="stylesheet" href="{{ asset('desktop/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.standalone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('desktop/js/plugins/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('desktop/js/plugins/select2/css/select2.css') }}">
    <link rel="stylesheet" href="{{asset('desktop/js/plugins/slick/slick.css')}}">
    <link rel="stylesheet" href="{{asset('desktop/js/plugins/slick/slick-theme.css')}}">
    <style>
    .alert-danger {
        border: 2px solid red;
    }
    .overlay-header {
        position: fixed;
        height: 75px;
        z-index: 999;
    }
    .overlay-header.show {
        opacity: 0.8;
    }
    </style>
    @yield('css')

</head>
<body>
    <!-- <div id="page-loader" class="show bg-gd-aqua"></div> -->
    <div id="page-container" class="sidebar-o sidebar-inverse enable-page-overlay side-scroll page-header-modern">
        @include('desktop.components.aside')
        @include('desktop.components.sidebar')
        @include('desktop.components.header')
        <main id="main-container">

            <div class="content">
                <nav class="breadcrumb bg-white push">
                    <a class="breadcrumb-item" href="javascript:;">Home</a>
                    @if(isset($breadcrumb))
                    @foreach($breadcrumb as $label => $link)
                        @if($link == 'javascript:;')
                        <span class="breadcrumb-item">{!! $label !!}</span>
                        @else
                        <a class="breadcrumb-item" href="{!! $link !!}">{!! $label !!}</a>
                        @endif
                    @endforeach
                    @endif
                </nav>
                @yield('content')
            </div>

        </main>
        <footer id="page-footer" class="opacity-0">
            <div class="content py-20 font-size-xs clearfix">
                <div class="float-left">
                   &copy; <span class="js-year-copy">{{date("Y")}}</span> - Bagian SDM <a class="font-w600" href="https://www.ptpn4.co.id" target="_blank">PTPN IV</a>
                </div>
            </div>
        </footer>
    </div>
            <div class="modal fade" id="modal-onboarding" tabindex="-1" role="dialog" aria-labelledby="modal-onboarding" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-popout" role="document">
                <div class="modal-content rounded">
                    <div class="block block-rounded block-transparent mb-0 bg-pattern" style="background-image: url('{{asset("/desktop/media/various/bg-pattern-inverse.png")}}');">
                        <div class="block-header justify-content-end">
                            <div class="block-options">
                                <a class="font-w600 text-danger" href="javascript:void(0);" data-dismiss="modal" aria-label="Close" onclick="localStorage.setItem('isshow', false);">
                                    Saya Mengerti
                                </a>
                            </div>
                        </div>
                        <div class="block-content block-content-full">
                            <div class="js-slider slick-dotted-inner" data-dots="true" data-arrows="false" data-infinite="false">
                                <div class="pb-50">
                                    <div class="row justify-content-center text-center">
                                        <div class="col-md-10 col-lg-8">
                                            <i class="fa fa-asterisk fa-4x fa-spin text-danger"></i>
                                            <h3 class="font-size-h2 font-w300 mt-20">Pemberitahuan</h3>
                                            <p class="text-muted" style="color:#405aa5 !important;">
                                                Pengisian Formulir Absensi untuk <b>Data Akhir  Bulan {{myMonthName(date("y-m-d",strtotime("-1 month")))}} </b> akan dibuka bagi Unit Kebun dan PKS maksimal sampai <b>Tanggal 5 bulan {{date("F")}} dan Kandir/Distrik/KPJ maksimal Tanggal 25 bulan {{date("F")}},</b> kecuali dalam keadaan khusus. Setelah itu, transaksi (input/hapus) hanya dibuka untuk bulan berjalan. <br/><b>__Bagian SDM, Kantor Direksi.</b>
                                            </p>
                                            <button type="button" class="btn btn-sm btn-hero btn-noborder btn-primary mb-10 mx-5" onclick="jQuery('.js-slider').slick('slickGoTo', 1);">
                                                Selanjutnya <i class="fa fa-arrow-right ml-5"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="slick-slide pb-50">
                                    <div class="row justify-content-center">
                                        <div class="col-md-10 col-lg-8">
                                            <h3 class="font-size-h2 font-w300 mb-5">Isilah Formulir Absensi Secara Rutin</h3>
                                            <p class="text-muted">
                                                <b>Isilah Formulir Absensi secara rutin agar tidak repot pada akhir bulan</b></span>. Atas kerjasamanya kami ucapkan terimakasih.
                                            </p>
                                            <h3 class="font-size-h2 font-w300 mb-5">Syarat Pengajuan Formulir Absensi</h3>
                                            <p class="text-muted">
                                                Formulir yang berkaitan dengan ketidakhadiran karyawan seharusnya sudah memiliki bukti berupa Surat Sakit/ Keterangan Dokter (untuk Jenis Absen Sakit) dan Surat Izin yang Sah dari Pimpinan Kerja (terkait Cuti dan Izin lainnya) di Unit/ Bagian Anda.
                                            </p>
                                           
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!--Start of Tawk.to Script-->
    {{-- <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/5c52799151410568a1096af4/default';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script> --}}
<!--End of Tawk.to Script-->
    <script src="{{asset('desktop/js/core/jquery.min.js')}}"></script>
    <script src="{{asset('desktop/js/plugins/handlebars/handlebars-v4.0.11.js')}}"></script>
    <script src="{{asset('desktop/js/core/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('desktop/js/core/jquery.slimscroll.min.js')}}"></script>
    <script src="{{asset('desktop/js/core/jquery-scrollLock.min.js')}}"></script>
    <script src="{{asset('desktop/js/core/jquery.appear.min.js')}}"></script>
    <script src="{{asset('desktop/js/core/jquery.countTo.min.js')}}"></script>
    <script src="{{asset('desktop/js/plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('desktop/js/core/js.cookie.min.js')}}"></script>
    <script src="{{asset('desktop/js/codebase.js')}}"></script>
    <script src="{{asset('desktop/js/absensi.desktop.js')}}"></script>
    <script src="{{ asset('desktop/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('desktop/js/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.id.min.js') }}"></script>
    <script src="{{ asset('desktop/js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('desktop/js/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{asset('desktop/js/plugins/moment/moment-with-locales.min.js')}}"></script>
    <script src="{{ asset('/desktop/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/desktop/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('desktop/js/absensi.datatable.js') }}"></script>
    <script src="{{asset('/desktop/js/plugins/slick/slick.min.js')}}"></script>
    {{-- <script src="{{asset('js/echo.js')}}"></script> --}}

    <script>
       const _BASE_URL = '{{ url('/') }}';
       moment.locale('id');
       $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

       $.fn.datepicker.dates['ID'] = {
            days: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"],
            daysShort: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
            daysMin: ["Mg", "Sn", "Sl", "Rb", "Km", "Jm", "Sb"],
            months: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
            monthsShort: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"],
            today: "Hari Ini",
            clear: "Bersih",
            format: "dd/mm/yyyy",
            titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
            weekStart: 0
        };

        @if (session('success'))
        $.notify({ message: '{{ session('success') }}'}, { type: 'success', placement: { align: 'center' }});
        @endif

        @if (session('error'))
        $.notify({ message: '{{ session('error') }}'}, { type: 'danger', placement: { align: 'center' }});
        @endif

        @if (session('swalSuccess'))
        swal(
            'Sukses',
            '{!! session('swalSuccess') !!}',
            'success'
            );
        @endif

        @if (session('swalError'))
        swal(
            'Gagal!',
            '{!! session('swalError') !!}',
            'error'
            );
        @endif
        @if (session('swalWarning'))
        swal(
            'Peringatan!',
            '{!! session('swalWarning') !!}',
            'warning'
            );
        @endif
        @if (session('swalWarningLink'))
        swal({
            title: "Peringatan!",
                text: '{{session("swalWarningLink")}}',
                type: 'warning', //width: '450px',
                footer:'{!!session("warning_footer")!!}'
        });
        @endif

        function destroy(e, route, app = this, custom = false, callback = '') {
            e.preventDefault();
            swal({
                title: "Yakin ingin menghapus?",
                text: "Data tidak dapat dikembalikan lagi",
                type: 'warning', //width: '450px',
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
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            if(response != 1) {
                                swal(
                                    'Gagal!',
                                    response.message,
                                    'error'
                                    );  
                            } else {
                                swal(
                                    'Sukses!',
                                    'Data berhasil dihapus',
                                    'success'
                                    );
                                if(custom === true) {
                                    setTimeout(function(){
                                        filterData();
                                    }, 500);
                                } else if(jQuery.isFunction(custom)) {
                                    custom();
                                } else {
                                    if ($.fn.DataTable.isDataTable("#datatable_ajax")) {
                                        $('#datatable_ajax').DataTable().clear().draw();
                                    }
                                }
                          }
                        }
                    });
                }
            });
        }

        $("input[data-toggle=tahunpicker]").datepicker({
            format: "yyyy",
            viewMode: "years", 
            minViewMode: "years",
            maxViewMode: "years",
            autoclose: true,
            startDate: "-2y",
            endDate: "+0y",
        });
        $("input[data-toggle=bulanpicker]").datepicker({
            autoclose: true,
            format: "MM",
            viewMode: "months", 
            minViewMode: "months",
            maxViewMode: "months",
            endDate: "+1y",
            language: "ID"
        });

        $('input[data-toggle=bulantahunpicker]').datepicker({
            startDate: '-2y',
            endDate: '+0y',
            format: 'MM yyyy',
            startView: 1,
            minViewMode: 1,
            maxViewMode: 2,
            language: 'ID'
        });

        @if(notifications())
            $.notify({ 
                message: '<strong>Perhatian:</strong>'
                    + '<ol style="padding-inline-start: 15px;">'
                    @foreach(notif_list() as $notif)
                        + '<li>{!! $notif->aktifitas !!} </li>'
                    @endforeach
                    + '</ol>'
                }, { 
                type: 'danger', 
                placement: { align: 'center' },
                delay: 0,
                // onShow: function() {
                //     this.css({'width':'80%','height':'auto'});
                // },
                onClose: function() {
                    set_all_notifs_readed(function(res) {}, function(err) { console.log(err); });
                }
            });
        @endif

        function set_all_notifs_readed(successCallback, errorCallback) {
            $.ajax({
                url: '{{ route('api.absensi.set-all-notifs-raded') }}',
                method: 'POST',
                data: {
                    token: '{{ bcrypt('niasharapan@gmail.com') }}'
                },
                success: successCallback,
                error: errorCallback,
            });
        }

        jQuery(document).ready(function() { 
            var popShow = localStorage.getItem('isshow');
            if (popShow !== 'false') {
                jQuery('#modal-onboarding').modal('show');
                $('#modal-onboarding').on('shown.bs.modal', function(e) {
                    jQuery('js-slider', '#modal-onboarding').removeClass('js-slider-enabled');
                    Codebase.helpers('slick');
                });
            }
        });

        function doLogout(e)
        {
            e.preventDefault();
            localStorage.clear();
            document.getElementById('logout-form').submit();
        }

          

    </script>
    @yield('js')
    @yield('subjs')
</body>
</html>