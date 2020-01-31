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
            <h2>Company<small>View</small></h2>
            <ul class="nav navbar-right panel_toolbox">
              <a href="company/create" class="btn btn-primary">Add New Company</a>
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
          <table id="datatable-buttons" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>S/N</th>
            <th>Company Name</th>
            <th>Company Email</th>
            
            <th>{{__('user_role.table.action')}}</th>
        </tr>
    </thead>
    <tbody>
        @if(count($companies)>0)
            @foreach ($companies as $company)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$company['name']}}</td>
                    <td>{{$company['email']}}</td>
                    
                   
                    <td>
                        <a href="{{action('CompanyController@edit', $company['id'])}}"><span class="label label-warning"> Edit <i class="fa fa-edit"></i></span></a>
                        <a onclick="return confirm('Are you sure you want to delete this Company ? ')" href="{{ route('Company.delete', $company['id']) }}"><span class="label label-danger">Delete<i class="fa fa-trash"></i></span></a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
          </div>
        </div>
      </div>
    </div>
    <br />

  <!-- /page content -->    
@endsection
