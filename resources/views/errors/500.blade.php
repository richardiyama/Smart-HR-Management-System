<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Smart Hr App </title>

    <!-- Bootstrap -->
    <link href="{{asset('vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{asset('vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{asset('vendors/nprogress/nprogress.css')}}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{asset('build/css/custom.min.css')}}" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <!-- page content -->
        <div class="col-md-12">
          <div class="col-middle">
            <div class="text-center">
              <h1 class="error-number">500</h1>
              <h2>Internal Server Error</h2>
              <p>We track these errors automatically, but if the problem persists feel free to contact us. In the meantime, try refreshing. 
              </p>
              <p>
                Kindly screeen shot this page and send it to James on <a href="mailto:ojames314@gmail.com?cc=james.oladimeji@aapeliltd.com&bcc=support@aapeliltd.com
                &amp;subject=ERROR500%20
                &amp;body=Dear%20James,%20">
               ojames314@gmail.com</a>, you could go further by explain the action you did that result to this error.
              </p>
              <a href="mailto:ojames314@gmail.com?cc=james.oladimeji@aapeliltd.com&bcc=support@aapeliltd.com
              &amp;subject=ERROR500%20
              &amp;body=Dear%20James,%20">
             Report this ? </a>
              <div class="mid_center">
              <a class="btn btn-danger" href="{{url('/dashboard')}}">Back to Dashboard</a>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
      </div>
    </div>

 
  </body>
</html>