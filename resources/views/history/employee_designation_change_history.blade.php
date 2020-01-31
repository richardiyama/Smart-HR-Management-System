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
            <h2>Employee Designation History<small>View</small></h2>
           <!-- <ul class="nav navbar-right panel_toolbox">
              <a href="site/create" class="btn btn-primary">Add New Site</a>
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>-->
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
          <table id="datatable-buttons" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>S/N</th>
            <th>Employee Name</th>
            <th>Previous Designation</th>
            <th>Date Of Change</th>
            
        </tr>
    </thead>
    <tbody>
        @if(count($designations)>0)
            @foreach ($designations as $designation)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$designation['employee_name']}}</td>
                    <td>{{$designation['employee_position']}}</td>
                    <td>{{$designation['created_at']}}</td>
                    
                   
                   
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
