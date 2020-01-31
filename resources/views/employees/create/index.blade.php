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
                         <h2>{{{__('employee.label.bvn_header')}}}</h2>
                         <div class="ln_solid"></div>
                        <form id="demo-form2" method="POST" action={{URL::to('employees/create/personalDetails') }} data-parsley-validate class="form-horizontal form-label-left">
                            {{ csrf_field() }}
                            <div class="form-group {{ $errors->has('bvn') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bvn">{{__('employee.form.bvn')}} <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="bvn" required name="bvn" value="{{old('bvn')}}"  autofocus class="form-control col-md-7 col-xs-12">
        
                                    @if ($errors->has('bvn'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('bvn') }}</strong>
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