@extends('layouts.dash')

@section('content')
    <!-- page content -->
<div class="right_col" role="main">
        <div class="">
          <div class="clearfix"></div>

          <div class="row">
                <a href="#" class="btn btn-warning"><i class="fa fa-arrow-left"></i>{{{__('employee.button.back')}}}</a>
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2> {{__('employee.label.add_new')}} <small>{{__('employee.label.session')}}</small></h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                        <br />
                         <p>{{{__('employee.label.header')}}}</p>
                         <h2>Personal Details</h2>
                         <div class="ln_solid"></div>
                    <form id="demo-form2" method="POST" action="{{URL::to('employees/company')}}"  data-parsley-validate class="form-horizontal form-label-left">
                            {{csrf_field() }}
                            <div class="form-group {{ $errors->has('f_name') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">{{__('employee.form.fname')}} <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="first-name" required name="f_name" value="{{old('f_name')}}"  autofocus class="form-control col-md-7 col-xs-12">
        
                                    @if ($errors->has('f_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('f_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('l_name') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">{{__('employee.form.lname')}} <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="last-name" name="l_name" value="{{old('l_name')}}" required class="form-control col-md-7 col-xs-12">
        
                                    @if ($errors->has('l_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('l_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('m_name') ? ' has-error' : '' }}">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">{{__('employee.form.lname')}}<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="middle-name" value="{{old('m_name')}}" class="form-control col-md-7 col-xs-12" type="text" name="m_name">
        
                                    @if ($errors->has('m_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('m_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
        
                            <div class="form-group  {{ $errors->has('dob') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">{{__('employee.form.dob')}} <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <fieldset>
                                        <div class="control-group">
                                            <div class="controls">
                                            <div class="col-md-13 xdisplay_inputx form-group has-feedback">
                                            <input type="text" name="dob" value="{{old('dob')}}" class="form-control has-feedback-left" id="single_cal2" placeholder="Choose date" aria-describedby="inputSuccess2Status2">
                                            @if ($errors->has('dob'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('dob') }}</strong>
                                                </span>
                                            @endif
                                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                                <span id="inputSuccess2Status2" class="sr-only">(success)</span>
                                            </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                                
                            <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">{{__('employee.form.gender')}}<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="gender" class="form-control">
                                        <option>Select</option>
                                        <option>{{__('employee.form.male')}}</option>
                                        <option>{{__('employee.form.female')}}</option>
                                        @if ($errors->has('gender'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('gender') }}</strong>
                                        </span>
                                    @endif
                                    </select>
                                </div>
                            </div>
        
                            <div class="form-group {{ $errors->has('nationality') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">{{__('employee.form.nationality')}} <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="nationality" name="nationality" value="{{old('nationality')}}" required class="form-control col-md-7 col-xs-12">
        
                                    @if ($errors->has('nationality'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('nationality') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
    
                            <div class="form-group {{ $errors->has('license') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                            <label>{{__('employee.form.license')}} <span class="required">*</span>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" value="1" name="license"> Yes
                                            </label>
                                            <label>
                                                <input type="radio"  name="license" value="0"> No 
                                            </label>
                                        </div>
                                        @if ($errors->has('license'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('license') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                    <h2>Contact Details</h2>
                                    <div class="ln_solid"></div>
                                    
                                <div class="form-group {{ $errors->has('phone_no') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">{{__('employee.form.phone_no')}} <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="phone-no" required name="phone_no" value="{{old('phone_no')}}" class="form-control col-md-7 col-xs-12">
        
                                        @if ($errors->has('phone_no'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('phone_no') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">{{__('employee.form.email')}} <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="email" id="email" name="email" value="{{old('email')}}" required class="form-control col-md-7 col-xs-12">
        
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('address') ? ' has-error' : '' }}">
                                    <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">{{__('employee.form.address')}}<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea id="middle-name" class="form-control col-md-7 col-xs-12" type="text" name="address">{{old('address')}}</textarea>
            
                                        @if ($errors->has('address'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('address') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                          <div class="ln_solid"></div>
                          <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                              <button type="submit" class="btn btn-success">{{__('employee.button.company_position')}}<i class="fa fa-arrow-right"></i></button>
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