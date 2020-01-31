<!DOCTYPE html>
<html lang="en">

<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/favicon.ico" type="image/ico" />

    <title>{{config('app.name')}} </title>
    <!-- Bootstrap -->
    <link href="{{asset('vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">

    <!------ Include the above in your HEAD tag ---------->

    <!-- Font Awesome -->
    <link href="{{asset('vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{asset('vendors/nprogress/nprogress.css')}}" rel="stylesheet">
    <!-- iCheck -->
    <link href="{{asset('vendors/iCheck/skins/flat/green.css')}}" rel="stylesheet">

    <!-- bootstrap-progressbar -->
    <link href="{{asset('vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css')}}" rel="stylesheet">
    <!-- JQVMap -->
    <link href="{{asset('vendors/jqvmap/dist/jqvmap.min.css')}}" rel="stylesheet" />
    <!-- bootstrap-daterangepicker -->
    <link href="{{asset('vendors/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{asset('build/css/custom.min.css')}}" rel="stylesheet">
    <!-- Datatables -->
    <link href="{{asset('vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css')}}" rel="stylesheet">

    <script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
</head>

<body class="nav-md">



    <div class="container body">

        <div class="main_container">


            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="">
                        <a href="{{url('dashboard')}}" class="site_title"><span>{{__('auth.app_name')}}</span></a>
                    </div>

                    <div class="clearfix"></div>

                    <!-- menu profile quick info -->
                    <div class="profile clearfix">
                        <div class="profile_pic">
                            <img src="{{asset('vendors/images/avatar.jpg')}}" alt="..." class="img-circle profile_img">
                        </div>
                        <div class="profile_info">
                            <span>{{__('dashboard.menu.welcome')}}</span>
                            <h2>{{ Auth::user()->name }}</h2>
                        </div>
                    </div>
                    <!-- /menu profile quick info -->

                    <br />
                    {{-- side menu --}}
                    @include('inc.navBar')
                    {{-- end --}}
                    <!-- /menu footer buttons -->
                    <div class="sidebar-footer hidden-small">
                        <!--  <a data-toggle="tooltip" data-placement="top" title="Settings">
                            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                            </a>
                            <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                            <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                            </a>
                            <a data-toggle="tooltip" data-placement="top" title="Lock">
                            <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                            </a>
                            <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
                            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                            </a> -->
                    </div>
                    <!-- /menu footer buttons -->
                </div>
            </div>

            <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>

                        <ul class="nav navbar-nav navbar-right">
                            @if (Auth::guest())
                            {{-- {{Route::redirect('/', 'login/index')}}; --}}
                            {{-- <li><a href="{{ route('register') }}">Register</a></li> --}}
                            @else
                            <li class="user-profile">

                                <a title="Click to logout" href="{{ route('logout') }}" onclick="event.preventDefault();
                                      document.getElementById('logout-form').submit();">

                                    <i class="fa fa-sign-out"> Logout</i>
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    {{ csrf_field() }}
                                </form>

                            </li>

                            <!-- <li role="presentation" class="dropdown">
                         
                          <a href="#"> <i class="fa fa-key"> Change Password</i></a>
                         
                        </li>  -->
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- /top navigation -->
            <div class="right_col" role="main">
                <div class="">
                    @if (session('status'))

                    <div class="alert alert-success">{{session('status')}}</div>

                    @endif

                    @if (session('error'))

                    <div class="alert alert-error">{{session('error')}}</div>

                    @endif


                </div>
                <div class="clearfix"></div>
                <div id="app_smarthr">


                    <div id="notification" class="modal fade" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Notifications</h4>
                                </div>
                                <div class="modal-body" style="overflow:auto; height:400px">
                                    <?php 
                                $notifications = DB::table('logs')->orderby('id', 'desc')->limit(20)->get();  
                                ?>
                                    <ul id="menu1" class="list-unstyled msg_list" role="menu">
                                        <?php 
                                      if($notifications) {
                                        foreach ($notifications as  $value) {
                                          ?>
                                        <li>
                                            <a href="#">
                                                <span>
                                                    <span></span>
                                                    <span
                                                        class="time">{{Carbon\Carbon::parse($value->created)->diffForHumans()}}</span>
                                                </span>
                                                <span class="message">
                                                    {{$value->logs}}
                                                </span>
                                            </a>
                                        </li>
                                        <?php
                                        }
                                      }
                                      ?>


                                        <!-- <li>
                                        <div class="text-center">
                                          <a>
                                            <strong>See All Alerts</strong>
                                            <i class="fa fa-angle-right"></i>
                                          </a>
                                        </div>
                                      </li> -->
                                    </ul>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>





                    @yield('content')

                </div>
            </div>
        </div>



        <!-- footer content -->
        <footer>
            <div class="pull-right">
                <a target="_blank" href="http://www.craneburg.com"> {{__('auth.app_name')}} - Powered by craneburg
                    construction</a>
            </div>
            <div class="clearfix"></div>
        </footer>


        <!-- /footer content -->
    </div>




    </div>

    <script src="{{asset('js/app.js')}}"></script>
    <script src="{{asset('js/textareacomment.js')}}"></script>

    <!-- jQuery -->
    <script src="{{asset('vendors/jquery/dist/jquery.min.js')}}"></script>
    <!-- Bootstrap -->
    <script src="{{asset('vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <!-- FastClick -->
    <script src="{{asset('vendors/fastclick/lib/fastclick.js')}}"></script>
    <!-- NProgress -->
    <script src="{{asset('vendors/nprogress/nprogress.js')}}"></script>
    <!-- Chart.js -->
    <script src="{{asset('vendors/Chart.js/dist/Chart.min.js')}}"></script>
    <!-- gauge.js -->
    <script src="{{asset('vendors/gauge.js/dist/gauge.min.js')}}"></script>
    <!-- bootstrap-progressbar -->
    <script src="{{asset('vendors/bootstrap-progressbar/bootstrap-progressbar.min.js')}}"></script>
    <!-- iCheck -->
    <script src="{{asset('vendors/iCheck/icheck.min.js')}}"></script>
    <!-- Skycons -->
    <script src="{{asset('vendors/skycons/skycons.js')}}"></script>
    <!-- Flot -->
    <script src="{{asset('vendors/Flot/jquery.flot.js')}}"></script>
    <script src="{{asset('vendors/Flot/jquery.flot.pie.js')}}"></script>
    <script src="{{asset('vendors/Flot/jquery.flot.time.js')}}"></script>
    <script src="{{asset('vendors/Flot/jquery.flot.stack.js')}}"></script>
    <script src="{{asset('vendors/Flot/jquery.flot.resize.js')}}"></script>
    <!-- Flot plugins -->
    <script src="{{asset('vendors/flot.orderbars/js/jquery.flot.orderBars.js')}}"></script>
    <script src="{{asset('vendors/flot-spline/js/jquery.flot.spline.min.js')}}"></script>
    <script src="{{asset('vendors/flot.curvedlines/curvedLines.js')}}"></script>
    <!-- DateJS -->
    <script src="{{asset('vendors/DateJS/build/date.js')}}"></script>
    <!-- JQVMap -->
    <script src="{{asset('vendors/jqvmap/dist/jquery.vmap.js')}}"></script>
    <script src="{{asset('vendors/jqvmap/dist/maps/jquery.vmap.world.js')}}"></script>
    <script src="{{asset('vendors/jqvmap/examples/js/jquery.vmap.sampledata.js')}}"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="{{asset('vendors/moment/min/moment.min.js')}}"></script>
    <script src="{{asset('vendors/bootstrap-daterangepicker/daterangepicker.js')}}"></script>

    <!-- Custom Theme Scripts -->
    <script src="{{asset('build/js/custom.min.js')}}"></script>

    <!-- jQuery Smart Wizard -->
    <script src="{{asset('vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js')}}"></script>
    <!-- <script type="text/javascript" src="{{asset('landing/js/jquery-1.11.2.min.js')}}"></script>  -->
    <!-- Datatables -->
    <script src="{{asset('vendors/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendors/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{asset('vendors/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js')}}"></script>
    <script src="{{asset('vendors/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
    <script src="{{asset('vendors/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('vendors/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js')}}"></script>
    <script src="{{asset('vendors/datatables.net-keytable/js/dataTables.keyTable.min.js')}}"></script>
    <script src="{{asset('vendors/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js')}}"></script>
    <script src="{{asset('vendors/datatables.net-scroller/js/dataTables.scroller.min.js')}}"></script>
    <script src="{{asset('vendors/jszip/dist/jszip.min.js')}}"></script>
    <script src="{{asset('vendors/pdfmake/build/pdfmake.min.js')}}"></script>
    <script src="{{asset('vendors/pdfmake/build/vfs_fonts.js')}}"></script>
    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}"></script> --}}

    <script src="{{asset('landing/js/easyResponsiveTabs.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $('#horizontalTab').easyResponsiveTabs({
            type: 'default', //Types: default, vertical, accordion           
            width: 'auto', //auto or any width like 600px
            fit: true // 100% fit in a container
        });
    });
    </script>

    <!--<script src="{{asset('js/pre_employment.js')}}"></script>-->
</body>

</html>