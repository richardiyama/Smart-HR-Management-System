<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Smart HR</title>

    <link href="{{asset('vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{asset('vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{asset('vendors/nprogress/nprogress.css')}}" rel="stylesheet">

    <link href="{{asset('build/css/custom.min.css')}}" rel="stylesheet">

  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <!-- page content -->
        <div class="col-md-12">
          <div class="col-middle">
            <div class="text-center text-center">
            <img style="background:#fff; padding:10px" src="{{asset('landing/images/logo.png')}}" alt="">
              <h1 class="error-number">{{__('auth.title')}}</h1>
              <h2 style="color:#fff">{{__('auth.discription')}}</h2>
              <div class="mid_center">
                <h3> <a class="btn btn-danger btn-lg" href="{{url('/login')}}">Login</a></h3>
                <form>
                  <div class="col-xs-12 form-group pull-right top_search">
                    <div class="input-group"> 
                   
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
      </div>
    </div>

    <!-- jQuery -->
   
  </body>
</html>






