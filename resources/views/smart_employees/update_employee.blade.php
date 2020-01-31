@extends('layouts.dash')

@section('content')
<div class="row">
    <div class="col-md-2"></div>
    
    <div class="col-md-8 col-sm-8 col-xs-12">
    <form action="{{url('update-employee1')}}" method="post">
    <input type="hidden" value="{{$employee_id}}" name="employee_id">
    {{ csrf_field() }}
 
    <a href="{{url('employees')}}" style="" title="Back to active employee"><span class="fa fa-arrow-left" style="color:green; font-size:15px;"> Back to Active Employee</span></a> <br><br>
            <button class="btn btn-secondary btn-lg" disabled>Terminate employee contract</button>
      <div class="x_panel"> <br>
       
        <div class="x_title">
        <button type="submit" class="btn btn-secondary" title="Save this employee"><span class="fa fa-save" style="color:green; font-size:15px;"> Save employee detail</span></button>
       <br><br>
          <h2>Personal Detail</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          
              
         
            <div class="row">
               <div class="col-md-6">
                   <span class="text-muted">First Name</span> <br>
               <input type="text" value="@if($personal){{$personal->firstname}}@endif" class="form-control" placeholder="Firstname" name="firstname" required>
               </div>
               <div class="col-md-6">
                    <span class="text-muted">Last name</span> <br>
                    <input type="text" value="@if($personal){{$personal->lastname}}@endif" class="form-control" placeholder="Lastname" name="lastname" required>
                </div>
           </div>
           <br>
           <div class="row">
                <div class="col-md-6">
                    <span class="text-muted">Middle Name</span> <br>
                    <input type="text" value="@if($personal){{$personal->middlename}}@endif" class="form-control" placeholder="Middle name" name="middlename">
                </div>
            </div>
            <br>
            <div class="row">
                 <div class="col-md-6">
                     <span class="text-muted">Date of birth</span> <br>
                 <input type="date" value="@if($personal){{$personal->date_of_birth}}@endif" class="form-control"  name="date_of_birth" required>
                 <span style="color:red">Date Format: mm/dd/yyyy eg 01/23/1986</span>
                 </div>
                 <div class="col-md-6">
                        <span class="text-muted">Gender</span> <br>
                    
                    <select name="gender" id="" class="form-control" required>
                        <option value="M" @if ($personal && $personal->gender == "M")
                            checked
                        @endif>Male</option>
                        <option value="F" @if ($personal && $personal->gender == "F")
                            checked
                        @endif>Female</option>
                    </select>
                    </div>
             </div>
             <br>
             <div class="row">
                  <div class="col-md-6">
                      <span class="text-muted">Nationality</span> <br>
                      <input type="text" value="@if($personal){{$personal->nationality}}@endif" class="form-control" placeholder="Nationality"  name="nationality" required>
                  </div>
                  <div class="col-md-6">
                        <span class="text-muted">Phone</span> <br>
                        <input type="text" value="@if($personal){{$personal->phone}}@endif" class="form-control" placeholder="Phone Number"  name="phone" required>
                    </div>
              </div>
              <br>
              <div class="row">
                   <div class="col-md-6">
                       <span class="text-muted">Does this employee have driver license ?</span> <br>
                   <strong>
                       <input type="radio" name="driver_license" @if ($personal && $personal->driver_license==1) checked
                           
                       @endif> Yes
                       <input type="radio" name="driver_license" @if ($personal && $personal->driver_license==0) checked
                           
                       @endif> No
                   </strong>
                   </div>
               </div>
               <br>
               <div class="row">
                    <div class="col-md-6">
                        <span class="text-muted">Driver license Number</span> <br>
                        <input type="text" value="@if($personal){{$personal->driver_license_no}}@endif" class="form-control" placeholder="Driver license"  name="driver_license_no">
                    </div>
                </div>
                
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
                
          
           <div class="row">
               <div class="col-md-6">
                   <span class="text-muted">Current company</span> <br>
                   <?php 
                    $companies = DB::table('companies')->get();
                    if($companies) {
                        ?>
<select name="company" class="form-control" id="">
    @foreach ($companies as $item)
<option value="{{$item->id}}" @if ($contact && $item->id == $contact->company)
    selected
@endif>{{$item->name}}</option>
    @endforeach
</select>
                        <?php
                    }
                    ?>
                    
               
               </div>
               <div class="col-md-6">
                    <span class="text-muted">Current site</span> <br>
                    
                            <?php 
                            $sites = DB::table('sites')->get();
                            if($sites) {
                                ?>
                                <select name="site" class="form-control" id="">
                                    @foreach ($sites as $item)
                                <option value="{{$item->id}}" @if ($contact && $item->id == $contact->site)
                                    selected
                                @endif>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                <?php
                            }
                            ?>
                
                </div>
           </div>
           <br>
           <div class="row">
                <div class="col-md-6">
                    <span class="text-muted">Current department</span> <br>
            
                    <?php 
                     $departments = DB::table('departments')->get();
                     if($departments) {
                         ?>
                           <select name="department" class="form-control" id="">
                            @foreach ($departments as $item)
                        <option value="{{$item->id}}" @if ($contact && $item->id == $contact->department)
                            selected
                        @endif>{{$item->name}}</option>
                            @endforeach
                        </select>
                         <?php
                     }
                     ?>
        
                </div>
                <div class="col-md-6">
                     <span class="text-muted">Current position</span> <br>
            
                             <?php 
                             $positions = DB::table('positions')->get();
                             if($positions) {
                                 ?>
                                   <select name="position" class="form-control" id="">
                                    @foreach ($positions as $item)
                                <option value="{{$item->id}}" @if ($contact && $item->id == $contact->job_position)
                                    selected
                                @endif>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                 <?php
                             }
                             ?>
                    
                 </div>
            </div>
            <br>
            <?php 
            $empl = DB::table('employee_bvn')->where('id', $employee_id)->first();
            ?>
            <div class="row">
                 <div class="col-md-6">
                     <span class="text-muted">Employee number</span> <br>
                
                 <input disabled type="text" class="form-control" name="empno" placeholder="Employee number" value="@if($empl){{$empl->employee_number}}@endif">
            
                 </div>
                 <div class="col-md-6">
                        <span class="text-muted">Start date</span> <br>
            
                        <input type="date" class="form-control" name="start_date" required  value="@if($contact){{$contact->start_date}}@endif">

                        <span style="color:red">Date Format: mm/dd/yyyy eg 01/23/2019</span>
                    
                    </div>
             </div>
             
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
                
           
            <div class="row">
                <div class="col-md-6">
                    <input type="radio" name="work_time"  @if ($contact && $contact->work_time == 1)
                        checked
                    @endif> Full time
                    &nbsp; &nbsp;
                    <input type="radio" name="work_time" @if ($contact && $contact->work_time == 0)
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
                        <select name="moday_morning" id="" class="form-control" required>
                                <option value="">Select a time</option>
                                <?php $morning = DB::table('morning_period')->get();
                                  if($morning) {
                                    ?>
                                    @foreach ($morning as $item)
                                  <option value="{{$item->id}}" @if ($contact && $item->id == $contact->moday_morning)
                                      selected
                                  @endif>{{date('h:i A', strtotime($item->time))}}</option>
                                    @endforeach
                                    <?php
                                  }
                                ?>
                              </select>
                    </div>
            
                    <div class="col-md-4">
                            <span class="text-muted">Closing time</span> <br>
                            <select name="moday_evening" id="" class="form-control" required>
                                    <option value="">Select a time</option>
                                    <?php $evening = DB::table('evening_period')->get();
                                      if($evening) {
                                        ?>
                                        @foreach ($evening as $item)
                                      <option value="{{$item->id}}" @if ($contact && $item->id == $contact->moday_evening)
                                          selected
                                      @endif>{{date('h:i A', strtotime($item->time))}}</option>
                                        @endforeach
                                        <?php
                                      }
                                    ?>
                                  </select>
                        </div>
                
            </div>
            <br>
            <div class="row">
                    <div class="col-md-2">
                        <span>Tuesday</span>
                    </div>
    
                    <div class="col-md-4">
                            <span class="text-muted">Resumption time</span> <br>
                            <select name="tuesday_morning" id="" class="form-control" required>
                                    <option value="">Select a time</option>
                                    <?php $morning = DB::table('morning_period')->get();
                                      if($morning) {
                                        ?>
                                        @foreach ($morning as $item)
                                      <option value="{{$item->id}}" @if ($contact && $item->id == $contact->tuesday_morning)
                                          selected
                                      @endif>{{date('h:i A', strtotime($item->time))}}</option>
                                        @endforeach
                                        <?php
                                      }
                                    ?>
                                  </select>
                        </div>
                
                        <div class="col-md-4">
                                <span class="text-muted">Closing time</span> <br>
                                <select name="tuesday_evening" id="" class="form-control" required>
                                        <option value="">Select a time</option>
                                        <?php $evening = DB::table('evening_period')->get();
                                          if($evening) {
                                            ?>
                                            @foreach ($evening as $item)
                                          <option value="{{$item->id}}" @if ($contact && $item->id == $contact->tuesday_evening)
                                              selected
                                          @endif>{{date('h:i A', strtotime($item->time))}}</option>
                                            @endforeach
                                            <?php
                                          }
                                        ?>
                                      </select>
                            </div>
                    
                </div>
                <br>
                <div class="row">
                        <div class="col-md-2">
                            <span>Wednesday</span>
                        </div>
        
                        <div class="col-md-4">
                                <span class="text-muted">Resumption time</span> <br>
                                <select name="wednesday_morning" id="" class="form-control" required>
                                        <option value="">Select a time</option>
                                        <?php $morning = DB::table('morning_period')->get();
                                          if($morning) {
                                            ?>
                                            @foreach ($morning as $item)
                                          <option value="{{$item->id}}" @if ($contact && $item->id == $contact->wednesday_morning)
                                              selected
                                          @endif>{{date('h:i A', strtotime($item->time))}}</option>
                                            @endforeach
                                            <?php
                                          }
                                        ?>
                                      </select>
                            </div>
                    
                            <div class="col-md-4">
                                    <span class="text-muted">Closing time</span> <br>
                                    <select name="wednesday_evening" id="" class="form-control" required>
                                            <option value="">Select a time</option>
                                            <?php $evening = DB::table('evening_period')->get();
                                              if($evening) {
                                                ?>
                                                @foreach ($evening as $item)
                                              <option value="{{$item->id}}" @if ($contact && $item->id == $contact->wednesday_evening)
                                                  selected
                                              @endif>{{date('h:i A', strtotime($item->time))}}</option>
                                                @endforeach
                                                <?php
                                              }
                                            ?>
                                          </select>
                                </div>
                        
                    </div>
                    <br>
                    <div class="row">
                            <div class="col-md-2">
                                <span>Thurseday</span>
                            </div>
            
                            <div class="col-md-4">
                                    <span class="text-muted">Resumption time</span> <br>
                                    <select name="thurseday_morning" id="" class="form-control" required>
                                            <option value="">Select a time</option>
                                            <?php $morning = DB::table('morning_period')->get();
                                              if($morning) {
                                                ?>
                                                @foreach ($morning as $item)
                                              <option value="{{$item->id}}" @if ($contact && $item->id == $contact->thurseday_morning)
                                                  selected
                                              @endif>{{date('h:i A', strtotime($item->time))}}</option>
                                                @endforeach
                                                <?php
                                              }
                                            ?>
                                          </select>
                                </div>
                        
                                <div class="col-md-4">
                                        <span class="text-muted">Closing time</span> <br>
                                        <select name="thurseday_evening" id="" class="form-control" required>
                                                <option value="">Select a time</option>
                                                <?php $evening = DB::table('evening_period')->get();
                                                  if($evening) {
                                                    ?>
                                                    @foreach ($evening as $item)
                                                  <option value="{{$item->id}}" @if ($contact && $item->id == $contact->thurseday_evening)
                                                      selected
                                                  @endif>{{date('h:i A', strtotime($item->time))}}</option>
                                                    @endforeach
                                                    <?php
                                                  }
                                                ?>
                                              </select>
                                    </div>
                            
                </div>
                <br>
                <div class="row">
                        <div class="col-md-2">
                            <span>Friday</span>
                        </div>
        
                        <div class="col-md-4">
                                <span class="text-muted">Resumption time</span> <br>
                                <select name="friday_morning" id="" class="form-control" required>
                                        <option value="">Select a time</option>
                                        <?php $morning = DB::table('morning_period')->get();
                                          if($morning) {
                                            ?>
                                            @foreach ($morning as $item)
                                          <option value="{{$item->id}}" @if ($contact && $item->id == $contact->friday_morning)
                                              selected
                                          @endif>{{date('h:i A', strtotime($item->time))}}</option>
                                            @endforeach
                                            <?php
                                          }
                                        ?>
                                      </select>
                            </div>
                    
                            <div class="col-md-4">
                                    <span class="text-muted">Closing time</span> <br>
                                    <select name="friday_evening" id="" class="form-control" required>
                                            <option value="">Select a time</option>
                                            <?php $evening = DB::table('evening_period')->get();
                                              if($evening) {
                                                ?>
                                                @foreach ($evening as $item)
                                              <option value="{{$item->id}}" @if ($contact && $item->id == $contact->friday_evening)
                                                  selected
                                              @endif>{{date('h:i A', strtotime($item->time))}}</option>
                                                @endforeach
                                                <?php
                                              }
                                            ?>
                                          </select>
                                </div>
                        
            </div>
            <br>
            <div class="row">
                    <div class="col-md-2">
                        <span>Saturday</span>
                    </div>
    
                    <div class="col-md-4">
                            <span class="text-muted">Resumption time</span> <br>
                            <select name="saturday_morning" id="" class="form-control" required>
                                    <option value="">Select a time</option>
                                    <?php $morning = DB::table('morning_period')->get();
                                      if($morning) {
                                        ?>
                                        @foreach ($morning as $item)
                                      <option value="{{$item->id}}" @if ($contact && $item->id == $contact->saturday_morning)
                                          selected
                                      @endif>{{date('h:i A', strtotime($item->time))}}</option>
                                        @endforeach
                                        <?php
                                      }
                                    ?>
                                  </select>
                        </div>
                
                        <div class="col-md-4">
                                <span class="text-muted">Closing time</span> <br>
                                <select name="saturday_evening" id="" class="form-control" required>
                                        <option value="">Select a time</option>
                                        <?php $evening = DB::table('evening_period')->get();
                                          if($evening) {
                                            ?>
                                            @foreach ($evening as $item)
                                          <option value="{{$item->id}}" @if ($contact && $item->id == $contact->saturday_evening)
                                              selected
                                          @endif>{{date('h:i A', strtotime($item->time))}}</option>
                                            @endforeach
                                            <?php
                                          }
                                        ?>
                                      </select>
                            </div>
                    
            </div>
            <br>
            <div class="row">
                    <div class="col-md-2">
                        <span>Sunday</span>
                    </div>
    
                    <div class="col-md-4">
                            <span class="text-muted">Resumption time</span> <br>
                            <select name="sunday_morning" id="" class="form-control">
                                    <option value="">Select a time</option>
                                    <?php $morning = DB::table('morning_period')->get();
                                      if($morning) {
                                        ?>
                                        @foreach ($morning as $item)
                                      <option value="{{$item->id}}" @if ($contact && $item->id == $contact->sunday_morning)
                                          selected
                                      @endif>{{date('h:i A', strtotime($item->time))}}</option>
                                        @endforeach
                                        <?php
                                      }
                                    ?>
                                  </select>
                        </div>
                
                        <div class="col-md-4">
                                <span class="text-muted">Closing time</span> <br>
                                <select name="sunday_evening" id="" class="form-control">
                                        <option value="">Select a time</option>
                                        <?php $evening = DB::table('evening_period')->get();
                                          if($evening) {
                                            ?>
                                            @foreach ($evening as $item)
                                          <option value="{{$item->id}}" @if ($contact && $item->id == $contact->sunday_evening)
                                              selected
                                          @endif>{{date('h:i A', strtotime($item->time))}}</option>
                                            @endforeach
                                            <?php
                                          }
                                        ?>
                                      </select>
                            </div>
                    
                </div>
                <br>
                <div class="row">
                     <div class="col-md-6">
                         <span class="text-muted">Does this employee qualify for overtime ? </span> <br>
                     <strong>
                         <input type="radio" name="overtime" @if ($contact && $contact->overtime==1) checked
                             
                         @endif> Yes
                         <input type="radio" name="overtime" @if ($contact && $contact->overtime==0) checked
                             
                         @endif> No
                     </strong>
                     </div>
                 </div>
                 
      
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
            <div class="row">
                    <div class="col-md-6">
                        <span class="text-muted">Salary</span> <br>
                    <input type="text" required name="salary" placeholder="Salary" required value="@if($salary){{$salary->salary}}@endif"> NGN
                    </div>
                    <div class="col-md-6">
    
                    </div>
                </div>
            <br>
            <div class="row">
                    <div class="col-md-6">
                        <span class="text-muted">Bank name</span> <br>
                    <select name="bank" required class="form-control" id="">
                        <option value="">Select Bank</option>
                   
                        <?php 
                        $banks = DB::table('banks')->get();
                        if($banks) {
                            ?>
                            @foreach ($banks as $item)
                        <option value="{{$item->id}}" @if ($salary && $item->id == $salary->bank)
                            selected
                        @endif>{{$item->name}}</option>
                            @endforeach
                            <?php
                        }    
                        ?>
                         </select>
                    
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted">Account number</span> <br>
                    <input type="text" maxlength="10" required name="account_number" required value="@if($salary){{$salary->account_number}}@endif" class="form-control" placeholder="Account number">
                    
                    </div>
                </div>
             <br>
             <div class="row">
                    <div class="col-md-6">
                        <span class="text-muted">Personal Fund Administrator(PFA)</span> <br>
                     <select name="pfa" id="" class="form-control">
                         <option value="">Select Pension Fund Administrator(PFA)</option>
                            <?php 
                            $pfas = DB::table('pfa')->get();
                            if($pfas) {
                                ?>
                                @foreach ($pfas as $item)
                            <option value="{{$item->id}}" @if ($salary && $item->id == $salary->pfa)
                                selected
                            @endif>{{$item->name}}</option>
                                @endforeach
                                <?php
                            }    
                            ?>
                     </select>
                        
                
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted">Pension number</span> <br>
                        <input type="text" name="pension_number" required value="@if($salary){{$salary->pension_number}}@endif" class="form-control" placeholder="Pension number">
                   
                    </div>
                </div>        
        </div>
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
            <div class="row">
                    <div class="col-md-6">
                        <span class="text-muted">Next of kin name</span> <br>
                        <input required type="text" name="next_kin_name" required value="@if($salary){{$salary->next_kin_name}}@endif" class="form-control" placeholder="next of kin name">
                    </div>
                    <div class="col-md-6">
                            <span class="text-muted">Next of kin phone number</span> <br>
                            <input type="text" name="next_kin_phone" required value="@if($salary){{$salary->next_kin_phone}}@endif" class="form-control" placeholder="next of kin phone number">
                    </div>
                </div>
            <br>
            <div class="row">
                    <div class="col-md-6">
                            <span class="text-muted">Next of kin relationship</span> <br>
                            <input type="text" name="next_kin_relationship" required value="@if($salary){{$salary->next_kin_relationship}}@endif" class="form-control" placeholder="next of kin relationship">
                    </div>
                    <div class="col-md-6">
                       
                    </div>
                </div>
             <br>
             <div class="row">
                    <div class="col-md-6">
                        <span class="text-muted">Next of kin address</span> <br>
                        <input type="text" name="next_kin_address" required value="@if($salary){{$salary->next_kin_address}}@endif" class="form-control" placeholder="next of kin address">
                    </div>
                    <div class="col-md-6">
                        
                    </div>
                </div>
        </div>
      </div>

    </form>


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
            
        <Emergencycontact :employee_id="'{{$employee_id}}'"></Emergencycontact>

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
                    <a onclick="return confirm('Are you sure you want to delete this documents')" href="{{url('delete-employee-file1')}}/{{$item->id}}/{{$item->employee_id}}" title="delete this file"><i class="fa fa-times"></i></a>
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
 
        <hr>
        <form class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="{{url('add_employee_support_document1')}}">
            {{ csrf_field() }}
               <strong>Emergency Contact</strong> <hr>

           <input type="hidden" value="{{$employee_id}}" name="employee_id">
           <input type="hidden" value="{{Auth::user()->id}}" name="user_id">
   
           
           
             <div class="form-group">
               <label class="control-label col-md-3 col-sm-3 col-xs-12">Select employee document to upload</label>
               <div class="col-md-9 col-sm-9 col-xs-12">
                 <div class="col-md-9 col-sm-9 col-xs-12">
                   <input type="file" required class="form-control" placeholder="" name="file">
                 </div>
               </div>
             </div>
             <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Document title</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" required class="form-control" placeholder="Document title" name="title">
                  </div>
                </div>
             </div>


               <button type="submit" class="btn btn-success btn-sm pull-right">Upload</button>
           </form>
           
        </div>
      </div>
    </div>
    <div class="col-md-2"></div>
  </div>
@endsection