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
                         <h2>{{{__('employee.label.salary_header')}}}</h2>
                         <div class="ln_solid"></div>
                    <form id="demo-form2" method="POST" action={{URL::to('employees/nextKin')}} data-parsley-validate class="form-horizontal form-label-left">
                                {{ csrf_field() }}
                            <div class="form-group {{ $errors->has('salary') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="salary">{{__('employee.form.salary')}} <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="first-name" required name="salary" value="{{old('salary')}}"  autofocus class="form-control col-md-7 col-xs-12">
        
                                    @if ($errors->has('salary'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('salary') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">{{__('employee.form.bank')}}<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="bank" class="form-control">
                                        @if(count($banks)>0)
                                            <option>All</option>
                                            @foreach ($banks as $bank)
                                                <option>{{$bank->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>


                            <div class="form-group {{ $errors->has('account_no') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="account-no">{{__('employee.form.account_no')}} <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="account-no" name="account_no" value="{{old('account_no')}}" required class="form-control col-md-7 col-xs-12">
        
                                    @if ($errors->has('account_no'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('l_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">{{__('employee.form.pension')}}<span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select name="pension" class="form-control">
                                            @if(count($pensions)>0)
                                                <option>All</option>
                                                @foreach ($pensions as $pension)
                                                    <option>{{$pension->name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                            <div class="form-group {{ $errors->has('pension_no') ? ' has-error' : '' }}">
                                <label for="pension-no" class="control-label col-md-3 col-sm-3 col-xs-12">{{__('employee.form.pension_no')}}<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="pension-no" value="{{old('pension_no')}}" class="form-control col-md-7 col-xs-12" type="text" name="m_name">
        
                                    @if ($errors->has('pension_no'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('pension_no') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                          <div class="ln_solid"></div>
                          <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                              <button type="submit" class="btn btn-success">{{__('employee.button.next_kin')}}<i class="fa fa-arrow-right"></i></button>
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