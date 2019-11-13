<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ config('motor-docs.name') }} Documentation</title>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="favicon.ico">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800'
          rel='stylesheet' type='text/css'>
    <!-- Theme CSS -->
    <link id="theme-style" href="{{mix('css/motor-docs.css')}}" rel="stylesheet">
</head>

<body class="body-blue">
<div class="page-wrapper">
    <!-- ******Header****** -->
    <header id="header" class="header">
        <div class="container">
            <div class="branding">
                <h1 class="logo">
                    <a href="/{{config('motor-docs.route')}}">
                        <img src="/{{ config('motor-docs.logo') }}">
                        <span class="text-highlight">{{ config('motor-docs.name') }}</span> <span class="text-bold">Documentation</span>
                    </a>
                </h1>
            </div><!--//branding-->

                        <div class="top-search-box">
                            <form class="form-inline search-form justify-content-center" action="" method="get">
                                <input type="text" value="@if (isset($query)){{$query}}@endif" placeholder="Search..." name="search" class="form-control search-input">
                                <button type="submit" class="btn search-btn" value="Search"><i class="fas fa-search"></i></button>
                            </form>
                        </div>

        </div><!--//container-->
    </header><!--//header-->

    <div class="doc-wrapper">
        <div class="container">
            <div class="doc-body row">
                <div class="doc-content col-md-9 col-12 order-1">
                    <div class="content-inner">
                        <section id="download-section" class="doc-section">
                            @yield('content')
                        </section><!--//doc-section-->
                    </div><!--//content-inner-->
                </div><!--//doc-content-->
                <div class="doc-sidebar col-md-3 col-12 order-0 d-none d-md-flex">
                    <div id="doc-nav" class="doc-nav sticky">
                        @yield('navigation')
                    </div>
                </div><!--//doc-sidebar-->
            </div><!--//doc-body-->
        </div><!--//container-->
    </div><!--//doc-wrapper-->
</div><!--//page-wrapper-->

<footer id="footer" class="footer text-center">
    <div class="container">
        <!--/* This template is released under the Creative Commons Attribution 3.0 License. Please keep the attribution link below when using for your own project. Thank you for your support. :) If you'd like to use the template without the attribution, you can buy the commercial license via our website: themes.3rdwavemedia.com */-->
        <small class="copyright">Copyright {{config('motor-docs.copyright')}}</small> | <small class="copyright">Designed
            with <i class="fas fa-heart"></i> by <a href="https://themes.3rdwavemedia.com/" target="_blank">Xiaoying
                Riley</a> for developers</small>
    </div><!--//container-->
</footer><!--//footer-->
<script type="text/javascript" src="{{mix('js/motor-docs.js')}}"></script>
<script>
    // Simple navigation highlighting
    var url = document.location.href;
    $('.doc-sidebar').find('a').each(function(index, element) {
        if ($(element).prop('href') == url) {
            $(element).addClass('active');
        }
    });
</script>
</body>
</html>
