@extends('layouts.dash')
@section('content')
    <!-- page content -->

    <div class="row">
      @if (session('success'))
        <div class="x_content bs-example-popovers">
            <div class="alert alert-success alert-dismissible fade in" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
                <strong>Done!</strong>  {{ session('success') }}
            </div>
          </div>
      @endif
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>{{__('user_role.general.user')}}<small>{{__('user_role.general.view')}}</small></h2>
            <ul class="nav navbar-right panel_toolbox">
              <a href="users/create" class="btn btn-primary">{{__('user_role.button.add_new')}}</a>
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            @include('inc.userTable')
          </div>
        </div>
      </div>
    </div>
    <br />

  <!-- /page content -->    
@endsection
