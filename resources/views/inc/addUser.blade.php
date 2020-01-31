<!-- page content -->

    <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
        <div class="x_title">
            <h2>Add New User</h2>
            
            <div class="clearfix"></div>
        </div>
        <div class="x_content" style="text-align:center">
                <h4 class="modal-title" id="myModalLabel">{{__('user_role.form.user_details')}}</h4>
            <br />
            <form id="roleform" data-parsley-validate class="form-horizontal form-label-left" method="POST" action="{{ route('UserRole.store') }}">
                {{ csrf_field() }}
              <div class="modal-body">
                <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">{{__('user_role.form.name')}} <span class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required="" class="form-control col-md-7 col-xs-12">
                    
                    @if ($errors->has('name'))
                      <span class="help-block">
                          <strong>{{ $errors->first('name') }}</strong>
                      </span>
                    @endif
                  </div>
                </div>
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">{{__('user_role.form.email')}}<span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="email" id="email" required="required" name="email" value="{{ old('email') }}" class="form-control col-md-7 col-xs-12">
                    
                      @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                      @endif
                    </div>
                </div>
                  <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role">{{__('user_role.form.role')}}<span class="required">*</span></label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="role" class="form-control">
                              <option value="">Select Role</option>
                              @foreach ($roles as $role)
                              <option value="{{$role['id']}}">{{$role['role_name']}}</option>
                              @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">{{__('user_role.form.password')}}<span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="password" id="password" required name="password" class="form-control col-md-7 col-xs-12">

                        @if ($errors->has('password'))
                          <span class="help-block">
                              <strong>{{ $errors->first('password') }}</strong>
                          </span>
                        @endif
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">{{__('user_role.form.confirm_password')}}<span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="password" id="password-confirm" name="password_confirmation" required="required" class="form-control col-md-7 col-xs-12">
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
              <a href="{{url('users')}}" class="btn btn-default">{{__('user_role.button.back')}}</a>
                <button type="submit" class="btn btn-success">{{__('user_role.button.submit')}}</button>
              </div>
          </form>
        </div>
        </div>
    </div>
    
</div>    
