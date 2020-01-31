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
                         <h2>{{{__('employee.label.nextkin_header')}}}</h2>
                         <div class="ln_solid"></div>
                        <form id="demo-form2" method="POST" data-parsley-validate class="form-horizontal form-label-left">
                                {{ csrf_field() }}
                            <div class="form-group {{ $errors->has('n_name') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="n_name">{{__('employee.form.n_name')}} <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="next-name" required name="n_name" value="{{old('n_name')}}"  autofocus class="form-control col-md-7 col-xs-12">
        
                                    @if ($errors->has('n_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('n_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('n_phone_no') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="next-phone-no">{{__('employee.form.n_phone_no')}} <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="next-phone-no" name="n_phone_no" value="{{old('n_phone_no')}}" required class="form-control col-md-7 col-xs-12">
        
                                    @if ($errors->has('n_phone_no'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('n_phone_no') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('n_relationship') ? ' has-error' : '' }}">
                                <label for="n-relationship" class="control-label col-md-3 col-sm-3 col-xs-12">{{__('employee.form.relationship')}}<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="n-relationship" name="n_relationship" value="{{old('n_relationship')}}" class="form-control col-md-7 col-xs-12" type="text">
        
                                    @if ($errors->has('n_relationship'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('n_relationship') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
        
        
                            <div class="form-group {{ $errors->has('n_address') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="n-address">{{__('employee.form.n_address')}} <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <textarea type="text" id="n-address" name="n_address" required class="form-control col-md-7 col-xs-12">{{old('n_address')}}</textarea>
        
                                    @if ($errors->has('n_address'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('n_address') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
    
                                    <h2>{{__('employee.label.emergency_header')}}</h2>
                                    <div class="ln_solid"></div>
                                    
                                <div class="form-group {{ $errors->has('e_name') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="e-name">{{__('employee.form.e_name')}} <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="e-name" required name="e_name" value="{{old('e_name')}}" class="form-control col-md-7 col-xs-12">
        
                                        @if ($errors->has('e_name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('e_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('e_phone_no') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">{{__('employee.form.e_phone_no')}} <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="e-number" name="e_phone_no" value="{{old('e_phone_no')}}" required class="form-control col-md-7 col-xs-12">
        
                                        @if ($errors->has('e_phone_no'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('e_phone_no') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                          <div class="ln_solid"></div>
                          <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                              <button type="submit" class="btn btn-success">{{__('employee.button.finish')}}</button>
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