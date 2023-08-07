<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Wee Share</title>



    <link href="https://fonts.googleapis.com/css?family=Comfortaa:300,400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100i,200,300,400,700,800,900" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/owl-carousel/owl.carousel.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup/magnific-popup.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/animate.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/font-awesome.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/export.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/line-awesome.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/mediaelementplayer.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/timeline.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom.css') }}">
</head>

<body data-spy="scroll" data-offset="80">

    <!-- <div id="loading">
        <div id="loading-center">
            <div class='loader loader2'>
                <div>
                    <div>
                        <div>
                            <div>
                                <div>
                                    <div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->


    <header>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <a class="navbar-brand" href="#">
                            <img src="{{ asset('assets/images/weeshare-logo.png') }}" alt="Wee Share">
                        </a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <i class="la la-bars"></i>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav mr-auto w-100 justify-content-end">
  
                                <li class="nav-item">
                                    <a class="nav-link active" href="#wave">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#WhyR3P">About Weeshare</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#WhyR3P">How It Works</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#WhyR3P">Road Map</a>
                                </li>
                                <li class="nav-item">
                                </li>
                            </ul>
                        </div>
                        <a href="#" class="button bt-white iq-mt-3 iq-ml-6">Login</a>  

                    </nav>
                </div>
            </div>
        </div>
    </header>


    <div id="wave" class="iq-banner">
        <div class="banner-info">
            <div class="container">
                <div class="row">
                    <div class="container">
  
                        <div class="row flex-row-reverse">
                            <div class="col-lg-6 align-self-center">
                                <img src="images\banner\visualtrading.png" class="img-fluid" alt="">
                            </div>
                            <div class="col-lg-6 align-self-center">
                                <div class="banner-text text-left iq-font-white">
                                    <h1 class="iq-font-white iq-mb-20">
                                        Share More, Earn More  
                                        <b class="iq-font-yellow"> The Social Media Revolution</b>
                                    </h1>
                                    <p>The first ecosystem social media platform that enables you to share other's posts and earn cypto</p>
                                    {{-- <a href="https://www.pinksale.finance/launchpad/0x0028CFa1e3870783218A53543783d66CA9990Bc6?chain=BSC"
                                        class="button bt-yellow iq-mt-5 iq-ml-10">Pre-sale on PINKSALE NOW</a> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <section id="WhyR3P" class="iq-product-description overview-block-pt">
        @yield('content') 
    </section>

    {{-- <script data-cfasync="false" src="cdn-cgi\scripts\5c5dd728\cloudflare-static\email-decode.min.js"></script> --}}
    <script src="{{ asset('assets/js/jquery-min.js') }}"></script>

    <script src="{{ asset('assets/js/popper.min.js') }}"></script>

    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

    <script src="{{ asset('assets/js/all-plugins.js') }}"></script>

    <script src="{{ asset('assets/js/timeline.min.js') }}"></script>
    <script src="{{ asset('assets/js/timeline.min.js') }}"></script>

    <script src="{{ asset('assets/js/wave/three.min.js') }}"></script>
    <script src="{{ asset('assets/js/wave/Projector.js') }}"></script>
    <script src="{{ asset('assets/js/wave/CanvasRenderer.js') }}"></script>
    <script src="{{ asset('assets/js/wave/index.js') }}"></script>

    <script src="{{ asset('assets/js/bubbly-bg.js') }}"></script>

    <script src="{{ asset('assets/js/amcharts\amcharts.js') }}"></script>
    <script src="{{ asset('assets/js/amcharts\serial.js') }}"></script>
    <script src="{{ asset('assets/js/amcharts\export.min.js') }}"></script>
    <script src="{{ asset('assets/js/amcharts\none.js') }}"></script>

    <script src="{{ asset('assets/js/custom.js') }}"></script>
    
</body>

</html>