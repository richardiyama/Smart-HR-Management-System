@extends('layouts.dash')

@section('content')
    <!-- page content -->
<div class="right_col" role="main">
        <div class="">
          <div class="clearfix"></div>

          <div class="row">
              <div class="col-md-6">
                <a href="#" class="btn btn-warning"><i class="fa fa-arrow-left"></i>{{{__('employee.button.back')}}}</a>
              </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2> {{__('employee.label.add_new')}} <small>{{__('employee.label.session')}}</small></h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <p>{{{__('employee.label.header')}}}</p>
                <div class="x_content">
                    <br />
                        <h2>{{{__('employee.label.company_header')}}}</h2>
                        <div class="ln_solid"></div>
                    <form id="demo-form2" action={{URL::to("employees/salary")}} method="post" data-parsley-validate class="form-horizontal form-label-left">
                        {{ csrf_field() }}
                        <div class="form-group {{ $errors->has('company') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="company">{{__('employee.label.company')}} <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="company" class="form-control">
                                        @if(count($companies)>0)
                                            <option>All</option>
                                            @foreach ($companies as $company)
                                                <option>{{$company['name']}}</option>
                                            @endforeach
                                        @endif
                                    </select>
    
                                @if ($errors->has('company'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('company') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('site') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">{{__('employee.label.site')}} <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select name="site" class="form-control">
                                    @if(count($sites)>0)
                                        <option>All</option>
                                        @foreach ($sites as $site)
                                            <option>{{$site->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
    
                                @if ($errors->has('site'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('site') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('department') ? ' has-error' : '' }}">
                            <label for="department" class="control-label col-md-3 col-sm-3 col-xs-12">{{__('employee.label.department')}}<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select name="department" class="form-control">
                                    @if(count($departments)>0)
                                        <option>All</option>
                                        @foreach ($departments as $department)
                                            <option>{{$department->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
    
                                @if ($errors->has('department'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('department') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
    
                            
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">{{__('employee.form.job_position')}}<span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select name="position" class="form-control">
                                    @if(count($positions)>0)
                                        <option>All</option>
                                        @foreach ($positions as $position)
                                            <option>{{$position->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
    
                        <div class="form-group {{ $errors->has('employee_no') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="job">{{__('employee.table.employee_no')}} <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="employee" name="employee_no" value="{{old('employee_no')}}" required class="form-control col-md-7 col-xs-12">
    
                                @if ($errors->has('employee_no'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('employee_no') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group  {{ $errors->has('start_date') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">{{__('employee.form.start_date')}} <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <fieldset>
                                    <div class="control-group">
                                        <div class="controls">
                                        <div class="col-md-13 xdisplay_inputx form-group has-feedback">
                                        <input type="text" name="start_date" value="{{old('start_date')}}" class="form-control has-feedback-left" id="single_cal2" placeholder="{{__('employee.form.start_date')}}" aria-describedby="inputSuccess2Status2">
                                            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                            <span id="inputSuccess2Status2" class="sr-only">(success)</span>
                                        </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                        <h2>{{__('employee.label.work_schedule')}}</h2>
                        <hr>
                        <div class="form-group {{ $errors->has('work_mode') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label>{{__('employee.form.work_mode')}} <span class="required">*</span>
                                <div class="radio">
                                    <label>
                                        <input type="radio" value="{{old('work_mode')}}" name="work_mode"> FT
                                    </label>
                                    <label>
                                        <input type="radio"  name="work_mode" value="{{old('work_mode')}}">PT 
                                    </label>
                                </div>
                                @if ($errors->has('work_mode'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('work_mode') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('schedule') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="schedule"> <span class="required"></span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                        <th>{{__('employee.table.no')}}</th>
                                        <th>{{__('employee.table.days')}}</th>
                                        <th>{{__('employee.table.resumption')}}</th>
                                        <th>{{__('employee.table.closing')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($days)>0)
                                            @foreach ($days as $day)
                                            <tr>
                                            <th scope="row">{{$loop->iteration}}</th>
                                                    <td><input type="radio" value="{{old('days')}}" name="{{$day['name']}}"> {{$day['name']}}</td>
                                                    <td>
                                                        <select class="form-control" name="resumption">
                                                            <option>Select</option>
                                                            <option>8:00 am</option>
                                                            <option>9:00 am</option>
                                                            <option>10:00 am</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="closing">
                                                            <option>Select</option>
                                                            <option>4:00 pm</option>
                                                            <option>5:00 pm</option>
                                                            <option>6:00 pm</option>
                                                        </select>
                                                    </td>
                                                    </tr>
                                            @endforeach
                                        @endif
                                        
                                    </tbody>
                                </table>

                                @if ($errors->has('schedule'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('schedule') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('over_time') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label>{{__('employee.form.over_time')}} <span class="required">*</span>
                                <div class="radio">
                                    <label>
                                        <input type="radio" value="{{old('over_time')}}" name="over_time"> Yes
                                    </label>
                                    <label>
                                        <input type="radio"  name="over_time" value="{{old('over_time')}}">No 
                                    </label>
                                </div>
                                @if ($errors->has('over_time'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('over_time') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        
                        <div class="ln_solid"></div>
                        <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button class="btn btn-warning" type="button"><i class="fa fa-arrow-left"></i>{{__('employee.button.back_to')}}</button>
                            <button type="submit" class="btn btn-success">{{__('employee.button.salary')}}<i class="fa fa-arrow-right"></i></button>
                        </div>
                        </div>
                    </form>
                </div>
              </div>
            </div>
          </div>
        </div>
</div>
    
@endsection