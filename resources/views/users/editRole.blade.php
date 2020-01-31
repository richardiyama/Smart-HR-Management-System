@extends('layouts.dash')

@section('content')
<!-- page content -->

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
        <div class="x_title">
            <h2>Edit Role </h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br />
            <form id="roleform" data-parsley-validate class="form-horizontal form-label-left" method="POST" action="{{ url('update1' )}}">
        
                    {{ csrf_field() }}
                   <input type="hidden" value="{{$role->id}}" name="id">
                  <div class="modal-body">
                    <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">{{__('user_role.form.name')}} <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" id="role_name" name="role_name" value="{{ $role->role_name }}" required="" class="form-control col-md-7 col-xs-12">
                        
                        @if ($errors->has('role_name'))
                          <span class="help-block">
                              <strong>{{ $errors->first('role_name') }}</strong>
                          </span>
                        @endif
                      </div>
                    </div>
                    
                     
                    
                  </div>
                  <div class="modal-footer">
                    <a href="{{url('users/roles')}}" class="btn btn-default">Back to role</a>
                    <button type="submit" class="btn btn-success">Update Role</button>
                  </div>
              </form>
        </div>
        </div>
    </div>
    </div>
  
  
@endsection