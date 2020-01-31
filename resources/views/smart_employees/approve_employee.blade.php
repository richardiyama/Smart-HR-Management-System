@extends('layouts.dash')

@section('content')
<div class="row">

        <?php
        $name="";
        if(isset($personal)) {
            $name = $personal->firstname. ' '. $personal->middlename . ' '. $personal->lastname;
        }
    ?>
    <div class="col-md-2"></div>
    
    <div class="col-md-8 col-sm-8 col-xs-12">
    <a href="{{url('employees/pending')}}" style="" title="Back to New employee"><span class="fa fa-arrow-left" style="color:green; font-size:15px;"> Back to New Employee</span></a> <br><br>

    <?php 
    $emp = DB::table('employee_bvn')->where('id', $employee_id)->first();
    if($emp->active == 1) {
     ?>
     @if(Auth::user()->role != 2) 
        <button class="btn btn-danger btn-lg"  data-backdrop="static"
        data-keyboard="false"
        data-toggle="modal"
        data-target="#terminate">Terminate employee contract</button>
    @endif
     <?php
    }else {
        ?>
     @if(Auth::user()->role != 2) 
    <Confirmemployee role_id="'{{Auth::user()->role}}'" :employee_name="'{{$name}}'" :employee_id="'{{$employee_id}}'" :user_id="'{{Auth::user()->id}}'"></Confirmemployee>

    @endif

        <?php
    }
    ?>

 
   

 
      <div class="x_panel"> <br>
       
        <div class="x_title">
        <a href="{{url('employee')}}/{{$employee_id}}/update" style="" title="Edit this employee"><span class="fa fa-pencil" style="color:green; font-size:15px;"> Edit employee detail</span></a>
        <a href="javascript:print()" class="pull-right" title="Print employee detail"><span class="fa fa-print" style="color:green; font-size:15px;"> Print</span></a> <br><br>
          <h2>Personal Detail</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          @if ($personal)
              
         
            <div class="row">
               <div class="col-md-6">
                   <span class="text-muted">First Name</span> <br>
               <strong>{{$personal->firstname}}</strong>
               </div>
               <div class="col-md-6">
                    <span class="text-muted">Last name</span> <br>
                    <strong>{{$personal->firstname}}</strong>
                </div>
           </div>
           <br>
           <div class="row">
                <div class="col-md-6">
                    <span class="text-muted">Middle Name</span> <br>
                <strong>{{$personal->middlename}}</strong>
                </div>
            </div>
            <br>
            <div class="row">
                 <div class="col-md-6">
                     <span class="text-muted">Date of birth</span> <br>
                 <strong>{{$personal->date_of_birth}}</strong>
                 </div>
                 <div class="col-md-6">
                        <span class="text-muted">Gender</span> <br>
                    <strong>{{$personal->gender}}</strong>
                    </div>
             </div>
             <br>
             <div class="row">
                  <div class="col-md-6">
                      <span class="text-muted">Nationality</span> <br>
                  <strong>{{$personal->nationality}}</strong>
                  </div>
              </div>
              <br>
              <div class="row">
                   <div class="col-md-6">
                       <span class="text-muted">Does this employee have driver license ?</span> <br>
                   <strong>
                       <input type="radio" @if ($personal->driver_license==1) checked
                           
                       @endif> Yes
                       <input type="radio" @if ($personal->driver_license==0) checked
                           
                       @endif> No
                   </strong>
                   </div>
               </div>
               <br>
               <div class="row">
                    <div class="col-md-6">
                        <span class="text-muted">Driver license Number</span> <br>
                    <strong>{{$personal->driver_license_no}}</strong>
                    </div>
                </div>
                @endif
            </div>
      </div>


      <div class="x_panel"> <br>
       
        <div class="x_title">
          <h2>Contact Detail</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
            @if ($contact)
                
          
           <div class="row">
               <div class="col-md-6">
                   <span class="text-muted">Current company</span> <br>
               <strong>
                   <?php 
                    $company = DB::table('companies')->where('id', $contact->company)->first();
                    if($company) {
                        echo $company->name;
                    }
                    ?>
               </strong>
               </div>
               <div class="col-md-6">
                    <span class="text-muted">Current site</span> <br>
                    <strong>
                            <?php 
                            $site = DB::table('sites')->where('id', $contact->site)->first();
                            if($site) {
                                echo $site->name;
                            }
                            ?>
                    </strong>
                </div>
           </div>
           <br>
           <div class="row">
                <div class="col-md-6">
                    <span class="text-muted">Current department</span> <br>
                <strong>
                    <?php 
                     $department = DB::table('departments')->where('id', $contact->department)->first();
                     if($department) {
                         echo $department->name;
                     }
                     ?>
                </strong>
                </div>
                <div class="col-md-6">
                     <span class="text-muted">Current position</span> <br>
                     <strong>
                             <?php 
                             $position = DB::table('positions')->where('id', $contact->job_position)->first();
                             if($position) {
                                 echo $position->name;
                             }
                             ?>
                     </strong>
                 </div>
            </div>
            <br>
            <div class="row">
                 <div class="col-md-6">
                     <span class="text-muted">Employee number</span> <br>
                 <strong>{{$contact->empno}}</strong>
                 </div>
                 <div class="col-md-6">
                        <span class="text-muted">Start date</span> <br>
                    <strong>{{$contact->start_date}}</strong>
                    </div>
             </div>
             @endif
            </div>
      </div>



      <div class="x_panel"> <br>
       
        <div class="x_title">
          <h2>Previous Company Position Detail</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
 
 
           
        </div>
      </div>


      <div class="x_panel"> <br>
       
        <div class="x_title">
          <h2>Work Schedule</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
            @if ($contact)
                
           
            <div class="row">
                <div class="col-md-6">
                    <input type="radio" @if ($contact->work_time == 1)
                        checked
                    @endif> Full time
                    &nbsp; &nbsp;
                    <input type="radio" @if ($contact->work_time == 0)
                    checked
                @endif> Part time
                </div>

            </div>
            <br>

            <div class="row">
                <div class="col-md-2">
                    <span>Monday</span>
                </div>

                <div class="col-md-4">
                        <span class="text-muted">Resumption time</span> <br>
                        <?php $mt = DB::table('morning_period')->where('id', $contact->moday_morning)->first(); ?>
                        <strong>
                            @if ($mt)
                            {{date('h:i A', strtotime($mt->time))}} 
                            @endif
                            
                        </strong>
                    </div>
            
                    <div class="col-md-4">
                            <span class="text-muted">Closing time</span> <br>
                            <?php $mt = DB::table('evening_period')->where('id', $contact->moday_evening)->first(); ?>
                            <strong>
                                @if ($mt)
                                {{date('h:i A', strtotime($mt->time))}} 
                                @endif
                                
                            </strong>
                        </div>
                
            </div>
            <br>
            <div class="row">
                    <div class="col-md-2">
                        <span>Tuesday</span>
                    </div>
    
                    <div class="col-md-4">
                            <span class="text-muted">Resumption time</span> <br>
                            <?php $mt = DB::table('morning_period')->where('id', $contact->tuesday_morning)->first(); ?>
                        <strong>
                            @if ($mt)
                            {{date('h:i A', strtotime($mt->time))}} 
                            @endif
                            
                        </strong>
                        </div>
                
                        <div class="col-md-4">
                                <span class="text-muted">Closing time</span> <br>
                                <?php $mt = DB::table('evening_period')->where('id', $contact->tuesday_evening)->first(); ?>
                        <strong>
                            @if ($mt)
                            {{date('h:i A', strtotime($mt->time))}} 
                            @endif
                            
                        </strong>
                            </div>
                    
                </div>
                <br>
                <div class="row">
                        <div class="col-md-2">
                            <span>Wednesday</span>
                        </div>
        
                        <div class="col-md-4">
                                <span class="text-muted">Resumption time</span> <br>
                                <?php $mt = DB::table('morning_period')->where('id', $contact->wednesday_morning)->first(); ?>
                                <strong>
                                    @if ($mt)
                                    {{date('h:i A', strtotime($mt->time))}} 
                                    @endif
                                    
                                </strong>
                            </div>
                    
                            <div class="col-md-4">
                                    <span class="text-muted">Closing time</span> <br>
                                    <?php $mt = DB::table('evening_period')->where('id', $contact->wednesday_evening)->first(); ?>
                        <strong>
                            @if ($mt)
                            {{date('h:i A', strtotime($mt->time))}} 
                            @endif
                            
                        </strong>
                                </div>
                        
                    </div>
                    <br>
                    <div class="row">
                            <div class="col-md-2">
                                <span>Thurseday</span>
                            </div>
            
                            <div class="col-md-4">
                                    <span class="text-muted">Resumption time</span> <br>
                                    <?php $mt = DB::table('morning_period')->where('id', $contact->thurseday_morning)->first(); ?>
                        <strong>
                            @if ($mt)
                            {{date('h:i A', strtotime($mt->time))}} 
                            @endif
                            
                        </strong>
                                </div>
                        
                                <div class="col-md-4">
                                        <span class="text-muted">Closing time</span> <br>
                                        <?php $mt = DB::table('evening_period')->where('id', $contact->thurseday_evening)->first(); ?>
                        <strong>
                            @if ($mt)
                            {{date('h:i A', strtotime($mt->time))}} 
                            @endif
                            
                        </strong>
                                    </div>
                            
                </div>
                <br>
                <div class="row">
                        <div class="col-md-2">
                            <span>Friday</span>
                        </div>
        
                        <div class="col-md-4">
                                <span class="text-muted">Resumption time</span> <br>
                                <?php $mt = DB::table('morning_period')->where('id', $contact->friday_morning)->first(); ?>
                                <strong>
                                    @if ($mt)
                                    {{date('h:i A', strtotime($mt->time))}} 
                                    @endif
                                    
                                </strong>
                            </div>
                    
                            <div class="col-md-4">
                                    <span class="text-muted">Closing time</span> <br>
                                    <?php $mt = DB::table('evening_period')->where('id', $contact->friday_evening)->first(); ?>
                                    <strong>
                                        @if ($mt)
                                        {{date('h:i A', strtotime($mt->time))}} 
                                        @endif
                                        
                                    </strong>
                                </div>
                        
            </div>
            <br>
            <div class="row">
                    <div class="col-md-2">
                        <span>Saturday</span>
                    </div>
    
                    <div class="col-md-4">
                            <span class="text-muted">Resumption time</span> <br>
                            <?php $mt = DB::table('morning_period')->where('id', $contact->saturday_morning)->first(); ?>
                            <strong>
                                @if ($mt)
                                {{date('h:i A', strtotime($mt->time))}} 
                                @endif
                                
                            </strong>
                        </div>
                
                        <div class="col-md-4">
                                <span class="text-muted">Closing time</span> <br>
                                <?php $mt = DB::table('evening_period')->where('id', $contact->saturday_evening)->first(); ?>
                        <strong>
                            @if ($mt)
                            {{date('h:i A', strtotime($mt->time))}} 
                            @endif
                            
                        </strong>
                            </div>
                    
            </div>
            <br>
            <div class="row">
                    <div class="col-md-2">
                        <span>Sunday</span>
                    </div>
    
                    <div class="col-md-4">
                            <span class="text-muted">Resumption time</span> <br>
                            <?php $mt = DB::table('morning_period')->where('id', $contact->sunday_morning)->first(); ?>
                        <strong>
                            @if ($mt)
                            {{date('h:i A', strtotime($mt->time))}} 
                            @endif
                            
                        </strong>
                        </div>
                
                        <div class="col-md-4">
                                <span class="text-muted">Closing time</span> <br>
                                <?php $mt = DB::table('evening_period')->where('id', $contact->sunday_evening)->first(); ?>
                        <strong>
                            @if ($mt)
                            {{date('h:i A', strtotime($mt->time))}} 
                            @endif
                            
                        </strong>
                            </div>
                    
                </div>
                <br>
                <div class="row">
                     <div class="col-md-6">
                         <span class="text-muted">Does this employee qualify for overtime ? </span> <br>
                     <strong>
                         <input type="radio" @if ($contact->overtime==1) checked
                             
                         @endif> Yes
                         <input type="radio" @if ($contact->overtime==0) checked
                             
                         @endif> No
                     </strong>
                     </div>
                 </div>
                 @endif
      
                </div>
      </div>

      <div class="x_panel"> <br>
       
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
    
    
          <div class="x_panel"> <br>
           
            <div class="x_title">
              <h2>Emergency Contacts</h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
                @if ($emergency)
                @foreach ($emergency as $item)
                <div class="row">
                        <div class="col-md-6">
                            <span class="text-muted">Emergency Contact </span> <br>
                        <strong>{{$item->name }}</strong>
                        </div>
                        <div class="col-md-6">
                                <span class="text-muted">Emergency contact phone number</span> <br>
                                <strong>
                                  {{$item->phone}}
                                </strong>
                        </div>
                    </div>
                <br>
                @endforeach
            
                @endif
     
            
               
            </div>
          </div>
    
    
          <div class="x_panel"> <br>
           
            <div class="x_title">
              <h2>Supporting Documents</h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
                @if ($documents)
                <div class="row">
                @foreach ($documents as $item)
                <div class="col-md-6">
                  <div class="thumbnail">
                    <div class="image view view-first">
                    <img title="Click the pencil icon view document" style="width: 100%; display: block;" src="{{asset('storage/assets/')}}/{{$item->file}}" />
                      <div class="mask">
                      <p>{{$item->created}}</p>
                        <div class="tools tools-bottom">
                        <a href="{{asset('storage/assets/')}}/{{$item->file}}" title="open this file"><i class="fa fa-pencil"></i></a>
                        </div>
                      </div>
                    </div>
                    <div class="caption">
                      <p>{{$item->title}}</p>
                    </div>
                  </div>
                </div>
              
                @endforeach
            </div>
            
                @endif
     
            
               
            </div>
          </div>
      </div>


     
    </div>
    <div class="col-md-2"></div>
  </div>



  <div id="terminate" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
    <Terminateemployee :employee_name="'{{$name}}'" :employee_id="'{{$employee_id}}'" :user_id="'{{Auth::user()->id}}'" ></Terminateemployee>
  
    </div>
  </div>



@endsection