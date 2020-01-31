@extends('layouts.auth')
@section('content')
    <div>
        <a class="hiddenanchor" id="signup"></a>
        <a class="hiddenanchor" id="signin"></a>
        <div class="login_wrapper">
          <div class="animate form login_form">
            <section class="login_content">
              <form method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}
                    <div>
                        <h1>{{__('auth.app_name')}}</h1>
                        <strong><p>{{__('auth.discription')}}</p></strong>
                    </div>
                    <div {{ $errors->has('email') ? ' has-error' : '' }}>
                        <input type="email" id="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus />
                        @if ($errors->has('email'))
                            <span class="help-block" style="color:red">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <input id="password" type="password" class="form-control"  name="password" placeholder="Password" required />
                        @if ($errors->has('password'))
                            <span class="help-block" style="color:red">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div>
                        <button type="submit" class="btn btn-default submit">
                            Login
                        </button>
                        <a class="reset_pass" href="{{ route('password.request') }}">Lost your password?</a>
                    </div>
        
                    <div class="clearfix"></div>
        
                        <div>
                        <div class="clearfix"></div>
                        <br />
                        <div>
                            <strong><h1><i class="fa fa-paw"></i>{{__('auth.app_name')}}</h1><strong>
                            <p>©<?= date('Y');?> All Rights Reserved. powered by <a target="_blank" href="https://www.aapeliltd.com">AAPELI<a></p>
                        </div>
                    </div>
              </form>
            </section>
          </div>
        </div>
    </div>

@endsection
   
