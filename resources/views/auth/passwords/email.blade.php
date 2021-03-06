<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->



    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Minovate - Admin Dashboard</title>
        <link rel="icon" type="image/ico" href="assets/images/favicon.ico" />
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- ============================================
        ================= Stylesheets ===================
        ============================================= -->
        <!-- vendor css files -->
        <link rel="stylesheet" href="{{ asset("themes/assets/css/vendor/bootstrap.min.css") }}">
        <link rel="stylesheet" href="{{ asset("themes/assets/css/vendor/animate.css") }}">
        <link rel="stylesheet" href="{{ asset("themes/assets/css/vendor/font-awesome.min.css") }}">
        <link rel="stylesheet" href="{{ asset("themes/assets/js/vendor/animsition/css/animsition.min.css") }}">

        <!-- project main css files -->
         <link rel="stylesheet" href="{{ asset("themes/assets/css/main.css") }}">
        <!--/ stylesheets -->
        <!-- ==========================================
        ================= Modernizr ===================
        =========================================== -->
        <script src="{{ asset("themes/assets/js/vendor/modernizr/modernizr-2.8.3-respond-1.4.2.min.js") }}  "></script>
        <!--/ modernizr -->
    </head>
    <body id="minovate" class="appWrapper">
        <!-- ====================================================
        ================= Application Content ===================
        ===================================================== -->
        <div id="wrap" class="animsition">
            <div class="page page-core page-login">

                <div class="text-center"><h3 class="text-light text-white"><span class="text-lightred">MSL</span>VietNam</h3></div>

                <div class="container w-420 p-15 bg-white mt-40 text-center">

                    <h2 class="text-light text-greensea">Forgot Password?</h2>
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form role="form" method="POST" action="{{ url('/password/email') }}" class="form-validation mt-20">
                         {!! csrf_field() !!}

                        <p class="help-block text-left">
                            Enter your e-mail address below to reset your password.
                        </p>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <input type="email" class="form-control underline-input" name="email" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="bg-slategray lt wrap-reset mt-40 text-left">
                        <p class="m-0">
                            <button type="submit" class="btn btn-greensea b-0 text-uppercase pull-right">Submit</button> 
                            <a href="{{ url('/login') }}"  class="btn btn-lightred b-0 text-uppercase">Back</a>
                        </p>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!--/ Application Content -->
        <!-- ============================================
        ============== Vendor JavaScripts ===============
        ============================================= -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="assets/js/vendor/jquery/jquery-1.11.2.min.js"><\/script>')</script>

        <script src="{{ asset("themes/assets/js/vendor/bootstrap/bootstrap.min.js") }}"></script>
        <script src="{{ asset("themes/assets/js/vendor/jRespond/jRespond.min.js") }}"></script>

        <script src="{{ asset("themes/assets/js/vendor/sparkline/jquery.sparkline.min.js") }}"></script>
        <script src="{{ asset("themes/assets/js/vendor/slimscroll/jquery.slimscroll.min.js") }}"></script>

        <script src="{{ asset("themes/assets/js/vendor/animsition/js/jquery.animsition.min.js") }}"></script>

        <script src="{{ asset("themes/assets/js/vendor/screenfull/screenfull.min.js") }}"></script>
        <!--/ vendor javascripts -->
        <!-- ============================================
        ============== Custom JavaScripts ===============
        ============================================= -->
        <script src="{{ asset("themes/assets/js/main.js") }}"></script>
        <!--/ custom javascripts -->
        <!-- ===============================================
        ============== Page Specific Scripts ===============
        ================================================ -->
        <script>
            $(window).load(function(){
                

            });
        </script>
        <!--/ Page Specific Scripts -->





        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='https://www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X','auto');ga('send','pageview');
        </script>

    </body>
</html>
