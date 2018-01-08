<!DOCTYPE html>
<html dir="ltr" lang="en-US" @yield('htmlschema')>
      <head>

        <!-- Document Title
        ============================================= -->
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" type="image/x-icon" href="{!! asset('/jeevandeep/images/favicon.ico') !!}">
        <link rel="canonical" href="{!! Request::url() !!}" />
        <title>@yield('title', 'Jeevandeep Prakashan Pvt. Ltd.')  </title>

        <meta property="og:url"             content="{!! Request::url() !!}" />
        @yield('json-ld')
        @include('frontend/layout/parts/default-styles')
        <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
        @yield('header_styles')
        <!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->

        <!-- start: CSS REQUIRED FOR REV SLIDER -->
        @yield('revcss')
        <!-- end: CSS REQUIRED FOR REV SLIDER -->

        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <script type="text/javascript" src="{!! asset('/assets/js/jquery/jquery.min.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('/jeevandeep/js/jquery.validate.js') !!}"></script>

        <script type="text/javascript" src="{!! asset('frontend/js/prettify_js-bundle.js') !!}"></script>
        <!-- Document Title 	============================================= -->

        @include('frontend.layout.jeevandeep.jd-issue-script')

    </head>

    <body class="no-transition side-push-panel stretched" @yield('bodyschema')>



          <div id="wrapper" class="clearfix">

            @yield('content')

        </footer><!-- #footer end -->
    </div><!-- #wrapper end -->

    @yield('footer_scripts')

    @yield('pp_footer_scripts')

    <!-- start: JS REQUIRED FOR REV SLIDER -->
    @yield('revjs')
    <!-- end: JS REQUIRED FOR REV SLIDER -->

    <script type="text/javascript">
    (function($){
        $(".errormsg").each(function(){
            if($(this).text().trim().length > 0 && !$(this).hasClass('help-block')){
                $(this).addClass('help-block');
            }else{
                $(this).removeClass('help-block');
            }
        });
    @yield('inlinejs')
    })(jQuery);
    </script>

</body>
</html>
