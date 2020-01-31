@extends('layouts.dash')

@section('content')



<div class="row">
    <div class="col-md-2 col-xs-12" ></div>
    <div class="col-md-8 col-xs-12">
        <div class="x_panel">
        <a href="{{url('employees')}}"><span class="fa fa-arrow-left" style="color:green"></span>   &nbsp; Back to active employee</a>
          <div class="x_title">
            <h2>Add New Employee <br> <small>Fill in the detail of the employee below then send it for approval</small></h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
              
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <br />

          <form class="form-horizontal form-label-left" method="post" action="{{url('add_employee_company_position')}}">
             {{ csrf_field() }}
                <strong>Company and Position Details</strong> <hr>

            <input type="hidden" value="{{$id}}" name="employee_id">
            <input type="hidden" value="{{Auth::user()->id}}" name="user_id">
    
             
            
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Company</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <select name="company" id="" class="form-control" required>
                      <option value="">Select new employee's company</option>
                      <?php
                        $lists = DB::table('companies')->orderby('id', 'desc')->get();
                        if($lists) {
                          foreach ($lists as $key => $value) {
                            ?>
                          <option value="{{$value->id}}" @if(isset($work)) {{$work->company==$value->id ? 'selected' : ''}} @endif>{{$value->name}}</option>
                            <?php
                          }
                        }
                      ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Site</label>
                  <div class="col-md-9 col-sm-9 col-xs-12">
                    <select name="site" id="" class="form-control" required>
                        <option value="">Select new employee's site</option>
                        <?php
                        $lists = DB::table('sites')->orderby('id', 'desc')->get();
                        if($lists) {
                          foreach ($lists as $key => $value) {
                            ?>
                          <option value="{{$value->id}}" @if(isset($work)) {{$work->site==$value->id ? 'selected' : ''}} @endif>{{$value->name}}</option>
                            <?php
                          }
                        }
                      ?>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Department</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <select name="department" id="" class="form-control" required>
                          <option value="">Select new employee's department</option>
                          <?php
                        $lists = DB::table('departments')->orderby('id', 'desc')->get();
                        if($lists) {
                          foreach ($lists as $key => $value) {
                            ?>
                          <option value="{{$value->id}}" @if(isset($work)) {{$work->department==$value->id ? 'selected' : ''}} @endif>{{$value->name}}</option>
                            <?php
                          }
                        }
                      ?>
                      </select>
                    </div>
                  </div>

                  <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Job Position</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <select name="job_position" id="" class="form-control" required>
                            <option value="">Select new employee's position</option>
                            <?php
                            $lists = DB::table('positions')->orderby('id', 'desc')->get();
                            if($lists) {
                              foreach ($lists as $key => $value) {
                                ?>
                              <option value="{{$value->id}}" @if(isset($work)) {{$work->job_position==$value->id ? 'selected' : ''}} @endif>{{$value->name}}</option>
                                <?php
                              }
                            }
                          ?>
                        </select>
                      </div>
                    </div>
      
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Employee Number</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                        <input disabled type="text" required class="form-control" placeholder="Enter Employee Number" name="empno" value="@if(isset($work)) {{$work->empno}}@endif">
                        <span class="color:red">It will be generated automatically</span>
                        </div>
                      </div>
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Start date <span class="required">*</span>
                          </label>
                          <div class="col-md-9 col-sm-9 col-xs-12">
                           <input @if(isset($work)) type="text" @else type="date" @endif  required name="start_date" class="form-control" value="@if(isset($work)) {{$work->start_date}}@endif">
                          </div>
                        </div>
            
  

              <strong>Work Schedule</strong> <hr>

              <div class="form-group">
                <label class="col-md-3 col-sm-3 col-xs-12 control-label">
                </label>
    
                <div class="col-md-9 col-sm-9 col-xs-12">
                  
                  <div class="radio">
                    <label>
                      <input type="radio" checked="" value="1" id="work_time" name="work_time" value="@if(isset($work)) {{$work->work_time==1 ? 'checked': ''}} @else checked  @endif"  > Full time
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" value="0"  id="work_time" name="work_time" value="@if(isset($work)) {{$work->work_time==0 ? 'checked': ''}} @endif" > Part time
                    </label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Monday</label>
                    <div class="col-md-4 col-sm-5 col-xs-12">
                      <select name="moday_morning" id="" class="form-control" required>
                        <?php $morning = DB::table('morning_period')->get();
                          if($morning) {
                            ?>
                            @foreach ($morning as $item)
                          <option value="{{$item->id}}" @if($item->id==1) selected @endif>{{$item->time}}</option>
                            @endforeach
                            <?php
                          }
                        ?>
                      </select>
                    </div>
                    
                      <div class="col-md-4 col-sm-5 col-xs-12">
                        <select name="moday_evening" id="" class="form-control" required>
                          
                          <?php $evening = DB::table('evening_period')->get();
                          if($evening) {
                            ?>
                            @foreach ($evening as $item)
                          <option value="{{$item->id}}" @if($item->id==1) selected @endif>{{$item->time}}</option>
                            @endforeach
                            <?php
                          }
                        ?>
                        </select>
                      </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Tuesday</label>
                <div class="col-md-4 col-sm-5 col-xs-12">
                  <select name="tuesday_morning" id="" class="form-control" required>
                   
                    <?php $morning = DB::table('morning_period')->get();
                      if($morning) {
                        ?>
                        @foreach ($morning as $item)
                      <option value="{{$item->id}}" @if($item->id==1) selected @endif>{{$item->time}}</option>
                        @endforeach
                        <?php
                      }
                    ?>
                  </select>
                </div>
                
                  <div class="col-md-4 col-sm-5 col-xs-12">
                    <select name="tuesday_evening" id="" class="form-control" required>
                      <?php $evening = DB::table('evening_period')->get();
                      if($evening) {
                        ?>
                        @foreach ($evening as $item)
                      <option value="{{$item->id}}" @if($item->id==1) selected @endif>{{$item->time}}</option>
                        @endforeach
                        <?php
                      }
                    ?>
                    </select>
                  </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Wednesday</label>
            <div class="col-md-4 col-sm-5 col-xs-12">
              <select name="wednesday_morning" id="" class="form-control" required>
                <?php $morning = DB::table('morning_period')->get();
                  if($morning) {
                    ?>
                    @foreach ($morning as $item)
                  <option value="{{$item->id}}" @if($item->id==1) selected @endif>{{$item->time}}</option>
                    @endforeach
                    <?php
                  }
                ?>
              </select>
            </div>
            
              <div class="col-md-4 col-sm-5 col-xs-12">
                <select name="wednesday_evening" id="" class="form-control" required>
                  <?php $evening = DB::table('evening_period')->get();
                  if($evening) {
                    ?>
                    @foreach ($evening as $item)
                  <option value="{{$item->id}}" @if($item->id==1) selected @endif>{{$item->time}}</option>
                    @endforeach
                    <?php
                  }
                ?>
                </select>
              </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Thursday</label>
        <div class="col-md-4 col-sm-5 col-xs-12">
          <select name="thurseday_morning" id="" class="form-control" required>
            <?php $morning = DB::table('morning_period')->get();
              if($morning) {
                ?>
                @foreach ($morning as $item)
              <option value="{{$item->id}}" @if($item->id==1) selected @endif>{{$item->time}}</option>
                @endforeach
                <?php
              }
            ?>
          </select>
        </div>
        
          <div class="col-md-4 col-sm-5 col-xs-12">
            <select name="thurseday_evening" id="" class="form-control" required>
              <?php $evening = DB::table('evening_period')->get();
              if($evening) {
                ?>
                @foreach ($evening as $item)
              <option value="{{$item->id}}" @if($item->id==1) selected @endif>{{$item->time}}</option>
                @endforeach
                <?php
              }
            ?>
            </select>
          </div>
  </div>

  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Friday</label>
    <div class="col-md-4 col-sm-5 col-xs-12">
      <select name="friday_morning" id="" class="form-control" required>
        <?php $morning = DB::table('morning_period')->get();
          if($morning) {
            ?>
            @foreach ($morning as $item)
          <option value="{{$item->id}}" @if($item->id==1) selected @endif>{{$item->time}}</option>
            @endforeach
            <?php
          }
        ?>
      </select>
    </div>
    
      <div class="col-md-4 col-sm-5 col-xs-12">
        <select name="friday_evening" id="" class="form-control" required>
          <?php $evening = DB::table('evening_period')->get();
          if($evening) {
            ?>
            @foreach ($evening as $item)
          <option value="{{$item->id}}" @if($item->id==1) selected @endif>{{$item->time}}</option>
            @endforeach
            <?php
          }
        ?>
        </select>
      </div>
