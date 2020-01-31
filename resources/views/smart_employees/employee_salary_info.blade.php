
    

@extends('layouts.dash')

@section('content')

@include('inc.profile_header')


<div class="x_title">
          <h2>Salary and Account Details</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
            @if ($salary)
            <div class="row">
                    <div class="col-md-6">
                        <span class="text-muted">Salary : Lumpsum</span> <br>
                    <strong>NGN {{ number_format($salary->salary, 2)}}</strong>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted">Basic Salary</span> <br>
                        <strong>NGN {{ number_format($salary->basic_salary, 2)}}</strong>
                        </div>
                    </div>
                </div>
            <br>
            <div class="row">
                    <div class="col-md-6">
                        <span class="text-muted">Bank name</span> <br>
                    <strong>
                        <?php 
                        $bank = DB::table('banks')->where('id', $salary->bank)->first();
                        if($bank) {
                            echo $bank->name;
                        }    
                        ?>
                    </strong>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted">Account number</span> <br>
                    <strong>{{$salary->account_number}}</strong>
                    </div>
                </div>
             <br>
             <div class="row">
                    <div class="col-md-6">
                        <span class="text-muted">Personal Fund Administrator(PFA)</span> <br>
                    <strong>
                        <?php 
                        $pfa = DB::table('pfa')->where('id', $salary->pfa)->first();
                        if($pfa) {
                            echo $pfa->name;
                        }    
                        ?>
                    </strong>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted">Pension number</span> <br>
                    <strong>{{$salary->pension_number}}</strong>
                    </div>
                </div>

            @endif
 
        
           
        </div>

        <div class="x_panel"> <br>
       
            <div class="x_title">
              <h2>Next of Kin Details</h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
                @if ($salary)
                <div class="row">
                        <div class="col-md-6">
                            <span class="text-muted">Next of kin name</span> <br>
                        <strong>{{$salary->next_kin_name }}</strong>
                        </div>
                        <div class="col-md-6">
                                <span class="text-muted">Next of kin phone number</span> <br>
                                <strong>
                                  {{$salary->next_kin_phone}}
                                </strong>
                        </div>
                    </div>
                <br>
                <div class="row">
                        <div class="col-md-6">
                                <span class="text-muted">Next of kin relationship</span> <br>
                                <strong>{{$salary->next_kin_relationship}}</strong>
                        </div>
                        <div class="col-md-6">
                           
                        </div>
                    </div>
                 <br>
                 <div class="row">
                        <div class="col-md-6">
                            <span class="text-muted">Next of kin address</span> <br>
                        <strong>
                          {{$salary->next_kin_address}}
                        </strong>
                        </div>
                        <div class="col-md-6">
                            
                        </div>
                    </div>
    
                @endif
     
            
               
            </div>
          </div>


<!-- ============================================================== -->
<!-- End Container fluid  -->


@endsection