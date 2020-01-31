<!-- page content -->
@extends('layouts.dash')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
        <div class="x_title">
            <h2>Add New Company</h2>
            
            <div class="clearfix"></div>
        </div>
        <div class="x_content" style="text-align:center">
                <h4 class="modal-title" id="myModalLabel">Please enter the Details of the New Company</h4>
            <br />
            <form id="roleform" data-parsley-validate class="form-horizontal form-label-left" method="POST" action="{{ route('company.store') }}">
                {{ csrf_field() }}
              <div class="modal-body">
                <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Company Name <span class="required">*</span>
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

                <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Company Email <span class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required="" class="form-control col-md-7 col-xs-12">
                    
                    @if ($errors->has('email'))
                      <span class="help-block">
                          <strong>{{ $errors->first('email') }}</strong>
                      </span>
                    @endif
                  </div>
                </div>
               
              <div class="modal-footer">
              <a href="{{url('company')}}" class="btn btn-default">Back to companies</a>
                <button type="submit" class="btn btn-success">Add Company</button>
              </div>
          </form>
        </div>
        </div>
    </div>
    
</div>    
@endsection