</div>

<div class="form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12">Saturday</label>
  <div class="col-md-4 col-sm-5 col-xs-12">
    <select name="saturday_morning" id="" class="form-control" required>
      <?php $morning = DB::table('morning_period')->get();
        if($morning) {
          ?>
          @foreach ($morning as $item)
        <option value="{{$item->id}}" @if($item->id==1) selected @endif>{{$item->time}}</option>
          @endforeach
          <?php
        }
      ?>
    </select>
  </div>
  
    <div class="col-md-4 col-sm-5 col-xs-12">
      <select name="saturday_evening" id="" class="form-control" required>
        <?php $evening = DB::table('evening_period')->get();
        if($evening) {
          ?>
          @foreach ($evening as $item)
        <option value="{{$item->id}}" @if($item->id==1) selected @endif >{{$item->time}}</option>
          @endforeach
          <?php
        }
      ?>
      </select>
    </div>
</div>

<div class="form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12">Sunday</label>
  <div class="col-md-4 col-sm-5 col-xs-12">
    <select name="sunday_morning" id="" class="form-control">
      <option value="">Select a time</option>
      <?php $morning = DB::table('morning_period')->get();
        if($morning) {
          ?>
          @foreach ($morning as $item)
        <option value="{{$item->id}}" @if($item->id==1) selected @endif >{{$item->time}}</option>
          @endforeach
          <?php
        }
      ?>
    </select>
  </div>
  
    <div class="col-md-4 col-sm-5 col-xs-12">
      <select name="sunday_evening" id="" class="form-control">
        <option value="">Select a time</option>
        <?php $evening = DB::table('evening_period')->get();
        if($evening) {
          ?>
          @foreach ($evening as $item)
        <option value="{{$item->id}}" @if($item->id==1) selected @endif>{{$item->time}}</option>
          @endforeach
          <?php
        }
      ?>
      </select>
    </div>
</div>

<div class="form-group">
  <label class="col-md-3 col-sm-3 col-xs-12 control-label">
    Does this employee qualify for overtime ?
  </label>

  <div class="col-md-9 col-sm-9 col-xs-12">
    
    <div class="radio">
      <label>
        <input type="radio" @if(isset($work)) {{$work->overtime == 1 ? 'checked' : ''}}  @endif value="1" id="overtime" name="overtime" > Yes
      </label>
    </div>
    <div class="radio">
      <label>
        <input type="radio" value="0"  id="overtime" name="overtime" @if(isset($work)) {{$work->overtime == 0 ? 'checked' : ''}} @else checked @endif>No
      </label>
    </div>
  </div>
</div>
                  
                 
                <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                                <button type="submit" class="btn btn-success">Salary and Account Details <span class="fa fa-arrow-right"></span></button>
                            </div>
                </div>
              
            </form>
          </div>
        </div>
      </div>
</div>


    
@endsection