<!doctype html>
<!--[if lte IE 9]>     <html lang="en" class="no-focus lt-ie10 lt-ie10-msg"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en" class="no-focus"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

    <title>N4-Absensi PTPN IV</title>

    <meta name="description" content="Aplikasi E-Internship PTPN IV Medan">
    <meta name="author" content="afrizal yusren harapan jaya harefa">
    <meta name="robots" content="noindex, nofollow">

    <!-- Open Graph Meta -->
    <meta property="og:title" content="E-Internship">
    <meta property="og:site_name" content="E-Internship">
    <meta property="og:description" content="Aplikasi E-Internship PTPN IV Medan">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="">

    <link rel="shortcut icon" href="{{asset('desktop/img/favicons/favicon.png')}}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{asset('desktop/img/favicons/favicon-192x192.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('desktop/img/favicons/apple-touch-icon-180x180.png')}}">

    <link rel="stylesheet" id="css-main" href="{{asset('desktop/css/codebase.min.css')}}">

</head>
<body>

    <div id="page-container" class="main-content-boxed">

        <main id="main-container">

            <div class="hero bg-white">
                <div class="hero-inner">
                    <div class="content content-full">
                        <div class="py-30 text-center">
                            <div class="display-3" style="color: #37abb1;">
                                <i class="fa fa-ambulance"></i> 503
                            </div>
                            <h1 class="h2 font-w700 mt-30 mb-10">Aplikasi Sedang Diperbaiki</h1>
                            <h2 class="h3 font-w400 text-muted mb-50">Harap bersabar, kami akan online <span id="demo"></span> Lagi.</h2>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <script src="{{asset('desktop/js/core/jquery.min.js')}}"></script>
    <script src="{{asset('desktop/js/core/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('desktop/js/core/jquery.slimscroll.min.js')}}"></script>
    <script src="{{asset('desktop/js/core/jquery.scrollLock.min.js')}}"></script>
    <script src="{{asset('desktop/js/core/jquery.appear.min.js')}}"></script>
    <script src="{{asset('desktop/js/core/jquery.countTo.min.js')}}"></script>
    <script src="{{asset('desktop/js/core/js.cookie.min.js')}}"></script>
    <script src="{{asset('desktop/js/codebase.js')}}"></script>
    <script type="text/javascript">
        var countDownDate = new Date("Oct 16, 2019 17:00:00").getTime();
        var x = setInterval(function() {
            var now = new Date().getTime();

          var distance = countDownDate - now;

          var days = Math.floor(distance / (1000 * 60 * 60 * 24));
          var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
          var seconds = Math.floor((distance % (1000 * 60)) / 1000);

          document.getElementById("demo").innerHTML = hours + " jam "
          + minutes + " menit " + seconds + " detik ";

          if (distance < 0) {
            clearInterval(x);
            document.getElementById("demo").innerHTML = "WAKTU SUDAH LEWAT";
        }
    }, 1000);
</script>
</body>
</html>