@extends('layouts.dash')
@section('content')
          <!-- page content -->
            
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                <!-- top tiles -->
                <div class="row tile_count">
                @if(Auth::user()->role == 3 || Auth::user()->role==5 || Auth::user()->role==1 )
                <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">         
                    <span class="count_top"><i class="fa fa-user"></i> Total Number of Employees</span>
                    <div class="count"><?php echo DB::table('employee_bvn')->where('active', 1)->count() ?></div>
                    
                </div>
                @endif
                <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-building-o"></i> Total Number of Companies</span>
                    <div class="count"><?php echo DB::table('companies')->count(); ?></div>
                </div>
                
                @if(Auth::user()->role == 1 || Auth::user()->role == 6)
                <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-money"></i> Payroll for Last Month</span>
                    <div class="count green"><?php 
                    
                    $last_month = date("Y-n-j", strtotime("first day of previous month"));
                     $thismonth = date('n', strtotime($last_month)) ; 
                     $gross= DB::table('payrolls')->whereMonth('date', $thismonth)->sum('lumpsum'); 
                     //$overtime = DB::table('payrolls')->whereMonth('date', $thismonth)->sum//('overtime');
                     echo number_format($gross, 2);
                    ?></div>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-money"></i> Payroll for This Month</span>
                    <div class="count"><?php
                     $thismonth = date('n');  
                     $gross= DB::table('payrolls')->whereMonth('date', $thismonth)->sum('lumpsum'); 
                    // $overtime = DB::table('payrolls')->whereMonth('date', $thismonth)->sum('overtime'); 

                     echo number_format($gross, 2);
                     
                     ?></div>
                </div>
                @endif

                </div>
                <!-- /top tiles -->
    
            <Dashboard :role_id="'{{Auth::user()->role}}'"  :pay_series="'{{json_encode($pay_series)}}'"  :months="'{{json_encode($months)}}'" :the_series="'{{json_encode($series)}}'" :the_labels="'{{json_encode($labels)}}'" ></Dashboard>
                <br />
          
          <!-- /page content -->
@endsection
    

     

        

