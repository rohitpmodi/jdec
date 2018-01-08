 <!DOCTYPE html>
<html dir="ltr" lang="en-US" @yield('htmlschema') >
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>@yield('title', 'Jeevandeep')</title>
    <meta name="author" content="Rohit Modi" />
@yield('goodrelations')
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{!! asset('/font-awesome/css/font-awesome.min.css') !!}" type="text/css" />
    <link rel="stylesheet" href="{!! asset('/frontend/css/bootstrap.css') !!}" type="text/css" />
    <link rel="stylesheet" href="{!! asset('/frontend/fonts/fonts.css') !!}" type="text/css" />
    <link rel="stylesheet" href="{!! asset('/frontend/style.css') !!}" type="text/css" />
    <link rel="stylesheet" href="{!! asset('/frontend/css/swiper.css') !!}" type="text/css" />
    <link rel="stylesheet" href="{!! asset('/frontend/css/dark.css') !!}" type="text/css" />
    <link rel="stylesheet" href="{!! asset('/frontend/css/font-icons.css') !!}" type="text/css" />

    <link rel="stylesheet" href="{!! asset('/frontend/css/animate.css') !!}" type="text/css" />
    <link rel="stylesheet" href="{!! asset('/frontend/css/magnific-popup.css') !!}" type="text/css" />

    <link rel="stylesheet" type="text/css" href="{!! asset('/frontend/custom-css/big-table.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/frontend/custom-css/block.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/frontend/custom-css/custom.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('/frontend/custom-css/header.css') !!}">


    <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->@yield('header_styles')
    <!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
    <link rel="stylesheet" type="text/css" href="{!! asset('/frontend/important.css') !!}">
    <link rel="stylesheet" href="{!! asset('/frontend/css/responsive.css') !!}" type="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- External JavaScripts ============================================= -->
    {{-- <script type="text/javascript" src="{!! asset('/frontend/js/jquery.js') !!}"></script> --}}
    {{-- <script type="text/javascript" src="{!! asset('/frontend/js/plugins.js') !!}"></script> --}}

@yield('scripts')
@yield('pp_header_scripts')

    <script type="text/javascript" src="{!! asset('/assets/js/jquery/jquery.min.js') !!}"></script>

    @include('frontend.layout.jeevandeep.jd-issue-script')
</head>
<body class=" @yield('bodytag') stretched" @yield('bodyschema')>
@include('googletagmanager::script')
    <div id="wrapper" class="clearfix">
    <!-- Header ============================================= -->
    @include('frontend/layout/partials/header/top-bar')
    @include('frontend/layout/partials/header/header')
    @yield('submenu')
    @yield('slider')
    @yield('intro')
    @yield('page-title')

    @yield('sidebar')
        <!-- Content ============================================= -->
        @yield('content')
        <!-- Footer ============================================= -->
        <footer id="footer" class="dark">
            <div class="container">
                @include('frontend.layout.partials.footer.footer-widgets')
            </div>
            <!-- Copyrights ============================================= -->
            <div id="copyrights">
                @include('frontend.layout.partials.footer.copyr')
            </div><!-- #copyrights end -->
        </footer><!-- #footer end -->
    </div><!-- #wrapper end -->
    <!-- Go To Top  ============================================= -->
    <div id="gotoTop" class="icon-angle-up"></div>
    <!-- External JavaScripts
    ============================================= -->
    <script type="text/javascript" src="{!! asset('/frontend/js/jquery.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('/frontend/js/plugins.js') !!}"></script>
    @yield('footer_scripts')
    <!-- Footer Scripts ============================================= -->
    <script type="text/javascript" src="{!! asset('/frontend/js/functions.js') !!}"></script>
    @yield('pp_footer_scripts')
    @yield('inlinejs')
</body>
</html>
