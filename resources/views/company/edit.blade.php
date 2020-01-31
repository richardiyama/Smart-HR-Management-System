<!-- page content -->
@extends('layouts.dash')

@section('content')
<div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
            <div class="x_title">
                <h2>Edit Company<small>Record</small></h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br />
                <form id="roleform" data-parsley-validate class="form-horizontal form-label-left" method="POST" action="{{ route('company.update', $company->id )}}">
                        {{ method_field('PATCH') }}
                        {{ csrf_field() }}
                        
                      <div class="modal-body">
                        <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">{{__('user_role.form.name')}} <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="name" name="name" value="{{ $company->name }}" required="" class="form-control col-md-7 col-xs-12">
                            
                            @if ($errors->has('name'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('name') }}</strong>
                              </span>
                            @endif
                          </div>
                        </div>
                        
                      <div class="modal-footer">
                        <a href="/company" class="btn btn-default">Back to Companies</a>
                        <button type="submit" class="btn btn-success">Update</button>
                      </div>
                  </form>
            </div>
            </div>
        </div>
        </div>
    
        @endsection