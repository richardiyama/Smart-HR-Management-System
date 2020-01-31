<!DOCTYPE html>
<html>
    <head>
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Smart HR</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="An More Effective and Efficient Tool for Human Resource Management" />
        <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
        <link href='http://fonts.googleapis.com/css?family=Raleway:400,100,200,300,500,600,700,800,900' rel='stylesheet' type='text/css'>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <link href="{{asset('landing/css/bootstrap.css')}}" rel='stylesheet' type='text/css'/>
        <link href="{{asset('landing/css/style.css')}}" rel="stylesheet" type="text/css" media="all" />	    
        <script type="text/javascript" src="{{asset('landing/js/jquery-1.10.2.min.js')}}"></script>  
        <script src="{{asset('landing/js/responsiveslides.min.js')}}"></script>
        <script>
            $(function () {
            $("#slider").responsiveSlides({
                auto: true,
                speed: 500,
                namespace: "callbacks",
                pager: true,
            });
            });
        </script>
        <script type="text/javascript" src="{{asset('landing/js/move-top.js')}}"></script>
        <script type="text/javascript" src="{{asset('landing/js/easing.js')}}"></script>
        <script type="text/javascript">
                jQuery(document).ready(function($) {
                    $(".scroll").click(function(event){		
                        event.preventDefault();
                        $('html,body').animate({scrollTop:$(this.hash).offset().top},1200);
                    });
                });
        </script>

    </head>
    <body>
        @yield('content')
    </body>
</html>