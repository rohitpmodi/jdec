<!DOCTYPE html>
<html dir="ltr" lang="en-US">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />


        @include('frontend/layout/parts/default-styles')
        <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
        @yield('header_styles')

        <!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
        <link rel="stylesheet" type="text/css" href="{!! asset('/frontend/important.css') !!}">

        <link rel="stylesheet" href="{!! asset('/frontend/css/responsive.css') !!}" type="text/css" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <!-- Document Title 	============================================= -->
        <title>@yield('title', 'Secure User Account Area')</title>
        @include('frontend.layout.jeevandeep.jd-issue-script')
    </head>
    <body class=" @yield('bodytag') stretched">
    @include('googletagmanager::script')
        <div id="wrapper" class="clearfix">

            <!-- Header ============================================= -->
            @include('frontend/layout/partials/header/header',[$cart,$total,$user])

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
        <!-- Go To Top ============================================= -->
        <div id="gotoTop" class="icon-angle-up"></div>
        @include('frontend/layout/parts/default-scripts')



<script type="text/javascript">
    $(document).ready(function(){
        var hash = document.location.hash;
        if (hash) {
            $('a[href="'+hash+'"].ui-tabs-anchor').click();
        }
        if($(".ui-tabs").length){
 $(".ui-tabs").tabs({
        activate: function(event, ui) {
             history.pushState(null, null,  "#"+ui.newPanel.attr('id'));
        }
    });
        }
    });
    $(window).on('hashchange', function() {
  var hash = document.location.hash;
        if (hash) {
            $('a[href="'+hash+'"].ui-tabs-anchor').click();
        }
});
</script>
</body>
</html>
